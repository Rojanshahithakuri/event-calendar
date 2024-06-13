<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $dbname = 'summer';

    $conn = mysqli_connect($host, $user, $pass, $dbname);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $sql = "SELECT * FROM users WHERE username='$username' AND password=PASSWORD('$password')";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        // Store user details in session
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $user['role'];

        // Fetch detailed info if the user is a guide
        if ($user['role'] == 'user') {
            $sql_guide = "SELECT * FROM users_guide WHERE username='$username'";
            $result_guide = mysqli_query($conn, $sql_guide);
            if (mysqli_num_rows($result_guide) == 1) {
                $guide = mysqli_fetch_assoc($result_guide);
                $_SESSION['name'] = $guide['name'];
                $_SESSION['license_no'] = $guide['license_no'];
                $_SESSION['phone_no'] = $guide['phone_no'];
                $_SESSION['email'] = $guide['email'];
                $_SESSION['photo'] = $guide['photo'];
            }
        }

        // Redirect to appropriate dashboard
        if ($user['role'] == 'admin') {
            header("Location: dashboard.php");
        } else {
            header("Location: user_dashboard.php");
        }
        exit();
    } else {
        $error = "Invalid username or password";
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f2f2f2;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .login-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .login-container h2 {
            margin-bottom: 20px;
        }
        .error {
            color: red;
        }
        .signup-button {
            margin-top: 10px;
            background-color: #5cb85c;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .signup-button:hover {
            background-color: #4cae4c;
        }
        .data-button {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #337ab7;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .data-button:hover {
            background-color: #286090;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if (isset($error)) { ?>
            <p class="error"><?php echo $error; ?></p>
        <?php } ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        <button class="signup-button" onclick="window.location.href='signup.php'">Sign Up</button>
    </div>
</body>
</html>
