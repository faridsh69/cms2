<?php

declare(strict_types=1);

namespace App\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Validator;

abstract class ApiController extends Controller
{
	use ApiTrait;
	use CmsMainTrait;

	final public function index(): JsonResponse
	{
		$this->authorize('index', $this->modelNamespace);
		$list = $this->modelRepository
			->active()
			->orderBy('updated_at', 'desc')
			->get();

		return $this->setSuccessStatus()
			->setMessage($this->modelNameTranslate . __('list_successfully'))
			->setData($list->toArray())
			->prepareJsonResponse();
	}

	final public function show(string $url): JsonResponse
	{
		$showedModel = $this->modelRepository
			->where('url', $url)
			->active()
			->first();

		if (!$showedModel) {
			return $this->prepareJsonResponse();
		}
		$this->authorize('view', $showedModel);

		return $this->setSuccessStatus()
			->setMessage(__('show_successfully'))
			->setData($showedModel->appendData())
			->prepareJsonResponse();
	}

	final public function create(): void
	{
		abort(404);
	}

	final public function store()
	{
		$this->authorize('create', $this->modelNamespace);
		$mainData = $this->httpRequest->all();
		$modelRules = $this->modelRepository->getRules();
		$validator = Validator::make($mainData, $modelRules);
		if ($validator->fails()) {
			return $this->setMessage(__('validation_failed'))
				->setData($validator->messages())
				->prepareJsonResponse();
		}
		$storedModel = $this->modelRepository->saveWithRelations($mainData);

		// @TODO activity
		// if (env('APP_ENV') !== 'testing') {
		//     activity($this->modelName)
		//         ->performedOn($storedModel)
		//         ->causedBy(Auth::user())
		//         ->log($this->modelName . ' Created');
		// }

		return $this->setSuccessStatus()
			->setMessage($this->modelNameTranslate . __('created_successfully'))
			->setData($storedModel->appendData())
			->prepareJsonResponse();
	}

	final public function edit(): void
	{
		abort(404);
	}

	final public function update(string $url)
	{
		$updatedModel = $this->modelRepository
			->where('url', $url)
			->first();

		if (!$updatedModel) {
			return $this->prepareJsonResponse();
		}
		$this->authorize('update', $updatedModel);

		$mainData = $this->httpRequest->all();
		$modelRules = $updatedModel->getRules();
		$validator = Validator::make($mainData, $modelRules);
		if ($validator->fails()) {
			return $this->setMessage(__('validation_failed'))
				->setData($validator->messages())
				->prepareJsonResponse();
		}

		$updatedModel->saveWithRelations($mainData);

		// @TODO activity
		// if (env('APP_ENV') !== 'testing') {
		//     activity($this->modelName)
		//         ->performedOn($modelUpdate)
		//         ->causedBy(Auth::user())
		//         ->log($this->modelName . ' Updated');
		// }

		return $this->setSuccessStatus()
			->setMessage(__('updated_successfully'))
			->setData($updatedModel->appendData())
			->prepareJsonResponse();
	}

	final public function destroy(string $url): JsonResponse
	{
		$deletedModel = $this->modelRepository->where('url', $url)->first();
		if (!$deletedModel) {
			return $this->prepareJsonResponse();
		}
		$this->authorize('delete', $deletedModel);

		$deletedModel->delete();

		// @TODO activity
		// if (env('APP_ENV') !== 'testing') {
		//     activity($this->modelName)
		//         ->performedOn($modelDelete)
		//         ->causedBy(Auth::user())
		//         ->log($this->modelName . ' Deleted');
		// }

		return $this->setSuccessStatus()
			->setMessage(__('deleted_successfully'))
			->setData($deletedModel)
			->prepareJsonResponse();
	}
}
