<?php

use Illuminate\Database\Seeder;

class StreamTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Stream::class, 1)->create();
    }
}
