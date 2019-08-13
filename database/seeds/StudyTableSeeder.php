<?php

use Illuminate\Database\Seeder;

class StudyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Study::class, 1)->create();
    }
}
