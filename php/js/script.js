// Initial Theme for the
var theme = 'php/css/darkly.css';
var mid;
var tid;

onload = function() {
    var progress = calcProgress(startdate, enddate);
    var project = name;
    progressBar(project, progress);
    adjustmentHeight(project);
    getGeolocation();
};

$("#save").click(function(){

    $("#warning").html("");
    var name = $("#name").val();
    $("#name").val("");
    var startdate = $("#startdate").val();
    $("#startdate").val("");
    var enddate = $("#enddate").val();
    $("#enddate").val("");

    if (name.length > 20) {
        name = name.substring(0, 20);
    }

    if (startdate < enddate) {
        $.ajax({
            type: "GET", url: "php/newproject.php",
            data: {name: name, startdate: startdate, enddate: enddate},
            error: function (e) {
                console.info(e.statusText);
            }
        });
        $("#alert").html("<div class='alert alert-success'><strong>Succes! </strong>" + name + " is now ready to use.</div>");
        setTimeout(function() {
            window.location = "projects.php?name=" + name;
        }, 500);
    } else if(startdate > enddate) {
        $("#name").val(name);
        $("#alert").html("<div class='alert alert-warning'><strong>Warning! </strong>The start date must be earlier than the end date.</div>");
    } else if (startdate === enddate) {
        $("#name").val(name);
        $("#alert").html("<div class='alert alert-warning'><strong>Warning! </strong>Start date and end date must be different.</div>")
    }
});

$(".msBtn").click(function () {
    mid = ($(this).val());
    $('html, body').animate({scrollTop : 110},600);
    $("#content").load("content_loader.php?pid=" + pid + "&mid=" + mid + "&mode=milestoneview");
});

tabButtons(".projectTab", "project");
tabButtons(".statsTab", "stats");
tabButtons(".milestoneTab", "milestone");
tabButtons(".meetingTab", "meeting");
tabButtons(".statsTab", "stats");

function tabButtons(button, name){
    $(button).click(function () {
        var mode = ($(this).val());
        if (name === "milestone" && mode !== "add") {            
            $("#content").load("content_loader.php?pid=" + pid + "&mid=" + mid + "&mode=" + name + mode);
        } else if (name === "project" && mode === "delete") {
            $("#content").load("content_loader.php?pid=" + pid + "&mode=" + name + mode);
        }
        else {
            $("#content").load("content_loader.php?pid=" + pid + "&mode=" + name + mode);
        }
    });
}

$("#content").on("click", ".task", function () {
    tid = ($(this).val());
    $("#content").load("content_loader.php?pid=" + pid + "&mid=" + mid + "&tid=" + tid + "&mode=taskmodal");
});

dynamicButtonsUsers("#remove_user", "removeuser");
dynamicButtonsUsers("#promote_user", "promoteuser");
dynamicButtonsUsers("#demote_user", "demoteuser");
dynamicButtonsUsers("#add_user", "adduser");

dynamicButtonsUsers("#Btn_confirmDelete", "confirmdelete");
dynamicButtonsUsers("#Btn_declineDelete", "declinedelete");

function dynamicButtonsUsers(button, mode){
    $("#content").on("click", button, function () {
        var uid = ($(this).val());
        if(button === "#add_user")
        {
            uid = ($("#add_user_select").val());
            $("#content").load("content_loader.php?pid=" + pid + "&uid=" + uid + "&mode=" + mode);
        } else if(button === "#Btn_confirmDelete") {
            $("#content").load("content_loader.php?pid=" + pid + "&uid=" + uid + "&mode=" + mode);
            window.location = "index.php";
        } else{
            $("#content").load("content_loader.php?pid=" + pid + "&uid=" + uid + "&mode=" + mode);
        }
    });
}

$("#content").on("click", "#save_milestone", function(){
    var name = $("#name").val();
    var desc = $("#desc").val();
    var start = $("[name='start']").val();
    var stop = $("[name='stop']").val();

    $.ajax({type: "GET", url: "php/editmilestone.php",
        data: {name: name, id: milestoneid, desc: desc, start: start, stop: stop},
        success : function() {
            alert("Milestone was changed successfully!");
        },
        error : function(e) {
            alert(e.statusText);
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
        loadWeather(position.coords.latitude+','+position.coords.longitude);
    });
}

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

// Create the chart for the trend analysis
function createLineChart() {
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
        paper_bgcolor: '#2C3E50',
        plot_bgcolor: '#2C3E50',
        margin: {
            l: 70,
            r: 70,
            b: 70,
            t: 70,
            pad: 7
        },
        title: 'Milestone Deviation',
        xaxis: {
            title: 'Dates',
            gridcolor: 'white'
        },
        yaxis: {
            title: 'Deviation',
            gridcolor: 'white'
        }
    };
    Plotly.newPlot("line-chart", data, layout);
}

function createPieChart()
{
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
        paper_bgcolor: '#2C3E50',
        plot_bgcolor: '#2C3E50',
        margin: {
            l: 70,
            r: 70,
            b: 70,
            t: 70,
            pad: 7
        },
        title: 'Milestones reached...'
    };

    Plotly.newPlot("pie-chart", data, layout);
}