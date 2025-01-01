<?php
session_start();
include('../connection.php');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $issue_id = $_POST['issue_id'] ?? null;
    $due_date = $_POST['due_date'] ?? null;

    // Validate input
    if (!empty($issue_id) && !empty($due_date)) {
        // Update the due date in the database
        $update_query = "UPDATE tbl_issue SET due_date = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("si", $due_date, $issue_id);

        if ($stmt->execute()) {
            // Redirect back with a success message
            header("Location: view-request.php?success=1");
        } else {
            // Redirect back with an error message
            header("Location: view-request.php?error=Database update failed.");
        }

        $stmt->close();
    } else {
        // Redirect back with an error message
        header("Location: view-request.php?error=Invalid input.");
    }
} else {
    // Redirect if accessed directly
    header("Location: view-request.php");
}
exit;
?>