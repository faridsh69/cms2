<?php

namespace App\Http\Controllers\Admin\Activity;

use App\Cms\AdminResourceController;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ResourceController extends AdminResourceController
{
    public string $modelNameSlug = 'activity';

    public function create(): View
    {
        $this->httpRequest->session()->flash('alert-danger', $this->modelNameTranslate . __(' create does not exist!'));

        return view('admin.page.dashboard.index', ['meta' => $this->meta]);
    }

    public function edit(int $id): View
    {
        $this->httpRequest->session()->flash('alert-danger', $this->modelNameTranslate . __(' edit does not exist!'));

        return view('admin.page.dashboard.index', ['meta' => $this->meta]);
    }

    public function update(int $id): RedirectResponse
    {
        $this->httpRequest->session()->flash('alert-danger', $this->modelNameTranslate . __(' update does not exist!'));

        return $this->redirect();
    }
}
