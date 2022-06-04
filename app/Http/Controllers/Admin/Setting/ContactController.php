<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Setting;

use App\Cms\AdminSettingsController;

final class ContactController extends AdminSettingsController
{
	public string $modelNameSlug = 'setting-contact';
}
