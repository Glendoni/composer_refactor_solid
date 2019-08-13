<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Study_item extends Model
{
    protected $fillable = ['name', 'study_id', 'created_by', 'note'];
    protected $visible = ['id', 'study_id', 'note', 'name'];

    public function items()
    {
        return $this->belongsToMany('App\study_item_access', 'accesses', 'study_items_id', '');
    }
}
