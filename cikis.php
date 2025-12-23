<?php
// Bilekliği YOK ETMEK için oturumu başlat
session_start();

$_SESSION = array();
session_destroy();

header("Location: index.php");
exit;
?>