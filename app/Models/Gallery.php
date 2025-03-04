<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;


class Gallery extends Model
{
    use HasFactory;

    protected $fillable = ['image', 'type', 'product_id'];

    public $uploadDir = 'assets/site/img/';

    public function image(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->uploadDir. $value
        );
    }
}
