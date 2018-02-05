<?php

include_once 'sql_util.php';

class CalendarUtil {
    public static function get_meetinglist($project_id) {
        // Verbindung zur Datenbank aufbauen
        //ID AND SORT

        $sql="select id, meetingdate, title, timestart, timeend, location, description, id as editid 
                      from calendarlist 
                      where project_id = $project_id 
                      order by meetingdate ASC;";
        
        //$res=dbquery($con,$sql);
        $res = SQL::query($sql);



        //$sql="delete from calendarlist where meetingdate < CURRENT_TIMESTAMP";
        //$res=dbquery($con,$sql);

        echo CalendarUtil::restolist($res, "editmeeting.php");

        $sql="select count(*) from calendarlist";
        $res = SQL::query($sql);
        $line=$res->fetch_row();
        $count=$line[0];

        $res->free_result();
    }

    public static function restolist($res, $editpage) {
        //INIT
        //$f = $res->fetch_fields();
        $months = ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OKT", "NOV", "DEC"];
        $days = ["MONDAY", "TUESDAY", "WEDNESDAY", "THURSDAY", "FRIDAY", "SATURDAY", "SUNDAY"];


        //RESTOLIST
        $s = "<div class=\"container\">";
        $s = $s . "<div class=\"row row-striped\" id='calendar_list'>";


        while ($entry = $res->fetch_assoc()) {
            $s = $s . "<div class=\"col-2 text-right calendar_list\">";

            foreach ($entry as $k => $v) {
                if ($k == "meetingdate") {

                    for ($i = 0; $i < sizeof($months); $i++) {
                        if ((date_format(date_create($v), 'w') - 1) == $i)
                            $s = $s . $days[$i];
                    }

                    $s = $s . "<h1 class=\"display-4\"><span class=\"badge badge-secondary\">";
                    $s = $s . date_format(date_create($v), 'd');
                    $s = $s . "</span></h1>";

                    for ($i = 0; $i < sizeof($months); $i++) {
                        if ((date_format(date_create($v), 'n') - 1) === $i) {
                            $s = $s . "<h2>";
                            $s = $s . $months[$i];
                            $s = $s . "</h2>";
                        }
                    }

                    $s = $s . "<br>";
                    $s = $s . "</div>";

                } else if ($k == "title") {
                    $s = $s . "<div class=\"col-10\">";
                    $s = $s . "<h3 class=\"text-uppercase\"><strong>";
                    $s = $s . $v;
                    $s = $s . "</strong></h3>";

                } else if ($k == "timestart") {

                    $s = $s . "<ul class=\"list-inline\">";
                    $s = $s . "<li class=\"list-inline-item\"><i class=\"fa fa-calendar-o\" aria-hidden=\"true\">";
                    //Uhrzeit prep


                    $s = $s . "</i>";
                    //$s = $s . $v;
                    $s = $s . date_format(date_create($v), 'h:i A');
                    $s = $s . " - ";

                } else if ($k == "timeend") {

                    //$s = $s . $v;
                    $s = $s . date_format(date_create($v), 'h:i A');
                    $s = $s . "</li>";

                } else if ($k == "location") {
                    $s = $s . "<li class=\"list-inline-item\">"; //<i class=\"fa fa-location-arrow\" aria-hidden=\"true\"></i>";
                    $s = $s . $v;
                    $s = $s . "</li>";
                    $s = $s . "</ul>";

                } else if ($k == "description") {
                    $s = $s . "<i>";
                    $s = $s . "<pre>" . $v . "</pre>";
                    $s = $s . "</i>";
                } else if($k == "editid") {
                    $s = $s . "<p>";
                    $s = $s ."<a href='$editpage?id=$v'><button type=\"button\" class=\"btn\">EDIT</button></a>";
                    $s = $s . "</p>";
                }
            }
            $s = $s . "</div>";
        }

        $s = $s . "</div>";
        $s = $s . "</div>";

        //$v++;
        //$s = $s . "<div>I WANT TO INSERT AN APPEND BUTTON RIGHT HERE</div>";

        $s = $s . "<a href='newmeeting.php'>";
        $s = $s . "<br>";
        $s = $s . "<button type=\"button\" class=\"btn btn-default btn-block\">ADD MEETING</button>";
        $s = $s . "<br>";
        $s = $s . "</a>";



        //$s = $s . "<div class=\"panel-group\>";
        //$s = $s . "<br>";
        //$s = $s . "<div class=\"panel-body\">";
        //$s = $s . "<a href='newmeeting.php'>";
        //$s = $s . "NEW MEETING</a>";
        //$s = $s . "</div>";
        //$s = $s . "<br>";
        //$s = $s . "</div>";


        //REMOVE OLD ENTRIES
        $sql="delete from calendarlist where meetingdate < CURRENT_TIMESTAMP";
        $res=SQL::query($sql);

        return $s;
    }
}