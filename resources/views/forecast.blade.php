<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Weather Forecast</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;600&display=swap" rel="stylesheet">
    <link href="{{ asset('app.css') }}" rel="stylesheet">
</head>        
<body>
  <div class="container">
  <div class="container-background">
    <h1 class="text-center"><b>天気予報</b></h1>
    {{-- 都市名入力フォーム --}}
    <form action="{{ url('/forecast') }}" method="get" class="mt-5 text-center">
    @csrf
      <div class="input-group mb-3">
        <input type="text" class="form-control" placeholder=都市名を入力してください" name="city" value="{{ $city ?? '' }}">
        <div class="input-group-append">
          <br>
            <button class="btn btn-outline-primary" type="submit">見せる</button>
        </div>
      </div>
        @error('city') {{-- 検証エラーメッセージを表示する --}}
            <p class="text-danger">{{ $message }}</p>
        @enderror
    </form>

    {{-- @WeatherDataが存在する場合に以下の処理を実行します --}}
    @if ($weatherData ?? false)
        <h2 class="text-center">{{ $weatherData['name'] }}</h2>
            <p class="text-center">温度: {{ $weatherData['main']['temp'] }}°C</p>
            <p class="text-center">湿度: {{ $weatherData['main']['humidity'] }}%</p>
            <p class="text-center">説明： {{ $weatherData['weather'][0]['description'] }}</p>
    <div class="text-center">
        <img class="weather-icon" src="https://openweathermap.org/img/wn/{{ $weatherData['weather'][0]['icon'] }}@2x.png" alt="Weather Icon">
    </div>
    @endif

    {{-- @weeklyWeatherDataが存在する場合に以下の処理を実行します --}}
    @if ($weeklyWeatherData ?? false)
    <div class="weekly-forecast text-center">
        <h3>週間天気予報</h3>
        @foreach ($weeklyWeatherData['list'] as $index => $day)
          <div class="day-weather" style="animation-delay: {{ ($index * 0.1) . 's' }};" >
              <span class="day-name">{{ \Carbon\Carbon::parse($day['dt_txt'])->format('Y-m-d')}}</span>
              <img class="day-icon" src="https://openweathermap.org/img/wn/{{ $day['weather'][0]['icon'] }}@2x.png" alt="Weather Icon">
              <span class="day-temp">{{ $day['main']['temp'] }}°C</span>
              <span class="day-humidity">{{ $day['main']['humidity'] }}%</span>
              <span class="day-description">{{ $day['weather'][0]['description'] }}</span>
          </div>
          <br>
        @endforeach
    </div>
    @endif
  </div>
  </div>

<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
