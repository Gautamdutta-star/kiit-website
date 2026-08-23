<?php

$correct_username = "Gautam";
$correct_password = "kiit@123";

$username = $_POST['username'];
$password = $_POST['password'];

// Check login
if ($username === $correct_username && $password === $correct_password) {
    header("Location: student.html");
    exit();
} 
else {
    echo "<script>
            alert('Invalid Username or Password');
            window.location.href = 'index.html';
          </script>";
}
?>