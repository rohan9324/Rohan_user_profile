<?php
session_start();
include 'db.php';

// Redirect if not logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

// Initialize edit mode
$edit_mode = false;
$edit_id = null;
$edit_title = "";
$edit_description = "";
$edit_due_date = "";

// ADD NEW TASK
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_task'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];

    $stmt = $conn->prepare("INSERT INTO task (title, description, due_date) VALUES (?,?,?)");
    if (!$stmt) {
        die("SQL Error (Insert Task): " . $conn->error);
    }
    $stmt->bind_param("sss", $title, $description, $due_date);
    $stmt->execute();

    echo "<script>alert('Task added successfully'); window.location='task.php';</script>";
}

// LOAD TASK FOR EDIT
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $task = $conn->query("SELECT * FROM task WHERE id=$edit_id")->fetch_assoc();
    if ($task) {
        $edit_mode = true;
        $edit_title = $task['title'];
        $edit_description = $task['description'];
        $edit_due_date = $task['due_date'];
    } else {
        echo "<script>alert('Task not found'); window.location='task.php';</script>";
    }
}

// UPDATE TASK
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_task'])) {
    $id = $_POST['task_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];

    $stmt = $conn->prepare("UPDATE task SET title=?, description=?, due_date=? WHERE id=?");
    if (!$stmt) {
        die("SQL Error (Update Task): " . $conn->error);
    }
    $stmt->bind_param("sssi", $title, $description, $due_date, $id);
    $stmt->execute();

    echo "<script>alert('Task updated successfully'); window.location='task.php';</script>";
}

// TOGGLE COMPLETE/PENDING
if (isset($_GET['toggle'])) {
    $id = $_GET['toggle'];
    $task = $conn->query("SELECT status FROM task WHERE id=$id")->fetch_assoc();

    $newStatus = ($task['status'] === 'pending') ? 'complete' : 'pending';
    $conn->query("UPDATE task SET status='$newStatus' WHERE id=$id");

    echo "<script>alert('Task status changed'); window.location='task.php';</script>";
}

// DELETE TASK
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM task WHERE id=$id");
    echo "<script>alert('Task deleted'); window.location='task.php';</script>";
}

// FETCH TASKS
$tasks = $conn->query("SELECT * FROM task ORDER BY due_date ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tasks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">
<div class="card shadow-sm p-3" style="width: 800px;">
    <h4 class="text-center mb-3"><?= $edit_mode ? 'Edit Task' : 'Add Task' ?></h4>

    <!-- Add/Edit Task Form -->
    <form method="POST" class="mb-3">
        <input type="hidden" name="task_id" value="<?= $edit_id ?>">
        <input type="text" name="title" class="form-control mb-2" placeholder="Task Title"
               value="<?= htmlspecialchars($edit_title) ?>" required>
        <textarea name="description" class="form-control mb-2" placeholder="Description"><?= htmlspecialchars($edit_description) ?></textarea>
        <input type="date" name="due_date" class="form-control mb-3" value="<?= $edit_due_date ?>" required>

        <?php if ($edit_mode): ?>
            <button type="submit" name="update_task" class="btn btn-primary w-100">Update Task</button>
            <a href="task.php" class="btn btn-secondary w-100 mt-2" onclick="return confirmCancelEdit()">Cancel</a>
        <?php else: ?>
            <button type="submit" name="add_task" class="btn btn-success w-100">Add Task</button>
        <?php endif; ?>
    </form>

    <!-- Task List -->
    <h5 class="text-center mb-3">Task List</h5>
    <table class="table table-bordered table-sm text-center">
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $tasks->fetch_assoc()) { ?>
            <tr>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['description']) ?></td>
                <td><?= htmlspecialchars($row['due_date']) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td>
                    <!-- Toggle Button -->
                    <a href="?toggle=<?= $row['id'] ?>" 
                       class="btn btn-sm <?= $row['status'] === 'pending' ? 'btn-success' : 'btn-warning' ?>"
                       onclick="return confirmToggle()">
                       <?= $row['status'] === 'pending' ? 'Complete' : 'Pending' ?>
                    </a>

                    <!-- Edit Button -->
                    <a href="?edit=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Edit</a>

                    <!-- Delete Button -->
                    <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger"
                       onclick="return confirmDelete()">Delete</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <a href="index.php" class="btn btn-secondary w-100 mt-2">Back to Dashboard</a>
</div>

<script src="js/script.js"></script>
</body>
</html>