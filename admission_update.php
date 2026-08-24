<?php
$conn = new mysqli(
    getenv('MYSQLHOST'),
    getenv('MYSQLUSER'),
    getenv('MYSQLPASSWORD'),
    getenv('MYSQLDATABASE'),
    (int) getenv("MYSQLPORT")
);

if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

if(isset($_POST['update'])){

    $id         = $_POST['id'];
    $fullname   = $_POST['fullname'];
    $fathername = $_POST['fathername'];
    $mothername = $_POST['mothername'];
    $dob        = $_POST['dob'];
    $gender     = $_POST['gender'];
    $phone      = $_POST['phone'];
    $email      = $_POST['email'];
    $address    = $_POST['address'];
    $program    = $_POST['program'];
    $branch     = $_POST['branch'];

    $sql = "UPDATE admission_form SET
            fullname=?, fathername=?, mothername=?, dob=?, gender=?, phone=?, email=?, address=?,
            program=?, branch=?
            WHERE id=?";

    $stmt = $conn->prepare($sql);

    if(!$stmt){
        die("SQL Prepare Failed: " . $conn->error);
    }

    $stmt->bind_param("ssssssssssi",
        $fullname, $fathername, $mothername, $dob, $gender,
        $phone, $email, $address, $program, $branch, $id
    );

    if($stmt->execute()){
        echo "<h2 style='text-align:center;color:green;'>✅ Admission Updated Successfully!</h2>";
        echo "<center><a href='admission_edit.php' style='font-size:18px;'>⬅ Back to Edit</a></center>";
    } else {
        echo "<h2 style='text-align:center;color:red;'>❌ Update Failed!</h2>";
    }

    $stmt->close();
}

$conn->close();
?>
