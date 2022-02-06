<?php

namespace App\Cms;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\JsonResponse;
use App\Models\Category;

class ApiController extends Controller
{
    use CmsMainTrait;

    public function index() : JsonResponse
    {
    	// abort(500, "Noooo");
        // $this->authorize('index', $this->modelNamespace);
        $list = $this->modelRepository
        	->active()
        	->language()
        	->orderBy('updated_at', 'desc')
        	->get();

        foreach($list as $listItem)
        {
        	$item = clone $listItem;
        	$item['id'] = $item['id'] + 100;
        	$list[] = $item;

        	$item = clone $listItem;
        	$item['id'] = $item['id'] + 200;
        	$list[] = $item;

        	$item = clone $listItem;
        	$item['id'] = $item['id'] + 300;
        	$list[] = $item;
        }

        $this->response['message'] = $this->modelNameTranslate . __('list_successfully');
        $this->response['data'] = $list;

        return response()->json($this->response);
    }

    public function create()
    {
        abort(404);
    }

    public function store()
    {
        $this->authorize('create', $this->modelNamespace);
        $mainData = $this->httpRequest->all();
        $validator = \Validator::make($mainData, $this->modelRules);
        if ($validator->fails()) {
            return response()->json($validator->messages(), 200);
        }
        $modelStore = $this->modelRepository->create($mainData);

        if(env('APP_ENV') !== 'testing'){
            activity($this->modelName)
                ->performedOn($modelStore)
                ->causedBy(Auth::user())
                ->log($this->modelName . ' Created');
        }

        $this->response['message'] = $this->modelNameTranslate . __('created_successfully');
        $this->response['data'] = $modelStore;

        return response()->json($this->response);
    }

    public function show(string $url) : JsonResponse
    {
        $model = $this->modelRepository
        	->where('url', $url)
        	->active()
        	->language()
        	->first();

        if(! $model)
        {
            $this->response['status'] = 'error';
            $this->response['message'] = $this->notFoundMessage;
            return response()->json($this->response);
        }
        // $this->authorize('view', $model);

        // $mainData = $model->getAttributes();
        $model->category = $model->category;
        $model->tags = $model->tags;
        $model->relateds = $model->relateds;


        $this->response['message'] = __('show_successfully');
        $this->response['data'] = $model;

        return response()->json($this->response);
    }

    public function edit($id)
    {
        $modelEdit = $this->modelRepository
            ->where('id', $id)
            ->first();

        if(! $modelEdit){
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

    public function update($id)
    {
        $modelUpdate = $this->modelRepository->where('id', $id)->first();
        if(! $modelUpdate){
            $this->response['status'] = 'error';
            $this->response['message'] = $this->notFoundMessage;
            return response()->json($this->response);
        }
        $this->authorize('update', $modelUpdate);

        $mainData = $this->httpRequest->all();
        $validator = \Validator::make($mainData, $this->modelRules);
        if ($validator->fails()) {
            return response()->json($validator->messages(), 200);
        }

        $modelUpdate->update($mainData);

        if(env('APP_ENV') !== 'testing'){
            activity($this->modelName)
                ->performedOn($modelUpdate)
                ->causedBy(Auth::user())
                ->log($this->modelName . ' Updated');
        }

        $this->response['message'] = $this->modelNameTranslate . __('updated_successfully');
        $this->response['data'] = $modelUpdate;

        return response()->json($this->response);
    }

    public function destroy($id)
    {
        $modelDelete = $this->modelRepository->where('id', $id)->first();
        if(! $modelDelete){
            $this->response['status'] = 'error';
            $this->response['message'] = $this->notFoundMessage;
            return response()->json($this->response);
        }
        $this->authorize('delete', $modelDelete);

        $modelDelete->delete();

        if(env('APP_ENV') !== 'testing'){
            activity($this->modelName)
                ->performedOn($modelDelete)
                ->causedBy(Auth::user())
                ->log($this->modelName . ' Deleted');
        }

        $this->response['message'] = $this->modelNameTranslate . __('deleted_successfully');
        $this->response['data'] = $modelDelete;

        return response()->json($this->response);
    }

    public function getCategories ()
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
