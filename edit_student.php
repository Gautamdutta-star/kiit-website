<?php
$conn = new mysqli("localhost", "root", "", "college_db");
if ($conn->connect_error) die("DB Error");
$student = null;

/* ✅ Search by Rollno */
if (isset($_POST['search'])) {
    $rollno = $_POST['rollno'];
    $stmt = $conn->prepare("SELECT * FROM student_info WHERE Rollno=?");
    $stmt->bind_param("s", $rollno);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        $student = $res->fetch_assoc();
    } else {
        echo "<p style='color:#d11a2a;text-align:center;font-size:18px;font-weight:700;'>❌ Student not found!</p>";
    }
}

/*  Update Student */
if (isset($_POST['update'])) {
    $old_rollno = $_POST['old_rollno'];
    $Rollno  = $_POST['Rollno'];
    $Name    = $_POST['Name'];
    $Dept    = $_POST['Dept'];
    $Sem     = $_POST['Sem'];
    $Address = $_POST['Address'];
    $Mobile  = $_POST['Mobile'];
    $Email   = $_POST['Email'];
    $CGPA    = $_POST['CGPA'];

    $stmt = $conn->prepare("
        UPDATE student_info SET
        Rollno=?, Name=?, Dept=?, Sem=?, Address=?, Mobile=?, Email=?, CGPA=?
        WHERE Rollno=?
    ");

    $stmt->bind_param(
        "sssssssss",
        $Rollno, $Name, $Dept, $Sem, $Address, $Mobile, $Email, $CGPA, $old_rollno
    );
    if ($stmt->execute()) {
        header("Location: display_students.php");
        exit();
    } else {
        echo "<p style='color:#d11a2a;text-align:center;font-size:18px;font-weight:700;'>❌ Update Failed!</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Student | KIIT Panel</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family: "Segoe UI", Arial, sans-serif;
}

body{
    min-height:100vh;
    background: linear-gradient(135deg, #eaf3ff, #ffffff);
    padding: 40px 0;
}

/* Main container */
.wrapper{
    width: 92%;
    max-width: 980px;
    margin: auto;
}

/* Header */
.header{
    background: linear-gradient(90deg, #003a8c, #0b66ff);
    padding: 22px;
    border-radius: 16px;
    color: white;
    box-shadow: 0 12px 35px rgba(0,0,0,0.18);
    text-align:center;
}

.header h1{
    font-size: 30px;
    letter-spacing: 1px;
    margin-bottom: 6px;
}

.header p{
    opacity: 0.9;
    font-size: 14px;
}

/* Card */
.card{
    margin-top: 22px;
    background: white;
    border-radius: 16px;
    padding: 22px;
    box-shadow: 0 12px 35px rgba(0,0,0,0.10);
    border: 1px solid rgba(0, 58, 140, 0.12);
}

/* Titles */
.section-title{
    font-size: 18px;
    color:#003a8c;
    font-weight: 700;
    margin-bottom: 16px;
    border-left: 5px solid #0b66ff;
    padding-left: 10px;
}

/* Form layout */
.grid{
    display:grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
}

.full{
    grid-column: 1 / -1;
}

label{
    display:block;
    font-size: 13px;
    color: #223;
    font-weight: 700;
    margin-bottom: 6px;
}

input, select, textarea{
    width:100%;
    padding: 11px 12px;
    border-radius: 12px;
    border: 1px solid #cfd9ea;
    outline: none;
    font-size: 14px;
    background: #f9fbff;
    transition: 0.2s ease;
}

textarea{
    resize:none;
    height: 70px;
}

input:focus, select:focus, textarea:focus{
    border-color:#0b66ff;
    box-shadow: 0 0 0 4px rgba(11, 102, 255, 0.12);
    background: #ffffff;
}

/* Buttons */
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

.btn-update{
    background: linear-gradient(135deg, #0a7a3e, #1db954);
}

.btn-back{
    background: linear-gradient(135deg, #c62828, #ff3b3b);
    text-decoration:none;
    display:inline-flex;
    align-items:center;
    justify-content:center;
}

.btn:hover{
    transform: translateY(-2px);
    box-shadow: 0 12px 28px rgba(0,0,0,0.18);
}

/* Small note */
.note{
    margin-top: 10px;
    font-size: 12px;
    color: #555;
}
</style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <h1>KIIT Student Panel</h1>
        <p>Search student by Rollno and update details</p>
    </div>
    <!--  Search Card -->
    <div class="card">
        <div class="section-title">🔍 Search Student</div>
        <form method="POST">
            <div class="grid">
                <div class="full">
                    <label>Rollno</label>
                    <input type="text" name="rollno" placeholder="Enter Rollno (example: 210101)" required>
                </div>
            </div>
            <div class="btn-row">
                <button class="btn btn-search" name="search">Search</button>
                <a class="btn btn-back" href="display_students.php">Back</a>
            </div>
            <div class="note">Tip: Rollno should be unique for best result ✅</div>
        </form>
    </div>

    <!--  Edit Card -->
    <?php if($student){ ?>
    <div class="card">
        <div class="section-title">✏️ Edit Student Details</div>
        <form method="POST">
            <input type="hidden" name="old_rollno" value="<?= htmlspecialchars($student['Rollno']) ?>">
            <div class="grid">
                <div>
                    <label>Rollno</label>
                    <input type="text" name="Rollno" value="<?= htmlspecialchars($student['Rollno']) ?>" required>
                </div>
                <div>
                    <label>Name</label>
                    <input type="text" name="Name" value="<?= htmlspecialchars($student['Name']) ?>" required>
                </div>
                <div>
                    <label>Dept</label>
                    <select name="Dept" required>
                        <option value="<?= htmlspecialchars($student['Dept']) ?>">
                            <?= htmlspecialchars($student['Dept']) ?> (Current)
                        </option>
                        <option value="CSE">CSE</option>
                        <option value="IT">IT</option>
                        <option value="CSCE">CSCE</option>
                        <option value="CSSE">CSSE</option>
                    </select>
                </div>
                <div>
                    <label>Sem</label>
                    <select name="Sem" required>
                        <option value="<?= htmlspecialchars($student['Sem']) ?>">
                            <?= htmlspecialchars($student['Sem']) ?> (Current)
                        </option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                    </select>
                </div>
                <div class="full">
                    <label>Address</label>
                    <textarea name="Address" required><?= htmlspecialchars($student['Address']) ?></textarea>
                </div>
                <div>
                    <label>Mobile</label>
                    <input type="text" name="Mobile" maxlength="10" value="<?= htmlspecialchars($student['Mobile']) ?>" required>
                </div>
                <div>
                    <label>Email</label>
                    <input type="email" name="Email" value="<?= htmlspecialchars($student['Email']) ?>" required>
                </div>
                <div class="full">
                    <label>CGPA</label>
                    <input type="text" name="CGPA" value="<?= htmlspecialchars($student['CGPA']) ?>" required>
                </div>
            </div>
            <div class="btn-row">
                <button class="btn btn-update" name="update">Update Student</button>
                <a class="btn btn-back" href="display_students.php">Back</a>
            </div>
        </form>
    </div>
    <?php } ?>
</div>
</body>
</html>
