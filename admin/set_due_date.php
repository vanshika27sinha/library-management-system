<?php
session_start();
include('../connection.php');

// Get the issue ID and the new due date from the POST request
if (isset($_POST['issue_id']) && isset($_POST['due_date'])) {
    $issue_id = $_POST['issue_id'];
    $due_date = $_POST['due_date'];

    // Update the due date in tbl_issue table for the given issue_id
    $update_query = "UPDATE tbl_issue SET due_date = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("si", $due_date, $issue_id);

    if ($stmt->execute()) {
        // Redirect with a success message if the update is successful
        header("Location: issue-request.php?msg=Due date updated successfully.");
    } else {
        // Handle error if the update fails
        header("Location: issue-request.php?msg=Failed to update due date.");
    }
    $stmt->close();
} else {
    // If data is not set, redirect with an error
    header("Location: issue-request.php?msg=Invalid data.");
}
?>