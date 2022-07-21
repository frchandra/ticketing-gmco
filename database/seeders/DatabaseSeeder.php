<?php

namespace Database\Seeders;


use App\Models\Seat;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(){
        $alpha = array('A','B','C','D','E','F');
        for($i=1;$i<=6;$i++){
            ($i<=3)?$price=200:$price=100;
            for($j=1;$j<=6;$j++){
                $name = "{$alpha[$i-1]}{$j}";
                Seat::create([
                    'name' => $name,
                    'price' => $price,
                    'link' => '#',
                    'is_attend' => false,
                    'is_reserved' => false
                ]);
            }
        }

        $this->call([
            BuyerSeeder::class,
        ]);
    }
}
