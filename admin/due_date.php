<?php
session_start();
include('connection.php'); // Include database connection

$user_id = $_SESSION['id']; // Get the user ID from session

// Fetch the books issued to the user along with their due dates
$query = "SELECT tbl_book.book_name, tbl_issue.due_date, tbl_issue.status 
          FROM tbl_issue 
          INNER JOIN tbl_book ON tbl_issue.book_id = tbl_book.id 
          WHERE tbl_issue.user_id = ? AND tbl_issue.status = 1"; // Only fetch issued books

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Display the books in a table format
if ($result->num_rows > 0) {
    echo '<table class="table">';
    echo '<tr><th>Book Name</th><th>Due Date</th><th>Action</th></tr>';
    
    while ($row = $result->fetch_assoc()) {
        $book_name = $row['book_name'];
        $due_date = $row['due_date'] ? $row['due_date'] : 'N/A'; // If no due date, show 'N/A'
        
        // Show book details with due date and a return button
        echo "<tr>
                <td>$book_name</td>
                <td>$due_date</td>
                <td><a href='return-book.php?book_id={$row['book_id']}'><button class='btn btn-danger'>Return</button></a></td>
              </tr>";
    }
    echo '</table>';
} else {
    echo 'No books issued yet.';
}
?>