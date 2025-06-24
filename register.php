<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
        $password = trim($_POST['password']);
        $hash = password_hash($password, PASSWORD_DEFAULT);

        // Check if username already exists
        $checkQuery = "SELECT * FROM users WHERE username = ?";
        $checkStmt = mysqli_prepare($conn, $checkQuery);
        mysqli_stmt_bind_param($checkStmt, 's', $username);
        mysqli_stmt_execute($checkStmt);
        mysqli_stmt_store_result($checkStmt);

        if (mysqli_stmt_num_rows($checkStmt) > 0) {
            echo "Username already exists! Please choose a different username.<br>";
        } else {
            $query = "INSERT INTO users (username, password) VALUES (?, ?)";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 'ss', $username, $hash);
            mysqli_stmt_execute($stmt);

            if (mysqli_stmt_affected_rows($stmt) > 0) {

                $_SESSION['user_id'] =  mysqli_insert_id($conn); // Store user ID in session
                $_SESSION['username'] = $username; // Store username in session

                header("Location: index.php");
                exit();
            } else {
                echo "Error registering user: " . mysqli_stmt_error($stmt) . "<br>";
            }
        }
    } else {
        echo "Username and password are required!<br>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Register</title>
    <link rel="stylesheet" href="css/style.css" />
    <style>

    </style>
</head>

<body>
    <div class="container fade" id="registerContainer">
        <h2>Create Account</h2>
        <form action="register.php" method="POST">
            <input type="text" name="username" placeholder="Username" required />
            <input type="password" name="password" placeholder="Password" required />
            <button type="submit">Register</button>
        </form>
        <div class="extra-link">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </div>

    <script>
        window.addEventListener('load', () => {
            document.getElementById('registerContainer').classList.add('show');
        });
    </script>
</body>

</html>