Laravel Subscribe
---

:email: User subscribe/unsubscribe feature for Laravel Application.

## Installing

```shell
$ composer require dapehe94/laravel-subscribe -vvv
```

### Configuration

This step is optional

```php
$ php artisan vendor:publish --provider="Dapehe94\\LaravelSubscribe\\SubscribeServiceProvider" --tag=config
```

### Migrations

**You need to publish the migration files for use the package:**

```php
$ php artisan vendor:publish --provider="Dapehe94\\LaravelSubscribe\\SubscribeServiceProvider" --tag=migrations
```


## Usage

### Traits

#### `Dapehe94\LaravelSubscribe\Traits\Subscriber`

```php

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Dapehe94\LaravelSubscribe\Traits\Subscriber;

class User extends Authenticatable
{
    use Subscriber;
    
    <...>
}
```

#### `Dapehe94\LaravelSubscribe\Traits\Subscribable`

```php
use Illuminate\Database\Eloquent\Model;
use Dapehe94\LaravelSubscribe\Traits\Subscribable;

class Post extends Model
{
    use Subscribable;

    <...>
}
```

### API

```php
$user = User::find(1);
$post = Post::find(2);

$user->subscribe($post);
$user->unsubscribe($post);
$user->toggleSubscribe($post);

$user->hasSubscribed($post); 
$post->isSubscribedBy($user); 
```

##### Get object subscribers:

```php
foreach($post->subscribers as $user) {
    // echo $user->name;
}
```

##### Aggregations

```php
// all
$user->subscriptions()->count(); 

// with type
$user->subscriptions()->withType(Post::class)->count(); 

// subscribers count
$post->subscribers()->count();
```

List with `*_count` attribute:

```php
$users = User::withCount('subscriptions')->get();

foreach($users as $user) {
    echo $user->subscriptions_count;
}
```

### Filter subscribables

```php
$posts = Post::hasSubscribers($user)->get();
$posts = Post::hasSubscribers($user->id)->get();
$posts = Post::hasSubscribers([$user1, $user2])->get();
$posts = Post::hasSubscribers([$user1->id, $user2->id])->get();

// or
$posts = Post::subscribedBy($user)->get();
$posts = Post::subscribedBy($user->id)->get();
$posts = Post::subscribedBy([$user1, $user2])->get();
$posts = Post::subscribedBy([$user1->id, $user2->id])->get();
```

### Order by subscribers count

You can query subscribable model order by subscribers count with following methods:

- `orderBySubscribersCountDesc()`
- `orderBySubscribersCountAsc()`
- `orderBySubscribersCount(string $direction = 'desc')`

example: 

```php
$posts = Post::orderBySubscribersCountDesc()->get();
$mostPopularPost = Post::orderBySubscribersCountDesc()->first();
```

### N+1 issue

To avoid the N+1 issue, you can use eager loading to reduce this operation to just 2 queries. When querying, you may specify which relationships should be eager loaded using the `with` method:

```php
// Subscriber
$users = App\User::with('subscriptions')->get();

foreach($users as $user) {
    $user->hasSubscribed($post);
}

// Subscribable
$posts = App\Post::with('subscriptions')->get();
// or 
$posts = App\Post::with('subscribers')->get();

foreach($posts as $post) {
    $post->isSubscribedBy($user);
}
```

### Attach the subscription status to subscribable collection

You can use `Subscriber::attachSubscriptionStatus(Collection $subscribeables)` to attach the user subscription status, it will set `has_subscribed` attribute to each model of `$subscribables`:

#### For model

```php
$user1 = User::find(1);

$user->attachSubscriptionStatus($user1);

// result
[
    "id" => 1
    "name" => "user1"
    "private" => false
    "created_at" => "2021-06-07T15:06:47.000000Z"
    "updated_at" => "2021-06-07T15:06:47.000000Z"
    "has_subscribed" => true  
  ]
```

#### For `Collection | Paginator | LengthAwarePaginator | array`:

```php
$user = auth()->user();

$posts = Post::oldest('id')->get();

$posts = $user->attachSubscriptionStatus($posts);

$posts = $posts->toArray();

// result
[
  [
    "id" => 1
    "title" => "title 1"
    "created_at" => "2021-06-07T15:06:47.000000Z"
    "updated_at" => "2021-06-07T15:06:47.000000Z"
    "has_subscribed" => true  
  ],
  [
    "id" => 2
    "title" => "title 2"
    "created_at" => "2021-06-07T15:06:47.000000Z"
    "updated_at" => "2021-06-07T15:06:47.000000Z"
    "has_subscribed" => true
  ],
  [
    "id" => 3
    "title" => "title 3"
    "created_at" => "2021-06-07T15:06:47.000000Z"
    "updated_at" => "2021-06-07T15:06:47.000000Z"
    "has_subscribed" => false
  ],
  [
    "id" => 4
    "title" => "title 4"
    "created_at" => "2021-06-07T15:06:47.000000Z"
    "updated_at" => "2021-06-07T15:06:47.000000Z"
    "has_subscribed" => false
  ],
]
```

#### For pagination

```php
$posts = Post::paginate(20);

$user->attachSubscriptionStatus($posts);
```

### Events

| **Event** | **Description** |
| --- | --- |
|  `Dapehe94\LaravelSubscribe\Events\Subscribed` | Triggered when the relationship is created. |
|  `Dapehe94\LaravelSubscribe\Events\Unsubscribed` | Triggered when the relationship is deleted. |

## License

MIT
