<?php
include 'db.php';
session_start();


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


function redirectHome()
{
    header("Location: index.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_task'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);

    if ($title && $description) {
        $query = "INSERT INTO tasks (title, description, user_id) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'ssi', $title, $description, $user_id);
        mysqli_stmt_execute($stmt);
        redirectHome();
    } else {
        $error = "Title and description are required!";
    }
}


if (isset($_GET['delete_id'])) {
    $delete_id = (int) $_GET['delete_id'];
    $query = "DELETE FROM tasks WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ii', $delete_id, $user_id);
    mysqli_stmt_execute($stmt);
    redirectHome();
}

//  Mark Done
if (isset($_GET['done_id'])) {
    $done_id = (int) $_GET['done_id'];
    $query = "UPDATE tasks SET status = 'Done' WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ii', $done_id, $user_id);
    mysqli_stmt_execute($stmt);
    redirectHome();
}

//  Edit Task
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_task'])) {
    $edit_id = (int) $_POST['task_id'];
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);

    if ($title && $description) {
        $query = "UPDATE tasks SET title = ?, description = ? WHERE id = ? AND user_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'ssii', $title, $description, $edit_id, $user_id);
        mysqli_stmt_execute($stmt);
        redirectHome();
    } else {
        $error = "Title and description are required!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Commitra - To-Do App</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <nav>
        <a class="logo" href="index.php">Commitra</a>
        <div class="nav-links">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="register.php">Register</a>
                <a href="login.php">Login</a>
            <?php endif; ?>
            <button id="toggle-theme">🌙</button>
        </div>
    </nav>

    <div class="container" id="todoContainer">
        <h1>Your Tasks</h1>

        <?php if (!empty($error)): ?>
            <div class="error-msg"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM tasks WHERE user_id = ? ORDER BY created_at DESC";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, 'i', $user_id);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                    echo "<td>" . nl2br(htmlspecialchars($row['description'])) . "</td>";
                    echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                    echo "<td><div class='btn-group'>";
                    if ($row['status'] !== 'Done') {
                        echo "<a class='action-btn done-btn' href='index.php?done_id={$row['id']}'>Done</a> ";
                        echo "<button class='action-btn edit-btn' onclick='showEditForm(" . json_encode($row) . ")'>Edit</button> ";
                    }
                    echo "<a class='action-btn delete-btn' href='index.php?delete_id={$row['id']}' onclick='return confirm(\"Are you sure?\");'>Delete</a>";
                    echo "</div></td>";
                    echo "</tr>";
                }

                if (mysqli_num_rows($result) === 0) {
                    echo "<tr><td colspan='4' style='text-align:center; color:#ccc;'>No tasks found</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <h3>Add Task</h3>
        <form method="POST">
            <input type="text" name="title" placeholder="Task title" required>
            <textarea name="description" placeholder="Task description" required></textarea>
            <button type="submit" name="add_task">Add Task</button>
        </form>

        <div id="editSection" style="display:none; margin-top:20px;">
            <h3>Edit Task</h3>
            <form method="POST">
                <input type="hidden" name="task_id" id="edit_id">
                <input type="text" name="title" id="edit_title" required>
                <textarea name="description" id="edit_description" required></textarea>
                <button type="submit" name="edit_task">Update Task</button>
            </form>
        </div>
    </div>

    <script src="js/script.js"></script>

</body>

</html>