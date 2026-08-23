<?php

$conn = new mysqli("localhost","root","","college_db");

if($conn->connect_error){
    die("DB Error");
}

if(isset($_POST['rollno'])){

    $rollno = $_POST['rollno'];
    $name   = $_POST['name'];
    $dept   = $_POST['dept'];
    $sem    = $_POST['sem'];
    $address= $_POST['address'];
    $mobile = $_POST['mobile'];
    $email  = $_POST['email'];
    $cgpa   = $_POST['cgpa'];

    $sql = "INSERT INTO student_info
    (Rollno, Name, Dept, Sem, Address, Mobile, Email, CGPA)
    VALUES (?,?,?,?,?,?,?,?)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("ississsd",
        $rollno,
        $name,
        $dept,
        $sem,
        $address,
        $mobile,
        $email,
        $cgpa
    );

    if($stmt->execute()){

        echo "<h2 style='color:green;text-align:center;'>
        ✅ Data Saved Successfully
        </h2>";

        echo "<center>
        <a href='display_students.php'>View Students</a>
        </center>";

    }else{

        echo "Error: ".$stmt->error;
    }
}
?>
