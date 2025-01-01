<?php
session_start();
include('connection.php');

$bookId = $_POST['bookId'];
$returnCode = $_POST['returnCode'];

// Assume the correct code is 'XYZ123' (or fetch dynamically)
$correctCode = 'XYZ123';

if ($returnCode === $correctCode) {
    $query = "UPDATE tbl_issue SET status = 0, returned_status = 1 WHERE book_id = '$bookId' AND user_id = '{$_SESSION['id']}' AND status = 1";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $_SESSION['transaction_status'] = 'success';
    } else {
        $_SESSION['transaction_status'] = 'error';
    }
} else {
    $_SESSION['transaction_status'] = 'error';
}

header("Location: issued-book.php");
exit();
?>