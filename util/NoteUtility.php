<?php
include_once 'wrapper/note_class.php';
include_once 'project_manager.php';

if(session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}
class note_manager {
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
        $note = new Note($result['id'], $result['pid'], $result['uid'], $result['timestamp'], $result['header'], $result['comment']);
        return $note;
    }
    
    public static function addNote($note) {
        if(note_manager::getNoteByHead($note->header) == null) {
            $sql = "INSERT INTO notes (pid, uid, timestamp, header, comment) VALUES('$note->project',  '$note->user', '$note->time', '$note->header', '$note->comment');";
            SQL::query($sql); // TODO: Error handling
            return $note;
        } else {
            echo '<p style="Color: red; Font-Size:24">headline already in use</p>';            
        }
        return 'schade';
    }
    
    public static function createNoteModal($id, $projectid, $text) {
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
            $note = note_manager::addNote(new Note($id, $projectid, $header, $comment));
            return $note;
        }
        return null;
    }
}