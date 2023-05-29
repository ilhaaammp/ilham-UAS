<?php
session_start();

// Koneksi ke database
$conn = mysqli_connect('localhost', 'root', '', 'login_system');
if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

// Cek jika users sudah login, maka akan diarahkan ke halaman dashboard
if (isset($_SESSION['username'])) {
    header("location: dashboard.php");
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
            header("location: dashboard.php");
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="dist/output.css">
</head>
<body>
    <div class="w-full h-screen bg-cover flex bg-[url('img/bg1.jpg')] justify-center items-center font-tajawal">
        <div class="w-72 md:w-[500px] h-auto py-10 bg-white/10 backdrop-blur p-2 rounded-xl text-white font-semibold md:text-black text-center uppercase">
            <h2 class="text-4xl font-semibold pb-8 md:text-6xl tracking-wider animate-lompat">halo👋</h2>
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
                <input type="submit" name="register" value="Register" class="mt-4 mr-4 px-3 py-2 bg-sky-300 text-sm rounded-md text-black uppercase hover:-translate-y-1 transition duration-300 hover:bg-sky-950 hover:text-white md:text-xl">
                <input type="submit" name="login" value="Login" class="px-3 py-2 bg-sky-300 text-sm rounded-md text-black uppercase hover:-translate-y-1 transition duration-300 hover:bg-sky-950 hover:text-white md:text-xl">
            </form>
        </div>
    </div>
</body>
</html>
