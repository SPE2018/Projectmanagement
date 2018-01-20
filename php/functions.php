<?php
function get_head()
{
    return file_get_contents("php/html/head.html");
}

function get_nav()
{
    return file_get_contents("php/html/nav.html");
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
    $progress = (intval($diff_start_now)/intval($diff_start_end))*100;
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

function get_d_m_Y($date)
{
    $date = date_create($date);
    return date_format($date, 'd-m-Y');
}

function get_projecttable($projectname, $startdate, $enddate)
{
    $progress = get_progress($startdate, $enddate);
    $rowspan = get_diffStartEnd($startdate, $enddate) + 1;
    $startDay = get_day($startdate);
    $endDay = get_day($enddate);
    $start = get_d_m_Y($startdate);
    $end = get_d_m_Y($enddate);
    $startDate = date_create($startdate);
    $endDate = date_create($enddate);

    $s = '<div class="col-lg-6">';
    $s = $s . "<table class='table table-responsive'><thead><tr class='align-middle'><th colspan='4' style='width: 714.4px'><span style='margin-right: 10px'>Project: ".$projectname."</span><i id='pm-btn-".$projectname."' class='fa fa-minus'></i></th></tr></thead><tbody>";

    $s = $s . "<tr class='collapse-".$projectname."'><th>Progress</th><th>Date</th><th>Milestones</th><th>Chart</th></tr>";
    $s = $s . '<tr class="collapse-'.$projectname.'"><td id="progress-td-'.$projectname.'" class="progress-td align-middle" rowspan="'. $rowspan .'"><div id="progress-'.$projectname.'" class="progress progress-bar-vertical"><div id="'.$projectname.'" style="width: 100%" class="progress-bar bg-success" role="progressbar" aria-valuenow="'. $progress.'" aria-valuemin="0" aria-valuemax="100"></div></div></td>
                        <td class="align-middle custom-td">
                            <div>'.$start.'</div>
                        </td>
                        <td class="align-middle custom-td">
                            <div>START</div>
                        </td>
                        <td class="chart-td" id="chart-'.$projectname.'" rowspan="'. $rowspan .'">
                        </td>
              </tr>';
    $interval = new DateInterval('P1D');
    $startDate = date_add($startDate, date_interval_create_from_date_string('1 days'));
    $daterange = new DatePeriod($startDate, $interval, $endDate);
    $milestonedate = date_create("2018-01-20");
    foreach($daterange as $date)
    {
            $s = $s . '<tr class="collapse-' . $projectname . '">
                    <td class="align-middle custom-td">';
            $s = $s . '<div>' . $date->format('d-m-Y') . '</div>';
            $s = $s . '</td>
                    <td class="align-middle custom-td"><div class="btn-group">
                            <button type="button" class="btn btn-primary btn-sm" style="height: 30.4px">Action</button>
                            <button type="button" class="btn dropdown-toggle dropdown-toggle-split btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 30.4px">
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#">Action</a>
                                <a class="dropdown-item" href="#">Another action</a>
                                <a class="dropdown-item" href="#">Something else here</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">Separated link</a>
                            </div>
                        </div></td>
                </tr>';
    }

    $s = $s . '<tr class="collapse-'.$projectname.'">
                    <td class="align-middle custom-td">
                        <div class="align-middle custom-td">'.$end.'</div>
                    </td>
                    <td class="align-middle custom-td">
                        <div>FINISH</div>
                    </td>
               </tr></div>';
    $s = $s . '</tbody></table></div>';

    return $s;
}
