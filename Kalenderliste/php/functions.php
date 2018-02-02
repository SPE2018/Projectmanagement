<?php

function get_head()
{
    return file_get_contents("php/html/head.html");
}

function get_bootstrap()
{
    return file_get_contents("php/html/bootstrap.html");
}

function dbconnect_calendarlist()
{
    $host="localhost";
    $user="calendaradm";
    $passwort="geheim";
    $database="calendarlist";

    $con = new mysqli($host, $user, $passwort, $database);

    if ($con->connect_errno)
    {
        die ("keine Verbindung " . $con->connect_errno . $con->connect_error);
    }
    $con->set_charset("utf8");

    return $con;
}

function dbquery ($con, $sql)
{
    $res = $con->query($sql);

    if ($res === false)
    {
        die ("Fehler bei der Abfrage " . $con->errno . $con->error);
    }

    return $res;
}

function getmeetingdata($id)
{
    $con= dbconnect_calendarlist();
    $sql="select id, meetingdate, title, timestart, timeend, location, description  from calendarlist where id=$id";
    $res=dbquery($con,$sql);

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

function get_meetinglist()
{
    // Verbindung zur Datenbank aufbauen
    $offset = 0;
    $limit = 100;
    if (isset($_GET["offset"]))
        $offset = $_GET["offset"];

    if (isset($_GET["limit"]))
        $limit=$_GET["limit"];

    //ID AND SORT
    $con=dbconnect_calendarlist();
    $sql="select id, meetingdate, title, timestart, timeend, location, description, id as editid from calendarlist order by meetingdate ASC limit $limit  offset $offset;";
    $res=dbquery($con,$sql);



    //$sql="delete from calendarlist where meetingdate < CURRENT_TIMESTAMP";
    //$res=dbquery($con,$sql);

    echo restolist($res, "editmeeting.php");

    $sql="select count(*) from calendarlist";
    $res = $con->query($sql);
    $line=$res->fetch_row();
    $count=$line[0];

    $res->free_result();
    $con->close();
}

function restolist($res, $editpage)
{
    //INIT
    //$f = $res->fetch_fields();
    $months = ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OKT", "NOV", "DEC"];
    $days = ["MONDAY", "TUESDAY", "WEDNESDAY", "THURSDAY", "FRIDAY", "SATURDAY", "SUNDAY"];


    //RESTOLIST
    $s = "<div class=\"container\">";
    $s = $s . "<div class=\"row row-striped\">";


    while ($entry = $res->fetch_assoc()) {
        $s = $s . "<div class=\"col-2 text-right\">";

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
                $s = $s . "<li class=\"list-inline-item\"><i class=\"fa fa-location-arrow\" aria-hidden=\"true\"></i>";
                $s = $s . $v;
                $s = $s . "</li>";
                $s = $s . "</ul>";

            } else if ($k == "description") {
                $s = $s . "<i>";
                $s = $s . "<pre>" . $v . "</pre>";
                $s = $s . "</i>";
            } else if($k == "editid") {
                $s = $s . "<p>";
                $s = $s ."<a href='$editpage?id=$v'>EDIT</a>";
                $s = $s . "</p>";
            }
        }
        $s = $s . "</div>";
    }

    $s = $s . "</div>";
    $s = $s . "</div>";

    //$v++;
    //$s = $s . "<div>I WANT TO INSERT AN APPEND BUTTON RIGHT HERE</div>";

    $s = $s . "<a href='newmeeting.php'><div class=\"jumbotron\">ADD MEETING</div></a>";

    //REMOVE OLD ENTRIES
    $con=dbconnect_calendarlist();
    $sql="delete from calendarlist where meetingdate < CURRENT_TIMESTAMP";
    $res=dbquery($con,$sql);

    return $s;
}

function edit_meeting($id)
{
    $con = dbconnect_calendarlist();

    if($con !== NULL)
    {
        if(isset($_GET["first"]))
        {
            $id=dbquery($con, "select min(id) from calendarlist;")->fetch_assoc()['min(id)'];
        }elseif(isset($_GET["previous"]))
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
            dbquery($con, "delete from calendarlist where id=$id;");
        }else if(isset($_GET["save"]))
        {
            //, familienstand='" . $_GET["fam"] . "'

            dbquery($con, "update calendarlist set meetingdate='" . $_GET["meetingdate"] . "', title='" . $_GET["title"] .
                "', timestart='" . $_GET["timestart"] . "', timeend='" . $_GET["timeend"] . "', location='" . $_GET["location"] .
                "', description='" . $_GET["description"] . "' where id=$id;");
        }
    }


    $p = getmeetingdata($id);

    $s='<form class="form-horizontal">';
    $s = $s . get_input("id", "number", "id", "id", $id, "");
    $s = $s . get_input("Meetingdate:", "date", "meetingdate", "meetingdate", $p["meetingdate"], "2018-01-01");
    $s = $s . get_input("Title:", "text", "title", "title", $p["title"], "Please Insert Title");
    $s = $s . get_input("Timestart:", "time", "timestart", "timestart", $p["timestart"], "00:00:00");
    $s = $s . get_input("Timeend:", "time", "timeend", "timeend", $p["timeend"], "00:00:00");
    $s = $s . get_input("Location:", "text", "location", "location", $p["location"], "Please Insert Location");
    $s = $s . get_input("Description:", "textbox", "description", "description", $p["description"], "Please Insert Description");


    $s = $s . get_button("first", "<<");
    $s = $s . get_button("prev", "<");
    $s = $s . get_button("save", "SAVE");
    $s = $s . get_button("del", "DEL");
    $s = $s . get_button("next", ">");
    $s = $s . get_button("last", ">>");

    $s = $s . "<br><br>";

    $s = $s . "<a href=\"index.php\">BACK</a>";

    $s = $s . "</form>";

    return $s;
}

function get_input ($labeltext, $type, $name, $id, $value, $placeholder)
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

function get_button($name, $buttontext)
{
    $s='<button type="submit" name="' .$name .
        '" class="btn btn-outline-primary">' . $buttontext . '</button> ' ;

    return $s;
}

function get_parameter($name, $default)
{
    if(isset($_GET[$name]))
    {
        return $_GET[$name];
    }
    return $default;
}

function new_meeting()
{
    $s='<form class="form-horizontal">';
    $s = $s . get_input("Meetingdate:", "date", "meetingdate", "meetingdate", null, "Please Insert Meetingdate");
    $s = $s . get_input("Title:", "text", "title", null, "", "Please Insert Title");
    $s = $s . get_input("Timestart:", "time", "time", "time", null, "00:00:00");
    $s = $s . get_input("Timeend:", "time", "timeend", "timeend", null, "00:00:00");
    $s = $s . get_input("Location:", "text", "location", "location", null, "Please Insert Location");
    $s = $s . get_input("Description:", "text", "description", "description", null, "Please Insert Description");

    $s = $s . get_button("save", "SAVE");

    $s = $s . "<a href=\"index.php\"><button type=\"button\" class=\"btn\">Back</button></a>";

    $s = $s . "</form>";

    return $s;
}

function neuer_Datensatz($list)
{
    if ($list === "calendarlist") {
        $meetingdate = get_parameter("meetingdate", false);
        $title = get_parameter("title", false);
        $timestart = get_parameter("timestart", false);
        $timeend = get_parameter("timeend", false);
        $location = get_parameter("location", false);
        $description = get_parameter("description", false);
    }

    $con=dbconnect_calendarlist();
    $sql = "INSERT INTO calendarlist (meetingdate, title, timestart, timeend, location, description) VALUES ('$meetingdate', '$title', '$timestart', '$timeend', '$location', '$description')";
    $res = dbquery ($con, $sql);
    $con->close();
}

function update_aktuellerDatensatz($id, $list)
{
    if ($list === "calendarlist") {
        $meetingdate = get_parameter("meetingdate", false);
        $title = get_parameter("title", false);
        $timestart = get_parameter("timestart", false);
        $timeend = get_parameter("timeend", false);
        $location = get_parameter("location", false);
        $description = get_parameter("description", false);


        $con = dbconnect_calendarlist();
        $sql = "UPDATE calendarlist SET meetingdate = '$meetingdate', title = '$title', timestart = '$timestart', timeend = '$timeend', location = '$location', description = '$description' WHERE id = $id";
        $res = dbquery($con, $sql);

        $sql="delete from calendarlist where meetingdate < CURRENT_TIMESTAMP";
        $res = dbquery($con, $sql);

        $con->close();

    }
}

function loesche_aktuellerDatensatz($id, $list)
{
    $con=dbconnect_calendarlist();
    $sql = "DELETE FROM $list WHERE id = $id";
    $res = dbquery ($con, $sql);
    $con->close();
}
function get_id_ersterDatensatz($list)
{
    $con = dbconnect_calendarlist();
    $sql = "SELECT min(id) FROM $list";
    $res = dbquery ($con, $sql);
    $res = $con->query($sql);
    $line=$res->fetch_row();
    $count=$line[0];
    $res->free_result();
    $con->close();
    return $count;
}

function get_id_letzterDatensatz($list)
{
    $con=dbconnect_calendarlist();
    $sql = "SELECT max(id) FROM $list";
    $res = dbquery ($con, $sql);
    $res = $con->query($sql);
    $line=$res->fetch_row();
    $count=$line[0];
    $res->free_result();
    $con->close();
    return $count;
}