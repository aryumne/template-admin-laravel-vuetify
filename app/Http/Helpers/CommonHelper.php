<?php

namespace App\Http\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


class CommonHelper
{
    public static function calculateCoordinates($userlat1, $userlng1, $lat2, $lng2)
    {
        $earthRadius = 6371; // Radius of the Earth in kilometers

        // Convert latitude and longitude from degrees to radians
        $lat1Rad = deg2rad($userlat1); // user
        $lon1Rad = deg2rad($userlng1); // user
        $lat2Rad = deg2rad($lat2);
        $lon2Rad = deg2rad($lng2);

        // Haversine formula
        $dLat = $lat2Rad - $lat1Rad;
        $dLon = $lon2Rad - $lon1Rad;
        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos($lat1Rad) * cos($lat2Rad) *
            sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c * 1000; // Convert to meters

        return $distance;
    }

    public static function storingImagePngBase64($base64, $folderPath)
    {
        $string    = Str::replace('data:image/png;base64,', '', $base64);
        $file      = Str::replace(' ', '+', $string);
        $name      = Str::slug(auth()->user()->profile->name);
        $datetime  = now()->format('ymdhis');
        $filename  = $datetime . '-' . $name . '.png';
        $src = $folderPath . $filename;
        $path      = Storage::put($src, base64_decode($file));
        return $path ? $src : null;
    }
}
