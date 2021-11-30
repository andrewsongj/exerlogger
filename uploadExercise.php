<?php
include_once "dbutil.php";
$db = DbUtil::loginConnection();
$stmt = $db->stmt_init();

if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
if(isset($_POST['submit'])){
    // read json file
    $data = file_get_contents($_FILES['importfile']['tmp_name']);
    echo $data;
    $arr = json_decode($data, true);

    foreach($arr as $item) { //foreach element in $arr
        $name = $item['name']; 
        $type = $item['type'];
        $equipment = $item['equipment'];
        $muscleUsed = $item['muscleUsed'];
        $notes = $item['notes'];
        if(strcmp($type, "weightlifting") === 0 || strcmp($type, "bodyweight") === 0 || strcmp($type, "cardio") === 0 || strcmp($type, "flexibility") === 0 ) {
            $sql = "INSERT INTO exercise (name, type, equipment, muscleUsed, notes) VALUES (?, ?, ?, ?, ?)";

            if($stmt = mysqli_prepare($db, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "sssss", $param_name, $param_type, $param_equipment, $param_muscleUsed, $param_notes);
                
                // Set parameters
                $param_name = $name;
                $param_type = $type;
                $param_equipment = $equipment;
                $param_muscleUsed = $muscleUsed;
                $param_notes = $notes;
                
                // Attempt to execute the prepared statement
                if(!mysqli_stmt_execute($stmt)){ 
                    break;
                }
            }
        }
    }
}
header("location: exercise.php");
mysqli_close($db);
?>