<!DOCTYPE html>
<html>
<head>
<title>Student Information</title>
<style>
body{
    font-family: Arial;
    background:#f2f4f7;
}

.container{
    width:420px;
    margin:40px auto;
    background:white;
    padding:25px;
    border-radius:6px;
    box-shadow:0 0 12px rgba(0,0,0,0.2);
}

h2{text-align:center;}

.form-group{margin-bottom:12px;}

label{font-weight:bold;}

input,select,textarea{
    width:100%;
    padding:7px;
}

textarea{height:60px;}

.dept-container{
    display:flex;
    justify-content:space-between;
}

.btn-group{text-align:center;}

button{
    padding:8px 18px;
    border:none;
    color:white;
}

.save-btn{background:green;}
.cancel-btn{background:red;}
</style>
</head>
<body>
<div class="container">
<h2>Student Information</h2>
<form action="save_student.php" method="POST">

<!-- Roll -->
<div class="form-group">
<label>Roll No</label>
<input type="text" name="rollno" required>
</div>

<!-- Name -->
<div class="form-group">
<label>Name</label>
<input type="text" name="name" required>
</div>

<!-- Dept -->
<div class="form-group">
<label>Dept</label>
<div class="dept-container">
<label>
<input type="radio" name="dept" value="CSE" required> CSE
</label>
<label>
<input type="radio" name="dept" value="IT"> IT
</label>
<label>
<input type="radio" name="dept" value="CSCE"> CSCE
</label>
<label>
<input type="radio" name="dept" value="CSSE"> CSSE
</label>
</div>
</div>

<!-- Sem -->
<div class="form-group">
<label>Sem</label>
<select name="sem" required>
<option value="">Select</option>
<option>1</option>
<option>2</option>
<option>3</option>
<option>4</option>
<option>5</option>
<option>6</option>
<option>7</option>
<option>8</option>
</select>
</div>

<!-- Address -->
<div class="form-group">
<label>Address</label>
<textarea name="address" required></textarea>
</div>

<!-- Mobile -->
<div class="form-group">
<label>Mobile</label>
<input type="text" name="mobile" required>
</div>

<!-- Email -->
<div class="form-group">
<label>Email</label>
<input type="email" name="email" required>
</div>

<!-- CGPA -->
<div class="form-group">
<label>CGPA</label>
<input type="text" name="cgpa" required>
</div>
<!-- Buttons -->
<div class="btn-group">
<button type="submit" class="save-btn">
SAVE
</button>
<button type="reset" class="cancel-btn">
CANCEL
</button>
</div>
</form>
</div>
</body>
</html>
