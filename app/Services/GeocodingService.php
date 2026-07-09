<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GeocodingService
{
    /**
     * Converte endereço em lat/lng via Nominatim (OpenStreetMap).
     */
    public static function geocode(string $street, string $city, string $state, string $cep): ?array
    {
        $query = "{$street}, {$city}, {$state}, {$cep}, Brasil";

        return Cache::remember('geo:'.md5($query), now()->addDays(30), function () use ($query) {
            try {
                $response = Http::withHeaders(['User-Agent' => 'Beework/1.0 (contato@beework.com.br)'])
                    ->timeout(6)
                    ->get('https://nominatim.openstreetmap.org/search', [
                        'q' => $query, 'format' => 'json', 'limit' => 1, 'countrycodes' => 'br',
                    ]);

                $data = $response->json();
                if (!empty($data[0]['lat'])) {
                    return ['lat' => (float) $data[0]['lat'], 'lng' => (float) $data[0]['lon']];
                }
            } catch (\Throwable $e) {
                report($e);
            }
            return null;
        });
    }
}
