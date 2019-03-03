<?php

use App\Models\User;
use App\Models\Kudos;
use App\Models\KudosValue;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Kudos::class, function (Faker $faker) {
    return [
        'sender_id' => function () { return factory(User::class)->create(); },
        'message' => $faker->sentence(6),
    ];
});

// $factory->afterCreating(App\Models\Kudos::class, function ($kudos, $faker) {
//     $kudos->receivers()->save(factory(App\Models\User::class)->make());
// });

$factory->define(KudosValue::class, function (Faker $faker) {
    return [
        'kudos_id' => function () { return factory(Kudos::class)->create(); },
        'text' => $faker->word,
    ];
});
