<?php

return [
    'lat' => (float) env('WEATHER_LAT', -6.2088),
    'lon' => (float) env('WEATHER_LON', 106.8456),
    'place' => env('WEATHER_PLACE', 'Jakarta'),
    'news_query' => env('NEWS_QUERY', 'pertanian OR padi OR panen Indonesia'),
];
