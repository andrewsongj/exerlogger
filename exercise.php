<?php
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
                    <a class="nav-link text-white" href="#">Edit Profile</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link text-white" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
    <?php
    $sql = "SELECT * FROM exercise;";
    $result = mysqli_query($db,$sql);

    echo "
    <p></p>
    <h2 class='text-primary'>Select the exercise for your workout!</h2>
    <p></p>
    <form action='workout.php' method='get' class='form'>
        <table class='table table-bordered table-condensed table-hover table-striped text-center'>
            <thead>
                <tr>
                    <th scope='col'>Name</th>
                    <th scope='col'>Type</th>
                    <th scope='col'>Equipment</th>
                    <th scope='col'>Muscle</th>
                    <th scope='col'>Notes</th>
                </tr>
            </thead>
            <tbody class='text-left'>";
            while($row = mysqli_fetch_array($result))
            {
              $eid = $row['id'];
              $ename = $row['name'];
              echo "<tr>";
              echo "<td><input type='radio' name='exercise' value='$eid'> " . $ename . "</td>";
              echo "<td>" . $row['type'] . "</td>";
              echo "<td>" . $row['equipment'] . "</td>";
              echo "<td>" . $row['muscleUsed'] . "</td>";
              echo "<td>" . $row['notes'] . "</td>";
              echo "</tr>";
            }
            echo "
            </tbody>
        </table>
        <input class='btn btn-outline-primary' type='submit' value='Continue'>
    </form"; 
    ?>
</body>
</html>

<?php
mysqli_close($db);
?>