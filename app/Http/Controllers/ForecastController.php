<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ForecastController extends Controller
{
    public function getCurrentWeather($city)
    {
        $apiKey = env('OPENWEATHERMAP_API_KEY');
        $response = Http::get("https://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$apiKey}&units=metric");

        if ($response->json()['cod'] == '404') {
            return redirect()->back()->with('error', 'City not found');
        }
        
        return $response->json();
    }
    public function getWeeklyForecast($city)
    {
        $apiKey = env('OPENWEATHERMAP_API_KEY');
        $response = Http::get("https://api.openweathermap.org/data/2.5/forecast?q={$city}&appid={$apiKey}&units=metric");
    
        if ($response->json()['cod'] == '404') {
            return redirect()->back()->with('error', 'City not found');
        }
    
        // 日付ごとのグループ予測
        $forecasts = $response->json()['list'];
        $groupedForecasts = [];
    
        foreach ($forecasts as $forecast) {
            $date = substr($forecast['dt_txt'], 0, 10); // 「dt_txt」フィールドからYYYY-MM-DD部分のみを抽出します
    
            if (!isset($groupedForecasts[$date])) {
                $groupedForecasts[$date] = $forecast;
            }
        }
    

        $result = $response->json();
        $result['list'] = array_values($groupedForecasts);
    
        return $result;
    }
    

    public function showForecast(Request $request)
    {
        $city = $request->input('city');
        if (!$city) {
            return view('forecast');
        }

        $currentWeatherData = $this->getCurrentWeather($city);
        $weeklyWeatherData = $this->getWeeklyForecast($city);

        return view('forecast', [
            'weatherData' => $currentWeatherData,
            'weeklyWeatherData' => $weeklyWeatherData,
            'city' => $city,
        ]);
    }
}
