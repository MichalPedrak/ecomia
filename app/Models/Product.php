<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use XMLReader;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'ean',
        'image',
        'category',
    ];
    public function dataCollectors(){ // hasOne hasMany belongsTo blongsToMany
        return $this->hasMany(DataCollector::class, 'product_ean', 'ean')->latest();
    }
    public function users(){ // hasOne hasMany belongsTo blongsToMany
        return $this->belongsToMany(User::class);
    }
    // public function lastDataCollector()
    // {
    //     return $this->hasOne(DataCollector::class, 'product_ean', 'ean')->latest();

    // }

    public function import()
    { // hasOne hasMany belongsTo blongsToMany

        ini_set('max_execution_time', 121);

        ini_set('memory_limit', 20480000000);

        $url = 'https://mpstrony.pl/feed_primavera.xml';
        header("Content-Type: text/plain");
        $content = file_get_contents($url);

        $xml = simplexml_load_string($content);

        $counter = 0;
        foreach ($xml as $product) {
            if($counter > 10000){
                exit();
            }
            $counter++;
            $name = $product->name[0];
            $eans = $product->eans->ean;
            $image = $product->image[0];
            $category = $product->categories->category;

            Product::create([
                'name' => $name,
                'ean' => $eans,
                'image' => $image,
                'category' => $category,
            ]);
        }

        // add to database



    }


}
