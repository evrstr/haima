<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Cards extends Model
{

    public function product()
    {
        return $this->belongsTo(Products::class, "product_id");
    }


    public static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub
        static::deleted(function ($model){
            if ($model->card_status != 2) {
                Products::where('id', $model->product_id)->decrement('in_stock', 1);
            }
        });

        static::created(function ($model){
            if ($model->card_status != 2) {
                Products::where('id', $model->product_id)->increment('in_stock', 1);
            }
        });
    }

}
