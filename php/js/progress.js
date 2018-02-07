// Initial Theme for the
var theme = 'php/css/darkly.css';
var mid;
var tid;

$("#progressContent").ready(progressInit);

function progressInit() {
    var progress = calcProgress(startdate, enddate);
    var project = name;
    progressBar(project, progress);
    adjustmentHeight(project);
}

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





