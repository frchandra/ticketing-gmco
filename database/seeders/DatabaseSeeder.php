<?php

namespace Database\Seeders;


use App\Models\Seat;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use function config;
use function env;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(){
        /*
         * blok kategori A depannya undangan/sponsor (A-E)(10-21)
         * */
        $temp = array('A', 'B', 'C', 'D', 'E');
        for($i=1; $i<=5; $i++){
            for($j=10; $j<=21; $j++){
                $name = "{$temp[$i-1]}{$j}";
                Seat::create([
                    'name' => $name,
                    'price' => 160000,
                    'link' => '#',
                    'ticket_status' => "notExchanged",
                    'is_reserved' => 0
                ]);
            }
        }
        /*
        * blok kategori A belakannya undangan/sponsor (H-L)(10-21)
        * */
        $temp = array('H', 'I', 'J', 'K', 'L');
        for($i=1; $i<=5; $i++){
            for($j=10; $j<=21; $j++){
                $name = "{$temp[$i-1]}{$j}";
                Seat::create([
                    'name' => $name,
                    'price' => 160000,
                    'link' => '#',
                    'ticket_status' => "notExchanged",
                    'is_reserved' => 0
                ]);
            }
        }
        /*
         * Kategori b sayap kiri (E-M)(1-9)
         * */
        $temp = array('E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M');
        for($i=1; $i<=9; $i++){
            for($j=1; $j<=9; $j++){
                $name = "{$temp[$i-1]}{$j}";
                Seat::create([
                    'name' => $name,
                    'price' => 140000,
                    'link' => '#',
                    'ticket_status' => "notExchanged",
                    'is_reserved' => 0
                ]);
            }
        }

        /*
        * Kategori b sayap kanan (E-M)(22-30)
        * */
        $temp = array('E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M');
        for($i=1; $i<=9; $i++){
            for($j=22; $j<=30; $j++){
                $name = "{$temp[$i-1]}{$j}";
                Seat::create([
                    'name' => $name,
                    'price' => 140000,
                    'link' => '#',
                    'ticket_status' => "notExchanged",
                    'is_reserved' => 0
                ]);
            }
        }
        /*
         * rest of kategoti a (paling belakang)
         * */
        for($i=10; $i<=15; $i++){
            Seat::create([
                'name' => "M{$i}",
                'price' => 160000,
                'link' => '#',
                'ticket_status' => "notExchanged",
                'is_reserved' => 0
            ]);
        }
        /*
         * rest of kategori b
         * */
        /*
         * pojok kiri atas
         * */
        $temp = array('B', 'C', 'D');
        for($i=1; $i<=3; $i++){
            for($j=3; $j<=9; $j++){
                $name = "{$temp[$i-1]}{$j}";
                Seat::create([
                    'name' => $name,
                    'price' => 140000,
                    'link' => '#',
                    'ticket_status' => "notExchanged",
                    'is_reserved' => 0
                ]);
            }
        }
        /*
         * pojok kanan atas
         * */
        $temp = array('B', 'C', 'D');
        for($i=1; $i<=3; $i++){
            for($j=22; $j<=28; $j++){
                $name = "{$temp[$i-1]}{$j}";
                Seat::create([
                    'name' => $name,
                    'price' => 140000,
                    'link' => '#',
                    'ticket_status' => "notExchanged",
                    'is_reserved' => 0
                ]);
            }
        }
        /*
         * paling belakakng
         * */
        for($i=1; $i<=15; $i++){
            Seat::create([
                'name' => "O{$i}",
                'price' => 140000,
                'link' => '#',
                'ticket_status' => "notExchanged",
                'is_reserved' => 0
            ]);
        }
        for($i=23; $i<=30; $i++){
            Seat::create([
                'name' => "O{$i}",
                'price' => 140000,
                'link' => '#',
                'ticket_status' => "notExchanged",
                'is_reserved' => 0
            ]);
        }
        Seat::create([
            'name' => 'A8',
            'price' => 140000,
            'link' => '#',
            'ticket_status' => "notExchanged",
            'is_reserved' => 0
        ]);
        Seat::create([
            'name' => 'A9',
            'price' => 140000,
            'link' => '#',
            'ticket_status' => "notExchanged",
            'is_reserved' => 0
        ]);
        Seat::create([
            'name' => 'A22',
            'price' => 140000,
            'link' => '#',
            'ticket_status' => "notExchanged",
            'is_reserved' => 0
        ]);
        Seat::create([
            'name' => 'A23',
            'price' => 140000,
            'link' => '#',
            'ticket_status' => "notExchanged",
            'is_reserved' => 0
        ]);
        /*
         * special reserved seat
         * */
        Seat::create([
            'name' => 'C1',
            'price' => 160000,
            'link' => '#',
            'ticket_status' => "notExchanged",
            'is_reserved' => config('constants.MAX_VALUE')
        ]);
        Seat::create([
            'name' => 'C2',
            'price' => 160000,
            'link' => '#',
            'ticket_status' => "notExchanged",
            'is_reserved' => config('constants.MAX_VALUE')
        ]);
        Seat::create([
            'name' => 'D1',
            'price' => 160000,
            'link' => '#',
            'ticket_status' => "notExchanged",
            'is_reserved' => config('constants.MAX_VALUE')
        ]);
        Seat::create([
            'name' => 'D2',
            'price' => 160000,
            'link' => '#',
            'ticket_status' => "notExchanged",
            'is_reserved' => config('constants.MAX_VALUE')
        ]);
        Seat::create([
            'name' => 'C29',
            'price' => 160000,
            'link' => '#',
            'ticket_status' => "notExchanged",
            'is_reserved' => config('constants.MAX_VALUE')
        ]);
        Seat::create([
            'name' => 'C30',
            'price' => 160000,
            'link' => '#',
            'ticket_status' => "notExchanged",
            'is_reserved' => config('constants.MAX_VALUE')
        ]);
        Seat::create([
            'name' => 'D29',
            'price' => 160000,
            'link' => '#',
            'ticket_status' => "notExchanged",
            'is_reserved' => config('constants.MAX_VALUE')
        ]);
        Seat::create([
            'name' => 'D30',
            'price' => 160000,
            'link' => '#',
            'ticket_status' => "notExchanged",
            'is_reserved' => config('constants.MAX_VALUE')
        ]);
        $temp = array('F', 'G');
        for($i=1; $i<=2; $i++){
            for($j=10; $j<=21; $j++){
                $name = "{$temp[$i-1]}{$j}";
                Seat::create([
                    'name' => $name,
                    'price' => 160000,
                    'link' => '#',
                    'ticket_status' => "notExchanged",
                    'is_reserved' => config('constants.MAX_VALUE')
                ]);
            }
        }


        /*
         * default uname password
         * */
        User::create([
            'name' => 'gmco',
            'password' => \Hash::make('w00d-w!nd'),
        ]);

        $this->call([
            BuyerSeeder::class,
        ]);
    }
}
