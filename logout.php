<?php
    session_start();
    session_destroy();

    echo password_hash("asd123", PASSWORD_DEFAULT);
?>