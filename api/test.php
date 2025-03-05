<?php
$output = shell_exec("python3 --version 2>&1");
echo "Python versión: " . $output;
?>