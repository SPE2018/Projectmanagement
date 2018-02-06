<?php
include_once('util/milestone_manager.php');

function get_head()
{
    return file_get_contents("php/html/head.html");
}

function get_simplenav() {
    return file_get_contents("php/html/simplenav.html");   
}

function get_navtop()
{
    return file_get_contents("php/html/navtop.html");
}

function get_navbottom($user)
{
    $str = file_get_contents("php/html/navbottom.html");    
    return str_replace("%MYUSERNAME%", $user, $str);
}

function get_jumbotop()
{
    return file_get_contents("php/html/jumbotop.html");
}

function get_index_jumbotop()
{
    return file_get_contents("php/html/index_jumbotop.html");
}

function get_jumbobot()
{
    return file_get_contents("php/html/jumbobot.html");
}

function get_tabs()
{
    return file_get_contents("php/html/tabs.html");
}

function get_parameter($name, $method, $default)
{
    if ($method == "GET" || $method == "get") {
        if (isset($_GET[$name])) {
            return $_GET[$name];
        }
    } else if ($method == "POST" || $method == "post") {
        if (isset($_POST[$name])) {
            return $_POST[$name];
        }
    }
    return $default;
}

function get_date()
{
    $now = date("Y-m-d");
    return $now;
}

function get_progress($start, $end)
{
    $diff_start_now = get_diffStartNow($start);
    $diff_start_end = get_diffStartEnd($start, $end);
    $progress = (intval($diff_start_now)/((intval($diff_start_end) == 0) ? 1 : intval($diff_start_end))*100);
    return $progress;
}

function get_diffStartEnd($start, $end)
{
    $start = date_create($start);
    $end = date_create($end);
    $diff = date_diff($start, $end);
    $diff_start_end = $diff->format("%a");
    return $diff_start_end;
}

function get_diffStartNow($start)
{
    $start = date_create($start);
    $now = date_create(get_date());
    $diff = date_diff($start, $now);
    $diff_start_now = $diff->format("%a");
    return $diff_start_now;
}

function get_day($date)
{
    $date = date_create($date);
    return date_format($date, "%a");
}

// Convert and format a date string to a date object with the specific format dd-mm-yyyy
function get_d_m_Y($date)
{
    $date = date_create($date);
    return date_format($date, 'd-m-Y');
}

// Create a new project table
function get_projecttable($id, $projectname, $startdate, $enddate)
{
    $progress = get_progress($startdate, $enddate);
    $rowspan = get_diffStartEnd($startdate, $enddate) + 1;
    $startDay = get_day($startdate);
    $endDay = get_day($enddate);
    $start = get_d_m_Y($startdate);
    $end = get_d_m_Y($enddate);
    $startDate = date_create($startdate);
    $endDate = date_create($enddate);
    $milestones = MilestoneManager::loadMilestones($id);

    $s = '<table class="table" style="width: 25rem">';
    $s = $s . '<tr><th class="bg-info">Progress</th><th class="bg-info">Date</th><th class="bg-info">Milestones</th></tr>';
    $s = $s . '<tr><td id="progress-td-'.$projectname.'" class="progress-td align-middle" rowspan="'. $rowspan .'"><div id="progress-'.$projectname.'" class="progress progress-bar-vertical"><div id="'.$projectname.'" style="width: 100%" class="progress-bar bg-success" role="progressbar" aria-valuenow="'. $progress.'" aria-valuemin="0" aria-valuemax="100"></div></div></td>
                        <td class="align-middle custom-td">
                            <div>'.$start.'</div>
                        </td>
                        <td class="align-middle custom-td">
                            <div>START</div>
                        </td>                     
              </tr>';

    $interval = new DateInterval('P1D');
    $startDate = date_add($startDate, date_interval_create_from_date_string('1 days'));
    $daterange = new DatePeriod($startDate, $interval, $endDate);
    foreach($daterange as $date)
    {
        $milestoneStr = "";
        $milestoneCount = 0;
        $url = $_SERVER['REQUEST_URI'];

        foreach ($milestones as $milestone) {
            if (date_sameDay($milestone->enddate, $date)) {
                $ms_name = $milestone->name;
                if (strlen($ms_name) > 22) {
                    $ms_name = substr($ms_name, 0, 22) . "...";
                }
                $milestoneCount++;
                $milestoneStr = $milestoneStr . '<div class="btn-group">         
                        <button type="button" class="btn dropdown-item text-success msBtn" data-toggle="tooltip" '
                        . 'title="' . $milestone->name . '" value="'. $milestone->id .'">'. $ms_name .'</button>'
                        . '</form></div><br>';
            }
        }

        if ($milestoneCount == 0) {
            $s = $s . '
                <td class="align-middle"><div></div></td>
                <td class="align-middle" style="opacity: 0"><div>-</div>';
        } else {
            $s = $s . '<tr>
                <td class="align-middle custom-td"><div>' . $date->format('d-m-Y') . '</div></td>
                <td class="align-middle custom-td">';
            if ($milestoneCount > 1) {
                $s = $s . '<div class="dropdown"><button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Milestones <span class="badge badge-info" style="font-size: 13px">'.$milestoneCount.'</span></button><div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
            }
            $s = $s . $milestoneStr;
            if ($milestoneCount > 1) {
                $s = $s . '</div></div>';
            }
        }

        $s = $s . '</td></tr>';

    }

    $s = $s . '<tr>
                    <td class="align-middle custom-td">
                        <div class="align-middle custom-td">'.$end.'</div>
                    </td>
                    <td class="align-middle custom-td">
                        <div>FINISH</div>
                    </td>
               </tr></div></table></div>';

    return $s;
}

function date_sameDay($dateA, $dateB) {
    return $dateA->format('Y-m-d') == $dateB->format('Y-m-d');
}

function get_chart($projectname)
{
    $s = '<table class="table table-responsive">
    <tr><th class="bg-info" style="width: 100rem"><i id="pm-btn-line-chart" class="fa fa-plus"></i>Milestone Trend Analysis</th></tr>
    <tr class="collapse-line-chart"><td id="line-chart-'.$projectname.'" class="bg-primary"></td></tr>
    <tr><th class="bg-info" style="width: 100rem"><i id="pm-btn-pie-chart" class="fa fa-plus"></i>Milestones reached...</th></tr>
    <tr class="collapse-pie-chart"><td id="pie-chart-'.$projectname.'" class="bg-primary"></td></tr></table>';
    return $s;
}



