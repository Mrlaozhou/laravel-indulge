<?php

use Faker\Generator as Faker;

$factory->define(\Mrlaozhou\Indulge\Entities\Value::class, function (Faker $faker) {
    return [
        //
        'table'         =>  $faker->randomElement(['leads', 'customs']),
        'model_id'      =>  $faker->numberBetween(1, 10),
        'field_id'      =>  $faker->numberBetween(1, 30),
        'value'         =>  $faker->randomElement([1,2,3,4,'Yes','No']),
    ];
});
