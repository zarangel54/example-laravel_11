<?php

namespace Modules\Lists\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Lists\Database\Factories\ListContactItemFactory;
use Modules\Contacts\Models\Contacts;

class ListContactItem extends Model
{
    use HasFactory;

    protected $table = 'list_contacts_items';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'list_id',
        'contact_id',
    ];


    public function list()
    {
        return $this->belongsTo(Lists::class, 'list_id');
    }


    public function contact()
    {
        return $this->belongsTo(Contacts::class, 'contact_id');
    }


   // protected static function newFactory(): ListContactItemFactory
   // {
   //     //return ListContactItemFactory::new();
  //  }
}
