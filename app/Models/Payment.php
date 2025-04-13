<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $guarded = [];

    // public $preventAttrSet = false;

    // protected function amount(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn(int $value) => $this->preventAttrSet == true ?  $value : $value / 100,
    //         set: fn(int $value) => $this->preventAttrSet == true ?  $value : $value * 100,
    //     );
    // }
}
