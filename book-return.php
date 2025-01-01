<?php 
session_start();
include ('connection.php');
$name = $_SESSION['user_name'];
$ids = $_SESSION['id'];
$id = $_GET['id'];

// Delete book from the tbl_issue table
$delete_book = mysqli_query($conn, "DELETE FROM tbl_issue WHERE book_id='$id' AND user_id='$ids' AND status=1");

// Insert record in tbl_return when the book is returned
$return_book = mysqli_query($conn, "INSERT INTO tbl_return (book_id, user_id, user_name, return_date) VALUES ('$id', '$ids', '$name', CURDATE())");

// Update the quantity of the book in tbl_book after it's returned
$select_quantity = mysqli_query($conn, "SELECT quantity FROM tbl_book WHERE id='$id'");
$number = mysqli_fetch_row($select_quantity);
$count = $number[0];
$count = $count + 1;
$update_book = mysqli_query($conn, "UPDATE tbl_book SET quantity='$count' WHERE id='$id'");

// Mark the book status as returned in tbl_issue (optional, already deleted but updating the status just for safety)
$update_issue_status = mysqli_query($conn, "UPDATE tbl_issue SET status=0 WHERE book_id='$id' AND user_id='$ids'");

if ($update_book > 0) {
    ?>
    <script type="text/javascript">
        alert("Book Returned successfully.");
        window.location.href="issued-book.php"; // Redirect to the "Issued Book" page
    </script>
    <?php
}
?>