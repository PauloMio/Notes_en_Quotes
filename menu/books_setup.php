<?php require_once "../database/db_connection.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Books Setup</title>
<link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="container mt-4">
  <h2>Books</h2>

  <button data-ui-toggle="modal" data-ui-target="#bookModal">+ Add Book</button>

  <div id="booksTable">
    <?php include "books_script.php"; ?>
  </div>
</div>

<!-- BOOK MODAL -->
<div class="modal" id="bookModal">
  <div class="modal-content container">
    <h3>Add / Edit Book</h3>

    <form method="POST" action="books_script.php" data-ui-ajax="true" data-ui-target="#booksTable">
      <input type="hidden" name="action" value="save">
      <input type="hidden" name="id" id="book_id">

      <label>Book Title</label>
      <input type="text" name="book_title" required>

      <label>Author</label>
      <input type="text" name="author">

      <label>Description</label>
      <textarea name="description"></textarea>

      <label>Copyright Year</label>
      <input type="number" name="copyrightyear">

      <!-- SUBJECT DROPDOWN -->
      <label>Subject</label>
      <div class="dropdown">
        <button type="button" data-ui-toggle="dropdown">Select Subject</button>
        <div class="dropdown-menu">
          <?php
          $subjects = $conn->query("SELECT * FROM subject");
          while ($s = $subjects->fetch_assoc()):
          ?>
          <div>
            <span onclick="selectSubject('<?= $s['subject'] ?>')"><?= $s['subject'] ?></span>
            <button type="button" onclick="deleteSubject(<?= $s['id'] ?>)">✕</button>
          </div>
          <?php endwhile; ?>

          <input type="text" id="newSubject" placeholder="New subject">
          <button type="button" onclick="addSubject()">Add</button>
        </div>
      </div>
      <input type="hidden" name="subject" id="subject_input">

      <!-- TYPE DROPDOWN -->
      <label>Book Type</label>
      <div class="dropdown">
        <button type="button" data-ui-toggle="dropdown">Select Type</button>
        <div class="dropdown-menu">
          <?php
          $types = $conn->query("SELECT * FROM book_types");
          while ($t = $types->fetch_assoc()):
          ?>
          <div>
            <span onclick="selectType('<?= $t['type'] ?>')"><?= $t['type'] ?></span>
            <button type="button" onclick="deleteType(<?= $t['id'] ?>)">✕</button>
          </div>
          <?php endwhile; ?>

          <input type="text" id="newType" placeholder="New type">
          <button type="button" onclick="addType()">Add</button>
        </div>
      </div>
      <input type="hidden" name="type" id="type_input">

      <button type="submit">Save</button>
      <button type="button" data-ui-dismiss="modal">Cancel</button>
    </form>
  </div>
</div>

<script src="../js/script.js"></script>

<script>
function selectSubject(val) {
  document.getElementById('subject_input').value = val;
}

function selectType(val) {
  document.getElementById('type_input').value = val;
}

async function addSubject() {
  const fd = new FormData();
  fd.append("action", "add_subject");
  fd.append("subject", document.getElementById("newSubject").value);
  await ajaxPost("books_script.php", fd);
  location.reload();
}

async function deleteSubject(id) {
  const fd = new FormData();
  fd.append("action", "delete_subject");
  fd.append("id", id);
  await ajaxPost("books_script.php", fd);
  location.reload();
}

async function addType() {
  const fd = new FormData();
  fd.append("action", "add_type");
  fd.append("type", document.getElementById("newType").value);
  await ajaxPost("books_script.php", fd);
  location.reload();
}

async function deleteType(id) {
  const fd = new FormData();
  fd.append("action", "delete_type");
  fd.append("id", id);
  await ajaxPost("books_script.php", fd);
  location.reload();
}
</script>

</body>
</html>
