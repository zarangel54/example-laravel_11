<?php

namespace Modules\Lists\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Modules\Lists\Database\Factories\ListsFactory;

class Lists extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'description',
        'list_type',
    ];


        /**
     * Define the relationship with ListContactItem.
     */
    public function contactItems()
    {
        return $this->hasMany(ListContactItem::class, 'list_id');
    }


    /**
     * Define the relationship with ListCompanyItem.
     */
    //public function companyItems()
    //{
    //    return $this->hasMany(ListCompanyItem::class, 'list_id');
    //}


    /**
     * Define the relationship with ListDealItem.
     */
    //public function dealItems()
    //{
    //    return $this->hasMany(ListDealItem::class, 'list_id');
    //}


    /**
     * Get a new factory instance for the model.
     *
     * @return \Modules\Lists\Database\Factories\ListsFactory
     */
   // protected static function newFactory(): ListsFactory
  //  {
  //      return ListsFactory::new();
  //  }
}
