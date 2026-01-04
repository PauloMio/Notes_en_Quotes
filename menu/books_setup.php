<?php
require_once "../database/db_connection.php";

// Fetch subjects and book types for dropdowns
$subjects = $conn->query("SELECT * FROM subject ORDER BY subject ASC")->fetch_all(MYSQLI_ASSOC);
$book_types = $conn->query("SELECT * FROM book_types ORDER BY type ASC")->fetch_all(MYSQLI_ASSOC);

// Fetch books for table
$books = $conn->query("
    SELECT b.id, b.book_title, b.author, b.description, b.copyrightyear, s.subject, bt.type
    FROM books b
    LEFT JOIN subject s ON b.subject = s.subject
    LEFT JOIN book_types bt ON b.type = bt.type
    ORDER BY b.book_title ASC
")->fetch_all(MYSQLI_ASSOC);
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
    <button data-ui-toggle="modal" data-ui-target="#addBookModal">+ Add Book</button>

    <!-- Books Table -->
    <table class="mt-3">
        <thead>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Description</th>
                <th>Year</th>
                <th>Subject</th>
                <th>Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="booksTableBody">
            <?php foreach($books as $book): ?>
            <tr data-id="<?= $book['id'] ?>">
                <td><?= htmlspecialchars($book['book_title']) ?></td>
                <td><?= htmlspecialchars($book['author']) ?></td>
                <td><?= htmlspecialchars($book['description']) ?></td>
                <td><?= htmlspecialchars($book['copyrightyear']) ?></td>
                <td><?= htmlspecialchars($book['subject']) ?></td>
                <td><?= htmlspecialchars($book['type']) ?></td>
                <td>
                    <button class="editBookBtn" data-ui-toggle="modal" data-ui-target="#editBookModal"
                        data-id="<?= $book['id'] ?>">Edit</button>
                    <button class="deleteBookBtn" data-ui-toggle="modal" data-ui-target="#deleteBookModal"
                        data-id="<?= $book['id'] ?>">Delete</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Add Book Modal -->
<div class="modal" id="addBookModal">
    <div class="modal-content">
        <h3>Add Book</h3>
        <form id="addBookForm" action="books_script.php" method="POST" data-ui-ajax="true" data-ui-target="#booksTableBody">
            <input type="hidden" name="action" value="add">
            <label>Title</label><input type="text" name="book_title" required>
            <label>Author</label><input type="text" name="author">
            <label>Description</label><textarea name="description"></textarea>
            <label>Year</label><input type="number" name="copyrightyear">
            <label>Subject</label>
            <select name="subject">
                <option value="">-- Select --</option>
                <?php foreach($subjects as $sub): ?>
                    <option value="<?= $sub['subject'] ?>"><?= $sub['subject'] ?></option>
                <?php endforeach; ?>
            </select>
            <label>Type</label>
            <select name="type" required>
                <option value="">-- Select --</option>
                <?php foreach($book_types as $bt): ?>
                    <option value="<?= $bt['type'] ?>"><?= $bt['type'] ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Save</button>
            <button type="button" data-ui-dismiss="modal">Cancel</button>
        </form>
    </div>
</div>

<!-- Edit Book Modal -->
<div class="modal" id="editBookModal">
    <div class="modal-content">
        <h3>Edit Book</h3>
        <form id="editBookForm" action="books_script.php" method="POST" data-ui-ajax="true" data-ui-target="#booksTableBody">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" id="editBookId">
            <label>Title</label><input type="text" name="book_title" id="editBookTitle" required>
            <label>Author</label><input type="text" name="author" id="editBookAuthor">
            <label>Description</label><textarea name="description" id="editBookDescription"></textarea>
            <label>Year</label><input type="number" name="copyrightyear" id="editBookYear">
            <label>Subject</label>
            <select name="subject" id="editBookSubject">
                <option value="">-- Select --</option>
                <?php foreach($subjects as $sub): ?>
                    <option value="<?= $sub['subject'] ?>"><?= $sub['subject'] ?></option>
                <?php endforeach; ?>
            </select>
            <label>Type</label>
            <select name="type" id="editBookType" required>
                <option value="">-- Select --</option>
                <?php foreach($book_types as $bt): ?>
                    <option value="<?= $bt['type'] ?>"><?= $bt['type'] ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Update</button>
            <button type="button" data-ui-dismiss="modal">Cancel</button>
        </form>
    </div>
</div>

<!-- Delete Book Modal -->
<div class="modal" id="deleteBookModal">
    <div class="modal-content">
        <h3>Delete Book</h3>
        <p>Are you sure you want to delete this book?</p>
        <form id="deleteBookForm" action="books_script.php" method="POST" data-ui-ajax="true" data-ui-target="#booksTableBody">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" id="deleteBookId">
            <button type="submit">Yes, Delete</button>
            <button type="button" data-ui-dismiss="modal">Cancel</button>
        </form>
    </div>
</div>

<script src="../js/script.js"></script>
<script>
  // Populate edit modal with selected book data
  document.querySelectorAll('.editBookBtn').forEach(btn => {
    btn.addEventListener('click', e => {
      const tr = e.target.closest('tr');
      document.getElementById('editBookId').value = tr.dataset.id;
      document.getElementById('editBookTitle').value = tr.children[0].textContent;
      document.getElementById('editBookAuthor').value = tr.children[1].textContent;
      document.getElementById('editBookDescription').value = tr.children[2].textContent;
      document.getElementById('editBookYear').value = tr.children[3].textContent;
      document.getElementById('editBookSubject').value = tr.children[4].textContent;
      document.getElementById('editBookType').value = tr.children[5].textContent;
    });
  });

  // Populate delete modal
  document.querySelectorAll('.deleteBookBtn').forEach(btn => {
    btn.addEventListener('click', e => {
      const tr = e.target.closest('tr');
      document.getElementById('deleteBookId').value = tr.dataset.id;
    });
  });
</script>
</body>
</html>
