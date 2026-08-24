<?php
include("admin_auth.php");

$conn = new mysqli(
    getenv('MYSQLHOST'),
    getenv('MYSQLUSER'),
    getenv('MYSQLPASSWORD'),
    getenv('MYSQLDATABASE'),
    (int) getenv("MYSQLPORT")
);
if($conn->connect_error) die("DB Error: " . $conn->connect_error);

$sql = "SELECT * FROM admission_form";
$result = $conn->query($sql);

if(!$result){
    die("<h3 style='color:red;text-align:center;'>
        ❌ Query Failed: ".$conn->error."
    </h3>");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admission Records (Admin)</title>
<style>
body{
    margin:0;
    font-family:"Segoe UI", Arial;
    background:#f3fff7;
}
.page-title{
    text-align:center;
    margin:25px 0;
    color:#0b7a36;
    letter-spacing:1px;
    font-size:30px;
    font-weight:900;
}
.table-container{
    margin: 20px auto;
    padding: 15px;
    width: 95%;
    background: white;
    border-radius: 14px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.10);
    border:1px solid rgba(0,0,0,0.08);
}
.topbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:12px;
    flex-wrap:wrap;
    gap:10px;
}
.admin-name{
    font-weight:800;
    color:#14532d;
    font-size:18px;
}
.btn-group{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
}
a.btn{
    padding:10px 16px;
    border-radius:10px;
    color:white;
    text-decoration:none;
    font-weight:900;
    display:inline-block;
}

.dashboard{
    background: linear-gradient(135deg,#0b7a36,#16c35a);
}
.logout{
    background:#c62828;
}

/* Table */
.order-table{
    width:100%;
    border-collapse: collapse;
    font-size:14px;
}
.order-table th{
    background: #0b7a36;
    color:white;
    padding:12px;
    border:1px solid #0b7a36;
    text-align:center;
}
.order-table td{
    padding:10px;
    border:1px solid #ddd;
    text-align:center;
}
.order-table tr:nth-child(even){
    background:#f2fff6;
}

.no-data{
    text-align:center;
    font-size:22px;
    font-weight:900;
    color:#0b7a36;
    padding:25px;
}
</style>
</head>
<body>

<h1 class="page-title">Admission Records (Admin Only)</h1>

<div class="table-container">

    <div class="topbar">
        <div class="admin-name">✅ Logged in as: <?php echo $_SESSION['admin']; ?></div>

        <div class="btn-group">
            <a class="btn dashboard" href="admin_dashboard.php">Back to Dashboard</a>
            <a class="btn logout" href="admin_logout.php">Logout</a>
        </div>
    </div>

    <?php
    if($result->num_rows > 0){

        echo "<table class='order-table'>
        <tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Father</th>
            <th>Mother</th>
            <th>DOB</th>
            <th>Gender</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Program</th>
            <th>Branch</th>
            <th>Status</th>
            <th>Approve</th>

        </tr>";

        while($row = $result->fetch_assoc()){
            echo "<tr>
                <td>".$row['id']."</td>
                <td>".$row['fullname']."</td>
                <td>".$row['fathername']."</td>
                <td>".$row['mothername']."</td>
                <td>".$row['dob']."</td>
                <td>".$row['gender']."</td>
                <td>".$row['phone']."</td>
                <td>".$row['email']."</td>
                <td>".$row['program']."</td>
                <td>".$row['branch']."</td>
                <td>".$row['status']."</td>
                <td>
                <a href='admin_aprove.php?id=".$row['id']."'
                style='background:#0b7a36;color:white;padding:6px 12px;border-radius:8px;text-decoration:none;font-weight:900;'
                onclick=\"return confirm('Approve this admission?');\">
                Approve
                </a>
                </td>

            </tr>";
        }

        echo "</table>";

    } else {
        echo "<div class='no-data'>No Admission Records Found.</div>";
    }
    ?>

</div>

</body>
</html>
