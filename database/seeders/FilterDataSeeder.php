<?php

namespace Database\Seeders;

use App\Models\Dish;
use App\Models\Restaurant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FilterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $fileJson = file_get_contents('data.json');
        $decodeJson = json_decode($fileJson, true);
        $dishes = $decodeJson['dishes'];

//        $res = [];
//        foreach ($dishes as $dish) {
//           $res[] = [
//               'name' => $dish['restaurant'],
//           ];
//        }
//
//        $resUnique = $this->array_key_unique($res, 'name');
//
//        foreach ($resUnique as $resU) {
//            Restaurant::create($resU);
//        }

        $dishArr = [];
        foreach ($dishes as $dish) {
            $dishArr[] = [
                'name' => $dish['name'],
                'res_name' => $dish['restaurant'],
                'available_meals' => $dish['availableMeals']
            ];
        }

        $dishUn = $this->array_key_unique($dishArr, 'name');

        foreach ($dishUn as $d) {
            $res = Restaurant::where('name', $d['res_name'])->first();
            Dish::create([
                'name' => $d['name'],
                'restaurant_id' => $res->id,
                'available_meals' => $d['available_meals']
            ]);
        }
    }

    public function array_key_unique ($arr, $key) {
        $uniquekeys = array();
        $output     = array();
        foreach ($arr as $item) {
            if (!in_array($item[$key], $uniquekeys)) {
                $uniquekeys[] = $item[$key];
                $output[]     = $item;
            }
        }
        return $output;
    }
}
