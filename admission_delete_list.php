<?php
include("admin_auth.php");

$url = getenv('MYSQL_URL');
$db = parse_url($url);

$host = $db['host'];
$user = 'root';
$password = urldecode($db['pass']);
$database = ltrim($db['path'], '/');
$port = $db['port'] ?? 3306;

$conn = new mysqli($host, $user, $password, $database, $port);
if($conn->connect_error) die("DB Error: " . $conn->connect_error);

/* ✅ Search feature */
$search = "";
if(isset($_GET['search'])){
    $search = trim($_GET['search']);
}

/* ✅ Query */
if($search != ""){
    $sql = "SELECT * FROM admission_form 
            WHERE fullname LIKE ? OR phone LIKE ? OR email LIKE ?
            ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    if(!$stmt) die("<h3 style='color:red;text-align:center;'>❌ Prepare Failed: ".$conn->error."</h3>");

    $like = "%".$search."%";
    $stmt->bind_param("sss", $like, $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT * FROM admission_form ORDER BY id DESC";
    $result = $conn->query($sql);
}

if(!$result){
    die("<h3 style='color:red;text-align:center;'>❌ Query Failed: ".$conn->error."</h3>");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Delete Admission Records (Admin)</title>

<style>
body{
    margin:0;
    font-family:"Segoe UI", Arial;
    background:#f3fff7;
}
.page-title{
    text-align:center;
    margin:25px 0;
    color:#c62828;
    letter-spacing:1px;
    font-size:30px;
    font-weight:900;
}
.table-container{
    margin: 20px auto;
    padding: 15px;
    width: 98%;
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
    font-weight:900;
    color:#14532d;
    font-size:16px;
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
    background:#003a8c;
}

/* Search box */
.search-box{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
    margin: 10px 0 15px 0;
}
.search-box input{
    flex:1;
    min-width:250px;
    padding:10px;
    border:1px solid #aaa;
    border-radius:10px;
    outline:none;
    font-size:14px;
    background:#f9fffb;
}
.search-box button{
    padding:10px 18px;
    border:none;
    border-radius:10px;
    cursor:pointer;
    font-weight:900;
    color:white;
    background:#c62828;
}
.search-box a{
    padding:10px 18px;
    border-radius:10px;
    text-decoration:none;
    font-weight:900;
    background:#444;
    color:white;
}

/* Table */
.order-table{
    width:100%;
    border-collapse: collapse;
    font-size:14px;
}
.order-table th{
    background: #c62828;
    color:white;
    padding:12px;
    border:1px solid #c62828;
    text-align:center;
    font-weight:900;
}
.order-table td{
    padding:10px;
    border:1px solid #ddd;
    text-align:center;
}
.order-table tr:nth-child(even){
    background:#fff3f3;
}
.order-table tr:hover{
    background:#ffe1e1;
    transition:0.2s;
}

.delete-btn{
    background:#c62828;
    color:white;
    padding:7px 14px;
    border-radius:8px;
    text-decoration:none;
    font-weight:900;
    display:inline-block;
}

.count-box{
    font-weight:900;
    color:#14532d;
    margin-bottom:12px;
    font-size:15px;
}

.no-data{
    text-align:center;
    font-size:20px;
    font-weight:900;
    color:#0b7a36;
    padding:25px;
}
</style>
</head>

<body>

<h1 class="page-title">🗑️ Delete Admission Records (Admin Only)</h1>

<div class="table-container">

    <div class="topbar">
        <div class="admin-name">✅ Logged in as: <?php echo htmlspecialchars($_SESSION['admin']); ?></div>

        <div class="btn-group">
            <a class="btn dashboard" href="admin_dashboard.php">Back to Dashboard</a>
            <a class="btn logout" href="admin_logout.php">Logout</a>
        </div>
    </div>

    <!-- ✅ Search Form -->
    <form method="GET" class="search-box">
        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>"
               placeholder="Search by Name / Phone / Email...">
        <button type="submit">Search</button>
        <a href="admission_delete_list.php">Reset</a>
    </form>

    <div class="count-box">
        ✅ Total Records Found: <?php echo $result->num_rows; ?>
    </div>

    <?php
    if($result->num_rows > 0){

        echo "<table class='order-table'>
        <tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Father Name</th>
            <th>Mother Name</th>
            <th>DOB</th>
            <th>Gender</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Address</th>
            <th>Program</th>
            <th>Branch</th>
            <th>Action</th>
        </tr>";

        while($row = $result->fetch_assoc()){

            echo "<tr>
                <td>".htmlspecialchars($row['id'])."</td>
                <td>".htmlspecialchars($row['fullname'])."</td>
                <td>".htmlspecialchars($row['fathername'])."</td>
                <td>".htmlspecialchars($row['mothername'])."</td>
                <td>".htmlspecialchars($row['dob'])."</td>
                <td>".htmlspecialchars($row['gender'])."</td>
                <td>".htmlspecialchars($row['phone'])."</td>
                <td>".htmlspecialchars($row['email'])."</td>
                <td>".htmlspecialchars($row['address'])."</td>
                <td>".htmlspecialchars($row['program'])."</td>
                <td>".htmlspecialchars($row['branch'])."</td>
                <td>
                    <a class='delete-btn'
                       href='admission_delete.php?id=".urlencode($row['id'])."'
                       onclick=\"return confirm('Are you sure you want to delete this admission record?');\">
                       Delete
                    </a>
                </td>
            </tr>";
        }

        echo "</table>";

    } else {
        echo "<div class='no-data'>No admission records found.</div>";
    }
    ?>

</div>

</body>
</html>
