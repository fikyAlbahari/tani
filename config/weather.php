<?php

return [
    'lat' => (float) env('WEATHER_LAT', -8.3564),
    'lon' => (float) env('WEATHER_LON', 116.1575),
    'place' => env('WEATHER_PLACE', 'Kabupaten Lombok Utara'),
    'news_query' => env('NEWS_QUERY', 'pertanian OR padi OR panen Indonesia'),
];
