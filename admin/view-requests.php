<?php
session_start();
include ('../connection.php');
$name = $_SESSION['name'];
$id = $_SESSION['id'];
if(empty($id))
{
    header("Location: index.php"); 
}
?>
<?php include('include/header.php'); ?>
<div id="wrapper">
    <?php include('include/side-bar.php'); ?>
    <div id="content-wrapper">
        <div class="container-fluid">
            <!-- Breadcrumbs-->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="#">View Book Issue Requests</a>
                </li>
            </ol>
            <div class="card mb-3">
                <div class="card-header">
                    <i class="fa fa-info-circle"></i>
                    Issue Requests
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Book Name</th>
                                    <th>User Name</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $select_query = mysqli_query($conn, "SELECT tbl_issue.id, tbl_book.book_name, tbl_issue.user_name, tbl_issue.due_date, tbl_issue.status FROM tbl_issue INNER JOIN tbl_book ON tbl_issue.book_id = tbl_book.id");
                                $sn = 1;
                                while($row = mysqli_fetch_array($select_query)) {
                                    $due_date = isset($row['due_date']) ? $row['due_date'] : 'N/A';
                                    $id = isset($row['id']) ? $row['id'] : ''; // Ensure 'id' is available
                                ?>
                                    <tr>
                                        <td><?php echo $sn; ?></td>
                                        <td><?php echo $row['book_name']; ?></td>
                                        <td><?php echo $row['user_name']; ?></td>
                                        <td>
                                            <input type="date" id="due_date_<?php echo $row['id']; ?>" value="<?php echo $due_date; ?>" class="form-control" />
                                        </td>
                                        <td>
                                            <?php
                                            if ($row['status'] == 1) {
                                                echo "<span class='badge badge-primary'>Book Issued</span>";
                                            } else if ($row['status'] == 2) {
                                                echo "<span class='badge badge-danger'>Rejected</span>";
                                            } else {
                                                echo "<a href='book-accept.php?ids=" . $row['id'] . "'><button class='btn btn-danger'>Reject</button></a>";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <button class="btn btn-primary" onclick="setDueDate(<?php echo $row['id']; ?>)">Set Due Date</button>
                                        </td>
                                    </tr>
                                <?php $sn++; } ?>
                            </tbody>
                        </table>
                    </div>
                </div>                   
            </div>
        </div>
    </div>
</div>
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>
<?php include('include/footer.php'); ?>

<!-- JavaScript to handle due date setting -->
<script>
function setDueDate(issueId) {
    var dueDate = document.getElementById('due_date_' + issueId).value;
    if (dueDate) {
        // Create a form and send a POST request
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = 'set_due_date.php'; // PHP script to update the due date

        // Add hidden inputs to send the issue ID and due date
        var issueIdInput = document.createElement('input');
        issueIdInput.type = 'hidden';
        issueIdInput.name = 'issue_id';
        issueIdInput.value = issueId;

        var dueDateInput = document.createElement('input');
        dueDateInput.type = 'hidden';
        dueDateInput.name = 'due_date';
        dueDateInput.value = dueDate;

        form.appendChild(issueIdInput);
        form.appendChild(dueDateInput);

        // Submit the form
        document.body.appendChild(form);
        form.submit();
    } else {
        alert('Please select a valid due date.');
    }
}
</script> 