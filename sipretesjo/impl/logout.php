<?php
    session_start();
    session_destroy();
    
    header('Location: proc_examen.html');
    exit;
?>
