<?php

declare(strict_types=1);

namespace App\Cms;

use App\Http\Controllers\Controller;
use App\Models\Category;
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
            ->language()
            ->orderBy('updated_at', 'desc')
            ->get();

        return $this->setSuccessStatus()
            ->setMessage($this->modelNameTranslate . __('list_successfully'))
            ->setData($list->toArray())
            ->prepareJsonResponse();
    }

    final public function show(string $url): JsonResponse
    {
        $model = $this->modelRepository
            ->where('url', $url)
            ->active()
            ->language()
            ->first();

        if (!$model) {
            return $this->prepareJsonResponse();
        }
        $this->authorize('view', $model);

        // $model->category = $model->category;
        // $model->tags = $model->tags;
        // $model->relateds = $model->relateds;
        // $model->images = $model->srcs('image');
        // $model->videos = $model->srcs('video');
        // $model->audios = $model->srcs('audio');
        // $model->avatar = $model->avatar();

        return $this->setSuccessStatus()
            ->setMessage(__('show_successfully'))
            ->setData($model)
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
        $validator = Validator::make($mainData, $this->modelRules);
        if ($validator->fails()) {
            return response()->json($validator->messages(), 200);
        }
        $modelStore = $this->modelRepository->create($mainData);

        // @TODO activity
        // if (env('APP_ENV') !== 'testing') {
        //     activity($this->modelName)
        //         ->performedOn($modelStore)
        //         ->causedBy(Auth::user())
        //         ->log($this->modelName . ' Created');
        // }

        return $this->setSuccessStatus()
            ->setMessage($this->modelNameTranslate . __('created_successfully'))
            ->setData($modelStore)
            ->prepareJsonResponse();
    }

    final public function edit($id)
    {
        $modelEdit = $this->modelRepository
            ->where('id', $id)
            ->first();

        if (!$modelEdit) {
            $this->response['status'] = 'error';
            $this->response['message'] = $this->notFoundMessage;

            return response()->json($this->response);
        }
        $this->authorize('update', $modelEdit);

        $mainData = $modelEdit->getAttributes();

        $this->response['message'] = __('show_successfully');
        $this->response['data'] = $mainData;

        return response()->json($this->response);
    }

    final public function update($id)
    {
        $modelUpdate = $this->modelRepository->where('id', $id)->first();
        if (!$modelUpdate) {
            $this->response['status'] = 'error';
            $this->response['message'] = $this->notFoundMessage;

            return response()->json($this->response);
        }
        $this->authorize('update', $modelUpdate);

        $mainData = $this->httpRequest->all();
        $validator = Validator::make($mainData, $this->modelRules);
        if ($validator->fails()) {
            return response()->json($validator->messages(), 200);
        }

        $modelUpdate->update($mainData);

        // @TODO activity
        // if (env('APP_ENV') !== 'testing') {
        //     activity($this->modelName)
        //         ->performedOn($modelUpdate)
        //         ->causedBy(Auth::user())
        //         ->log($this->modelName . ' Updated');
        // }

        $this->response['message'] = $this->modelNameTranslate . __('updated_successfully');
        $this->response['data'] = $modelUpdate;

        return response()->json($this->response);
    }

    final public function destroy($id)
    {
        $modelDelete = $this->modelRepository->where('id', $id)->first();
        if (!$modelDelete) {
            $this->response['status'] = 'error';
            $this->response['message'] = $this->notFoundMessage;

            return response()->json($this->response);
        }
        $this->authorize('delete', $modelDelete);

        $modelDelete->delete();

        // @TODO activity
        // if (env('APP_ENV') !== 'testing') {
        //     activity($this->modelName)
        //         ->performedOn($modelDelete)
        //         ->causedBy(Auth::user())
        //         ->log($this->modelName . ' Deleted');
        // }

        $this->response['message'] = $this->modelNameTranslate . __('deleted_successfully');
        $this->response['data'] = $modelDelete;

        return response()->json($this->response);
    }

    final public function getCategories()
    {
        $list = Category::ofType($this->modelName)
            ->active()
            ->language()
            ->orderBy('updated_at', 'desc')
            ->get();

        $this->response['message'] = 'Category' . __('list_successfully');
        $this->response['data'] = $list;

        return response()->json($this->response);
    }
}
