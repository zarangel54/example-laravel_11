<?php

namespace Modules\Contacts\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Contacts\Database\Factories\ContactsFactory;

class Contacts extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'last_name',
        'email',
        'phone',
        'workspace_id',
        'custom_attributes'
    ];

    protected static function newFactory(): ContactsFactory
    {
        return ContactsFactory::new();
    }

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'custom_attributes' => 'array',
    ];
}
