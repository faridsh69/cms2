<?php

namespace App\Http\Controllers\Admin\Activity;

use App\Cms\AdminResourceController;
use Illuminate\View\View;

class ResourceController extends AdminResourceController
{
    public string $modelNameSlug = 'activity';

    public function create(): View
    {
        $this->httpRequest->session()->flash('alert-danger', $this->modelNameTranslate . __(' create does not exist!'));

        return $this->redirect();
    }

    public function edit(int $id): View
    {
        $this->httpRequest->session()->flash('alert-danger', $this->modelNameTranslate . __(' edit does not exist!'));

        return $this->redirect();
    }

    public function update(int $id)
    {
        $this->httpRequest->session()->flash('alert-danger', $this->modelNameTranslate . __(' update does not exist!'));

        return $this->redirect();
    }
}
