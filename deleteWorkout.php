<?php
include_once "dbutil.php";
$db = DbUtil::loginConnection();
$stmt = $db->stmt_init();

if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$wid = $_POST['workout_delete'];
$etype = $_POST['exercise_type'];
$sql = "DELETE FROM does WHERE workoutID = ?";
$stmt = mysqli_prepare($db, $sql);
mysqli_stmt_bind_param($stmt, "i", $param_wid);
$param_wid = $wid;
mysqli_stmt_execute($stmt);
$sql = "DELETE FROM involves WHERE workoutID = ?";
$stmt = mysqli_prepare($db, $sql);
mysqli_stmt_bind_param($stmt, "i", $param_wid);
$param_wid = $wid;
mysqli_stmt_execute($stmt);
if(strcmp($etype, "weightlifting") === 0) {
    $sql = "DELETE FROM weightworkout WHERE workoutID = ?";
} else if(strcmp($etype, "bodyweight") === 0) {
    $sql = "DELETE FROM bodyweightworkout WHERE workoutID = ?";
} else if(strcmp($etype, "cardio") === 0) {
    $sql = "DELETE FROM cardioworkout WHERE workoutID = ?";
} else {
    $sql = "DELETE FROM stretchworkout WHERE workoutID = ?";
}
if($stmt = mysqli_prepare($db, $sql)){
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "i", $param_wid);
    
    // Set parameters
    $param_wid = $wid;

    // Attempt to execute the prepared statement
    if(mysqli_stmt_execute($stmt)){ 
        $sql = "DELETE FROM workout WHERE workoutID = ?";
        if($stmt = mysqli_prepare($db, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_wid);
            
            // Set parameters
            $param_wid = $wid;
            if(mysqli_stmt_execute($stmt)){ 
                header("location: home.php");
            }
            else {
                echo "Delete the workout failed";
            }
        }
    } else {
        echo "Delete from table failed";
    }
}
mysqli_close($db);
?>
