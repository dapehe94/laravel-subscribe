<?php

namespace Tests;

use Illuminate\Database\Eloquent\Model;
use Dapehe94\LaravelSubscribe\Traits\Subscribable;
use Dapehe94\LaravelSubscribe\Traits\Subscriber;

class User extends Model
{
    use Subscriber;
    use Subscribable;

    protected $fillable = ['name'];
}
