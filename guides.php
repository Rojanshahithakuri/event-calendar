<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Fetch guides from the users_guide table
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'summer';

$conn = mysqli_connect($host, $user, $pass, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT * FROM users_guide";
$result = mysqli_query($conn, $sql);

$user_guides = array();
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $user_guides[] = $row;
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Hard Rock Treks & Expedition</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css"/>
    <link rel="stylesheet" href="guides.css">
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            padding-top: 100px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
            border-radius: 10px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        .guide-form {
            display: flex;
            justify-content: space-between;
        }
        form {
            width: 60%;
        }
        .photo-upload {
            width: 150px;
            height: 150px;
            border: 2px dashed #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            margin-bottom: 10px;
            position: relative;
        }
        .photo-upload img {
            max-width: 100%;
            max-height: 100%;
        }
        #previewImage {
            position: absolute;
            width: 100%;
            height: 100%;
            object-fit: cover;
            top: 0;
            left: 0;
            display: none;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .guide-photo-preview {
            margin-left: 20px;
            width: 150px;
            height: 150px;
            border: 1px solid #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            background-color: #f0f0f0;
            position: relative;
        }

        .guide-photo-preview img {
            max-width: 100%;
            max-height: 100%;
        }

        .guide-photo-preview::after {
            content: "+";
            font-size: 2em;
            color: #aaa;
            position: absolute;
        }
        table.tables img {
            cursor: pointer;
            max-width: 50px;
            max-height: 50px;
        }
        .logout {
            margin-top: 310px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <img src="hrt.png" alt="HRT Logo" class="logo">
        <a href="dashboard.php" >Dashboard</a>
        <a href="calendar.php" >Calendar</a>
        <a href="guides.php" class="dash">Guides</a>
        <a href="destination.php" >Destination</a>
        <a href="todo.php" >Todo</a>
        <a href="logout.php" class="logout">Logout</a>
    </div>

    <div class="content">
        <button class="guide_add_btn">Add Guide</button>
        <table>
            <thead>
                <tr>
                    <th>S.N</th>
                    <th>Guide Name</th>
                    <th>License No</th>
                    <th>Phone No</th>
                    <th>Email</th>
                    <th>Photo</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $counter = 1;
                foreach ($user_guides as $guide) {
                    echo "<tr>";
                    echo "<td>{$counter}</td>";
                    echo "<td>{$guide['name']}</td>";
                    echo "<td>{$guide['license_no']}</td>";
                    echo "<td>{$guide['phone_no']}</td>";
                    echo "<td>{$guide['email']}</td>";
                    echo "<td><img src='{$guide['photo']}' alt='Guide Photo' style='width:150px;height:150px;'></td>";
                    echo "<td>
                            <button class='btn btn-warning' onclick='openUpdateModal(" . $guide['id'] . ")'>Update</button>
                            <button class='btn btn-danger' onclick='deleteGuide(" . $guide['id'] . ")'>Delete</button>
                          </td>";
                    echo "</tr>";
                    $counter++;
                }
                ?>
            </tbody>
        </table>
    </div>

    <div id="guideModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Add Guide</h2>
            <div class="guide-form">
                <form action="add_guide.php" method="post" enctype="multipart/form-data">
                    <label for="guideName">Guide Name:</label><br>
                    <input type="text" id="guideName" name="guideName" required><br>
                    <label for="licenseNo">License No:</label><br>
                    <input type="text" id="licenseNo" name="licenseNo" required pattern="\d+"><br>
                    <label for="phoneNo">Phone No:</label><br>
                    <input type="text" id="phoneNo" name="phoneNo" required pattern="\d{10}" title="Please enter exactly 10 digits"><br><br>
                    <label for="guidePhoto">Guide Photo:</label><br>
                    <div class="photo-upload" onclick="document.getElementById('guidePhoto').click();">
                        <img id="uploadIcon" src="plus_icon.jpg" alt="Add Photo">
                        <img id="previewImage" src="#" alt="Guide Photo" style="display:none;">
                    </div>
                    <input type="file" id="guidePhoto" name="guidePhoto" accept="image/*" style="display:none;"><br><br>
                    <input type="submit" value="Submit">
                </form>
                
            </div>
        </div>
        <!-- Modal for updating guide -->
    <!-- Modal for updating guide -->
<div id="updateModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeUpdateModal()">&times;</span>
        <h3>Update Guide</h3>
        <form id="updateGuideForm" action="update_guide.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" id="update_guide_id" name="guide_id">
            <label for="update_guide_name">Guide Name:</label><br>
            <input type="text" id="update_guide_name" name="guide_name" required><br>
            <label for="update_license_no">License No:</label><br>
            <input type="text" id="update_license_no" name="license_no" required><br>
            <label for="update_phone_no">Phone No:</label><br>
            <input type="text" id="update_phone_no" name="phone_no" required><br>
            <label for="update_guide_photo">Guide Photo:</label><br>
            <input type="file" id="update_guide_photo" name="guide_photo" accept="image/*"><br><br>
            <input type="submit" value="Update Guide">
        </form>
    </div>
</div>

    </div>
    <div id="imageModal" class="modal">
        <span class="close" onclick="closeImageModal()">&times;</span>
        <img class="modal-content" id="enlargedImage">
    </div>
    <script>
        document.getElementById('guidePhoto').addEventListener('change', function() {
            var reader = new FileReader();
            reader.onload = function(e) {
                var preview = document.getElementById('previewImage');
                var uploadIcon = document.getElementById('uploadIcon');
                preview.src = e.target.result;
                preview.style.display = 'block';
                uploadIcon.style.display = 'none';
            };
            reader.readAsDataURL(this.files[0]);
        });
        document.getElementById('update_guide_photo').addEventListener('change', function() {
    var reader = new FileReader();
    reader.onload = function(e) {
        var previewImage = document.getElementById('update_guide_photo_preview');
        previewImage.src = e.target.result;
    };
    reader.readAsDataURL(this.files[0]);
});
        // Open modal
        document.querySelector('.guide_add_btn').addEventListener('click', function() {
            document.getElementById('guideModal').style.display = 'block';
        });

        // Close modal
        document.querySelector('.close').addEventListener('click', function() {
            document.getElementById('guideModal').style.display = 'none';
        });
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('photoPreview');
                output.style.backgroundImage = 'none';
                output.innerHTML = '<img src="' + reader.result + '">';
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        function enlargeImage() {
            var modal = document.getElementById("imageModal");
            var img = document.getElementById("photoPreview").querySelector('img');
            var modalImg = document.getElementById("enlargedImage");
            modal.style.display = "block";
            modalImg.src = img.src;
        }

        function closeImageModal() {
            var modal = document.getElementById("imageModal");
            modal.style.display = "none";
        }

        var span = document.getElementsByClassName("close")[0];
        span.onclick = function() {
            var modal = document.getElementById("guideModal");
            modal.style.display = "none";
        }
        function validateForm() {
            var phoneNo = document.getElementById('phoneNo').value;
            if (phoneNo.length !== 10) {
                alert('Phone number must be exactly 10 digits.');
                return false;
            }
        }

        // Open update modal
function openUpdateModal(guide_id) {
    var modal = document.getElementById('updateModal');
    modal.style.display = 'block';

    // Fetch guide details from the table row
    var guideRow = document.querySelector('button[onclick="openUpdateModal(' + guide_id + ')"]').parentNode.parentNode;
    var guideName = guideRow.cells[1].innerText;
    var licenseNo = guideRow.cells[2].innerText;
    var phoneNo = guideRow.cells[3].innerText;

    // Populate the update form with guide details
    document.getElementById('update_guide_id').value = guide_id;
    document.getElementById('update_guide_name').value = guideName;
    document.getElementById('update_license_no').value = licenseNo;
    document.getElementById('update_phone_no').value = phoneNo;
}

// Close update modal
function closeUpdateModal() {
    document.getElementById('updateModal').style.display = 'none';
}
// Event listener for update button click
document.querySelectorAll('.update_btn').forEach(function(button) {
    button.addEventListener('click', function() {
        var guide_id = this.getAttribute('data-guide-id');
        openUpdateModal(guide_id);
    });
});

        // Delete guide
        function deleteGuide(guide_id) {
            if (confirm('Are you sure you want to delete this guide?')) {
                window.location.href = 'delete_guide.php?id=' + guide_id;
            }
        }
    </script>
</body>
</html>
