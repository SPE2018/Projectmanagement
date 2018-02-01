<?php
include_once "util/milestone_manager.php";

MilestoneManager::displayMilestone(
    ProjectManager::getProjectId(),
    MilestoneManager::getMilestoneId()
);

