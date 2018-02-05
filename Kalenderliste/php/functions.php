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




function edit_meeting($id)
{
    $con = dbconnect_calendarlist();

    if($con !== NULL)
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

/*
function edit_meeting($id)
{
    $con = dbconnect_calendarlist();
    $date = "";

    if ($con !== NULL)
        if (isset($_GET["first"])) {
            $date = dbquery($con, "SELECT min(meetingdate as meetingdate) FROM calendarlist;")->fetch_assoc()['min(meetingdate)'];
        } elseif (isset($_GET["previous"])) {
            $date = dbquery($con, "select max(meetingdate as meetingdate) from calendarlist where id<$id;")->fetch_assoc()['max(meetingdate)'];
        } else if (isset($_GET["next"])) {
            $date = dbquery($con, "select min(meetingdate as meetingdate) from calendarlist where id>$id;")->fetch_assoc()['min(meetingdate)'];
        } else if (isset($_GET["last"])) {
            $date = dbquery($con, "SELECT max(meetingdate as meetingdate) FROM calendarlist;")->fetch_assoc()['max(meetingdate)'];
        } else if (isset($_GET["del"])) {
            dbquery($con, "delete from calendarlist where id=$id;");
        } else if (isset($_GET["save"])) {
            //, familienstand='" . $_GET["fam"] . "'
        }
    if(count($date) != 6) {
        dbquery($con, "update calendarlist set meetingdate='" . $date["meetingdate"] . "', title='" . $date["title"] .
            "', timestart='" . $date["timestart"] . "', timeend='" . $date["timeend"] . "', location='" . $date["location"] .
            "', description='" . $date["description"] . "' where meetingdate=$date;");
    } else {
        echo 'something went wrong';
    }

    $p = getmeetingdata($id);

    $s = '<form class="form-horizontal">';
    $s = $s . get_input("id", "number", "id", "id", $id, "");
    $s = $s . get_input("Meetingdate:", "date", "meetingdate", "meetingdate", $p["meetingdate"], "2018-01-01");
    $s = $s . get_input("Title:", "text", "title", "title", $p["title"], "Please Insert Title");
    $s = $s . get_input("Timestart:", "time", "timestart", "timestart", $p["timestart"], "00:00:00");
    $s = $s . get_input("Timeend:", "time", "timeend", "timeend", $p["timeend"], "00:00:00");
    $s = $s . get_input("Location:", "text", "location", "location", $p["location"], "Please Insert Location");
    $s = $s . get_input("Description:", "textbox", "description", "description", $p["description"], "Please Insert Description");


    $s = $s . get_button("first", "<<");
    $s = $s . get_button("previous", "<");
    $s = $s . get_button("save", "SAVE");
    $s = $s . get_button("del", "DEL");
    $s = $s . get_button("next", ">");
    $s = $s . get_button("last", ">>");

    $s = $s . "<br><br>";

    $s = $s . "<a href=\"index.php\"><button type=\"button\" class=\"btn\" >BACK</button></a>";

    $s = $s . "</form>";

    return $s;
}
*/

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

function get_meeting_as_array_by_id($id)
{
    $con = dbconnect_calendarlist();
    $sql = "SELECT * FROM calendarlist where meetingdate = $id";
    $res = dbquery ($con, $sql);
    return $res->fetch_row();
}