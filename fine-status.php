<?php
session_start();
include('connection.php');  // Make sure this is the correct path to your connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to view your fine status.";
    exit();
}

// Fetch the user ID from the session
$user_id = $_SESSION['user_id'];

// Query to get fine details for the logged-in user
$query = "SELECT * FROM tbl_fine WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Display the fine details in a table
echo "<h2>Fine Status</h2>";
echo "<table>";
echo "<tr><th>Book Title</th><th>ISBN</th><th>Fine Amount</th><th>Due Date</th><th>Status</th><th>Action</th></tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row['book_title'] . "</td>";
    echo "<td>" . $row['isbn'] . "</td>";
    echo "<td>" . $row['fine_amount'] . "</td>";
    echo "<td>" . $row['return_date'] . "</td>";
    
    // Display fine status and pay button if the fine is unpaid
    if ($row['fine_amount'] > 0 && $row['fine_status'] !== 'paid') {
        echo "<td>Unpaid</td>";
        echo "<td><a href='pay-fine.php?fine_id=" . $row['id'] . "'>Pay Fine</a></td>";
    } else {
        echo "<td>Paid</td>";
        echo "<td>-</td>";
    }
    
    echo "</tr>";
}
echo "</table>";
?>