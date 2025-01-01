<?php
session_start();
include('../connection.php');

// Check if the admin is logged in
$name = $_SESSION['name'];
$id = $_SESSION['id'];
if (empty($id)) {
    header("Location: index.php"); 
}

// Handle accepting or rejecting a book issue request
if (isset($_GET['id'])) {
    $issue_id = $_GET['id'];
    
    // Check for accept action
    $accept_query = "UPDATE tbl_issue SET status = 1 WHERE id = '$issue_id'";
    if (mysqli_query($conn, $accept_query)) {
        echo "<script>alert('Book issue request accepted successfully!'); window.location.href='view-requests.php';</script>";
    } else {
        echo "<script>alert('Error accepting the request. Please try again.'); window.location.href='view-requests.php';</script>";
    }
}

// Handle rejecting the book issue request
if (isset($_GET['ids'])) {
    $issue_id = $_GET['ids'];
    
    // Update status to 2 (Rejected)
    $reject_query = "UPDATE tbl_issue SET status = 2 WHERE id = '$issue_id'";
    if (mysqli_query($conn, $reject_query)) {
        echo "<script>alert('Book issue request rejected successfully!'); window.location.href='view-requests.php';</script>";
    } else {
        echo "<script>alert('Error rejecting the request. Please try again.'); window.location.href='view-requests.php';</script>";
    }
}
?>