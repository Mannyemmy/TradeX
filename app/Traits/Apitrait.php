<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait Apitrait
{

    public function get_rate($coin, $currency)
    {
        $assetbase = $coin . $currency;
        $price = Http::get("")["0"]["Price"];
        return $price;
    }
};