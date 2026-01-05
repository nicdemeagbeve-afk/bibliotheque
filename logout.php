<?php
session_start();
session_destroy();
header("Location: /revisionphp/index.php");
exit;
