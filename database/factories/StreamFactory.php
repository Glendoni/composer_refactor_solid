<?php

use Faker\Generator as Faker;

$factory->define(App\Stream::class, function (Faker $faker) {
    return [
       'name' => 'Bicycles',
       'description' => 'Bicycles',
       'name' => 'Survey seeks to find out the percentage of bikers who wear helmets',
       'invite_code' => 777888,
       'start_date' => '2019-08-11',
       'end_date' => '2019-12-29'
    ];
});
