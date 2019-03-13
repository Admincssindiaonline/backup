<?php

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Models\Agreement::class, function (Faker $faker) {
    // determines whether the agreement has been seen, 50/50 chance
    $seen = ($faker->boolean()) ? Carbon::instance($faker->dateTimeThisMonth()) : null;

    // determines whether the agreement has been accepted, 50/50 chance only if seen
    $accepted = (!is_null($seen) && $faker->boolean()) ? Carbon::instance($faker->dateTimeBetween($seen)) : null;

    // determines whether the agreement has notes, 50/50 chance only if accepted
    $notes = ($accepted && $faker->boolean()) ? $faker->text(40) : null;

    return [
        'client_name' => $faker->name,
        'subject' => $faker->text(20),
        'initial_text' => 'Hi %client_name%, please accept the %subject% agreement.',
        'notes' => $notes,
        'seen_at' => $seen,
        'accepted_at' => $accepted
    ];
});
