<?php

namespace Database\Seeders;


use App\Models\Seat;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use function env;

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
            ($i<=3)?$price=200000:$price=100000;
            for($j=1;$j<=6;$j++){
                $name = "{$alpha[$i-1]}{$j}";
                Seat::create([
                    'name' => $name,
                    'price' => $price,
                    'link' => '#',
                    'ticket_status' => "notExchanged",
                    'is_reserved' => false
                ]);
            }
        }

        User::create([
            'name' => 'gmco',
            'password' => \Hash::make('w00d-w!nd'),
        ]);

        $this->call([
            BuyerSeeder::class,
        ]);
    }
}
