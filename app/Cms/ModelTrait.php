<?php

declare(strict_types=1);

namespace App\Cms;

use Auth;
use Cache;
use Hash;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, belongsToMany, morphMany, morphToMany};
use Illuminate\Database\Eloquent\{Builder, Model, SoftDeletes};
use ReflectionClass;
use Str;

trait ModelTrait
{
	use HasFactory;
	use SoftDeletes;

	public function scopeActive($query): Builder
	{
		return $query->where('activated', 1);
	}

	public function scopeAuthUser($query): Builder
	{
		return $query->where('user_id', Auth::id());
	}

	public function scopeLanguage($query): Builder
	{
		return $query->where('language', config('app.locale'));
	}

	public function scopeOfType($query, $type): Builder
	{
		return $query->where('type', $type);
	}

	public function user(): BelongsTo
	{
		return $this->belongsTo('App\Models\User', 'user_id', 'id');
	}

	public function category(): BelongsTo
	{
		return $this->belongsTo('App\Models\Category', 'category_id', 'id');
	}

	public function tags(): morphToMany
	{
		return $this->morphToMany('App\Models\Tag', 'taggable');
	}

	public function comments(): morphMany
	{
		return $this->morphMany('App\Models\Comment', 'commentable');
	}

	public function likes(): morphMany
	{
		return $this->morphMany('App\Models\Like', 'likeable');
	}

	public function rates(): morphMany
	{
		return $this->morphMany('App\Models\Rate', 'rateable');
	}

	public function activities(): morphMany
	{
		return $this->morphMany('App\Models\Activity', 'activitiable');
	}

	public function relateds(): belongsToMany
	{
		return $this->belongsToMany(
			config('cms.config.models_namespace') . class_basename($this),
			'model_related',
			'model_id',
			'related_id'
		);
	}

	public function files(): morphMany
	{
		return $this->morphMany('App\Models\File', 'fileable');
	}

	// Get file srcs from that column
	public function srcs(string $fileColumnName): array
	{
		return $this->files()
			->where('title', $fileColumnName)
			->get()
			->pluck('src')
			->toArray();
	}

	public function avatar($fileColumnName = 'image'): string
	{
		$srcs = $this->srcs($fileColumnName);
		if (\count($srcs)) {
			return preg_replace('/(\.[^.]+)$/', sprintf('%s$1', '-thumbnail'), $srcs[0]);
		}

		return asset(config('cms.config.default_images') . mb_strtolower(class_basename($this)) . '.png');
	}

	public function getAvatarAttribute(): string
	{
		return $this->avatar();
	}

	public function getUserAttribute()
	{
		return $this->user()->select('id', 'url', 'first_name', 'last_name')->first();
	}

	public function saveWithRelations(array $data): Model
	{
		$formDataWitoutUploadFilesAndArrays = $this->clearFilesAndArrays($data);
		if ($this->id) {
			$model = $this;
			$model->update($formDataWitoutUploadFilesAndArrays);
		} else {
			$model = $this->create($formDataWitoutUploadFilesAndArrays);
		}
		$this->saveRelatedDataAfterCreate($data, $model);

		return $model;
	}

	/*
	* This is the main method in this cms, we are defining all models columns here,
	* Other models will extend this columns and we have same properties for one type of column.
	* name: Define name of the column in database and forms and everywhere
	* type: string, text, boolean, integer, decimal, array (for tags), file (image uploader)
	* database: nullable, default(1), none (Dont create that column)
	* rule: required, min, max, nullable, unique
	* help: A hint in forms under the input
	* form_type: textarea, ckeditor, switch-m, checkbox-m, switch-bootstrap-m, entity, enum, file, number, time, date, , color. Defines the type of form input.
	* table: true or false, shows that this column in showing in table or not.
	*/
	public function getColumns(): array
	{
		$modelName = class_basename($this);
		$modelNamespace = (new ReflectionClass($this))->getName();
		$tableName = $this->getTable();

		$cacheSeconds = 1;

		return Cache::remember(
			'model_' . $modelName,
			$cacheSeconds,
			function () use ($modelName, $modelNamespace, $tableName) {
				$default_columns = $this->getDefaultColumns($modelName, $modelNamespace, $tableName);

				$columns = $this->columns;
				foreach ($columns as $key => $column) {
					if (isset($column['same_column_name'])) {
						$columns[$key] = $default_columns[$column['same_column_name']];
						$columns[$key]['name'] = $column['name'];
					} elseif (\array_key_exists($column['name'], $default_columns) && !isset($column['type'])) {
						$columns[$key] = $default_columns[$column['name']];
					} elseif (!isset($columns[$key]['type'])) {
						$columns[$key]['type'] = 'text';
						$columns[$key]['database'] = 'nullable';
						$columns[$key]['form_type'] = '';
					}
				}

				return $columns;
			}
		);
	}

	final public function getRules(): array
	{
		return collect($this->getColumns())
			->pluck('rule', 'name')
			->map(fn ($rule) => mb_strpos($rule, 'unique') !== false ? $rule . $this->id : $rule)->toArray();
	}

	final public function appendData(): Model
	{
		$this->images = $this->srcs('image');
		$this->videos = $this->srcs('video');
		$this->audios = $this->srcs('audio');
		$this->documents = $this->srcs('document');
		$this->likes = $this->likes;
		$this->category = $this->category;
		$this->tags = $this->tags;
		$this->relateds = $this->relateds;

		return $this;
	}

	// Before save a form data we need to write 0 for unchecked checkboxes
	// All relational data that are array should eliminate from form data.
	private function clearFilesAndArrays(array $data): array
	{
		// convert boolean input values: null and false => 0, true => 1
		foreach (collect($this->getColumns())
			->where('type', 'boolean')
			->pluck('name') as $boolean_column) {
			if (!isset($data[$boolean_column])) {
				$data[$boolean_column] = 0;
			}
		}
		// unset file and array attributes before saving
		foreach (collect($this->getColumns())
			->whereIn('type', ['file', 'array', 'captcha'])
			->pluck('name') as $file_or_array_column) {
			unset($data[$file_or_array_column]);
		}
		// For User model
		if (class_basename($this) === 'User') {
			unset($data['password_confirmation']);
			if (isset($data['password'])) {
				$data['password'] = Hash::make($data['password']);
			} else {
				if ($this->id) { // update user
					$data['password'] = $this->password;
				} else {
					// create user, and there is no password
					$data['password'] = Hash::make(123456);
				}
			}
		}

		// Convert blog to App\Models\Blog
		if (class_basename($this) === 'Like') {
			$modelName = Str::studly($data['likeable_type']);
			$data['likeable_type'] = config('cms.config.models_namespace') . $modelName;
		}

		return $data;
	}

	// Save all relational data.
	private function saveRelatedDataAfterCreate(array $data, Model $model): void
	{
		// Upload all columns with type file.
		foreach (collect($this->getColumns())
			->where('type', 'file')
			->pluck('name') as $fileColumn) {
			if (isset($data[$fileColumn]) && $data[$fileColumn]) {
				$file = $data[$fileColumn];
				$fileService = new FileService();
				$fileService->save($file, $model, $fileColumn);
			}
		}
		// save relations with array type column like tags, related_models, etc.
		foreach (collect($this->getColumns())
			->where('type', 'array')
			->pluck('name') as $arrayColumn) {
			if (isset($data[$arrayColumn]) && $data[$arrayColumn]) {
				$model->{$arrayColumn}()->sync($data[$arrayColumn]);
			}
		}
	}

	private function getDefaultColumns(string $modelName, string $modelNamespace, string $tableName)
	{
		return [
			'title' => [
				'name' => 'title',
				'type' => 'string',
				'database' => '',
				'rule' => 'required|min:' . config('setting-developer.seo_title_min')
					. '|max:' . config('setting-developer.seo_title_max'),
				'help' => 'Title should be unique.',
				'form_type' => '',
				'table' => true,
			],
			'description' => [
				'name' => 'description',
				'type' => 'text',
				'database' => 'nullable',
				'rule' => 'nullable',
				'help' => 'Description should be 50 - 70 characters, maximum 160 characters.',
				'form_type' => 'textarea',
				'table' => true,
			],
			'content' => [
				'name' => 'content',
				'type' => 'text',
				'database' => 'nullable',
				'rule' => 'nullable', // only page and blog need seo_header
				'help' => '',
				'form_type' => 'ckeditor',
				'table' => false,
			],
			'url' => [
				'name' => 'url',
				'type' => 'string',
				'database' => 'nullable',
				'rule' => 'nullable|max:' . config('setting-developer.seo_url_max') . '|unique:' . $tableName . ',url,',
				'help' => 'Url should be unique, contain [a-z, 0-9, -], required for seo',
				'form_type' => '',
				'table' => false,
			],
			'keywords' => [
				'name' => 'keywords',
				'type' => 'string',
				'database' => 'nullable',
				'rule' => 'nullable|max:191',
				'help' => 'Keywords is optional and is not important for google',
				'form_type' => '',
				'table' => false,
			],
			'activated' => [
				'name' => 'activated',
				'type' => 'boolean',
				'database' => 'default',
				'rule' => 'boolean',
				'help' => '',
				'form_type' => 'switch-m', // switch-m, checkbox-m, switch-bootstrap-m
				'table' => false,
			],
			'google_index' => [
				'name' => 'google_index',
				'type' => 'boolean',
				'database' => 'default',
				'rule' => 'boolean',
				'help' => 'Shows google robots will follow this link.',
				'form_type' => 'checkbox-m',
				'table' => false,
			],
			'canonical_url' => [
				'name' => 'canonical_url',
				'type' => 'string',
				'database' => 'nullable',
				'rule' => 'nullable|max:191',
				'help' => 'Canonical url is neccessary if one content will show from two different urls.',
				'form_type' => '',
				'table' => false,
			],
			'icon' => [
				'name' => 'icon',
				'type' => 'string',
				'database' => 'nullable',
				'rule' => '',
				'help' => 'Click: <a target="blank" href="/admin/icons/list">List of Icons</a> - for example: fa-glass',
				'form_type' => '',
				'table' => false,
			],
			'full_name' => [
				'name' => 'full_name',
				'type' => 'string',
				'database' => 'nullable',
				'rule' => '',
				'help' => '',
				'form_type' => '',
				'table' => false,
			],
			'user_id' => [
				'name' => 'user_id',
				'type' => 'unsignedBigInteger',
				'database' => 'nullable',
				'relation' => 'users',
				'rule' => 'nullable|exists:users,id',
				'help' => '',
				'form_type' => 'entity',
				'class' => 'App\Models\User',
				'property' => 'email',
				'property_key' => 'id',
				'multiple' => false,
				'table' => true,
			],
			'category_id' => [
				'name' => 'category_id',
				'type' => 'unsignedBigInteger',
				'database' => 'nullable',
				'relation' => 'categories',
				'rule' => 'nullable|exists:categories,id',
				'help' => '',
				'form_type' => 'entity',
				'class' => 'App\Models\Category',
				'property' => 'title',
				'property_key' => 'id',
				'query_builder' => 'type|' . $modelName,
				'multiple' => false,
				'table' => true,
			],
			'tags' => [
				'name' => 'tags',
				'type' => 'array',
				'database' => 'none',
				'rule' => 'nullable',
				'help' => '',
				'form_type' => 'entity',
				'class' => 'App\Models\Tag',
				'property' => 'title',
				'property_key' => 'id',
				'query_builder' => 'type|' . $modelName,
				'multiple' => true,
				'table' => false,
			],
			'relateds' => [
				'name' => 'relateds',
				'type' => 'array',
				'database' => 'none',
				'rule' => 'nullable',
				'help' => 'Select related items to suggest to user.',
				'form_type' => 'entity',
				'class' => $modelNamespace,
				'property' => 'title',
				'property_key' => 'id',
				'multiple' => true,
				'table' => false,
			],
			'language' => [
				'name' => 'language',
				'type' => 'string',
				'database' => 'nullable',
				'rule' => '',
				'help' => 'Specify language.',
				'form_type' => 'enum',
				'form_enum_class' => 'AppLanguage',
				'table' => false,
			],
			'order' => [
				'name' => 'order',
				'type' => 'integer',
				'database' => 'nullable',
				'rule' => 'nullable|numeric',
				'help' => 'Sort by this column, lower order will be ahead',
				'form_type' => '',
				'table' => false,
			],
			'image' => [
				'name' => 'image',
				'type' => 'file',
				'database' => 'none',
				'rule' => 'nullable',
				'help' => 'You can choose multiple images',
				'form_type' => 'file',
				'file_accept' => 'image/*',
				'file_multiple' => true,
				'table' => true,
			],
			'video' => [
				'name' => 'video',
				'type' => 'file',
				'database' => 'none',
				'rule' => 'nullable',
				'help' => 'You can choose multiple videos',
				'form_type' => 'file',
				'file_accept' => 'video/*',
				'file_multiple' => true,
				'table' => false,
			],
			'document' => [
				'name' => 'document',
				'type' => 'file',
				'database' => 'none',
				'rule' => 'nullable',
				'help' => 'You can choose multiple documents',
				'form_type' => 'file',
				'file_accept' => '*',
				'file_multiple' => true,
				'table' => false,
			],
			'audio' => [
				'name' => 'audio',
				'type' => 'file',
				'database' => 'none',
				'rule' => 'nullable',
				'help' => 'You can choose multiple videos',
				'form_type' => 'file',
				'file_accept' => 'audio/*',
				'file_multiple' => true,
				'table' => false,
			],
			'opening_hours' => [
				'name' => 'opening_hours',
				'type' => 'string',
				'database' => 'nullable',
				'rule' => 'nullable|max:191',
				'help' => '',
				'form_type' => '',
				'table' => false,
			],
			'city' => [
				'name' => 'city',
				'type' => 'string',
				'database' => 'nullable',
				'rule' => 'nullable|max:191',
				'help' => '',
				'form_type' => '',
				'table' => false,
			],
			'country' => [
				'name' => 'country',
				'type' => 'string',
				'database' => 'nullable',
				'rule' => '',
				'help' => '',
				'form_type' => '',
				'table' => false,
			],
			'province' => [
				'name' => 'province',
				'type' => 'string',
				'database' => 'nullable',
				'rule' => '',
				'help' => '',
				'form_type' => '',
				'table' => false,
			],
			'city' => [
				'name' => 'city',
				'type' => 'string',
				'database' => 'nullable',
				'rule' => '',
				'help' => '',
				'form_type' => '',
				'table' => true,
			],
			'address' => [
				'name' => 'address',
				'type' => 'text',
				'database' => 'nullable',
				'rule' => '',
				'help' => '',
				'form_type' => 'textarea',
				'table' => true,
			],
			'postal_code' => [
				'name' => 'postal_code',
				'type' => 'string',
				'database' => 'nullable',
				'rule' => '',
				'help' => '',
				'form_type' => '',
				'table' => false,
			],
			'phone' => [
				'name' => 'phone',
				'type' => 'string',
				'database' => 'nullable',
				'rule' => 'nullable|numeric',
				'help' => 'International format +4917...',
				'form_type' => 'phone',
				'table' => true,
			],
			'telephone' => [
				'name' => 'telephone',
				'type' => 'string',
				'database' => 'nullable',
				'rule' => 'nullable|max:30',
				'help' => 'Home Number',
				'form_type' => '',
				'table' => false,
			],
			'latitude' => [
				'name' => 'latitude',
				'type' => 'decimal',
				'database' => 'nullable',
				'rule' => '',
				'help' => '',
				'form_type' => '',
				'table' => false,
			],
			'longitude' => [
				'name' => 'longitude',
				'type' => 'decimal',
				'database' => 'nullable',
				'rule' => '',
				'help' => '',
				'form_type' => '',
				'table' => false,
			],
			'properties' => [
				'name' => 'properties',
				'type' => 'text',
				'database' => 'nullable',
				'rule' => '',
				'help' => '',
				'form_type' => 'textarea',
				'table' => false,
			],
			'website' => [
				'name' => 'website',
				'type' => 'string',
				'database' => 'nullable',
				'rule' => 'nullable',
				'help' => '',
				'form_type' => '',
				'table' => false,
			],
			'email' => [
				'name' => 'email',
				'type' => 'string',
				'database' => 'nullable',
				'rule' => 'nullable|unique:' . $tableName . ',email,',
				'help' => 'Wrtie valid email address',
				'form_type' => 'email',
				'table' => false,
			],
			'price' => [
				'name' => 'price',
				'type' => 'bigInteger',
				'database' => 'nullable',
				'rule' => 'nullable|numeric',
				'help' => '',
				'form_type' => 'number',
				'table' => true,
			],
			'discount_price' => [
				'name' => 'discount_price',
				'type' => 'bigInteger',
				'database' => 'nullable',
				'rule' => 'nullable|numeric',
				'help' => '',
				'form_type' => 'number',
				'table' => false,
			],
			'year' => [
				'name' => 'year',
				'type' => 'integer',
				'database' => 'nullable',
				'rule' => 'nullable|numeric',
				'help' => '',
				'form_type' => 'number',
				'table' => false,
			],
			'rooms' => [
				'name' => 'rooms',
				'type' => 'integer',
				'database' => 'nullable',
				'rule' => 'nullable|numeric',
				'help' => '',
				'form_type' => 'number',
				'table' => true,
			],
			'color' => [
				'name' => 'color',
				'type' => 'string',
				'database' => 'nullable',
				'rule' => '',
				'help' => '',
				'form_type' => '',
				'table' => false,
			],
			'foundation' => [
				'name' => 'foundation',
				'type' => 'string',
				'database' => 'nullable',
				'rule' => '',
				'help' => '',
				'form_type' => '',
				'table' => true,
			],
			'calorie' => [
				'name' => 'calorie',
				'type' => 'integer',
				'database' => 'nullable',
				'rule' => 'nullable|numeric',
				'help' => '',
				'form_type' => '',
				'table' => false,
			],
			'singer' => [
				'name' => 'singer',
				'type' => 'string',
				'database' => 'nullable',
				'rule' => '',
				'help' => '',
				'form_type' => '',
				'table' => true,
			],
			'director' => [
				'name' => 'director',
				'type' => 'string',
				'database' => 'nullable',
				'rule' => '',
				'help' => '',
				'form_type' => '',
				'table' => true,
			],
			'date' => [
				'name' => 'date',
				'type' => 'string',
				'database' => 'nullable',
				'rule' => '',
				'help' => '',
				'form_type' => 'date',
				'table' => true,
			],
			'time' => [
				'name' => 'time',
				'type' => 'string',
				'database' => 'nullable',
				'rule' => '',
				'help' => '',
				'form_type' => 'time',
				'table' => true,
			],
			'destination' => [
				'name' => 'destination',
				'type' => 'string',
				'database' => 'nullable',
				'rule' => '',
				'help' => '',
				'form_type' => '',
				'table' => true,
			],
			'origin' => [
				'name' => 'origin',
				'type' => 'string',
				'database' => 'nullable',
				'rule' => '',
				'help' => '',
				'form_type' => '',
				'table' => true,
			],
		];
	}
}
