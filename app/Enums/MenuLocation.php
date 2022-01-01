<?php

namespace App\Enums;

use App\Services\BaseEnum;

final class MenuLocation extends BaseEnum
{
    const data = [
		'1' => 'top-menu',
		'2' => 'footer-menu',
		'3' => 'side-menu',
	];
}
