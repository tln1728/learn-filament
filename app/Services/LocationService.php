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
                $response = Http::timeout(60)->get('https://countriesnow.space/api/v0.1/countries');

                if ($response->successful()) {
                    $countries = collect($response->json()['data'])
                        ->pluck('country', 'country')
                        ->toArray();
                    info('Countries fetched: ' . json_encode($countries));
                    return $countries;
                }

                Log::error('Failed to fetch countries: Empty or invalid response');
                return [];
            } catch (\Exception $e) {
                Log::error('Failed to fetch countries: ' . $e->getMessage());
                return [];
            }
        });
    }

    public function getCities($country = null)
    {
        if (!$country) {
            return [];
        }

        return Cache::remember("{$country}_cities", now()->addDays(7), function () use ($country) {
            try {
                info("Fetching cities for country: {$country}");
                $response = Http::timeout(60)->post('https://countriesnow.space/api/v0.1/countries/cities', [
                    'country' => $country
                ]);

                if ($response->successful()) {
                    $cities = collect($response->json()['data'])
                        ->mapWithKeys(function ($city) {
                            return [$city => $city];
                        })
                        ->toArray();
                    info('Cities fetched: ' . json_encode($cities));
                    return $cities;
                }

                Log::error("Failed to fetch cities for {$country}: Empty or invalid response");
                return [];
            } catch (\Exception $e) {
                Log::error("Failed to fetch cities for {$country}: " . $e->getMessage());
                return [];
            }
        });
    }
}