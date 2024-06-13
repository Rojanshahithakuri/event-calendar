<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $license_no = $_POST['license_no'];
    $phone_no = $_POST['phone_no'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = 'user'; // Default role

    // Handle file upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["photo"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["photo"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $uploadOk = 0;
        $error = "File is not an image.";
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        $uploadOk = 0;
        $error = "Sorry, file already exists.";
    }

    // Check file size
    if ($_FILES["photo"]["size"] > 500000) {
        $uploadOk = 0;
        $error = "Sorry, your file is too large.";
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        $uploadOk = 0;
        $error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        // Do not proceed with the rest of the code if file upload failed
    } else {
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            // File uploaded successfully, now insert user data into database
            $host = 'localhost';
            $user = 'root';
            $pass = '';
            $dbname = 'summer';

            $conn = mysqli_connect($host, $user, $pass, $dbname);
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
                mysqli_begin_transaction($conn); 
                try {
                    // Insert into users_guide table
                    $sql_guide = "INSERT INTO users_guide (name, license_no, phone_no, email, photo, username, password, role)
                          VALUES ('$name', '$license_no', '$phone_no', '$email', '$target_file', '$username', PASSWORD('$password'), '$role')";
                if (!mysqli_query($conn, $sql_guide)) {
                    throw new Exception("Error: " . $sql_guide . "<br>" . mysqli_error($conn));
                }

                // Insert into users table
                $sql_user = "INSERT INTO users (username, password, role)
                             VALUES ('$username', PASSWORD('$password'), '$role')";
                if (!mysqli_query($conn, $sql_user)) {
                    throw new Exception("Error: " . $sql_user . "<br>" . mysqli_error($conn));
                }

                // Commit transaction
                mysqli_commit($conn);

                $_SESSION['username'] = $username;
                $_SESSION['role'] = $role;
                header("Location: user_dashboard.php");
                exit();
            } catch (Exception $e) {
                // Rollback transaction in case of error
                mysqli_rollback($conn);
                $error = $e->getMessage();
            }

            mysqli_close($conn);
        } else {
            $error = "Sorry, there was an error uploading your file.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sign Up</title>
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
        .signup-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 400px;
        }
        .signup-container h2 {
            margin-bottom: 20px;
        }
        .error {
            color: red;
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
    <div class="signup-container">
        <h2>Sign Up</h2>
        <?php if (isset($error)) { ?>
            <p class="error"><?php echo $error; ?></p>
        <?php } ?>
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="license_no">License No:</label>
                <input type="text" name="license_no" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="phone_no">Phone No:</label>
                <input type="text" name="phone_no" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="photo">Photo:</label>
                <input type="file" name="photo" class="form-control" accept="image/*" required>
            </div>
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Sign Up</button>
        </form>
        <button class="data-button" onclick="window.location.href='login.php'">Login</button>
    </div>
</body>
</html>
