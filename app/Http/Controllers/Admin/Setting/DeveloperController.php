<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Setting;

use App\Cms\AdminSettingsController;

final class DeveloperController extends AdminSettingsController
{
    public string $modelNameSlug = 'setting-developer';
}
