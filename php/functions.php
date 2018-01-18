<?php
function get_head()
{
    return file_get_contents("html/head.html");
}

function get_nav()
{
    return file_get_contents("html/nav.html");
}

function get_date()
{
    $now = date("Y-m-d");
    return $now;
}

function get_progress()
{
    $diff_start_end = get_diffStartEnd();
    $diff_start_now = get_diffStartNow();
    $progress = ($diff_start_now/$diff_start_end)*100;

    return $progress;
}

function get_diffStartEnd()
{
    $start = date_create("2018-01-01");
    $end = date_create("2018-01-31");
    $diff = date_diff($start, $end);
    $diff_start_end = $diff->format("%d");
    return $diff_start_end;
}

function get_diffStartNow()
{
    $now = date_create(get_date());
    $start = date_create("2018-01-01");
    $diff = date_diff($start, $now);
    $diff_start_now = $diff->format("%d");
    return $diff_start_now;
}

function get_projecttable($projectname)
{
    $valuenow = get_progress();
    $rowspan = get_diffStartEnd();
    $s = '<div class="col-lg-9">';
    $s = $s . "<table class='table'><thead><tr><th colspan='2'>".$projectname."</th></tr></thead><tbody>";
    $s = $s . '<tr><td rowspan="'. $rowspan .'"><div class="progress progress-bar-vertical"><div id="'.$projectname.'" class="progress-bar bg-success" role="progressbar" aria-valuenow="'. $valuenow.'" aria-valuemin="0" aria-valuemax="100"></div></div></td>
                        <td>
                            <div>startdate</div>
                        </td>
                    </tr>';
    for($i = 0; $i < $rowspan-1; $i++)
    {
        $s = $s . '<tr>
                        <td><div class="btn-group">
                                <button type="button" class="btn btn-primary">Action</button>
                                <button type="button" class="btn dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
    $s = $s . '<tr>
                    <td>
                        <div>enddate</div>
                    </td>
               </tr>';
    $s = $s . '</tbody></table></div>';

    return $s;

}