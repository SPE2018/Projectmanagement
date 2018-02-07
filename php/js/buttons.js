////////////////////////////////////////////////////////////////////
//// Creates all dynamic buttons for the pages loaded with ajax ////
////////////////////////////////////////////////////////////////////
createAllDynamicButtons();

var theme = "php/css/darkly.css";


function reloadProgress(button) {
    if (button === "#Btn_SaveNewMiSt" || button === "#Btn_MconfirmDelete") {
        $.get(location.href).then(function(page) {
            $("#progressContent").html($(page).find("#progressContent").html());
            $("#progressContent").ready(progressInit);
        });
        $.getScript("php/js/progress.js");
    }
}

function dynamicButtons(button, mode) {
    $("#content").on("click", button, function () {
        var uid = ($(this).val());
        if (uid === "custom_params") {
            var params = "";
            // Gets all elements with an id that starts with 'param_'
            $("[id^=param_]").each(function (index, value) {
                // Add this parameter with key and value to the parameter string
                var key = value.id.replace("param_", "");
                var val = value.value.replace(/ /g, "%20"); // Replace spaces with '%20'
                params += key + "=" + val + "&";
            });

            $("#content").load("content_loader.php?pid=" + pid + "&uid=" + uid + "&mode=" + mode + "&" + params);

            if (button === "#saveproject") {
                setTimeout(function() {window.location.href = "index.php";}, 2000);
            }

            setTimeout(function() {reloadProgress(button);}, 1000);

            return;
        }

        if (button === "#edittask" || button === "#finishedtask" || button === "#unfinishedtask") {
            // Close the currently open modals
            $('.modal').modal('hide');
            $('.modal-backdrop').remove();
        }
        if(button === "#add_user")
        {
            uid = ($("#add_user_select").val());
            $("#content").load("content_loader.php?pid=" + pid + "&uid=" + uid + "&mode=" + mode);
        } else if (button === "#Btn_PconfirmDelete") {
            $("#content").load("content_loader.php?pid=" + pid + "&uid=" + uid + "&mode=" + mode);
            setTimeout(function() {window.location = "index.php";}, 1000);
        }
        else {
            $("#content").load("content_loader.php?pid=" + pid + "&uid=" + uid + "&mode=" + mode);
        }
        setTimeout(function() {reloadProgress(button);}, 1000);
    });
}

function createAllDynamicButtons() {
    // User Manager
    dynamicButtons("#remove_user", "removeuser");
    dynamicButtons("#promote_user", "promoteuser");
    dynamicButtons("#demote_user", "demoteuser");
    dynamicButtons("#add_user", "adduser");

    // Project
    dynamicButtons("#saveproject", "projectupdate");

    // Project confirm delete
    dynamicButtons("#Btn_PconfirmDelete", "projConfirmdelete");
    dynamicButtons("#Btn_PdeclineDelete", "projDeclinedelete");

    // Meetings
    dynamicButtons("#addmeeting", "addmeetingbutton");
    dynamicButtons("#editmeeting", "meetingedit");
    dynamicButtons("#savemeeting", "meetingsave");
    dynamicButtons("#delmeeting", "meetingdelete");

    // Milestones
    dynamicButtons("#Btn_SaveNewMiSt", "saveNewMiSt");
    dynamicButtons("#Btn_CancelNewMiSt", "cancelAddMiSt");
    dynamicButtons("#save_milestone", "save_milestone");
    dynamicButtons("#editmilestone", "milestoneedit");
    dynamicButtons("#deletemilestone", "milestonedelete");
    // Milestone confirm delete
    dynamicButtons("#Btn_MconfirmDelete", "mileConfirmdelete");
    dynamicButtons("#Btn_MdeclineDelete", "mileDeclinedelete");
    
    // Tasks
    dynamicButtons("#addtask", "taskadd");
    dynamicButtons("#createnewtask", "taskcreate");
    dynamicButtons("#updatetask", "taskupdate");
    dynamicButtons("#edittask", "taskedit");
    dynamicButtons("#finishedtask", "taskfinished");
    dynamicButtons("#unfinishedtask", "taskunfinished");
    
    
    // Cancel
    dynamicButtons("#cancel", "cancel");
}

// View clicked milestone
$("#progressContent").on("click", ".viewmilestone", function () {
    mid = $(this).val();
    $("#content").load("content_loader.php?pid=" + pid + "&mid=" + mid + "&mode=milestoneview");
    $('html, body').animate({scrollTop : 110},600);
});

// Save Project
$("#save").click(function(){

    $("#warning").html("");
    var name = $("#name").val();
    $("#name").val("");
    var startdate = $("#startdate").val();
    $("#startdate").val("");
    var enddate = $("#enddate").val();
    $("#enddate").val("");

    if (name.length > 20) {
        name = name.substring(0, 20);
    }

    if (startdate < enddate) {
        name = name.replace(/ /g, "%20"); // Replace spaces with '%20'
        $("#creation-form").load("php/newproject.php?name=" + name + "&startdate=" + startdate + "&enddate=" + enddate);
        setTimeout(function() {
            window.location = "projects.php?name=" + name;
        }, 2000);

    } else if(startdate > enddate) {
        $("#name").val(name);
        $("#alert").html("<div class='alert alert-warning'><strong>Warning! </strong>The start date must be earlier than the end date.</div>");
    } else if (startdate === enddate) {
        $("#name").val(name);
        $("#alert").html("<div class='alert alert-warning'><strong>Warning! </strong>Start date and end date must be different.</div>")
    }
});

tabButtons(".projectTab", "project");
tabButtons(".statsTab", "stats");
tabButtons(".milestoneTab", "milestone");
tabButtons(".meetingTab", "meeting");
tabButtons(".statsTab", "stats");

function tabButtons(button, name){
    $(button).click(function () {
        var mode = ($(this).val());
        if (name === "milestone" && mode !== "add") {
            $("#content").load("content_loader.php?pid=" + pid + "&mid=" + mid + "&mode=" + name + mode);
        } else if (name === "project" && mode === "delete") {
            $("#content").load("content_loader.php?pid=" + pid + "&mode=" + name + mode);
        }
        else {
            $("#content").load("content_loader.php?pid=" + pid + "&mode=" + name + mode);
        }
    });
}

$("#content").on("click", ".task", function () {
    tid = ($(this).val());
    $("#content").load("content_loader.php?pid=" + pid + "&mid=" + mid + "&tid=" + tid + "&mode=taskmodal");
});

$('#themeBtn').click(function(){
    if(theme === 'php/css/darkly.css'){
        $('#theme').attr('href', 'php/css/flatly.css');
        $('#logo').attr('src', 'php/images/planIT_logo_bright.png');
        theme = 'php/css/flatly.css';
    } else if(theme === 'php/css/flatly.css'){
        $('#theme').attr('href', 'php/css/darkly.css');
        $('#logo').attr('src', 'php/images/planIT_logo_dark.png');
        theme = 'php/css/darkly.css';
    }
});