<?php

declare(strict_types=1);

namespace App\Cms\Traits;

use App\Cms\Services\FileService;
use Auth;
use Cache;
use Hash;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, belongsToMany, morphMany, morphToMany};
use Illuminate\Database\Eloquent\{Builder, Model, SoftDeletes};
use Str;

trait CmsModelTrait
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
		if (true) {
			return $query;
		}

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

	public function mainImage($fileColumnName = 'image'): string
	{
		$srcs = $this->srcs($fileColumnName);
		if (\count($srcs)) {
			return $srcs[0];
		}

		return asset(config('cms.config.default_images') . mb_strtolower(class_basename($this)) . '.png');
	}

	public function getAvatarAttribute(): string
	{
		return $this->avatar();
	}

	public function getMainImageAttribute(): string
	{
		return $this->mainImage();
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

		return Cache::remember(
			'model_' . $modelName,
			config('cms.config.cache_time'),
			function () {
				$default_columns = config('cms.default_columns');

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
}
