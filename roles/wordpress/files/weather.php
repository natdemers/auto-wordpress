<?php
require( 'wp-load.php' );

// Where On Earth ID. Use this to get data from a particular location.
$woeid = 2379574;

$curl_url = "https://www.metaweather.com/api/location/{$woeid}/";

// Initialize
$ch = curl_init();

// Specify which site to request from.
curl_setopt( $ch, CURLOPT_URL, $curl_url );

// Capture output for further processing
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );

// Execute and return the output for processing
$weather_result = curl_exec( $ch );

if($weather_result == FALSE){
   echo "cURL ERROR: " . curl_error($ch);
   exit("unable to fetch weather data");
}

curl_close($ch);

$weather_obj 	= json_decode($weather_result, 1);

// Get the city name
$city_name 	= $weather_obj['title'];

$weather_name 	= $weather_obj['consolidated_weather'][0]['weather_state_name'];

// Get the temperature, round to the nearest decimal, and convert to Fahrenheit
$temperature 	= $weather_obj['consolidated_weather'][0]['the_temp'];
$temperature 	= round($temperature, 1);
$fahrenheit 	= ( $temperature * 9 / 5 + 32 );

// Get the wind speed
$wind_speed 	= $weather_obj['consolidated_weather'][0]['wind_speed'];
$wind_speed 	= round($wind_speed, 2);

// Get the humidity
$humidity 	= $weather_obj['consolidated_weather'][0]['humidity'];

// Get the weather abbreviation for icon
$weather_abbr 	= $weather_obj['consolidated_weather'][0]['weather_state_abbr'];

// Store the post in a variable
$weather_post = "<p style='text-align: center'>
                 <b> $city_name </b><br />
		 Weather: 	 $weather_name <br />
		 Temperature:    $fahrenheit °F <br />
		 Wind Speed:	 $wind_speed MPH
                 Humidity:	 {$humidity}%
                 <img src=https://www.metaweather.com/static/img/weather/{$weather_abbr}.svg>
                 </p>";

// Insert the post
wp_insert_post( array(
   'post_title'		=> "Current $city_name Weather",
   'post_author'	=> $user_ID,
   'post_content'	=> $weather_post,
   'post_status'	=> 'publish'
));
?>
