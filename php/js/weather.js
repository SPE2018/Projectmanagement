onload = getGeolocation;

// get the actual location of the client for weather data
function getGeolocation(){
    // Browser support geolocation
    if ("geolocation" in navigator) {
        $('.js-geolocation').show();
    } else {
        $('.js-geolocation').hide();
    }

    // Get geolocation and load weather data
    navigator.geolocation.getCurrentPosition(function(position) {
        loadWeather(position.coords.latitude+','+position.coords.longitude);
    });
}

// load the actual weather of the clients location
function loadWeather(location, woeid) {
    $.simpleWeather({
        location: location,
        woeid: woeid,
        unit: 'C',
        success: function(weather) {
            html = '<i class="icon-'+weather.code+'"></i>'+ weather.temp+'&deg;'+weather.units.temp+', '+weather.currently+', '+weather.city+', '+weather.region;

            $("#weather").html(html);
        },
        error: function(error) {
            $("#weather").html('<p>'+error+'</p>');
        }
    });
}