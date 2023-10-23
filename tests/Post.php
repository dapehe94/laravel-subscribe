<?php

namespace Tests;

use Illuminate\Database\Eloquent\Model;
use Dapehe94\LaravelSubscribe\Traits\Subscribable;

class Post extends Model
{
    use Subscribable;

    protected $fillable = ['title'];
}
