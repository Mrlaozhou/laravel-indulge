# Laravel-Indulge

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

You can modify the provider at will.

Filepath: config/indulge.php

```php
return [
    'providers'                 =>  [
        /**
         *
         * Indulge option provider
         */
        'option'            =>  \Mrlaozhou\Indulge\Entities\Option::class,

        /**
         *
         * Indulge field provider
         */
        'field'             =>  \Mrlaozhou\Indulge\Entities\Field::class,

        /**
         *
         * Indulge value provider
         */
        'value'             =>  \Mrlaozhou\Indulge\Entities\Value::class
    ],
];
```


### Using

#### Model
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

#### Facade

```angular2html
IndulgeOption::trees($pid);	// recursion

IndulgeOption::lists($pid); // recursion

IndulgeOption::roots(); // pid === 0

IndulgeOption::child($pid); // sub
```