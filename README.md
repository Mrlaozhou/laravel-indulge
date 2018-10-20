# Laravel-Indulge
<p align="center">
<a href="http://blog.52laozhou.com"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
</p>


## Install
To install through Composer, by run the following command:

```bash
composer require "mrlaozhou/laravel-indulge"
```

The package will automatically register a service provider and alias.

Optionally, publish the package's configuration file by running:

``` bash
php artisan vendor:publish --provider="Mrlaozhou\laravel-indulge\LaravelIndulgeServiceProvider"
```

## Document

### Config



### Using

Use trait "\mrlaozhou\laravel-indulge\Indulge" in you (Eloquent)Model.

ep: 
    Model: App\Models\Leads
	
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Mrlaozhou\Indulge\Indulge;

class Leads extends Model
{
    use Indulge;
}
```

#### Create a new data

```bash
(new Leads())->indulgeCreate();
```

| 		Action		 |      Data Model      |      Instance Model      |    
|:-------:|:-------------:| :----------:|
|  create  |     `$leads->indulgeCreate($attributes)`    |   ~   |
|  update  |     `$leads->indulgeUpdate($attributes)`    |   `$leads->newQuery()->find($id)->indulgeUpdate($attributes)`   |
|  select  |     `$leads->indulgeList()->where()->get()`   |   ~   |
|  delete  |     `$leads->indulgeDelete()`    |   ~   |
|  destroy |     `$leads->indulgeCreate($attributes)`    |   ~   |
|  find    |     `$leads->indulgeFind()`    |   `$leads->indulgeFind($id, $columns)`   |
