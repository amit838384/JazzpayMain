<?php
// Enable error reporting for debugging

// Database credentials
$host = 'localhost';
$dbname = 'tsmudgmy_shop';
$username = 'tsmudgmy_shop';
$password = 'Noew$(!9JJEL';

$conn = mysqli_connect($host, $username, $password, $dbname);

if ($conn) {
    // echo "Connected successfully<br>";
} else {
    die("Connection failed: " . mysqli_connect_error());
}


   $sql = "SELECT * FROM tbl_statuschnage WHERE id = 1";  // Query to fetch the status

$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    // Fetch the row from the result
    $row = mysqli_fetch_assoc($result);


    $response = [
        'success' => true,
        'message' => 'Status retrieved successfully',
        'status' => $row['status'],  // Fetch the 'status' field
    ];
} else {
    $response = [
        'success' => false,
        'message' => 'Error retrieving status: ' . mysqli_error($conn)
    ];
}



// Close the connection
mysqli_close($conn);

// Send the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
