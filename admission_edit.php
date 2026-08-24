<?php
$conn = new mysqli(
    getenv('MYSQLHOST'),
    getenv('MYSQLUSER'),
    getenv('MYSQLPASSWORD'),
    getenv('MYSQLDATABASE'),
    (int) getenv("MYSQLPORT")
);
if ($conn->connect_error) die("DB Error");

$student = null;

/* ✅ Search using Phone OR Email */
if(isset($_POST['search'])){
    $key = $_POST['key'];

    $stmt = $conn->prepare("SELECT * FROM admission_form WHERE phone=? OR email=?");
    $stmt->bind_param("ss", $key, $key);
    $stmt->execute();
    $res = $stmt->get_result();

    if($res->num_rows > 0){
        $student = $res->fetch_assoc();
    }else{
        echo "<p style='color:red;text-align:center;font-size:18px;font-weight:bold;'>❌ Record Not Found!</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit Admission Form</title>
  <style>
    body{
      margin:0;
      font-family: "Segoe UI", Arial, sans-serif;
      background:#f3fff7;
      padding:30px;
    }

    .container{
      width: 65%;
      margin:auto;
      background: white;
      padding: 25px 30px;
      border-radius: 14px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.10);
      border: 1px solid rgba(0,0,0,0.10);
    }

    h2{
      text-align:center;
      color:#0b7a36;
      margin-bottom: 20px;
      font-size:28px;
    }

    label{
      font-weight:700;
      color:#14532d;
      display:block;
      margin-top:10px;
      margin-bottom:6px;
    }

    input, select, textarea{
      width:100%;
      padding:10px;
      border:1px solid #aaa;
      border-radius:8px;
      outline:none;
      font-size:14px;
      background:#f9fffb;
    }

    textarea{
      resize:none;
      height:70px;
    }

    .row{
      display:flex;
      gap:12px;
    }
    .row .col{
      flex:1;
    }

    .radio-group{
      display:flex;
      gap:15px;
      margin-top:5px;
    }

    .btn-group{
      text-align:center;
      margin-top:20px;
    }

    button{
      padding:12px 22px;
      border:none;
      border-radius:10px;
      cursor:pointer;
      font-weight:800;
      font-size:15px;
      color:white;
      margin:0 8px;
    }

    .search-btn{
      background: linear-gradient(135deg, #0b7a36, #16c35a);
    }

    .update-btn{
      background: #0b66ff;
    }

    .back-btn{
      background: #c62828;
      text-decoration:none;
      padding:12px 22px;
      border-radius:10px;
      font-weight:800;
      color:white;
      display:inline-block;
    }

    @media(max-width:900px){
      .container{ width:95%; }
      .row{ flex-direction:column; }
    }
  </style>
</head>

<body>

<div class="container">
  <h2>✏️ Edit Admission Form</h2>

  <!-- ✅ Search Form -->
  <form method="POST">
    <label>Search by Phone or Email</label>
    <input type="text" name="key" placeholder="Enter Phone or Email" required>

    <div class="btn-group">
      <button type="submit" name="search" class="search-btn">SEARCH</button>
    </div>
  </form>

  <hr style="margin:20px 0;">

  <!-- ✅ Update Form -->
  <?php if($student){ ?>
  <form action="admission_update.php" method="POST">

    <input type="hidden" name="id" value="<?php echo $student['id']; ?>">

    <label>Full Name</label>
    <input type="text" name="fullname" value="<?php echo $student['fullname']; ?>" required>

    <div class="row">
      <div class="col">
        <label>Father Name</label>
        <input type="text" name="fathername" value="<?php echo $student['fathername']; ?>" required>
      </div>

      <div class="col">
        <label>Mother Name</label>
        <input type="text" name="mothername" value="<?php echo $student['mothername']; ?>" required>
      </div>
    </div>

    <div class="row">
      <div class="col">
        <label>Date of Birth</label>
        <input type="date" name="dob" value="<?php echo $student['dob']; ?>" required>
      </div>

      <div class="col">
        <label>Gender</label>
        <div class="radio-group">
          <label><input type="radio" name="gender" value="Male" <?php if($student['gender']=="Male") echo "checked"; ?>> Male</label>
          <label><input type="radio" name="gender" value="Female" <?php if($student['gender']=="Female") echo "checked"; ?>> Female</label>
          <label><input type="radio" name="gender" value="Other" <?php if($student['gender']=="Other") echo "checked"; ?>> Other</label>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col">
        <label>Phone</label>
        <input type="text" name="phone" maxlength="10" value="<?php echo $student['phone']; ?>" required>
      </div>

      <div class="col">
        <label>Email</label>
        <input type="email" name="email" value="<?php echo $student['email']; ?>" required>
      </div>
    </div>

    <label>Address</label>
    <textarea name="address" required><?php echo $student['address']; ?></textarea>

    <div class="row">
      <div class="col">
        <label>Program</label>
        <select name="program" required>
          <option value="<?php echo $student['program']; ?>"><?php echo $student['program']; ?> (Current)</option>
          <option value="B.Tech">B.Tech</option>
          <option value="M.Tech">M.Tech</option>
          <option value="BCA">BCA</option>
          <option value="MCA">MCA</option>
          <option value="MBA">MBA</option>
        </select>
      </div>

      <div class="col">
        <label>Branch</label>
        <select name="branch" required>
          <option value="<?php echo $student['branch']; ?>"><?php echo $student['branch']; ?> (Current)</option>
          <option value="CSE">CSE</option>
          <option value="IT">IT</option>
          <option value="CSCE">CSCE</option>
          <option value="CSSE">CSSE</option>
          <option value="ECE">ECE</option>
          <option value="EEE">EEE</option>
          <option value="ME">ME</option>
          <option value="CE">CE</option>
        </select>
      </div>
    </div>

    <div class="btn-group">
      <button type="submit" name="update" class="update-btn">UPDATE</button>
      <a href="admission_form.html" class="back-btn">BACK</a>
    </div>

  </form>
  <?php } ?>

</div>

</body>
</html>
