<?php
include_once "dbutil.php";
$db = DbUtil::loginConnection();
$stmt = $db->stmt_init();

if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$eid = $_GET["exercise"]; //get the exercise ID passed from the form
$sql = "SELECT * FROM exercise WHERE id = ?";
$stmt = mysqli_prepare($db, $sql);
mysqli_stmt_bind_param($stmt, "i", $param_id);
$param_id = $eid; //set parameters
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$exercise = mysqli_fetch_array($result);
$etype = $exercise['type']; //the exercise type
mysqli_close($db);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light sticky-top" style="background-color: #4056a1;box-shadow: 0px 8px 24px rgb(13 13 18 / 10%);">
        <div class="container">
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item active">
                        <a class="nav-link text-white" href="home.php">Home</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link text-white" href="exercise.php">Add Workout</a>
                    </li>
                </ul>
            </div>
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link text-white" href="profile.php">Edit Info</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link text-white" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
    <h2 class="text-primary">Upload a workout!</h2>
    <div class="container">
        <form action="submitWorkout.php" method="post" class='form'>
            <?php
            if(strcmp($etype, "weightlifting") === 0) {
                echo "
                <div class='form-group'>
                    <label class='text-primary'>Weight</label>
                    <input type='number' class='form-control' placeholder='lbs' name='weight'>
                    <label class='text-primary'>Reps</label>
                    <input type='number' class='form-control' name='reps'>
                    <label class='text-primary'>Sets</label>
                    <input type='number' class='form-control' name='sets'>
                </div>
                ";
            } else if(strcmp($etype, "cardio") === 0) {
                echo "
                <div class='form-group'>
                    <label class='text-primary'>Distance</label>
                    <input type='number' class='form-control' placeholder='m' name='distance'>
                    <label class='text-primary'>Duration</label>
                    <input type='number' class='form-control' name='duration' placeholder='mins'>
                </div>
                ";
            } else if(strcmp($etype, "bodyweight") === 0) {
                echo "
                <div class='form-group'>
                    <label class='text-primary'>Reps</label>
                    <input type='number' class='form-control' name='reps'>
                    <label class='text-primary'>Sets</label>
                    <input type='number' class='form-control' name='sets'>
                </div>
                ";
            } else { //for flexibility
                echo "
                <div class='form-group'>
                    <label class='text-primary'>Reps</label>
                    <input type='number' class='form-control' name='reps'>
                    <label class='text-primary'>Duration</label>
                    <input type='number' class='form-control' name='duration' placeholder='mins'>
                </div>
                ";
            }   
            ?>
            <div class="form-group">
                <label class="text-primary" for="exampleInputPassword1">Date/Time</label>
                <input type="datetime-local" class="form-control" placeholder="YYYY-MM-DD HH:MM" id="datetime" name="datetime">
            </div>
            <div class="form-group">
                <label class="text-primary">Notes</label>
                <input type="text" class="form-control" placeholder="Notes" name="notes" , id="notes">
            </div>
            <input type="hidden" class="form-control" name="exercise" value="<?php echo $eid; ?>">
            <input type="hidden" class="form-control" name="exercisetype" value="<?php echo $etype; ?>">
            <input class="btn btn-outline-primary" type="submit" value="Upload">
        </form>
    </div>
</body>
</html>