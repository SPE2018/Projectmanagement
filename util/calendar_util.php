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

    public static function get_button($name, $buttontext, $value = null)
    {
        $s='<button type="button" id ="' . $name . '" name="' .$name .
            '" class="btn btn-primary" value="' . $value . '">' . $buttontext . '</button> ' ;

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
                    $s = $s . "</strong><span>";
                    $s = $s . "%EDITID%";
                    $s = $s . "</span></h3>";
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
                    $s = $s . "<li class=\"list-inline-item\">";
                    $s = $s . $v;
                    $s = $s . "</li>";
                    $s = $s . "</ul>";
                } else if ($k == "description") {
                    if ($v !== "") {
                        $s = $s . "<li class='list-inline-item bg-secondary p-2 mb-3'>";
                        $s = $s . $v;
                        $s = $s . "</li>";
                    } else {
                        $s = $s . "<li class='list-inline-item bg-secondary p-2 mb-3'>No description.</li>";
                    }
                } else if ($k == "editid") {
                    $string = ButtonFactory::createButton(ButtonType::PRIMARYSMALL, 'EDIT', false, 'editmeeting',"$v")->get();
                    $s = str_replace("%EDITID%", $string, $s);
                }
            }
            $s = $s . "</div>";
        }

        $s = $s . "</div>";
        $s = $s . "</div>";

        //REMOVE OLD ENTRIES
        $sql="delete from calendarlist where meetingdate < CURRENT_TIMESTAMP";
        $res=SQL::query($sql);

        return $s;
    }
    
    public static function new_meeting()
    {
        $s = '<form class="form-horizontal">';
        $s = $s . CalendarUtil::get_input("Date:", "date", "meetingdate", "param_meetingdate", null, "Please Insert Meetingdate");
        $s = $s . CalendarUtil::get_input("Title:", "text", "title", "param_title", "", "Please Insert Title");
        $s = $s . CalendarUtil::get_input("Start:", "time", "time", "param_time", null, "00:00:00");
        $s = $s . CalendarUtil::get_input("End:", "time", "timeend", "param_timeend", null, "00:00:00");
        $s = $s . CalendarUtil::get_input("Location:", "text", "location", "param_location", null, "Please Insert Location");
        $s = $s . CalendarUtil::get_input("Description:", "text", "description", "param_description", null, "Please Insert Description");

        $s = $s . ButtonFactory::createButton(ButtonType::SUCCESS, "Save", false, "addmeeting", "custom_params")->get();

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
    
    public static function loesche_aktuellerDatensatz($id)
    {
        $sql = "DELETE FROM calendarlist WHERE id = $id";
        SQL::query($sql);
    }
    
    public static function update_aktuellerDatensatz($id)
    {
        $meetingdate = CalendarUtil::get_parameter("meetingdate", false);
        $title = CalendarUtil::get_parameter("title", false);
        $timestart = CalendarUtil::get_parameter("timestart", false);
        $timeend = CalendarUtil::get_parameter("timeend", false);
        $location = CalendarUtil::get_parameter("location", false);
        $description = CalendarUtil::get_parameter("description", false);


        $sql = "UPDATE calendarlist SET meetingdate = '$meetingdate', title = '$title', timestart = '$timestart', timeend = '$timeend', location = '$location', description = '$description' WHERE id = $id";
        SQL::query($sql);

        $sql="delete from calendarlist where meetingdate < CURRENT_TIMESTAMP";
        SQL::query($sql);
    }
    
    public static function edit_meeting($id)
    {
        
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
        $s = $s . CalendarUtil::get_input("id", "number", "id", "param_id", $id, "");
        $s = $s . CalendarUtil::get_input("Date:", "date", "meetingdate", "param_meetingdate", $p["meetingdate"], "2018-01-01");
        $s = $s . CalendarUtil::get_input("Title:", "text", "title", "param_title", $p["title"], "Please Insert Title");
        $s = $s . CalendarUtil::get_input("Start:", "time", "timestart", "param_timestart", $p["timestart"], "00:00:00");
        $s = $s . CalendarUtil::get_input("End:", "time", "timeend", "param_timeend", $p["timeend"], "00:00:00");
        $s = $s . CalendarUtil::get_input("Location:", "text", "location", "param_location", $p["location"], "Please Insert Location");
        $s = $s . CalendarUtil::get_input("Description:", "textbox", "description", "param_description", $p["description"], "Please Insert Description");

        $s = $s . CalendarUtil::get_button("savemeeting", "SAVE", "custom_params");
        $s = $s . CalendarUtil::get_button("delmeeting", "DELETE", "$id");

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