<?php 
 session_start();
include 'koneksi.php';
$kon;

if (isset($_POST['input'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query  =$kon-> prepare("INSERT INTO users VALUES ('$username', '$password')");
    

    if ($query) {
        $result = $query->execute();
        header("location:index.php");
        return $result;
        
    }else {
        die("Oops! Terjadi kesalahan");
    }  
}
?>