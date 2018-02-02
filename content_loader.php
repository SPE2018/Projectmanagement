<?php
include_once("util/sql_util.php");
include_once("php/functions.php");
$mode = get_parameter('mode', 'GET', false);
$mid = get_parameter('mid', 'GET', false);
$pid = get_parameter('pid', 'GET', false);
$tid = get_parameter('tid', 'GET', false);

if($mode === "milestoneview"){
    MilestoneManager::displayMilestone($pid, $mid);
}
if($mode === "taskmodal"){
    MilestoneManager::displayMilestone($pid, $mid);
    echo TaskEditor::displayTaskModal($pid, $mid, $tid);
    echo '<script>$("#' . $tid . '_task").modal("show");</script>';
}
if($mode === "projectusers"){
    ProjectManager::displayProjectUsers($pid);
}

if($mode === "stats"){
echo '<script>createLineChart(); createPieChart()</script>
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
}