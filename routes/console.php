<?php
 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;
use App\Models\DataCollector;



Artisan::command('collect data', function () {
    DataCollector::collect();
})->purpose('Delete recent users')->everyMinute();