<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Fetch events from the database
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'summer';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM calendar"; // Adjust the table and column names as needed
$result = $conn->query($sql);

$todos = array();
$currentDate = date('Y-m-d');
$twoDaysLater = date('Y-m-d', strtotime('+2 days'));

echo "<!-- Current Date: $currentDate -->\n";
echo "<!-- Two Days Later: $twoDaysLater -->\n";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $eventStart = date('Y-m-d', strtotime($row['start'])); // Convert the event start date to Y-m-d format
        echo "<!-- Event Start: $eventStart -->\n";
        if ($eventStart == $twoDaysLater) {
            $todos[] = $row;
        }
    }
} else {
    echo "No events found.";
}
// Database connection parameters
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'summer';

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get today's date and the date exactly 2 days from now
$currentDate = date('Y-m-d');
$twoDaysLater = date('Y-m-d', strtotime('+2 days'));

// SQL query to fetch events exactly 2 days after today
$sql = "SELECT * FROM calendar WHERE start = '$twoDaysLater'"; // Adjust table and column names as needed
$result = $conn->query($sql);

$events = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="todo.css">
    <style>
        .todo-list {
            margin: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
        }
        .todo-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .todo-item:last-child {
            border-bottom: none;
        }
        .todo-completed {
            text-decoration: line-through;
            color: grey;
        }
        .logout{
            margin-top:310px;
        }
        
    </style>
</head>
<body>
    <div class="sidebar">
        <img src="hrt.png" alt="HRT Logo" class="logo">
        <a href="dashboard.php">Dashboard</a>
        <a href="calendar.php">Calendar</a>
        <a href="guides.php" >Guides</a>
        <a href="destination.php">Destination</a>
        <a href="todo.php" class="dash">Todo</a>
        <a href="logout.php" class="logout">Logout</a>
    </div>

    <div class="content">
        <div class="todo-list">
            <h2>To-Do List</h2>
            <?php if (empty($todos)): ?>
                <p>No tasks found.</p>
            <?php else: ?>
                <?php foreach ($todos as $todo): ?>
                    <div class="todo-item <?php echo ($todo['completed'] == 1) ? 'todo-completed' : ''; ?>">
                        <div>
                            <strong>Event Name:</strong> <?php echo htmlspecialchars($todo['destination']); ?><br>
                            <strong>Start Date:</strong> <?php echo htmlspecialchars($todo['start']); ?><br>
                            <strong>End Date:</strong> <?php echo htmlspecialchars($todo['end']); ?><br>
                            <strong>Guide:</strong> <?php echo htmlspecialchars($todo['guide']); ?><br>
                            <strong>Guests:</strong> <?php echo htmlspecialchars($todo['guests']); ?><br>
                            <strong>Porter:</strong> <?php echo htmlspecialchars($todo['porter']); ?>
                        </div>
                        <div>
                            <input type="checkbox" class="todo-complete" data-id="<?php echo $todo['ID']; ?>" <?php echo ($todo['completed'] == 1) ? 'checked' : ''; ?>>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
        window.onload = function() {
                    alert("You have events scheduled for <?php echo $twoDaysLater; ?> please prepare for that.");
                };

        document.querySelectorAll('.todo-complete').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                var todoItem = this.closest('.todo-item');
                var todoId = this.getAttribute('data-id');

                if (this.checked) {
                    todoItem.classList.add('todo-completed');
                } else {
                    todoItem.classList.remove('todo-completed');
                }

                // Update the completion status in the database
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'update_todo_status.php');
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.send('id=' + todoId + '&completed=' + (this.checked ? 1 : 0));
            });
        });
    </script>
</body>
</html>
