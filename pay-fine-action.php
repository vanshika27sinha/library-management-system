<?php
session_start();
include('connection.php');

$bookId = $_POST['bookId'];
$userId = $_POST['userId'];
$paymentCode = $_POST['paymentCode'];

// Assume the admin code is 'XYZ123' (this should be dynamically fetched if needed)
$correctCode = 'XYZ123';

// Check if the payment code is correct
if ($paymentCode === $correctCode) {
    // Update the book status to returned (status = 0 for returned)
    $query = "UPDATE tbl_issue SET returned_status = 1, fine_status = 'Paid' WHERE book_id = '$bookId' AND user_id = '$userId' AND status = 1";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Transaction successful
        $_SESSION['success_message'] = "Transaction successful. The book has been returned.";
        // Optionally, also update the fine status or other info if needed
    } else {
        // If there was an issue with updating the book status
        $_SESSION['error_message'] = "Failed to process the return. Please try again.";
    }
} else {
    // If the code doesn't match
    $_SESSION['error_message'] = "Incorrect payment code. Please try again.";
}

header("Location: issued-book.php");
exit();
?>