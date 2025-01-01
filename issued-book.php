<?php
session_start();
include('connection.php');

// Display popup message if it exists
if (isset($_SESSION['popup_message'])) {
    echo "<script>alert('" . $_SESSION['popup_message'] . "');</script>";
    unset($_SESSION['popup_message']); // Clear the message after showing it
}

$name = $_SESSION['user_name'];
$ids = $_SESSION['id'];
if (empty($ids)) {
    header("Location: index.php");
    exit();
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
                    <a href="#">Issued Books</a>
                </li>
            </ol>

            <div class="card mb-3">
                <div class="card-header">
                    <i class="fa fa-info-circle"></i>
                    Issued Book Details
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Book Name</th>
                                    <th>Category</th>
                                    <th>Due Date</th>
                                    <th>Return</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $select_query = mysqli_query($conn, "SELECT tbl_issue.book_id, tbl_book.book_name, tbl_book.category, tbl_issue.due_date 
                                    FROM tbl_issue 
                                    INNER JOIN tbl_book 
                                    ON tbl_issue.book_id = tbl_book.id 
                                    WHERE tbl_issue.user_id = '$ids' AND tbl_issue.status = 1");

                                $sn = 1;
                                while ($row = mysqli_fetch_array($select_query)) {
                                    $due_date = $row['due_date'];
                                    $current_date = date('Y-m-d');
                                    $book_id = $row['book_id'];

                                    // Check if due date is exceeded
                                    $is_due_exceeded = ($current_date > $due_date) ? true : false;
                                ?>
                                    <tr>
                                        <td><?php echo $sn; ?></td>
                                        <td><?php echo $row['book_name']; ?></td>
                                        <td><?php echo $row['category']; ?></td>
                                        <td><?php echo $due_date; ?></td>
                                        <td>
                                            <?php if ($is_due_exceeded) { ?>
                                                <button class="btn btn-danger return-with-code-btn" data-book-id="<?php echo $book_id; ?>" data-toggle="modal" data-target="#returnWithCodeModal">
                                                    Return with Code
                                                </button>
                                            <?php } else { ?>
                                                <a href="book-return.php?id=<?php echo $book_id; ?>">
                                                    <button class="btn btn-success">Return</button>
                                                </a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php $sn++;
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Return with Code -->
<div class="modal fade" id="returnWithCodeModal" tabindex="-1" role="dialog" aria-labelledby="returnWithCodeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="returnWithCodeModalLabel">Return Book with Admin Code</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="return-with-code.php">
                    <div class="form-group">
                        <label for="adminCode">Enter the code given by admin to return the book:</label>
                        <input type="text" class="form-control" id="adminCode" name="adminCode" required>
                        <input type="hidden" id="bookId" name="bookId">
                        <input type="hidden" name="userId" value="<?php echo $ids; ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
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
        $('.return-with-code-btn').on('click', function() {
            var bookId = $(this).data('book-id');
            $('#bookId').val(bookId);
        });
    });
</script>