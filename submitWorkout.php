<?php
session_start();

include_once "dbutil.php";
$db = DbUtil::loginConnection();
$stmt = $db->stmt_init();

if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$eid = $_POST["exercise"]; //get the exercise ID passed from the form
$etype = $_POST["exercisetype"];
if(strcmp($etype, "weightlifting") === 0) {
    $weight = $_POST["weight"];
    $reps = $_POST["reps"];
    $sets = $_POST["sets"];
} else if(strcmp($etype, "cardio") === 0) {
    $distance = $_POST["distance"];
    $duration = $_POST["duration"];
} else if(strcmp($etype, "bodyweight") === 0) {
    $reps = $_POST["reps"];
    $sets = $_POST["sets"];
} else {
    $reps = $_POST["reps"];
    $duration = $_POST["duration"];
}
$datetime = $_POST["datetime"];
$date = substr($datetime, 0, 10);
$time = substr($datetime, 11, 5) .":00";
$notes = $_POST["notes"];

$sql = "SELECT * FROM `personalrecords` WHERE id = ?";
$stmt = mysqli_prepare($db, $sql);
mysqli_stmt_bind_param($stmt, "i", $param_uid);
$param_uid = $_SESSION["userID"];
mysqli_stmt_execute($stmt);
$records_result = mysqli_stmt_get_result($stmt); //sub workout
$records = mysqli_fetch_array($records_result);
$weight_rec = $records['weight'];
$distance_rec = $records['distance'];
$duration_rec = $records['duration'];

$sql = "INSERT INTO workout (userID, exerciseID, date, time, notes) VALUES (?, ?, ?, ?, ?)";

if($stmt = mysqli_prepare($db, $sql)){
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "iisss", $param_uid, $param_eid, $param_date, $param_time, $param_notes);
    
    // Set parameters
    $param_uid = $_SESSION["userID"];
    $param_eid = $eid;
    $param_date = $date;
    $param_time = $time;
    $param_notes = $notes;
    
    // Attempt to execute the prepared statement
    if(mysqli_stmt_execute($stmt)){
        // Redirect to login page
        //header("location: home.php");
        $wid = mysqli_insert_id($db); //last inserted workout ID
        $uid = $_SESSION["userID"];
        $sql = "INSERT INTO `does` VALUES (?, ?);";
        $stmt = mysqli_prepare($db, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $param_wid, $param_uid);
        $param_wid = $wid;
        $param_uid = $uid;
        mysqli_stmt_execute($stmt);
        $sql = "INSERT INTO `involves` VALUES (?, ?);";
        $stmt = mysqli_prepare($db, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $param_wid, $param_eid);
        $param_wid = $wid;
        $param_uid = $eid;
        mysqli_stmt_execute($stmt);
        if(strcmp($etype, "weightlifting") === 0) {
            $sql = "INSERT INTO weightworkout (workoutID, weight, reps, sets) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($db, $sql);
            mysqli_stmt_bind_param($stmt, "iiii", $param_workoutID, $param_weight, $param_reps, $param_sets);
            $param_workoutID = $wid;
            $param_weight = $weight;
            $param_reps = $reps;
            $param_sets = $sets;
            if(mysqli_stmt_execute($stmt)){
                if($weight > $weight_rec){
                    $sql = "UPDATE personalrecords SET weight = ? WHERE id = ?";
                    $stmt = mysqli_prepare($db, $sql);
                    mysqli_stmt_bind_param($stmt, "ii", $param_weight, $param_userID);
                    $param_weight = $weight;
                    $param_userID = $_SESSION["userID"];
                    mysqli_stmt_execute($stmt);
                }
                header("location: home.php");
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        } else if(strcmp($etype, "cardio") === 0) {
            $sql = "INSERT INTO cardioworkout (workoutID, distance, duration) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($db, $sql);
            mysqli_stmt_bind_param($stmt, "iii", $param_workoutID, $param_distance, $param_duration);
            $param_workoutID = $wid;
            $param_distance = $distance;
            $param_duration = $duration;
            if(mysqli_stmt_execute($stmt)){
                if($distance > $distance_rec){
                    $sql = "UPDATE personalrecords SET distance = ? WHERE id = ?";
                    $stmt = mysqli_prepare($db, $sql);
                    mysqli_stmt_bind_param($stmt, "ii", $param_distance, $param_userID);
                    $param_distance = $distance;
                    $param_userID = $_SESSION["userID"];
                    mysqli_stmt_execute($stmt);
                }
                if($duration > $duration_rec){
                    $sql = "UPDATE personalrecords SET duration = ? WHERE id = ?";
                    $stmt = mysqli_prepare($db, $sql);
                    mysqli_stmt_bind_param($stmt, "ii", $param_duration, $param_userID);
                    $param_duration = $duration;
                    $param_userID = $_SESSION["userID"];
                    mysqli_stmt_execute($stmt);
                }
                header("location: home.php");
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        } else if(strcmp($etype, "bodyweight") === 0) {
            $sql = "INSERT INTO bodyweightworkout (workoutID, reps, sets) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($db, $sql);
            mysqli_stmt_bind_param($stmt, "iii", $param_workoutID, $param_reps, $param_sets);
            $param_workoutID = $wid;
            $param_reps = $reps;
            $param_sets = $sets;
            if(mysqli_stmt_execute($stmt)){
                header("location: home.php");
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        } else {
            $sql = "INSERT INTO stretchworkout (workoutID, reps, duration) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($db, $sql);
            mysqli_stmt_bind_param($stmt, "iii", $param_workoutID, $param_reps, $param_duration);
            $param_workoutID = $wid;
            $param_reps = $reps;
            $param_duration = $duration;
            if(mysqli_stmt_execute($stmt)){
                header("location: home.php");
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }

    // Close statement
    mysqli_stmt_close($stmt);
}
mysqli_close($db);
?>
