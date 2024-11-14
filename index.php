<?php
// Koneksi Database
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'todo_db';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Tambah Kegiatan
if (isset($_POST['add'])) {
    $task = $_POST['task'];
    $sql = "INSERT INTO tasks (task) VALUES ('$task')";
    $conn->query($sql);
    header("Location: index.php");
}

// Hapus Kegiatan
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM tasks WHERE id=$id";
    $conn->query($sql);
    header("Location: index.php");
}

// Update Kegiatan
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $task = $_POST['task'];
    $sql = "UPDATE tasks SET task='$task' WHERE id=$id";
    $conn->query($sql);
    header("Location: index.php");
}

// Ambil Data
$sql = "SELECT * FROM tasks";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <style>
        /* CSS untuk tampilan */
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }

        .container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        form input[type="text"] {
            width: calc(100% - 50px);
            padding: 10px;
            margin-right: 5px;
        }

        form button {
            padding: 10px;
        }

        .task-list {
            list-style: none;
            padding: 0;
        }

        .task-list li {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }

        .task-list li a {
            color: #ff6347;
            text-decoration: none;
            margin-left: 10px;
        }

        .task-list li a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>To-Do List</h1>

        <!-- Form Tambah Kegiatan -->
        <form action="index.php" method="POST">
            <input type="text" name="task" placeholder="Tambah kegiatan baru..." required>
            <button type="submit" name="add">Tambah</button>
        </form>

        <!-- Daftar Kegiatan -->
        <ul class="task-list">
            <?php while ($row = $result->fetch_assoc()): ?>
                <li>
                    <?php echo $row['task']; ?>
                    <a href="index.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus?')">Hapus</a>
                    <a href="index.php?edit=<?php echo $row['id']; ?>">Edit</a>
                </li>
            <?php endwhile; ?>
        </ul>

        <!-- Form Edit Kegiatan (Hanya Tampil Jika Dalam Mode Edit) -->
        <?php if (isset($_GET['edit'])):
            $id = $_GET['edit'];
            $sql = "SELECT * FROM tasks WHERE id=$id";
            $result = $conn->query($sql);
            $task = $result->fetch_assoc();
        ?>
            <form action="index.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $task['id']; ?>">
                <input type="text" name="task" value="<?php echo $task['task']; ?>" required>
                <button type="submit" name="update">Update</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
