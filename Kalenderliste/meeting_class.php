<?php

class Meeting
{
    public $id;
    public $meetingdate;
    public $title;
    public $timestart;
    public $timeend;
    public $location;
    public $description;

    public function __construct($id, $meetingdate, $title, $timestart, $timeend, $location, $description)
    {
        $this->id = intval($id);
        $this->meetingdate = $meetingdate;
        $this->title = $title;
        $this->timestart=$timestart;
        $this->timeend=$timeend;
        $this->location=$location;
        $this->description=$description;
    }
}