<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Buyer;
use App\Product;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model {

      use SoftDeletes;

      protected $dates = ['deleted_at'];
      protected $fillable = [
          'quantity',
          'product_id',
          'buyer_id',
      ];

      public function buyer() {

            return $this->belongsTo('App\Buyer');
      }

      public function product() {

            return $this->belongsTo('App\Product');
      }

}
