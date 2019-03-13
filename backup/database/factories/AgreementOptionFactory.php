<?php

use Faker\Generator as Faker;

$factory->define(App\Models\AgreementOption::class, function (Faker $faker) {
    return [
        'prompt' => $faker->text(20)
    ];
});
