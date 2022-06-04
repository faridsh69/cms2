<?php

declare(strict_types=1);

namespace App\Cms;

use Illuminate\Database\Eloquent\Model as LaravelModel;

abstract class Model extends LaravelModel
{
    use ModelTrait;

    protected $guarded = [];

    protected $hidden = ['deleted_at'];

    protected $casts = [
        'activated' => 'boolean',
    ];

    protected $appends = ['avatar'];
}
