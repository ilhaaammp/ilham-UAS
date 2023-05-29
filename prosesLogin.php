<?php 
 session_start();
require_once("koneksi.php");
$db = $kon;

if(isset($_POST['submit'])){
    $idpengguna = strip_tags($_POST['username']);
    $password = strip_tags($_POST['password']);

$query_sql = "SELECT * FROM tbl_users WHERE username = '$username' AND password = '$password'";

$result = mysqli_query($kon, $query_sql);

if(mysqli_num_rows($result) > 0){
    session_start();
    header("location:crud_utama.php");
}else{
    header("location:index.php");
}
}

?>