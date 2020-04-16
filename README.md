# Queryable

<a href="https://packagist.org/packages/hasnath/queryable"><img src="https://poser.pugx.org/hasnath/queryable/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/hasnath/queryable"><img src="https://poser.pugx.org/hasnath/queryable/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/hasnath/queryable"><img src="https://poser.pugx.org/hasnath/queryable/license.svg" alt="License"></a>

## Introduction

`Queryable` gives you query builder helpers such as `Filter` and `OrderBy` to make APIs easier to create.

## Requirements

The following is required to use Queryable
```text
Laravel 
PHP 7
```

## Installation

To get started, install Queryable via the Composer package manager:

```bash
composer require hasnath/queryable
```

Add the `QueryableServiceProvider` to your `providers` in `config/app.php`

```php
HasnatH\Queryable\Providers\QueryableServiceProvider::class
``` 

Publish the configuration file  
```bash
php artisan vendor:publish --provider="HasnatH\Queryable\Providers\QueryableServiceProvider"
```

## Configuration

The configuration file will be published `config/queryable.php`

```php
<?php

return [
    'filter' => [
        'separator' => env('QUERYABLE_FILTER_SEPARATOR', ',')
    ],

    'orderBy' => [
        'separator' => env('QUERYABLE_ORDER_BY_SEPARATOR', ',')
    ],
];
``` 

## Usage

Instantiate a new `Queryable` and pass in the Model to be used 

Use the available `Queryable` methods

```php
<?php

use App\User;
use HasnatH\Queryable\Queryable;

class UserService 
{    
    protected $queryable;

    public function index() 
    {
        $queryable = new Queryable(User::class);

        $query = User::query();
        
        $query = $queryable->filter($query, request()->get('filter'));
        $query = $queryable->orderBy($query, request()->get('orderBy'));
    
        return $query->get();
    }

}
``` 

### Filter 

`Queryable` gives you the ability to easily filter database queries

#### Configuration

Specific configuration for the `filter` method can be found in the `filter` array in`config/queryable.php`

As default, all fields in a model can be filtered

To override which fields you want to filter by add the following in to your model

```php
public static $filterable = [
    'first_name',
    'last_name'
];
```  

Any filters that contain a field that is not present in the model/filterable will be ignored.

#### Format

To apply filters, call the `filter` method on your `Queryable` instance.
The `filter` method accepts two parameters: `$query`, `$filters`

The `$query` parameter is an instance of your query builder

```php
$query = User::query();
```
```php
$query = auth()->user();
``` 

The `$filter` parameter is an array of your filters in the following format

```php
[
    "FIELD,VALUE,OPERATOR",
]
```

The `FIELD` and `VALUE` fields are required for each filter. 

The `OPERATOR` field is optional and will default to `=`

The following `OPERATORS` can be used

```
= 
< 
> 
<= 
>=
<>
!=
LIKE
NOT LIKE
BETWEEN
ILIKE
```

You can apply as many filters as you would like.

For example, to apply filters for `first_name` and `last_name`:
```php
[
    ["first_name,John"],
    ["last_name,Smith"]   
]
``` 

The default separator for the `filter` method is `,`

This value is retrieved from `config/queryable.php` `filter.separator`

To change the separator, update the `filter.separator` value in the config or add an `.env` variable

```dotenv
QUERYABLE_FILTER_SEPARATOR=|
```

You will then need to pass filters in the following format 

```php
[
    ["first_name|John"],
    ["last_name|Smith"]   
]
``` 

#### Usage Example
 
The following will pass any filters passed via the request and apply them 

```
public function index() 
{
    $queryable = new Queryable(User::class);
    
    $filters = request()->get('filter');

    $query = User::query();
    
    $query = $queryable->filter($query, $filters);

    return $query->get();
}
```

To get the users that have the `first_name` `John` and `id` greater than `3`
```
GET /users?filter[]=first_name,John&filter[]=id,3,>
```

### OrderBy 

`Queryable` gives you the ability to easily order database queries

#### Configuration

Specific configuration for the `orderBy` method can be found in the `orderBy` array in`config/queryable.php`

As default, you can apply an order on all fields in a model 

To override which fields you want to order by add the following in to your model

```php
public static $orderable = [
    'first_name',
    'last_name'
];
```  

Any orders that contain a field that is not present in the model/orderable will be ignored.

#### Format

To apply ordering, call the `orderBy` method on your `Queryable` instance.
The `orderBy` method accepts two parameters: `$query`, `$fields`

The `$query` parameter is an instance of your query builder

```php
$query = User::query();
```
```php
$query = auth()->user();
``` 

The `$fields` parameter is an array of your order fields in the following format

```php
[
    "FIELD,ORDER",
]
```

The `FIELD` field is required for each order. 

The `ORDER` field is optional and will default to `ASC`

The following `ORDER` values can be used

```
ASC
DESC
```

You can apply as many orders as you would like.

For example, to order by `first_name` and then `last_name`
```php
[
    ["first_name"],
    ["last_name,DESC"]   
]
``` 

The default separator for the `orderBy` method is `,`

This value is retrieved from `config/queryable.php` `orderBy.separator`

To change the separator, update the `orderBy.separator` value in the config or add an `.env` variable

```dotenv
QUERYABLE_ORDER_BY_SEPARATOR=|
```

You will then need to pass filters in the following format 

```php
[
    ["first_name"],
    ["last_name|DESC"]   
]
``` 

#### Usage Example
 
The following will pass any orders passed via the request and apply them 

```
public function index() 
{
    $queryable = new Queryable(User::class);
    
    $filters = request()->get('filter');

    $query = User::query();
    
    $query = $queryable->orderBy($query, $filters);

    return $query->get();
}
```

To order by the `first_name` in ascending order and then the `id` in descending order 
```
GET /users?orderBy[]=first_name&orderBy[]=id,DESC
```

## License

`Queryable` is open-sourced software licensed under the [MIT license](LICENSE.md).
