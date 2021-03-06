<?php
include_once 'sql_util.php';
include_once 'wrapper/note_class.php';

class NoteManager {
    public static function getNoteByHead($head) {
        if($head == null) {
            return null;
        }
        $sql = "SELECT * FROM notes WHERE header='$head';";
        $tblResult = SQL::query($sql);
        $result = $tblResult->fetch_assoc();
        if ($result == null) {
            return null;
        }
        return new Note(ProjectManager::getProjectFromId($result['pid'])->id, 'now', UserManager::getUserByID('uid')->userid,  $result['header'], $result['comment']);
    }

    public static function addNote($project, $header, $comment) {
        $sql = "INSERT INTO notes (pid, uid, timestamp, header, comment) VALUES('$project', '" . $_SESSION['user'] . "', '" . date("Y-m-d h:m") . "', '$header', '$comment')";
        SQL::query($sql); // TODO: Error handling   
    }
    
    public static function getNote($id) {
        $helpvar = NoteManager::getNoteByID($id);
        echo ProjectManager::getProjectFromId($helpvar->project)->name . '<br>' . $helpvar->user . '<br>' . $helpvar->time . '<br>' . $helpvar->header . '<br>' . $helpvar->comment;
    }
    
    public static function createNoteModal($projectid, $text) {
        if(!isset($_SESSION['user'])) {
            return null;
        }
        $user = $_SESSION ['user'];
        echo   '<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">' . $text . '</button><br>
                <div id="myModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Leave a note concerning ' . ProjectManager::getProjectFromID($projectid)->name . ',<br> ' . $user . '</h4>
                            </div>
                            <div class="modal-body">
                                <form action="admin.php" method="get">leave a note:<br>
                                    <input type="text" name="header" maxlength="40" placeholder="headline (40 characters)" required><br>
                                    <textarea id="inputNote" style="resize:none;" name="comment" maxlength="250" cols="75" rows="5" placeholder="Your comment, ' . $user . ' (250 characters)" required></textarea><br><br>
                                    <button type="submit" name="btnSave" value="true" class="btn btn-default">Save</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Dismiss</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>';
        $header = filter_input(INPUT_GET, 'header');
        $comment = filter_input(INPUT_GET, 'comment');
        if(filter_input(INPUT_GET, 'btnSave') == 'true') {
            NoteManager::addNote(ProjectManager::getProjectFromId($projectid)->id, $header, $comment);
        }
        return null;
    }
    
    public static function getNoteByID($id) {
        $getTimeSQL = "SELECT `timestamp` FROM notes WHERE id=$id;";
        $getTimeResult = SQL::query($getTimeSQL)->fetch_assoc()['timestamp'];
        if ($getTimeResult == null) {
            return null;
        }
        $sql = "SELECT * FROM notes WHERE id=$id;";
        $result = SQL::query($sql)->fetch_assoc(); // TODO: Error handling
        if($result == null) {
            return null;
        }
        return new Note(UserManager::getUser('admin')->userid, 'now', $result['header'], $result['comment']);
    }
}