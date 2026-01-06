<?php
session_start();
include __DIR__ . '/config.php'; // Include config for SITE_URL
session_destroy();
header("Location: " . SITE_URL . "/index.php");
exit;
