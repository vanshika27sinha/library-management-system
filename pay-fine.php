<?php
session_start();
include('connection.php');

// Ensure user is logged in
if (!isset($_SESSION['user_name']) || empty($_SESSION['user_name'])) {
    header("Location: dashboard.php");
    exit();
}

$name = $_SESSION['user_name'];  // Get the user's name from session
$ids = $_SESSION['id'];  // User ID from session
?>

<?php include('include/header.php'); ?>

<div id="wrapper">
    <?php include('include/side-bar.php'); ?>

    <div id="content-wrapper">
        <div class="container-fluid">
            <!-- Breadcrumbs-->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="#">Pay Fine</a>
                </li>
            </ol>

            <div class="card mb-3">
                <div class="card-header">
                    <i class="fa fa-info-circle"></i>
                    Pay Fine Details
                </div>
                <div class="card-body">
                    <!-- Display success or error messages -->
                    <?php if (isset($_SESSION['success_message'])): ?>
                        <div class="alert alert-success">
                            <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                        </div>
                    <?php elseif (isset($_SESSION['error_message'])): ?>
                        <div class="alert alert-danger">
                            <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
                        </div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Book Name</th>
                                    <th>Category</th>
                                    <th>Due Date</th>
                                    <th>Fine Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $select_query = mysqli_query($conn, "SELECT tbl_issue.book_id, tbl_book.book_name, tbl_book.category, tbl_issue.due_date, tbl_issue.fine_status FROM tbl_issue INNER JOIN tbl_book ON tbl_issue.book_id=tbl_book.id WHERE tbl_issue.user_id='$ids' AND tbl_issue.status=1 AND tbl_issue.returned_status = 0");
                                $sn = 1;
                                while ($row = mysqli_fetch_array($select_query)) {
                                    $due_date = $row['due_date'];
                                    $current_date = date('Y-m-d');
                                    $fine_status = "No Fine Imposed";
                                    $pay_button = '';

                                    // Check if due date is passed
                                    if ($due_date < $current_date) {
                                        $fine_status = "You have to pay a fine";
                                        $pay_button = '<button class="btn btn-danger pay-fine-btn" data-book-id="' . $row['book_id'] . '" data-user-id="' . $ids . '" data-toggle="modal" data-target="#payFineModal">Pay Fine</button>';
                                    }

                                    ?>
                                    <tr id="book-<?php echo $row['book_id']; ?>">
                                        <td><?php echo $sn; ?></td>
                                        <td><?php echo $row['book_name']; ?></td>
                                        <td><?php echo $row['category']; ?></td>
                                        <td><?php echo $due_date; ?></td>
                                        <td><?php echo $fine_status; ?></td>
                                        <td><?php echo $pay_button; ?></td>
                                    </tr>
                                <?php
                                    $sn++;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>                   
            </div>
        </div>
    </div>
</div>

<!-- Modal for paying fine -->
<div class="modal fade" id="payFineModal" tabindex="-1" role="dialog" aria-labelledby="payFineModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="payFineModalLabel">Pay Fine</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="pay-fine-action.php">
                    <div class="form-group">
                        <label for="paymentCode">Enter the code given by admin to return the book </label>
                        <input type="text" class="form-control" id="paymentCode" name="paymentCode" required>
                        <input type="hidden" id="bookId" name="bookId">
                        <input type="hidden" id="userId" name="userId" value="<?php echo $ids; ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Pay Fine</button>
                </form>
            </div>
        </div>
    </div>
</div>

<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<?php include('include/footer.php'); ?>

<!-- JavaScript to set the book ID dynamically in the modal -->
<script>
    $(document).ready(function() {
        $('.pay-fine-btn').on('click', function() {
            var bookId = $(this).data('book-id');
            var userId = $(this).data('user-id');
            $('#bookId').val(bookId);
            $('#userId').val(userId);
        });
    });
</script>