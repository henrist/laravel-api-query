# Laravel API Query
This repository contains a work-in-progress package for giving a API-endpoint filtering, pagination, relationships and ordering capabilities.

It currently only supports models that is built on Eloquent.

The package will be put on packagist when it is considered stable. For now the interfaces might change.

## Installation
As the package is not currently released to Packagist, you have to set up the repository manually in `composer.json`:
```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/henrist/laravel-api-query"
    }
],
"require": {
    "henrist/laravel-api-query": "dev-master"
}
```

Add the service provider in the providers array in ```app/config/app.php```.

```php
'Henrist\LaravelApiQuery\LaravelApiQueryServiceProvider',
```

Add the facade to the aliases array.

```php
'ApiQuery' => 'Henrist\LaravelApiQuery\Facades\ApiQuery',
```

## Usage
In a controller, pass a Eloquent-builder through ApiQuery's processCollection-method and return the results.

```php
return ApiQuery::processCollection(MyModel::query());
```

You can also force some attributes to the query by using regular methods:

```php
return ApiQuery::processCollection(MyModel::where('active', true));
```

For the model to work correctly it has to implement `ApiQueryInterface`. This will tell which fields can be used for filtering/ordering and which fields are relations to other models. This is needed as it is not possible to determine this by the application itself, and for security conserns.

The data returned will eventually be a collection of MyModel and will respect visible-, hidden-, appends-attributes.

### Querying at the API-endpoint
Ordering (prepend field with - for descending):

```
url/to/api?order=myfield
```

Filtering:

```
url/to/api?filter=myfield=Value
url/to/api?filter=myfield:like:Val%
url/to/api?filter=myfield<10,myfield>0
```

Attaching relations:
```
url/to/api?with=user
url/to/api?with=user.groups&filter=user.name=henrist
```

Pagination:
```
url/to/api?limit=10&offset=10
```

Limiting fields:
```
url/to/api?fields=id,title
url/to/api?fields=id,parent.id,children.id,children.title&with=parent,children
```

## License
The MIT License (MIT)

Copyright (c) 2014 Henrik Steen

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

