<?php
include_once("util/sql_util.php");
include_once("php/functions.php");
//include_once("Kalenderliste/calendar_util.php");

$mode = get_parameter('mode', 'GET', false);
$mid = get_parameter('mid', 'GET', false);
$pid = get_parameter('pid', 'GET', false);
$tid = get_parameter('tid', 'GET', false);
$uid = get_parameter('uid', 'GET', false);

if($mode === "milestoneview"){
    MilestoneManager::displayMilestone($pid, $mid);
}
else if($mode === "taskmodal"){
    MilestoneManager::displayMilestone($pid, $mid);
    echo TaskEditor::displayTaskModal($pid, $mid, $tid);
    echo '<script>$("#' . $tid . '_task").modal("show");</script>';
}
else if($mode === "statscharts"){
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
else if($mode === "removeuser")
{
    ProjectManager::removeUser($pid, $uid);
    ProjectManager::displayProjectUsers($pid);
}
else if($mode === "adduser")
{
    $uid = UserManager::getUser($uid)->userid;
    ProjectManager::addUser($pid, $uid, "user");
    ProjectManager::displayProjectUsers($pid);
}
else if($mode === "promoteuser")
{
    ProjectManager::setUserPermission($pid, $uid, "leader");
}
else if($mode === "demoteuser")
{
    ProjectManager::setUserPermission($pid, $uid, "user");
}
else if($mode === "projectusers"){
    ProjectManager::displayProjectUsers($pid);
}
else if($mode === "projectedit")
{
    echo "edit_project";
}
else if($mode === "projectdelete")
{
    ProjectManager::confirmDelete($pid);
}
else if($mode === "confirmdelete")
{
    ProjectManager::deleteProject($pid);
}
else if($mode === "declinedelete")
{
    echo "Test";
}
else if($mode === "milestoneedit")
{
    echo "edit_milestone";
}
else if($mode === "milestonedelete")
{
    echo "delete_milestone";
}
else if($mode === "milestoneadd")
{
    echo "add_milestone";
}
else if($mode === "meetingview")
{
    CalendarUtil::get_meetinglist($pid);
}
else if($mode === "meetingadd")
{
    echo "add_meetings";
}
else if($mode === "meetingdelete")
{
    echo "delete_meetings";
}
else if($mode === "meetingedit")
{
    echo "edit_meetings";
}