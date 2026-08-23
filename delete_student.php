<?php
$conn = new mysqli(
    getenv('MYSQLHOST'),
    getenv('MYSQLUSER'),
    getenv('MYSQLPASSWORD'),
    getenv('MYSQLDATABASE'),
    getenv('MYSQLPORT')
);
if ($conn->connect_error) die("DB Error");

$student = null;

/*  Search Student by Rollno */
if (isset($_POST['search'])) {
    $rollno = $_POST['rollno'];

    $stmt = $conn->prepare("SELECT * FROM student_info WHERE Rollno=?");
    $stmt->bind_param("s", $rollno);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $student = $res->fetch_assoc();
    } else {
        echo "<p style='color:#d11a2a;text-align:center;font-weight:700;'>❌ Student not found!</p>";
    }
}

/*  Delete Student */
if (isset($_POST['delete'])) {
    $rollno = $_POST['rollno'];

    $stmt = $conn->prepare("DELETE FROM student_info WHERE Rollno=?");
    $stmt->bind_param("s", $rollno);
    $stmt->execute();

    header("Location: display_students.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Delete Student | KIIT Panel</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:"Segoe UI", Arial, sans-serif;
}

body{
    min-height:100vh;
    background: linear-gradient(135deg, #eaf3ff, #ffffff);
    padding: 40px 0;
}

.wrapper{
    width: 92%;
    max-width: 900px;
    margin: auto;
}

.header{
    background: linear-gradient(90deg, #003a8c, #0b66ff);
    padding: 22px;
    border-radius: 16px;
    color: white;
    box-shadow: 0 12px 35px rgba(0,0,0,0.18);
    text-align:center;
}

.header h1{
    font-size: 28px;
    letter-spacing: 1px;
    margin-bottom: 6px;
}

.card{
    margin-top: 22px;
    background: white;
    border-radius: 16px;
    padding: 22px;
    box-shadow: 0 12px 35px rgba(0,0,0,0.10);
    border: 1px solid rgba(0, 58, 140, 0.12);
}

.section-title{
    font-size: 18px;
    color:#003a8c;
    font-weight: 700;
    margin-bottom: 16px;
    border-left: 5px solid #0b66ff;
    padding-left: 10px;
}

label{
    display:block;
    font-size: 13px;
    color: #223;
    font-weight: 700;
    margin-bottom: 6px;
}

input{
    width:100%;
    padding: 11px 12px;
    border-radius: 12px;
    border: 1px solid #cfd9ea;
    outline: none;
    font-size: 14px;
    background: #f9fbff;
    transition: 0.2s ease;
}

input:focus{
    border-color:#0b66ff;
    box-shadow: 0 0 0 4px rgba(11, 102, 255, 0.12);
    background: #ffffff;
}

.btn-row{
    margin-top: 14px;
    display:flex;
    gap: 12px;
    flex-wrap: wrap;
}

.btn{
    padding: 11px 18px;
    border:none;
    border-radius: 12px;
    cursor:pointer;
    font-weight:800;
    font-size: 14px;
    color:white;
    transition: 0.25s ease;
}

.btn-search{
    background: linear-gradient(135deg, #003a8c, #0b66ff);
}

.btn-delete{
    background: linear-gradient(135deg, #c62828, #ff3b3b);
}

.btn-back{
    background: linear-gradient(135deg, #444, #222);
    text-decoration:none;
    display:inline-flex;
    align-items:center;
    justify-content:center;
}

.btn:hover{
    transform: translateY(-2px);
    box-shadow: 0 12px 28px rgba(0,0,0,0.18);
}

.details{
    margin-top: 10px;
    line-height: 1.9;
    color: #222;
    font-size: 15px;
}

.details b{
    color:#003a8c;
}

.warning{
    margin-top: 10px;
    font-size: 13px;
    font-weight: 700;
    color:#c62828;
}
</style>
</head>
<body>
<div class="wrapper">

    <div class="header">
        <h1>🗑️ Delete Student</h1>
        <p>Search by Rollno and confirm delete</p>
    </div>

    <!--  Search Card -->
    <div class="card">
        <div class="section-title">🔍 Search Student</div>

        <form method="POST">
            <label>Enter Rollno</label>
            <input type="text" name="rollno" placeholder="Enter Rollno (example: 210101)" required>

            <div class="btn-row">
                <button class="btn btn-search" name="search">Search</button>
                <a class="btn btn-back" href="display_students.php">Back</a>
            </div>
        </form>
    </div>

    <!--  Student Details + Confirm Delete -->
    <?php if($student){ ?>
    <div class="card">
        <div class="section-title">⚠️ Confirm Delete</div>

        <div class="details">
            <p><b>Rollno:</b> <?= htmlspecialchars($student['Rollno']) ?></p>
            <p><b>Name:</b> <?= htmlspecialchars($student['Name']) ?></p>
            <p><b>Dept:</b> <?= htmlspecialchars($student['Dept']) ?></p>
            <p><b>Sem:</b> <?= htmlspecialchars($student['Sem']) ?></p>
            <p><b>Mobile:</b> <?= htmlspecialchars($student['Mobile']) ?></p>
            <p><b>Email:</b> <?= htmlspecialchars($student['Email']) ?></p>
            <p><b>CGPA:</b> <?= htmlspecialchars($student['CGPA']) ?></p>
        </div>
        <p class="warning">This action cannot be undone!</p>
        <form method="POST" onsubmit="return confirm('Are you sure you want to delete this student?');">
            <input type="hidden" name="rollno" value="<?= htmlspecialchars($student['Rollno']) ?>">
            <div class="btn-row">
                <button class="btn btn-delete" name="delete">Confirm Delete</button>
                <a class="btn btn-back" href="display_students.php">Cancel</a>
            </div>
        </form>
    </div>
    <?php } ?>
</div>
</body>
</html>
