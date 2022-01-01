<?php

namespace App\Models;

use App\Cms\Model;

class Comment extends Model
{
    public $columns = [
        ['name' => 'user_id'],
        ['name' => 'content'],
        ['name' => 'activated'],
        ['name' => 'user_image'],
        ['name' => 'user_video'],
        [
            'name' => 'commentable_type',
            'type' => 'string',
            'database' => 'nullable',
            'rule' => '',
            'help' => '',
            'form_type' => '',
            'table' => true,
        ],
        [
            'name' => 'commentable_id',
            'type' => 'unsignedBigInteger',
            'database' => 'nullable',
            'rule' => '',
            'help' => '',
            'form_type' => '',
            'table' => true,
        ],
    ];

    public function commentable()
    {
        return $this->morphTo();
    }

    public function getAuthorAttribute()
    {
        $user = $this->user()->first();

        if ($user) {
            return $user->name;
        }

        return 'User';
    }
}
