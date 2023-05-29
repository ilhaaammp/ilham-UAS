<?php
session_start();

// Koneksi ke database
$conn = mysqli_connect('localhost', 'root', '', 'ilham');
if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

// Cek jika users sudah login, maka akan diarahkan ke halaman dashboard
if (isset($_SESSION['username'])) {
    header("location: crud_utama.php");
    exit;
}

// Proses login
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk memeriksa username
    $query = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        // Memeriksa password dengan fungsi password_verify()
        if (password_verify($password, $row['password'])) {
            // Jika login berhasil, simpan username dalam session
            $_SESSION['username'] = $username;
            header("location: crud_utama.php");
            exit;
        } else {
            $error = "Password salah";
        }
    } else {
        $error = "Username tidak ditemukan";
    }
}

// Proses registrasi
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk memeriksa apakah username sudah digunakan
    $query = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $error = "Username sudah digunakan";
    } else {
        // Enkripsi password sebelum disimpan ke database
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Query untuk menyimpan akun baru ke dalam tabel users
        $query = "INSERT INTO users (username, password) VALUES ('$username', '$hashedPassword')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            $success = "Registrasi berhasil. Silakan login.";
        } else {
            $error = "Registrasi gagal";
        }
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>seek coding</title>
    <link rel="stylesheet" href="style.css">
	<script src="https://cdn.tailwindcss.com"></script>
    <!---we had linked our css file----->
</head>
<style>
    *
{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}
.full-page
{
    height: 100%;
	width: 100%;
	background-image: linear-gradient(rgba(0,0,0,0.4),rgba(0,0,0,0.4)),url(img/pexels-eberhard-grossgasteiger-1062249.jpg);
	background-position: center;
	background-size: cover;
	position: absolute;
}
.navbar
{
    display: flex;
    align-items: center;
    padding: 20px;
    padding-left: 50px;
    padding-right: 30px;
    padding-top: 50px;
}
nav
{
    flex: 1;
    text-align: right;
}
nav ul 
{
    display: inline-block;
    list-style: none;
}
nav ul li
{
    display: inline-block;
    margin-right: 70px;
}
nav ul li a
{
    text-decoration: none;
    font-size: 20px;
    color: white;
    font-family: sans-serif;
}
nav ul li button
{
    font-size: 20px;
    color: white;
    outline: none;
    border: none;
    background: transparent;
    cursor: pointer;
    font-family: sans-serif;
}
nav ul li button:hover
{
    color: aqua;
}
nav ul li a:hover
{
    color: aqua;
}
a
{
    text-decoration: none;
    color: palevioletred;
    font-size: 28px;
}
#login-form
{
    display: none;
}
.form-box
{
    width:380px;
	height:480px;
	position:relative;
	margin:2% auto;
	background:rgba(0,0,0,0.3);
	padding:10px;
    overflow: hidden;
}
.button-box
{
	width:220px;
	margin:35px auto;
	position:relative;
	box-shadow: 0 0 20px 9px #ff61241f;
	border-radius: 30px;
}
.toggle-btn
{
	padding:10px 30px;
	cursor:pointer;
	background:transparent;
	border:0;
	outline: none;
	position: relative;
}
#btn
{
	top: 0;
	left:0;
	position: absolute;
	width: 110px;
	height: 100%;
	background: #F3C693;
	border-radius: 30px;
	transition: .5s;
}
.input-group-login
{
	top: 150px;
	position:absolute;
	width:280px;
	transition:.5s;
}
.input-group-register
{
    top: 120px;
	position:absolute;
	width:280px;
	transition:.5s;
}
.input-field
{
	width: 100%;
	padding:10px 0;
	margin:5px 0;
	border-left:0;
	border-top:0;
	border-right:0;
	border-bottom: 1px solid #999;
	outline:none;
	background: transparent;
}
.submit-btn
{
	width: 85%;
	padding: 10px 30px;
	cursor: pointer;
	display: block;
	margin: auto;
	background: #F3C693;
	border: 0;
	outline: none;
	border-radius: 30px;
}
.check-box
{
	margin: 30px 10px 34px 0;
}
span
{
	color:#777;
	font-size:12px;
	bottom:68px;
	position:absolute;
}
#login
{
	left:50px;
}
#login input
{
	color:white;
	font-size:15;
}
#register
{
	left:450px;
}
#register input
{
	color:white;
	font-size: 15;
}
</style>
<body>
    <div class="full-page">
        <div class="navbar">
            <div>
                <a href='website.html' class="text-white">ILHAM PRATAMA Y</a>
            </div>
            <nav>
                <ul id='MenuItems'>
                    <li><a href='#'>Home</a></li>
                    <li><a href='#'>About Us</a></li>
                    <li><a href='#'>Services</a></li>
                    <li><a href='#'>Contact</a></li>
                    <li><button class='loginbtn' onclick="document.getElementById('login-form').style.display='block'" style="width:auto;">Login</button></li>
                </ul>
            </nav>
        </div>
		<div class="w-full h-screen bg-cover flex bg-[url('img/bg1.jpg')] justify-center items-center font-tajawal">
        <div class="w-72 md:w-[500px] h-auto py-10 text-white p-2 rounded-xl text-white font-semibold text-center uppercase">
            <h2 class="text-4xl font-semibold pb-8 md:text-6xl tracking-wider animate-lompat">login</h2>
            <?php if (isset($error)) { ?>
                <p><?php echo $error; ?></p>
            <?php } ?>
            <?php if (isset($success)) { ?>
                <p><?php echo $success; ?></p>
            <?php } ?>
            <form action="" method="post" class="font-semibold">
                <label for="username" class="text-sm md:block md:text-xl">Username :</label>
                <input type="text" id="username" name="username" class="my-2 p-2 rounded-lg font-normal text-black" required>
                <br>
                <label for="password" class="text-sm md:block md:text-xl">Password :</label>
                <input type="password" id="password" name="password" class="my-2 p-2 rounded-lg font-normal text-black" required>
                <br>
                <input type="submit" name="login" value="Login" class="px-3 py-2 bg-red-300 text-sm rounded-md text-black uppercase hover:-translate-y-1 transition duration-300 hover:bg-white hover:text-black md:text-xl">
                <input type="submit" name="register" value="Register" class="mt-4 mr-4 px-3 py-2 bg-red-300 text-sm rounded-md text-black uppercase hover:-translate-y-1 transition duration-300 hover:bg-white hover:text-black md:text-xl">
            </form>
        </div>
    </div>
    </div>
</body>
</html>