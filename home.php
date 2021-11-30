<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.html");
    exit;
}

include_once "dbutil.php";
$db = DbUtil::loginConnection();
$stmt = $db->stmt_init();

if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
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
    <div class="container">
        <h4 class="my-5" style="text-align:left;">You are currently logged in as: <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Let's get some exercise in!</h4>
        <?php
        $sql = "SELECT * FROM workout WHERE userID = ?;";
        $stmt = mysqli_prepare($db, $sql);
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        $param_id = $_SESSION["userID"]; //set parameters
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        while($row = mysqli_fetch_array($result))
        {
            $wid = $row['workoutID'];
            $eid = $row['exerciseID'];
            $date = $row['date'];
            $time = $row['time'];
            $notes = $row['notes'];
            $sql = "SELECT * FROM exercise WHERE id = ?";
            $stmt = mysqli_prepare($db, $sql);
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            $param_id = $eid; //set parameters
            mysqli_stmt_execute($stmt);
            $ex_result = mysqli_stmt_get_result($stmt);
            $exercise = mysqli_fetch_array($ex_result);
            $ename = $exercise['name'];
            $etype = $exercise['type']; //the exercise type
            echo "
            <div class='card' style='background-color: #4056a1;'>
                <div class='card card-body'>
                    <h5 style='text-align:left;'>" . $ename . " -- " . $date . " at " . $time . "</h5>
                    <b> Notes: " . $notes . "</b>
                </div>
            </div>
            <p></p>
            ";
        }
        ?>
    </div>
</body>
</html>

<?php
mysqli_close($db);
?>