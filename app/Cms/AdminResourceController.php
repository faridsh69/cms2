<?php

declare(strict_types=1);

namespace App\Cms;

use Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Str;

abstract class AdminResourceController extends AdminController
{
    public function index(): view
    {
        $this->authorize('index', $this->modelNamespace);
        $this->meta['link_route'] = route('admin.' . $this->modelNameSlug . '.list.create');
        $this->meta['link_name'] = __('create_new') . $this->modelNameTranslate;
        $this->meta['title'] = $this->modelNameTranslate . __('manager');
        $this->meta['search'] = 1;
        $columns = [];
        foreach (collect($this->modelColumns)->where('table', true) as $column) {
            $columns[] = [
                'field' => $column['name'],
                'title' => preg_replace('/([a-z])([A-Z])/s', '$1 $2', Str::studly($column['name'])),
            ];
        }

        return view('admin.list.index', [
            'meta' => $this->meta, 'columns' => $columns,
        ]);
    }

    final public function create(): View
    {
        $this->authorize('create', $this->modelNamespace);
        $this->meta['title'] = __('create_new') . $this->modelNameTranslate;
        $form = $this->laravelFormBuilder->create($this->modelForm, [
            'method' => 'POST',
            'url' => route('admin.' . $this->modelNameSlug . '.list.store'),
            'class' => 'm-form m-form--state',
            'id' => 'admin_form',
            'enctype' => 'multipart/form-data',
        ]);

        return view('admin.list.form', [
            'form' => $form, 'meta' => $this->meta,
        ]);
    }

    final public function store(): RedirectResponse
    {
        $this->authorize('create', $this->modelNamespace);
        $form = $this->laravelFormBuilder->create($this->modelForm);
        if (!$form->isValid()) {
            if (env('APP_ENV') === 'testing') {
                dd($form->getErrors(), $this->modelName, $form->getFieldValues());
            }

            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }
        $this->modelRepository->saveWithRelations($form->getFieldValues());

        // @TODO activity
        // if (env('APP_ENV') !== 'testing') {
        //     activity('Created')->performedOn($storedModel)->causedBy(Auth::user())
        //         ->log($this->modelName . ' Created');
        // }
        $this->httpRequest->session()->flash(
            'alert-success',
            $this->modelNameTranslate . __('created_successfully')
        );

        return redirect()->route('admin.' . $this->modelNameSlug . '.list.index');
    }

    final public function show(int $id): View
    {
        $showedModal = $this->modelRepository->findOrFail($id);
        $this->authorize('view', $showedModal);
        $activities = \App\Models\Activity::where('activitiable_type', $this->modelNamespace)
            ->where('activitiable_id', $id)
            ->get();

        $this->meta['title'] = $this->modelNameTranslate . __('show');
        $this->meta['link_route'] = route('admin.' . $this->modelNameSlug . '.list.edit', $showedModal);
        $this->meta['link_name'] = $this->modelNameTranslate . __('edit form');

        return view('admin.list.show', [
            'data' => $showedModal, 'meta' => $this->meta, 'activities' => $activities,
        ]);
    }

    final public function edit(int $id)
    {
        $editedModel = $this->modelRepository->findOrFail($id);
        $this->authorize('update', $editedModel);
        $this->meta['title'] = __('edit') . $this->modelNameTranslate . ' - #' . $id;
        $form = $this->laravelFormBuilder->create($this->modelForm, [
            'method' => 'PUT',
            'url' => route('admin.' . $this->modelNameSlug . '.list.update', $editedModel),
            'class' => 'm-form m-form--state',
            'id' => 'admin_form',
            'model' => $editedModel,
            'enctype' => 'multipart/form-data',
        ]);

        return view('admin.list.form', [
            'form' => $form, 'meta' => $this->meta,
        ]);
    }

    final public function update(int $id): RedirectResponse
    {
        $updatedModel = $this->modelRepository->findOrFail($id);
        $this->authorize('update', $updatedModel);

        $form = $this->laravelFormBuilder->create($this->modelForm, [
            'model' => $updatedModel,
        ]);
        if (!$form->isValid()) {
            if (env('APP_ENV') === 'testing') {
                $errors = $form->getErrors();
                if (mb_strpos(json_encode($errors), 'file') === false) {
                    dd($errors);
                }
            } else {
                return redirect()->back()->withErrors($form->getErrors())->withInput();
            }
        }
        $updatedModel->saveWithRelations($form->getFieldValues());

        // @TODO activity
        // if (env('APP_ENV') !== 'testing') {
        //     activity('Updated')->performedOn($model)->causedBy(Auth::user())
        //         ->log($this->modelName . ' Updated');
        // }
        $this->httpRequest->session()->flash(
            'alert-success',
            $this->modelNameTranslate . __('updated_successfully')
        );

        return redirect()->route('admin.' . $this->modelNameSlug . '.list.index');
    }

    final public function destroy(int $id): RedirectResponse
    {
        $model = $this->modelRepository->findOrFail($id);
        $this->authorize('delete', $model);

        $model->delete();

        // @TODO activity
        // if (env('APP_ENV') !== 'testing') {
        //     activity('Deleted')->performedOn($model)->causedBy(Auth::user())
        //         ->log($this->modelName . ' Deleted');
        // }
        // $this->httpRequest->session()->flash('alert-success', $this->modelNameTranslate . __('deleted_successfully'));

        return redirect()->route('admin.' . $this->modelNameSlug . '.list.index');
    }

    final public function restore(int $id): RedirectResponse
    {
        $model = $this->modelRepository->withTrashed()->findOrFail($id);
        $this->authorize('delete', $model);

        $model->restore();

        // @TODO activity
        // if (env('APP_ENV') !== 'testing') {
        //     activity('Restored')->performedOn($model)->causedBy(Auth::user())
        //         ->log($this->modelName . ' Restored');
        // }
        $this->httpRequest->session()->flash(
            'alert-success',
            $this->modelNameTranslate . __('restored_successfully')
        );

        return redirect()->route('admin.' . $this->modelNameSlug . '.list.index');
    }

    final public function print(): View
    {
        $this->authorize('index', $this->modelNamespace);
        $list = $this->modelRepository->all();

        return view('admin.common.print', compact('list'));
    }

    final public function pdf()
    {
        $this->authorize('index', $this->modelNamespace);
        $list = $this->modelRepository->all();

        return PDF::loadView('admin.common.print', compact('list'))
            ->setPaper('a4', 'landscape')
            ->download($this->modelName . '.pdf');
    }

    final public function export()
    {
        $this->authorize('index', $this->modelNamespace);
        $exportClassName = 'App\Cms\Export';
        $exportRepository = new $exportClassName();
        $exportRepository->setModelName($this->modelName);

        return Excel::download($exportRepository, $this->modelName . '.xlsx');
    }

    final public function import()
    {
        $this->authorize('index', $this->modelNamespace);
        $importClassName = 'App\Cms\Import';
        $importRepository = new $importClassName();
        $importRepository->setModelName($this->modelName);
        Excel::import($importRepository, storage_path('app/public/import.xlsx'));

        return redirect()->route('admin.' . $this->modelNameSlug . '.list.index');
    }

    final public function toggleActivated(int $id)
    {
        $model = $this->modelRepository->findOrFail($id);
        $this->authorize('update', $model);
        $model->activated = !$model->activated;
        $model->update();

        return response()->json([
            'data' => [
                'activated' => $model->activated,
            ],
        ]);
    }

    public function redirect(): RedirectResponse
    {
        return redirect()->route('admin.' . $this->modelNameSlug . '.list.index');
    }

    final public function datatable()
    {
        $this->authorize('index', $this->modelNamespace);
        $list = $this->modelRepository->orderBy('updated_at', 'desc');

        $datatable = datatables()
            ->of($list)
            ->addColumn('show_url', fn ($model) => route('admin.' . $this->modelNameSlug . '.list.show', $model))
            ->addColumn('edit_url', fn ($model) => route('admin.' . $this->modelNameSlug . '.list.edit', $model))
            ->addColumn(
                'delete_url',
                fn ($model) => route('admin.' . $this->modelNameSlug . '.list.destroy', $model)
            );
        if ($this->modelName === 'Notification') {
            $datatable->addColumn('user', fn ($model) => $model->user->name)->addColumn(
                'type',
                fn ($model) => str_replace('App\Notifications\\', '', $model->type)
            )->addColumn('data', fn ($model) => json_decode($model->data)->data);
        } elseif ($this->modelName === 'Block') {
            $datatable->addColumn('pages', function ($model) {
                $pages = $model->pages()->pluck('title')->toArray();
                $output = 'Only: ';
                if ($model->show_all_pages) {
                    $output = 'Except: ';
                }
                if ($pages) {
                    $output .= implode(',<br>', $pages);
                } else {
                    $output .= '-';
                }

                return $output;
            });
        } elseif ($this->modelName === 'Role') {
            $datatable->addColumn(
                'permissions',
                fn ($model) => implode(',<br>', $model->permissions()->pluck('name')->toArray())
            )
                ->addColumn('users', function ($model) {
                    return implode(
                        ',<br>',
                        \App\Models\User::role($model->name)->select('email')->pluck('email')->toArray()
                    );
                });
        }
        if (collect($this->modelColumns)->where('name', 'tags')->count() > 0) {
            $datatable->addColumn('tags', fn ($model) => implode(', ', $model->tags->pluck('title')->toArray()));
        }
        $datatable->addColumn(
            'user_id',
            fn ($model) => $model->user ? $model->user->id . ' ' . $model->user->name : ''
        );
        $datatable->addColumn(
            'category_id',
            fn ($model) => $model->category ? $model->category->id . '-' . $model->category->title : ''
        );
        $datatable->addColumn('image', function ($model) {
            if (method_exists($model, 'avatar')) {
                return '<img style="width:80%" src="' . $model->avatar() . '">';
            }

            return '';
        });
        $datatable->addColumn('images_gallery', function ($model) {
            if (method_exists($model, 'avatar')) {
                return '<img style="width:80%" src="' . $model->avatar('images_gallery') . '">';
            }

            return '';
        });

        return $datatable->rawColumns(['id', 'order', 'image', 'images_gallery', 'content', 'users', 'permissions'])
            ->toJson();
    }
}
