<?php
session_start();

session_unset();
session_destroy();

// Redirigir al index
header("Location: /biasmarket/HTML/INDEX/index.php");
exit;
?>