<?php
$conn = new mysqli(
    getenv('MYSQLHOST'),
    getenv('MYSQLUSER'),
    getenv('MYSQLPASSWORD'),
    getenv('MYSQLDATABASE'),
    (int) getenv("MYSQLPORT")
);
if($conn->connect_error){
    die("DB Error: " . $conn->connect_error);
}
if(isset($_POST['fullname'])){
    $student_uid = "KIIT" . date("YmdHis") . rand(10,99);
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
    $status = "Pending";
    $sql = "INSERT INTO admission_form
    (student_uid, fullname, fathername, mothername, dob, gender, phone, email, address, program, branch, status)
    VALUES
    (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if(!$stmt){
        die("❌ SQL Prepare Failed: " . $conn->error);
    }
    $stmt->bind_param(
        "ssssssssssss",
        $student_uid, $fullname, $fathername, $mothername,
        $dob, $gender, $phone, $email, $address, $program, $branch, $status
    );

    if($stmt->execute()){
        echo "
        <h2 style='text-align:center;color:green;margin-top:40px;'>✅ Form Submitted Successfully!</h2>

        <h3 style='text-align:center;color:#0b7a36;'>
          ✅ Your Unique Student ID: <span style='color:#003a8c;'>$student_uid</span>
        </h3>

        <center style='margin-top:15px;'>
          <a href='student_edit_uid.php'
             style='font-size:18px;font-weight:900;color:#0b7a36;text-decoration:none;'>
            ✏️ Edit using Unique ID
          </a>
        </center>
        ";
    } else {
        echo "<h2 style='text-align:center;color:red;'>❌ Insert Failed: ".$stmt->error."</h2>";
    }

    $stmt->close();
}

$conn->close();
?>
