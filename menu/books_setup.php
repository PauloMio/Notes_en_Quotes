<?php
require_once "../database/db_connection.php";

// Fetch subjects for dropdown
$subject_result = $conn->query("SELECT * FROM subject ORDER BY subject ASC");
$subjects = $subject_result->fetch_all(MYSQLI_ASSOC);

// Fetch book types for dropdown
$type_result = $conn->query("SELECT * FROM book_types ORDER BY type ASC");
$types = $type_result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Books Setup</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="container mt-4">
    <h2>Books Setup</h2>
    <button data-ui-toggle="modal" data-ui-target="#addBookModal">Add New Book</button>

    <!-- Books Table -->
    <div id="booksTable" class="mt-3">
        <?php include "books_table.php"; ?>
    </div>
</div>

<!-- ======================
     ADD BOOK MODAL
====================== -->
<div id="addBookModal" class="modal">
    <div class="modal-content block">
        <h3>Add Book</h3>
        <form id="addBookForm" action="books_script.php" method="POST" data-ui-ajax="true" data-ui-target="#booksTable">
            <input type="hidden" name="action" value="add">

            <div class="form-group">
                <label>Book Title</label>
                <input type="text" name="book_title" required>
            </div>
            <div class="form-group">
                <label>Author</label>
                <input type="text" name="author">
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description"></textarea>
            </div>
            <div class="form-group">
                <label>Copyright Year</label>
                <input type="number" name="copyrightyear">
            </div>
            <div class="form-group">
                <label>Subject</label>
                <select name="subject" required>
                    <option value="">--Select Subject--</option>
                    <?php foreach($subjects as $sub): ?>
                        <option value="<?= htmlspecialchars($sub['subject']) ?>"><?= htmlspecialchars($sub['subject']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Type</label>
                <select name="type" required>
                    <option value="">--Select Type--</option>
                    <?php foreach($types as $type): ?>
                        <option value="<?= htmlspecialchars($type['type']) ?>"><?= htmlspecialchars($type['type']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit">Add Book</button>
            <button type="button" data-ui-dismiss="modal">Cancel</button>
        </form>
    </div>
</div>

<!-- ======================
     EDIT BOOK MODAL (Dynamic)
====================== -->
<div id="editBookModal" class="modal">
    <div class="modal-content block" id="editBookContent">
        <!-- AJAX will load edit form here -->
    </div>
</div>

<!-- ======================
     DELETE CONFIRM MODAL
====================== -->
<div id="deleteBookModal" class="modal">
    <div class="modal-content block" id="deleteBookContent">
        <!-- AJAX will load delete confirmation here -->
    </div>
</div>

<script src="../js/script.js"></script>
<script>
    // Open Edit Modal
    async function editBook(id) {
        const html = await ajaxGet('books_script.php?action=editForm&id=' + id);
        document.getElementById('editBookContent').innerHTML = html;
        document.getElementById('editBookModal').classList.add('show');
        document.body.classList.add('modal-open');
    }

    // Open Delete Modal
    async function deleteBook(id) {
        const html = await ajaxGet('books_script.php?action=deleteForm&id=' + id);
        document.getElementById('deleteBookContent').innerHTML = html;
        document.getElementById('deleteBookModal').classList.add('show');
        document.body.classList.add('modal-open');
    }
</script>
</body>
</html>
