<?php
session_start();
include('connection.php');

$bookId = $_POST['bookId'];
$userId = $_POST['userId'];
$adminCode = $_POST['adminCode'];

// Admin's pre-set code
$correctCode = 'XY23'; // Change to dynamically fetch if required

if ($adminCode === $correctCode) {
    // Update book status as returned
    $query = "UPDATE tbl_issue SET status = 0 WHERE book_id = '$bookId' AND user_id = '$userId'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $_SESSION['popup_message'] = "Transaction successful! The book has been returned.";
    } else {
        $_SESSION['popup_message'] = "Failed to return the book. Please try again.";
    }
} else {
    $_SESSION['popup_message'] = "Incorrect admin code. Please try again.";
}

// Redirect back to issued-book.php
header("Location: issued-book.php");
exit();
?>