<?php
include_once "dbutil.php";
$db = DbUtil::loginConnection();
$stmt = $db->stmt_init();

if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

echo $_POST['workout_delete'];

mysqli_close($db);
?>
