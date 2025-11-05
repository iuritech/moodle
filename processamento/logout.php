<?php
// Fecho de sessão

session_start();
session_destroy();
header("Location: ../index.php");