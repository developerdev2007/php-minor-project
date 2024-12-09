<?php
session_start();
require_once 'db.php';
if (!isset($_SESSION['tasks'])) $_SESSION['tasks'] = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $id = $_POST['id'] ?? null;
    $task = $_POST['task'] ?? '';

    if ($action === 'create' && $task) {
        $stmt = $conn->prepare("INSERT INTO tasks (description) VALUES (:desc)");
        $stmt->execute([':desc' => $task]);
    } elseif ($action === 'update' && $id && $task) {
        $stmt = $conn->prepare("UPDATE tasks SET description = :desc WHERE id = :id");
        $stmt->execute([':desc' => $task, ':id' => $id]);
    } elseif ($action === 'delete' && $id) {
        $stmt = $conn->prepare("DELETE FROM tasks WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }
}

$tasks = $conn->query("SELECT * FROM tasks")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Task Manager</title>
    <link rel="stylesheet" href="styles.css" />
</head>

<body>
    <h1>Task Manager</h1>
    <form method="POST">
        <input type="hidden" name="action" value="create">
        <input type="text" name="task" placeholder="Enter a task" required>
        <button type="submit">Add Task</button>
    </form>
    <table>
        <tr>
            <th>ID</th>
            <th>Task</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($tasks as $task): ?>
            <tr>
                <td><?= $task['id'] ?></td>
                <td><?= htmlspecialchars($task['description']) ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id" value="<?= $task['id'] ?>">
                        <input type="text" name="task" placeholder="New task name">
                        <button type="submit">Update</button>
                    </form>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= $task['id'] ?>">
                        <button type="submit" style="background-color:red;color:white;">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>