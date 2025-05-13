<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LocationService
{
    public function getCountries()
    {
        return Cache::remember('countries', now()->addDays(7), function () {
            try {
                info('Fetching countries from API');
                $response = Http::timeout(60)->get('https://restcountries.com/v3.1/all');
                $data = $response->successful() ? collect($response->json())->pluck('name.common', 'cca2')->toArray() : [];
                info('Countries fetched: ' . json_encode($data));
                return $data;
            } catch (\Exception $e) {
                Log::error('Failed to fetch countries: ' . $e->getMessage());
                return [];
            }
        });
    }

    public function getCities($countryCode = null)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.countrystatecity.in/v1/countries/IN/cities',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                'X-CSCAPI-KEY: API_KEY'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

        // $url = 'https://api.countrystatecity.in/v1/countries/' . ($countryCode ?: 'VN') . '/cities';
        // $response = Http::withHeaders([
        //     'X-CSCAPI-KEY' => env('CSCAPI_KEY')
        // ])->get($url);
        // return $response->successful() ? collect($response->json())->pluck('name', 'id')->toArray() : [];
    }
}