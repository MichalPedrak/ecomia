<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataCollector extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_ean',
        'price',
        'quantity'
    ];

    public function products(){ // hasOne hasMany belongsTo blongsToMany
        return $this->belongsTo(Product::class, 'product_ean');
    }


    public static function collect()
    { // hasOne hasMany belongsTo blongsToMany

        ini_set('max_execution_time', 0);

        ini_set('memory_limit', 20480000000);

        $url = 'https://orders.pvex.pl/pricelist.php?wh=all&token=404D9EC0AA35F0255B392D2A8F20091B8518&format=xml';
        header("Content-Type: text/plain");
        $content = file_get_contents($url);

        $xml = simplexml_load_string($content);


        foreach ($xml as $product) {


            $ean = $product->model;
            $price = $product->cena;
            $quantity = $product->ilosc;


            DataCollector::create([

                'product_ean' => $ean,
                'price' => $price,
                'quantity' => $quantity,

            ]);
        }

    }
}
