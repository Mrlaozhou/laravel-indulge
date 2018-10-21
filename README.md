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
php artisan vendor:publish --provider="Mrlaozhou\Indulge\LaravelIndulgeServiceProvider"

php artisan indulge:migrate
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

-----
Seamless expansion .