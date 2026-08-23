<?php
include("admin_auth.php"); // ✅ Admin protection
?>
<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard | KIIT</title>

<style>
body{
    margin:0;
    font-family:"Segoe UI", Arial, sans-serif;
    background:#f3fff7;
}

.container{
    width: 55%;
    margin: 80px auto;
    background: white;
    padding: 35px;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.12);
    border: 1px solid rgba(0,0,0,0.08);
    text-align:center;
}

h1{
    color:#0b7a36;
    font-size:30px;
    font-weight:900;
    margin-bottom:12px;
}

p{
    color:#14532d;
    font-size:16px;
    font-weight:700;
    margin-bottom:25px;
}

.btn{
    display:block;
    width: 90%;
    margin: 12px auto;
    padding: 14px;
    border-radius: 12px;
    font-weight:900;
    font-size:18px;
    text-decoration:none;
    color:white;
    transition:0.2s;
}

.btn:hover{
    transform: translateY(-2px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.15);
}

.display{
    background: linear-gradient(135deg,#0b7a36,#16c35a);
}

.delete{
    background: linear-gradient(135deg,#c62828,#ff3b3b);
}

.logout{
    background: linear-gradient(135deg,#003a8c,#0b66ff);
}

.admin-info{
    margin-top:15px;
    font-size:16px;
    font-weight:900;
    color:#0b7a36;
}

@media(max-width:900px){
    .container{
        width: 90%;
        margin: 50px auto;
    }
}
</style>

</head>
<body>

<div class="container">
    <h1>Admin Dashboard</h1>
    <p>Welcome Admin! Manage Admission Records Easily</p>

    <a class="btn display" href="admission_display.php">✅ Admission Display</a>

    <!-- ✅ Delete page direct open (will show list + delete button if you make it) -->
    <a class="btn delete" href="admission_delete_list.php">🗑️ Delete Admission</a>

    <a class="btn logout" href="admin_logout.php">🚪 Logout</a>

    <div class="admin-info">
        ✅ Logged in as: <?php echo $_SESSION['admin']; ?>
    </div>
</div>

</body>
</html>
