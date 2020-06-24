<?php

use Faker\Generator as Faker;

$factory->define(
    Modules\Indexings\Entities\Indexing::class,
    function (Faker $faker) {
        return [
            'name'       => $faker->name,
            'job_title'  => $faker->jobTitle,
            'company'    => $faker->company,
            'mobile'     => $faker->e164PhoneNumber,
            'email'      => $faker->safeEmail,
            'address1'   => $faker->address,
            'city'       => $faker->city,
            'stage_id'   => App\Entities\Category::indexings()->inRandomOrder()->first()->id,
            'indexing_value' => $faker->randomFloat(2, 10, 1000),
            'country'    => $faker->country,
            'website'    => 'https://' . $faker->domainName,
            'due_date'   => now()->addDays(rand(3, 60)),
            'source'     => App\Entities\Category::whereModule('source')->inRandomOrder()->first()->id,
            'sales_rep'  => $faker->numberBetween(1, 5),
        ];
    }
);
