<?php
$conn = new mysqli(
    getenv('MYSQLHOST'),
    getenv('MYSQLUSER'),
    getenv('MYSQLPASSWORD'),
    getenv('MYSQLDATABASE'),
    getenv('MYSQLPORT')
);

if($conn->connect_error){
    die("DB Error: " . $conn->connect_error);
}

$student = null;
$msg = "";

if(isset($_POST['search'])){

    $roll = trim($_POST['rollno']);

    $stmt = $conn->prepare(
        "SELECT * FROM student_info WHERE Rollno=?"
    );

    if(!$stmt){
        die("Prepare Failed: " . $conn->error);
    }

    $stmt->bind_param("s", $roll);
    $stmt->execute();
    $res = $stmt->get_result();

    if($res->num_rows > 0){
        $student = $res->fetch_assoc();
    }else{
        $msg = "❌ No record found for this Roll No!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Check Student Details</title>

<style>
body{
  margin:0;
  font-family:Arial;
  background:#f3fff7;
  padding:30px;
}

.container{
  width:60%;
  margin:auto;
  background:white;
  padding:25px;
  border-radius:12px;
  box-shadow:0 10px 25px rgba(0,0,0,0.1);
}

h2{
  text-align:center;
  color:#0b7a36;
}

label{
  font-weight:bold;
  color:#14532d;
  display:block;
  margin-top:12px;
}

input{
  width:100%;
  padding:10px;
  border:1px solid #aaa;
  border-radius:6px;
}

button{
  width:100%;
  margin-top:15px;
  padding:12px;
  background:#0b7a36;
  color:white;
  border:none;
  border-radius:8px;
  font-weight:bold;
  cursor:pointer;
}

.msg{
  text-align:center;
  color:red;
  font-weight:bold;
  margin-top:15px;
}

table{
  width:100%;
  border-collapse:collapse;
  margin-top:20px;
}

th{
  background:#0b7a36;
  color:white;
  padding:10px;
}

td{
  padding:10px;
  border:1px solid #ddd;
}

@media(max-width:900px){
  .container{ width:95%; }
}
</style>
</head>

<body>

<div class="container">

<h2>📘 Check Student Information</h2>

<!-- Search Form -->
<form method="POST">

  <label>Enter Roll Number</label>

  <input type="text"
         name="rollno"
         placeholder="Example: 2305*****"
         required>

  <button type="submit" name="search">
    CHECK DETAILS
  </button>

</form>

<!-- Error -->
<?php if($msg!=""){ ?>
  <div class="msg"><?php echo $msg; ?></div>
<?php } ?>


<!-- Student Details -->
<?php if($student){ ?>

<table>

<tr><th colspan="2">Student Details</th></tr>

<tr>
  <td><b>Roll No</b></td>
  <td><?php echo $student['Rollno']; ?></td>
</tr>

<tr>
  <td><b>Name</b></td>
  <td><?php echo $student['Name']; ?></td>
</tr>

<tr>
  <td><b>Department</b></td>
  <td><?php echo $student['Dept']; ?></td>
</tr>

<tr>
  <td><b>Semester</b></td>
  <td><?php echo $student['Sem']; ?></td>
</tr>

<tr>
  <td><b>Address</b></td>
  <td><?php echo $student['Address']; ?></td>
</tr>

<tr>
  <td><b>Mobile</b></td>
  <td><?php echo $student['Mobile']; ?></td>
</tr>

<tr>
  <td><b>Email</b></td>
  <td><?php echo $student['Email']; ?></td>
</tr>

<tr>
  <td><b>CGPA</b></td>
  <td><?php echo $student['CGPA']; ?></td>
</tr>

</table>

<?php } ?>

</div>

</body>
</html>
