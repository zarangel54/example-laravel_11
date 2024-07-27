<?php

namespace Modules\Contacts\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Contacts\Database\Factories\DynamicAttributeFactory;

class DynamicAttribute extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'type',
        'default_value',
        'group_attribute_id',
        'required_flag',
        'visible_flag',
    ];

    //protected static function newFactory(): DynamicAttributeFactory
   // {
   //    // return DynamicAttributeFactory::new();
   // }
}
