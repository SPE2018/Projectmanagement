<?php

include_once 'sql_util.php';

class CalendarUtil {
    public static function get_input ($labeltext, $type, $name, $id, $value, $placeholder)
    {
        $s = '<div class="form-group row">';

        $s = $s . '<label class="control-label col-sm-2" for="' . $name . '">' . $labeltext . '</label>';

        $s = $s . '<div class="col-sm-10">';

        $s = $s . '<input type="' . $type . '" class="form-control" id="' . $id .
            '" placeholder="' . $placeholder  .'" name="' . $name . '" value= "' . $value . '">';
        $s = $s.  '</div>
        </div>';

        return $s;
    }

    public static function get_button($name, $buttontext)
    {
        $s='<button type="button" name="' .$name .
            '" class="btn btn-outline-primary">' . $buttontext . '</button> ' ;

        return $s;
    }
    
    public static function get_meetinglist($project_id) {

        $sql="select id, meetingdate, title, timestart, timeend, location, description, id as editid 
                      from calendarlist 
                      where project_id = $project_id 
                      order by meetingdate ASC;";
        
        $res = SQL::query($sql);


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
                        if ((date_format(date_create($v), 'w') - 1) == $i) {
                            $s = $s . $days[$i];
                        }
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
                    //$s = $s ."<a href='$editpage?id=$v'><button type=\"button\" class=\"btn\">EDIT</button></a>";
                    $s = $s . ButtonFactory::createButton(ButtonType::PRIMARY, "EDIT", false, "editmeeting", "$v")->get();
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
    
    public static function new_meeting()
    {
        $s = '<form class="form-horizontal">';
        $s = $s . CalendarUtil::get_input("Meetingdate:", "date", "meetingdate", "param_meetingdate", null, "Please Insert Meetingdate");
        $s = $s . CalendarUtil::get_input("Title:", "text", "title", "param_title", "", "Please Insert Title");
        $s = $s . CalendarUtil::get_input("Timestart:", "time", "time", "param_time", null, "00:00:00");
        $s = $s . CalendarUtil::get_input("Timeend:", "time", "timeend", "param_timeend", null, "00:00:00");
        $s = $s . CalendarUtil::get_input("Location:", "text", "location", "param_location", null, "Please Insert Location");
        $s = $s . CalendarUtil::get_input("Description:", "text", "description", "param_description", null, "Please Insert Description");

        //$s = $s . CalendarUtil::get_button("save", "SAVE");
        $s = $s . ButtonFactory::createButton(ButtonType::SUCCESS, "Save", false, "addmeeting", "custom_params")->get();

        //$s = $s . "<a href=\"index.php\"><button type=\"button\" class=\"btn\">Back</button></a>";

        $s = $s . "</form>";

        return $s;
    }

    public static function neuer_Datensatz($pid)
    {
        $meetingdate = CalendarUtil::get_parameter("meetingdate", false);
        $title = CalendarUtil::get_parameter("title", false);
        $timestart = CalendarUtil::get_parameter("timestart", false);
        $timeend = CalendarUtil::get_parameter("timeend", false);
        $location = CalendarUtil::get_parameter("location", false);
        $description = CalendarUtil::get_parameter("description", false);

        $sql = "INSERT INTO calendarlist (meetingdate, title, timestart, timeend, location, description, project_id) VALUES ('$meetingdate', '$title', '$timestart', '$timeend', '$location', '$description', '$pid');";
        SQL::query($sql);        
    }
    
    public static function get_parameter($name, $default)
    {
        if(isset($_GET[$name]))
        {
            return $_GET[$name];
        }
        return $default;
    }
    
    public static function edit_meeting($id)
    {
        //$con = dbconnect_calendarlist();

        
        if(isset($_GET["first"]))
        {
            $id=dbquery($con, "select min(id) from calendarlist;")->fetch_assoc()['min(id)'];
        }elseif(isset($_GET["prev"]))
        {
            $id=dbquery($con, "select max(id) from calendarlist where id<$id;")->fetch_assoc()['max(id)'];
        }else if(isset($_GET["next"]))
        {
            $id=dbquery($con, "select min(id) from calendarlist where id>$id;")->fetch_assoc()['min(id)'];
        }else if(isset($_GET["last"]))
        {
            $id=dbquery($con, "select max(id) from calendarlist;")->fetch_assoc()['max(id)'];
        }else if(isset($_GET["del"]))
        {
            SQL::query("delete from calendarlist where id=$id;");
        }else if(isset($_GET["save"]))
        {
            //, familienstand='" . $_GET["fam"] . "'

            $sql = "update calendarlist set meetingdate='" . $_GET["meetingdate"] . "', title='" . $_GET["title"] .
                "', timestart='" . $_GET["timestart"] . "', timeend='" . $_GET["timeend"] . "', location='" . $_GET["location"] .
                "', description='" . $_GET["description"] . "' where id=$id;";
            SQL::query($sql);

        }


        $p = CalendarUtil::getmeetingdata($id);

        $s='<form class="form-horizontal">';
        $s = $s . CalendarUtil::get_input("id", "number", "id", "id", $id, "");
        $s = $s . CalendarUtil::get_input("Meetingdate:", "date", "meetingdate", "meetingdate", $p["meetingdate"], "2018-01-01");
        $s = $s . CalendarUtil::get_input("Title:", "text", "title", "title", $p["title"], "Please Insert Title");
        $s = $s . CalendarUtil::get_input("Timestart:", "time", "timestart", "timestart", $p["timestart"], "00:00:00");
        $s = $s . CalendarUtil::get_input("Timeend:", "time", "timeend", "timeend", $p["timeend"], "00:00:00");
        $s = $s . CalendarUtil::get_input("Location:", "text", "location", "location", $p["location"], "Please Insert Location");
        $s = $s . CalendarUtil::get_input("Description:", "textbox", "description", "description", $p["description"], "Please Insert Description");


        //$s = $s . get_button("first", "<<");
        //$s = $s . get_button("prev", "<");
        $s = $s . CalendarUtil::get_button("savemeeting", "SAVE");
        $s = $s . CalendarUtil::get_button("delmeeting", "DEL");
        //$s = $s . get_button("next", ">");
        //$s = $s . get_button("last", ">>");

        $s = $s . "</form>";

        return $s;
    }
    
    public static function getmeetingdata($id)
    {
        //$con= dbconnect_calendarlist();
        $sql="select id, meetingdate, title, timestart, timeend, location, description  from calendarlist where id=$id";
        //$res=dbquery($con,$sql);
        $res = SQL::query($sql);

        if ($line=$res->fetch_assoc())
        {
            return $line;

        } else {

            return array (
                "id"=>-1,
                "meetingdate"=>"",
                "title"=>"",
                "timestart"=>"",
                "timeend"=>"",
                "location"=>"",
                "description"=>"");
        }
    }
}