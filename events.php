<?php
if(isset($_POST['submit'])){
    $destination=$_POST["destination"];
    $guests=$_POST["guests"];
    $guide=$_POST["guide"];
    $porter=$_POST["porter"];
    $start=$_POST["start"];
    $end=$_POST["end"];

    $host='localhost';
    $user='root';
    $pass='';
    $dbname='summer';

    $conn=mysqli_connect($host,$user,$pass,$dbname);
    $sql="INSERT INTO calendar(destination,guests,guide,porter,start,end)VALUES('$destination','$guests','$guide','$porter','$start','$end')";
    mysqli_query($conn,$sql);
    echo('Event added successfully!');
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Hard Rock Treks & Expedition</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css"/>
    <link rel="stylesheet" href="events.css">
    <!-- Add your CSS and JavaScript links here -->
    <style>
    
    </style>
</head>
<body>
    <!-- Your HTML content here -->
    <div class="sidebar">
    <img src="hrt.png" alt="HRT Logo" class="logo">
        <a href="dashboard.php">Dashboard</a>
        <a href="calendar.php">Calendar</a>
        <a href="guides.php">Guides</a>
      
        
    </div>

    <!-- Main content -->
    <div class="content">
        <button class="print" id="print">Print</button>
        <h2 class="hello">Total Events</h2>
        <div id="calendar"></div>
        <div class="container">
            <table class="tables" id="table">
                <thead>
                    <tr>
                        <th>S.N</th>
                        <th>Destination</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Total Guests</th>
                        <th>Guide Name</th>
                        <th>Total Guides</th>
                        <th>Porter</th>
                    </tr>
                </thead>
                <tbody>
                   <?php
                   $hostname= "localhost";
                   $dbUser="root";
                   $dbPass="";
                   $dbName="summer";
                   $conn=mysqli_connect($hostname,$dbUser,$dbPass,$dbName);
                   if(!$conn){
                       die("Connection unsuccessful");
                   }
                   $n=1;
                   $sql="SELECT * FROM calendar ORDER BY start ASC";
                   $result=mysqli_query($conn,$sql);
                   while($row= mysqli_fetch_array($result)){
                       $ID=$n++;
                       $destination=$row["destination"];
                       $guests=$row["guests"];
                       $guide_name=$row["guide_name"];
                       $guide=$row["guide"];
                       $porter=$row["porter"];
                       $start=$row["start"];
                       $end=$row["end"];
                       echo "<tr>
                       <td>$ID</td>
                       <td>$destination</td>
                       <td>$start</td>
                       <td>$end</td>
                       <td>$guests</td>
                       <td>$guide_name</td>
                       <td>$guide</td>
                       <td>$porter</td>
                       </tr>";
                   }
                   ?>
                </tbody>
            </table>
        </div>
    </div>

    

    <script>
        function printTable() {
            var tableContent = document.getElementById('table').outerHTML;
            var printWindow = window.open('', '', 'height=500, width=800');
            printWindow.document.write('<html><head><title>Total Events</title>');
            printWindow.document.write('<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>');
            printWindow.document.write('<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css"/>');
            printWindow.document.write('<style>');
            printWindow.document.write('.sidebar .events { background-color: grey; border-radius: 20px; }');
            printWindow.document.write('table.tables { width: 100%; border-collapse: collapse; margin: 20px 0; }');
            printWindow.document.write('table.tables th, table.tables td { border: 1px solid #ddd; padding: 8px; }');
            printWindow.document.write('table.tables th { background-color: #f2f2f2; text-align: left; }');
            printWindow.document.write('</style>');
            printWindow.document.write('</head><body>');
            printWindow.document.write(tableContent);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }

        document.getElementById('print').addEventListener('click', printTable);

        function openModal() {
            document.getElementById("myModal").style.display = "block";
        }

        function closeModal() {
            document.getElementById("myModal").style.display = "none";
        }
    </script>
</body>
</html>
