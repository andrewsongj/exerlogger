<?php
// Initialize the session
session_start();

// Check if the user is logged in, otherwise redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Include config file
require_once "dbutil.php";
$db = dbUtil::loginConnection();
//$stmt = $db->stmt_init();

// Define variables and initialize with empty values
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";
$new_username = $new_username_err = "";
$new_lastName = $new_lastName_err = "";
$new_firstName = $new_firstName_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if($_POST["new_username"]){ //if updating username
        //Validate new username
        if(empty(trim($_POST["new_username"]))){
            $new_username_err = "Please enter the new username.";     
        } else {
            $new_username = trim($_POST["new_username"]);
        }

        //Check input errors before updating DB
        if(empty($new_username_err)) {
            // Prepare an update statement
            $sql = "UPDATE user SET username = ? WHERE userID = ?";

            if($stmt = mysqli_prepare($db, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "si", $param_username, $param_id);

                // Set parameters
                $param_username = $new_username;
                $param_id = $_SESSION["userID"];
                echo "$param_id, $param_username";
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    // Username updated successfully. Destroy the session, and redirect to home page
                    session_destroy();
                    header("location: login.php");
                    exit();
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }
    
                // Close statement
                mysqli_stmt_close($stmt);
            }
        }
    } elseif($_POST["new_firstName"]) {
        //Validate new first name
        if(empty(trim($_POST["new_firstName"]))){
            $new_firstName_err = "Please enter the new first name.";     
        } else {
            $new_firstName = trim($_POST["new_firstName"]);
        }

        //Validate new last name
        /*if(empty(trim($_POST["new_lastName"]))){
            $new_username_err = "Please enter the new last name.";     
        } else {
            $new_username = trim($_POST["new_lastName"]);
        }*/

        if(empty($new_firstName_err)/* && empty($new_lastName_err)*/) {
            // Prepare an update statement
            $sql = "UPDATE user SET firstName = ? WHERE userID = ?";

            if($stmt = mysqli_prepare($db, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "si", $param_firstName, $param_id);

                // Set parameters
                $param_firstName = $new_firstName;
                //$param_lastName = $new_lastName;
                $param_id = $_SESSION["userID"];
                echo "$param_id, $param_firstName";
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    // first name updated successfully. Destroy the session, and redirect to home page
                    session_destroy();
                    header("location: login.php");
                    exit();
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }
    
                // Close statement
                mysqli_stmt_close($stmt);
            }
        }
    } elseif($_POST["new_lastName"]) {
        //Validate new last name
        if(empty(trim($_POST["new_lastName"]))){
            $new_lastName_err = "Please enter the new last name.";     
        } else {
            $new_lastName = trim($_POST["new_lastName"]);
        }

        if(empty($new_lastName_err)) {
            // Prepare an update statement
            $sql = "UPDATE user SET lastName = ? WHERE userID = ?";

            if($stmt = mysqli_prepare($db, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "si", $param_lastName, $param_id);

                // Set parameters
                $param_lastName = $new_lastName;
                $param_id = $_SESSION["userID"];
                echo "$param_id, $param_lastName";
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    // last name updated successfully. Destroy the session, and redirect to home page
                    session_destroy();
                    header("location: login.php");
                    exit();
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }
    
                // Close statement
                mysqli_stmt_close($stmt);
            }
        }
    } else {
    // Validate new password
    if(empty(trim($_POST["new_password"]))){
        $new_password_err = "Please enter the new password.";     
    } elseif(strlen(trim($_POST["new_password"])) < 6){
        $new_password_err = "Password must have atleast 6 characters.";
    } else{
        $new_password = trim($_POST["new_password"]);
    }

    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm the password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($new_password_err) && ($new_password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }

    // Check input errors before updating the database
    if(empty($new_password_err) && empty($confirm_password_err)){
        // Prepare an update statement
        $sql = "UPDATE user SET password = ? WHERE userID = ?";

        if($stmt = mysqli_prepare($db, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);

            // Set parameters
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $_SESSION["userID"];
            echo "$param_id, $param_password";
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Password updated successfully. Destroy the session, and redirect to login page
                session_destroy();
                header("location: login.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
}
    // Close connection
    mysqli_close($db);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h1>User Profile & User Information Change</h1>
        <h2>Reset Password</h2>
        <p>Fill out this form to reset your password.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="new_password" class="form-control <?php echo (!empty($new_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_password; ?>">
                <span class="invalid-feedback"><?php echo $new_password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <a class="btn btn-link ml-2" href="home.php">Cancel</a>
            </div>
        </form>
        <h2>Set new username.</h2>
        <p>Fill out this form to change your username.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>New Username</label>
                <input type="username" name="new_username" class="form-control <?php echo (!empty($new_username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_username; ?>">
                <span class="invalid-feedback"><?php echo $new_username_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <a class="btn btn-link ml-2" href="home.php">Cancel</a>
            </div>
        </form>
        <h2>Set new first name.</h2>
        <p>Fill out this form to change your first name.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>New first name</label>
                <input type="firstName" name="new_firstName" class="form-control <?php echo (!empty($new_firstName_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_firstName; ?>">
                <span class="invalid-feedback"><?php echo $new_firstName_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <a class="btn btn-link ml-2" href="home.php">Cancel</a>
            </div>
        </form>
        <h2>Set new last name.</h2>
        <p>Fill out this form to change your name.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>New last name</label>
                <input type="lastName" name="new_lastName" class="form-control <?php echo (!empty($new_lastName_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_lastName; ?>">
                <span class="invalid-feedback"><?php echo $new_lastName_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <a class="btn btn-link ml-2" href="home.php">Cancel</a>
            </div>
        </form>
    </div>    
</body>
</html>