<?php

include_once 'milestone_manager.php';

class Deviation implements JsonSerializable {
    public $value;
    public $date;

    public function __construct($value, $date) {
        $this->value = $value;
        $this->date = $date;
    }
    
    public function jsonSerialize () {
        return array(
            'value'=>$this->value,
            'date'=>$this->date
        );
    }
}

function get_Charts($project_id) {    
    // db projectid -> milestoneid -> planned enddate -> actual enddate
    $milestones = MilestoneManager::loadMilestones($project_id);
    
    $finished_milestones = array();
    
    foreach ($milestones as $milestone) {
        if ($milestone->isFinished()) {
            array_push($finished_milestones, $milestone);
        }
    }
    
    $value = array();    
    $date = array();
    
    foreach ($finished_milestones as $m) {        
        $toAddValue = date_diff($m->enddate, $m->finisheddate)->format("%a");
        if ($m->enddate > $m->finisheddate) {
            $toAddValue *= -1;
        }
        $toAddDate = $m->finisheddate->format("Y-m-d");
        
        array_push($value, $toAddValue);
        array_push($date, $toAddDate);
    }

    
    $deviation = new Deviation($value, $date);

    
    //$deviation = { value: [{planned enddate - actual enddate}], date: [{actual enddate}];
    $s = '<script>var deviation = '. json_encode($deviation) . '; createLineChart(deviation); createPieChart(deviation)</script>
        <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
          <div class="carousel-inner">
            <div class="carousel-item active">
              <div class="d-block w-100" id="line-chart"></div>
            </div>
            <div class="carousel-item">
              <div class="d-block w-100" id="pie-chart"></div>
            </div>
          </div>
          <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
          </a>
          <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
          </a>
        </div>';

    return $s;
}
