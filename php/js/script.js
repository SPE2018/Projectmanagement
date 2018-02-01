var theme = 'php/css/darkly.css';

onload = function() {
    var progress = calcProgress(startdate, enddate);
    var project = name;
    progressBar(project, progress);
    adjustmentHeight(project);
    createChart(project);
    getGeolocation();
}

$("#save").click(function(){
    var name = $("#name").val();
    $("#name").val("");
    var startdate = $("#startdate").val();
    $("#startdate").val("");
    var enddate = $("#enddate").val();
    $("#enddate").val("");

    $.ajax({type: "GET", url: "php/newproject.php",
        data: {name: name, startdate: startdate, enddate: enddate},
        error : function(e) {
            console.info(e.statusText);
        }
    });
    window.location = "projects.php?name=" + name + "&startdate=" + startdate + "&enddate=" + enddate;
});

$("#save-milestone").click(function(){
    var name = $("#name").val();
    var desc = $("#desc").val();
    var start = $("[name='start']").val();
    var stop = $("[name='stop']").val();

    $.ajax({type: "GET", url: "php/editmilestone.php",
        data: {name: name, id: milestoneid, desc: desc, start: start, stop: stop},
        error : function(e) {
            console.info(e.statusText);
        }
    });
    window.location.reload();
});


// Calculate the actual project progress
function calcProgress(startDate, endDate){
    var now = moment().format("YYYY-MM-DD");
    now = moment(now);
    var start = moment(startDate);
    var end = moment(endDate);
    var startToNow = moment.duration(now.diff(start));
    var startToEnd = moment.duration(end.diff(start));
    startToNow = startToNow.asDays();
    startToEnd = startToEnd.asDays();
    var progress = (startToNow/startToEnd)*100;
    return progress;
}

// Show the progress bar --> animated
function progressBar(project, progress)
{
    var elem = document.getElementById(project);
    var height = 0;
    elem.style.height = height + '%';
    var id = setInterval(frame, 25);

    function frame() {
        if (height >= progress) {
            clearInterval(id);
        } else {
            height++;
            elem.style.height = height + '%';
        }
    }
}

// Adjust the height of the progress column
function adjustmentHeight(project)
{
    var bar = document.getElementById("progress-"+project);
    var tdHeight = document.getElementById("progress-td-"+project).offsetHeight;
    bar.style.height = tdHeight+"px";

}

// Handle the collapse of the project tables
$("#pm-btn-line-chart").click(function () {
    $(".collapse-line-chart").toggleClass('collapse');
    $("#pm-btn-line-chart").toggleClass('fa-plus');
    $("#pm-btn-line-chart").toggleClass('fa-minus');
});

$("#pm-btn-pie-chart").click(function () {
    $(".collapse-pie-chart").toggleClass('collapse');
    $("#pm-btn-pie-chart").toggleClass('fa-plus');
    $("#pm-btn-pie-chart").toggleClass('fa-minus');
});


$('#themeBtn').click(function(){
    if(theme == 'php/css/darkly.css'){
        $('#theme').attr('href', 'php/css/flatly.css');
        theme = 'php/css/flatly.css';
    } else if(theme == 'php/css/flatly.css'){
        $('#theme').attr('href', 'php/css/darkly.css');
        theme = 'php/css/darkly.css';
    }
});

setInterval(function(){
    var time = moment().format("HH:mm:ss");
    var date = moment().format("DD.MM.YYYY");
    $('#clock').html(time);
    $('#date').html(date);
}, 1000);

function getGeolocation(){
    // Browser support geolocation
    if ("geolocation" in navigator) {
        $('.js-geolocation').show();
    } else {
        $('.js-geolocation').hide();
    }

    // Get geolocation and load weather data
    navigator.geolocation.getCurrentPosition(function(position) {
        alert(position.coords.latitude+','+position.coords.longitude);
        loadWeather(position.coords.latitude+','+position.coords.longitude);
    });
}

function loadWeather(location, woeid) {
    alert(location);
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

// Create the chart for the trend analysis
function createChart(project){
    var trace01 = {
        x: ["01-01-2018", "02-01-2018", "03-01-2018", "04-01-2018"],
        y: [0, 0, 0, 0],
        mode: 'lines',
        name: 'goal',
        line: {
            color: '#3498db'
        }
    };

    var trace02 = {
        x: ["01-01-2018"],
        y: [-1],
        mode: 'markers',
        name: 'positiv',
        marker: {
            color: '#28a745',
            size: 10
        }
    };

    var trace03 = {
        x: ["03-01-2018"],
        y: [0],
        mode: 'markers',
        name: 'neutral',
        marker: {
            color: '#f39c12',
            size: 10
        }
    };

    var trace04 = {
        x: ["02-01-2018", "04-01-2018"],
        y: [1, 2],
        mode: 'markers',
        name: 'negativ',
        marker: {
            color: '#dc3545',
            size: 10
        }
    };

    var data = [trace01, trace02, trace03, trace04];

    var layout = {
        autosize: true,
        height: 450,
        width: 700,
        font: {
            color: '#ffffff'
        },
        paper_bgcolor: 'rgba(0,0,0,0)',
        plot_bgcolor: 'rgba(0,0,0,0)',
        margin: {
            l: 50,
            r: 50,
            b: 50,
            t: 50,
            pad: 5
        },
        xaxis: {
            title: 'Dates'
        },
        yaxis: {
            title: 'Deviation'
        }
    };
    Plotly.newPlot("line-chart-"+project, data, layout);

    var colorSelection = ['#3498db', '#f39c12', '#dc3545'];

    var data = [{
        values: [31, 46, 23],
        labels: ['...earlier', '...in time', '...later'],
        type: 'pie',
        marker: {
            colors: colorSelection
        }
    }];

    var layout = {
        height: 450,
        width: 700,
        font: {
            color: '#ffffff'
        },
        paper_bgcolor: 'rgba(0,0,0,0)',
        plot_bgcolor: 'rgba(0,0,0,0)',
        margin: {
            l: 50,
            r: 50,
            b: 50,
            t: 50,
            pad: 5
        }
    };

    Plotly.newPlot("pie-chart-"+project, data, layout);
}