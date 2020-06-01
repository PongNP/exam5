<?php

use Illuminate\Database\Seeder;
use App\Order;
use MStaack\LaravelPostgis\Geometries\Point;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $isFirst = true;
        $csvPath = storage_path('csv/orders_data.csv');
        if (($file = fopen($csvPath , "r")) !== FALSE) {
            while(! feof($file)){
                $tmp = fgetcsv($file);
                if($isFirst){
                    $isFirst = false;
                    continue;
                }
                if(!$tmp){
                    break;
                }
                Order::updateOrCreate(
                    array(
                        "location" => (new Point(floatval($tmp[0]),floatval($tmp[1])))->toWKT()
                    ),
                    array(
                        "location" => new Point(floatval($tmp[0]),floatval($tmp[1])),
                        "total" => intval($tmp[2])
                    )
                );
            }
            fclose($file);
        }
    }
}
