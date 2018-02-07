<?php
include_once("util/sql_util.php");
include_once("php/functions.php");
include_once 'util/charts.php';
//include_once("util/calendar_util.php");

$mode = get_parameter('mode', 'GET', false);
$mid = get_parameter('mid', 'GET', false);
$pid = get_parameter('pid', 'GET', false);
$tid = get_parameter('tid', 'GET', false);
$uid = get_parameter('uid', 'GET', false);
$name = get_parameter('name', 'GET', false);
$startdate = get_parameter('startdate', 'GET', false);
$enddate = get_parameter('enddate', 'GET', false);

if($mode === "milestoneview"){
    MilestoneManager::displayMilestone($pid, $mid);
}
else if($mode === "taskmodal"){
    MilestoneManager::displayMilestone($pid, $mid);
    echo TaskEditor::displayTaskModal($pid, $mid, $tid);
    echo '<script>$("#' . $tid . '_task").modal("show");</script>';
}
else if($mode === "statscharts"){
    echo get_Charts($pid);
    /*echo '<script>var deviation = {value: [-1,0,2,-3], date: ["07-02-2018", "08-02-2018", "09-02-2018", "10-02-2018"]}; createLineChart(deviation); createPieChart(deviation)</script>
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
        </div>';*/
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
    echo ProjectManager::displayEditProject($pid);
}
else if($mode === "projectupdate")
{
    echo ProjectManager::saveEditedProject();
}
else if($mode === "projectdelete")
{
    ProjectManager::confirmDelete($pid);
}
else if($mode === "projConfirmdelete")
{
    ProjectManager::deleteProject($pid);
}
else if($mode === "projDeclinedelete")
{
    echo "";
}
else if($mode === "milestoneview")
{
    echo MilestoneManager::displayMilestone($pid, $uid); // uid is the milestone id here
}
else if($mode === "milestoneedit")
{
    echo MilestoneManager::displayEditMilestone($pid, $uid); // uid is the milestone id here
}
elseif($mode === "save_milestone") {
    echo MilestoneManager::save(filter_input(INPUT_GET, "id"));
}
else if($mode === "milestonedelete")
{
    MilestoneManager::confirmDelete($pid, $uid);
}
else if($mode === "mileConfirmdelete")
{
    MilestoneManager::deleteMilestone($uid);
}
else if($mode === "mileDeclinedelete")
{
    echo "";
}
else if($mode === "milestoneadd")
{
    echo MilestoneManager::addMiSt();
}
else if($mode === "saveNewMiSt")
{
    echo MilestoneManager::saveNewMiSt($pid);
}
else if($mode === "reloadprogress"){
    echo "test";
    //echo get_projectprogress($pid, $name, $startdate, $enddate);
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
else if($mode === "taskadd")
{
    TaskEditor::createTask($uid); // $uid is the milestone id here
}
else if($mode === "taskcreate")
{
    $milestone_id = TaskEditor::addTaskToDb();
    echo BUtil::success("The Task has been created!");
    MilestoneManager::displayMilestone($pid, $milestone_id);
}
else if($mode === "taskedit")
{
    TaskEditor::editTask($uid);
}
else if($mode === "taskupdate")
{
    $name = filter_input(INPUT_GET, "name");
    $desc = filter_input(INPUT_GET, "desc");

    $previous = filter_input(INPUT_GET, "selectprevious");        
    $previous_id = -1;
    if ($previous != "No Previous Task") {
        $previous_id = intval(
                         explode(": ", $previous)[0]
                       );
    }
    $enddate = filter_input(INPUT_GET, "end");
    $milestone_id = filter_input(INPUT_GET, "milestone_id");
    $task_id = filter_input(INPUT_GET, "task_id");
    TaskManager::updateTask($task_id, $name, $desc, $previous_id, $enddate);
    echo BUtil::success("The Task has been updated!");
    
    MilestoneManager::displayMilestone($pid, $milestone_id);
}
else if($mode === "taskfinished")
{
    TaskManager::setFinished($uid, true);
    $milestone_id = TaskManager::getTask($uid)->milestone_id;
    MilestoneManager::displayMilestone($pid, $milestone_id);
}
else if($mode === "taskunfinished")
{
    TaskManager::setFinished($uid, false);
    $milestone_id = TaskManager::getTask($uid)->milestone_id;
    MilestoneManager::displayMilestone($pid, $milestone_id);
}
else {
    echo "Cancelled<br>";
}
