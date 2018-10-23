<?php

use Faker\Generator as Faker;

$factory->define(\Mrlaozhou\Indulge\Entities\Field::class, function (Faker $faker) {
    return [
        //
        'table'     =>  $faker->randomElement(['leads', 'customs']),
        'name'      =>  $faker->firstName,
        'label'     =>  $faker->name,
        'type'      =>  $faker->randomElement(['char', 'varchar', 'int', 'enum', 'timestamp']),
        'form_type' =>  $faker->randomElement(['select', 'input', 'email', 'checkbox', 'radio', 'number']),
        'option_id' =>  $faker->numberBetween(1,3),
        'require'   =>  'required',
        'default'   =>  '',
        'description'=> $faker->realText(),
        'showable'  =>  $faker->randomElement([1,0]),
        'writeable'  =>  $faker->randomElement([1,0]),
    ];
});
