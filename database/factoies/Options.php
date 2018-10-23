<?php

use Faker\Generator as Faker;

$factory->define(\Mrlaozhou\Indulge\Entities\Option::class, function (Faker $faker) {
    return [
        //
        'name'          =>  $faker->name,
        'keywords'      =>  $faker->words(1)[0],
        'pid'           =>  $faker->randomElement([1,2,3]),
        'created_at'    =>  date('Y-m-d H:i:s', time()-3600),
        'updated_at'    =>  date('Y-m-d H:i:s'),
    ];
});
