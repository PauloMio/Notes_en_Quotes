<?php
include "../database/db_connection.php";

// Fetch subjects and book types for dropdowns
$subjects = $conn->query("SELECT * FROM subject ORDER BY subject ASC");
$types = $conn->query("SELECT * FROM book_types ORDER BY type ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Books Setup</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="container block">
    <h1>Books Setup</h1>

    <!-- Add Book Button -->
    <button data-ui-toggle="modal" data-ui-target="#addBookModal">Add Book</button>

    <!-- Books Table -->
    <div id="booksTable" class="mt-3">
        <!-- Table rows will load via AJAX -->
    </div>
</div>

<!-- =========================
     Add Book Modal
=========================== -->
<div class="modal" id="addBookModal">
    <div class="modal-content block">
        <h2>Add Book</h2>
        <form id="addBookForm" data-ui-ajax="true" action="books_script.php" method="POST" data-ui-target="#booksTable">
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
                    <option value="">Select Subject</option>
                    <?php while($row = $subjects->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($row['subject']) ?>"><?= htmlspecialchars($row['subject']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Type</label>
                <select name="type" required>
                    <option value="">Select Type</option>
                    <?php while($row = $types->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($row['type']) ?>"><?= htmlspecialchars($row['type']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit">Save</button>
            <button type="button" data-ui-dismiss="modal">Cancel</button>
        </form>
    </div>
</div>

<!-- =========================
     Edit Book Modal
=========================== -->
<div class="modal" id="editBookModal">
    <div class="modal-content block">
        <h2>Edit Book</h2>
        <form id="editBookForm" data-ui-ajax="true" action="books_script.php" method="POST" data-ui-target="#booksTable">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" id="editBookId">
            <div class="form-group">
                <label>Book Title</label>
                <input type="text" name="book_title" id="editBookTitle" required>
            </div>
            <div class="form-group">
                <label>Author</label>
                <input type="text" name="author" id="editAuthor">
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" id="editDescription"></textarea>
            </div>
            <div class="form-group">
                <label>Copyright Year</label>
                <input type="number" name="copyrightyear" id="editCopyrightyear">
            </div>
            <div class="form-group">
                <label>Subject</label>
                <select name="subject" id="editSubject" required>
                    <option value="">Select Subject</option>
                    <?php
                    $subjects->data_seek(0); // reset pointer
                    while($row = $subjects->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($row['subject']) ?>"><?= htmlspecialchars($row['subject']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Type</label>
                <select name="type" id="editType" required>
                    <option value="">Select Type</option>
                    <?php
                    $types->data_seek(0);
                    while($row = $types->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($row['type']) ?>"><?= htmlspecialchars($row['type']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit">Update</button>
            <button type="button" data-ui-dismiss="modal">Cancel</button>
        </form>
    </div>
</div>

<!-- =========================
     Delete Book Modal
=========================== -->
<div class="modal" id="deleteBookModal">
    <div class="modal-content block">
        <h2>Delete Book</h2>
        <p>Are you sure you want to delete this book?</p>
        <form id="deleteBookForm" data-ui-ajax="true" action="books_script.php" method="POST" data-ui-target="#booksTable">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" id="deleteBookId">
            <button type="submit">Yes, Delete</button>
            <button type="button" data-ui-dismiss="modal">Cancel</button>
        </form>
    </div>
</div>

<script src="../js/script.js"></script>
<script>
    // Load books table on page load
    async function loadBooks() {
        const res = await fetch('books_script.php?action=list');
        document.getElementById('booksTable').innerHTML = res;
    }
    loadBooks();

    // Open edit modal and populate fields
    window.editBook = function(book){
        const data = JSON.parse(book);
        document.getElementById('editBookId').value = data.id;
        document.getElementById('editBookTitle').value = data.book_title;
        document.getElementById('editAuthor').value = data.author;
        document.getElementById('editDescription').value = data.description;
        document.getElementById('editCopyrightyear').value = data.copyrightyear;
        document.getElementById('editSubject').value = data.subject;
        document.getElementById('editType').value = data.type;

        const modal = document.getElementById('editBookModal');
        modal.classList.add('show');
        document.body.classList.add('modal-open');
    }

    // Open delete modal
    window.deleteBook = function(id){
        document.getElementById('deleteBookId').value = id;
        const modal = document.getElementById('deleteBookModal');
        modal.classList.add('show');
        document.body.classList.add('modal-open');
    }
</script>
</body>
</html>
