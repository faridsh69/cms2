<?php

declare(strict_types=1);

namespace App\Enums;

final class FactorStatus
{
	public const data = [
		1 => 'Initial',
		2 => 'Payment',
		3 => 'Proccessing',
		4 => 'Preparing',
		5 => 'Delivaering',
		6 => 'Canceled',
		7 => 'Succeed',
	];
}
