<?php
if(session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}
include_once 'util/note_manager.php';
NoteManager::getNote(1);