<?php
$conn = new mysqli(
    getenv('MYSQLHOST'),
    getenv('MYSQLUSER'),
    getenv('MYSQLPASSWORD'),
    getenv('MYSQLDATABASE'),
    getenv('MYSQLPORT')
);
if($conn->connect_error){
    die("DB Error");
}
$result = $conn->query("SELECT * FROM student_info");
?>
<!DOCTYPE html>
<html>
<head>
<title>Students Details</title>
<style>
body{font-family:Arial;background:#eef6ff;}

table{
    width:90%;
    margin:30px auto;
    border-collapse:collapse;
}
th,td{
    border:1px solid #ccc;
    padding:10px;
    text-align:center;
}

th{
    background:#003a8c;
    color:white;
}
</style>
</head>
<body>
<h2 align="center">Students Details</h2>
<table>
<tr>
<th>Roll</th>
<th>Name</th>
<th>Dept</th>
<th>Sem</th>
<th>Address</th>
<th>Mobile</th>
<th>Email</th>
<th>CGPA</th>
</tr>
<?php
while($row = $result->fetch_assoc()){
?>
<tr>
<td><?= $row['Rollno'] ?></td>
<td><?= $row['Name'] ?></td>
<td><?= $row['Dept'] ?></td>
<td><?= $row['Sem'] ?></td>
<td><?= $row['Address'] ?></td>
<td><?= $row['Mobile'] ?></td>
<td><?= $row['Email'] ?></td>
<td><?= $row['CGPA'] ?></td>
</tr>
<?php } ?>
</table>
</body>
</html>
