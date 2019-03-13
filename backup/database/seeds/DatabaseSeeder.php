<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = App\Models\User::create([
            'name' => config('app.name'),
            'password' => Hash::make(env('APP_PASSWORD', 'password')),
            'email' => 'admin@mintfinancial.com.au',
        ]);

        foreach (range(0, mt_rand(5, 20)) as $_) {
            $agreement = factory(App\Models\Agreement::class)->make();
            $user->agreements()->save($agreement);

            foreach (range(0, mt_rand(1, 4)) as $_) {
                $value = is_null($agreement->accepted_at) ? null : (bool) mt_rand(0, 1);
                $agreement->options()->save(factory(App\Models\AgreementOption::class)->make(['value' => $value]));
            }
        }
    }
}
