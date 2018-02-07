<?php
include_once("util/sql_util.php");
include_once("php/functions.php");
//include_once("util/calendar_util.php");

$mode = get_parameter('mode', 'GET', false);
$mid = get_parameter('mid', 'GET', false);
$pid = get_parameter('pid', 'GET', false);
$tid = get_parameter('tid', 'GET', false);
$uid = get_parameter('uid', 'GET', false);

echo "MODE: $mode<br>";

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
    echo ProjectManager::addUser($pid, $uid, "user");
    ProjectManager::displayProjectUsers($pid);
}
else if($mode === "promoteuser")
{
    ProjectManager::setUserPermission($pid, $uid, "leader");
    ProjectManager::displayProjectUsers($pid);
}
else if($mode === "demoteuser")
{
    ProjectManager::setUserPermission($pid, $uid, "user");
    ProjectManager::displayProjectUsers($pid);
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
    echo "";
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
    echo MilestoneManager::addMiSt();
}
else if($mode === "saveNewMiSt")
{
    echo MilestoneManager::saveNewMiSt($pid);
}
else if($mode === "cancelAddMiSt")
{
    echo "";
}
else if($mode === "meetingview")
{
    CalendarUtil::get_meetinglist($pid);
}
else if($mode === "meetingadd")
{
    echo CalendarUtil::new_meeting();
}
else if($mode === "addmeetingbutton")
{
    echo CalendarUtil::neuer_Datensatz($pid);
    CalendarUtil::get_meetinglist($pid);
}
else if($mode === "meetingdelete")
{
    CalendarUtil::loesche_aktuellerDatensatz($uid); // $uid is the meeting id here
    echo BUtil::success("The meeting has been <strong>removed.</strong>");
}
else if($mode === "meetingsave")
{
    CalendarUtil::update_aktuellerDatensatz(filter_input(INPUT_GET, "id"));
    echo BUtil::success("Your changes have been <strong>saved.</strong>");
}
else if($mode === "meetingedit")
{
    echo CalendarUtil::edit_meeting($uid); // $uid is the meeting id here
}