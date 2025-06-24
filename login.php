<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
        $password = trim($_POST['password']);

        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);

            if (password_verify($password, $user['password'])) {
                // Password correct → start session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header("Location: index.php");
                exit();
            } else {
                // Password incorrect
                echo "Invalid password!<br>";
            }
        } else {
            // Username not found
            echo "Username not found!<br>";
        }
    } else {
        echo "Both username and password are required!<br>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css" />

</head>

<body>
    <div class="container fade" id="loginContainer">
        <h2>Login to Your Account</h2>
        <form action="login.php" method="POST">
            <input type="text" name="username" placeholder="Username" required />
            <input type="password" name="password" placeholder="Password" required />
            <button type="submit">Login</button>
        </form>
        <div class="extra-link">
            Don't have an account? <a href="register.php">Create one</a>
        </div>
    </div>

    <script>
        window.addEventListener('load', () => {
            document.getElementById('loginContainer').classList.add('show');
        });
    </script>
</body>

</html>