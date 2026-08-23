<?php
$conn = new mysqli(
    getenv('MYSQLHOST'),
    getenv('MYSQLUSER'),
    getenv('MYSQLPASSWORD'),
    getenv('MYSQLDATABASE'),
    getenv('MYSQLPORT')
);
if($conn->connect_error){
    die("❌ DB Error: " . $conn->connect_error);
}

if(isset($_POST['update'])){

    if(!isset($_POST['student_uid']) || $_POST['student_uid']==""){
        die("<h2 style='color:red;text-align:center;'>❌ Student UID Missing!</h2>");
    }

    $student_uid = trim($_POST['student_uid']);
    $fullname    = trim($_POST['fullname']);
    $fathername  = trim($_POST['fathername']);
    $mothername  = trim($_POST['mothername']);
    $dob         = $_POST['dob'];
    $gender      = $_POST['gender'];
    $phone       = trim($_POST['phone']);
    $address     = trim($_POST['address']);
    $program     = $_POST['program'];
    $branch      = $_POST['branch'];

    
    $check = $conn->prepare("SELECT status FROM admission_form WHERE student_uid=?");
    $check->bind_param("s", $student_uid);
    $check->execute();
    $res = $check->get_result();
    $data = $res->fetch_assoc();

    if($data && $data['status']=="Admission Successful"){
        die("
        <h2 style='text-align:center;color:red;'>❌ Update Blocked!</h2>
        <p style='text-align:center;font-size:18px;'>
          Your admission is already <b>Approved</b>. Editing is disabled.
        </p>
        <center><a href='student_edit.php'>⬅ Back</a></center>
        ");
    }

    /*  Update query */
    $stmt = $conn->prepare("
        UPDATE admission_form SET
            fullname=?,
            fathername=?,
            mothername=?,
            dob=?,
            gender=?,
            phone=?,
            address=?,
            program=?,
            branch=?
        WHERE student_uid=?
    ");

    if(!$stmt){
        die("❌ Prepare Failed: " . $conn->error);
    }

    $stmt->bind_param(
        "ssssssssss",
        $fullname,
        $fathername,
        $mothername,
        $dob,
        $gender,
        $phone,
        $address,
        $program,
        $branch,
        $student_uid
    );

    if($stmt->execute()){
        echo "
        <h2 style='color:green;text-align:center;'>✅ Updated Successfully</h2>
        <center><a href='student_edit.php'>⬅ Back</a></center>
        ";
    }else{
        echo "
        <h2 style='color:red;text-align:center;'>❌ Update Failed</h2>
        <p style='text-align:center;'>".$stmt->error."</p>
        ";
    }
}
?>
