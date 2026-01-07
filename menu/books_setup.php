<?php
require_once "../database/db_connection.php";

$subjects = $conn->query("SELECT * FROM subject ORDER BY subject ASC");
$types    = $conn->query("SELECT * FROM book_types ORDER BY type ASC");
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
  <div class="block">

    <h2>Books</h2>

    <div class="mb-3">
      <button data-ui-toggle="modal" data-ui-target="#bookModal">
        + Add Book
      </button>
    </div>

    <div id="booksTable">
      <?php include "books_script.php"; ?>
    </div>

  </div>
</div>

<!-- =========================
     BOOK MODAL
========================= -->
<div class="modal" id="bookModal">
  <div class="modal-content container">
    <div class="block">

      <h3 class="mb-3">Add / Edit Book</h3>

      <form method="POST"
            action="books_script.php"
            data-ui-ajax="true"
            data-ui-target="#booksTable">

        <input type="hidden" name="action" value="save">
        <input type="hidden" name="id" id="book_id">

        <!-- BOOK TITLE -->
        <div class="form-group">
          <label>Book Title</label>
          <input type="text" name="book_title" required>
        </div>

        <!-- AUTHOR -->
        <div class="form-group">
          <label>Author</label>
          <input type="text" name="author">
        </div>

        <!-- DESCRIPTION -->
        <div class="form-group">
          <label>Description</label>
          <textarea name="description"></textarea>
        </div>

        <!-- COPYRIGHT YEAR -->
        <div class="form-group">
          <label>Copyright Year</label>
          <input type="number" name="copyrightyear">
        </div>

        <!-- SUBJECT DROPDOWN -->
        <div class="form-group">
          <label>Subject</label>

          <div class="dropdown">
            <button type="button" data-ui-toggle="dropdown">
              Select Subject
            </button>

            <div class="dropdown-menu block">
              <?php while ($s = $subjects->fetch_assoc()): ?>
                <div class="row mb-1">
                  <div class="col">
                    <span style="cursor:pointer"
                          onclick="selectSubject('<?= htmlspecialchars($s['subject']) ?>')">
                      <?= htmlspecialchars($s['subject']) ?>
                    </span>
                  </div>
                  <div>
                    <button type="button"
                            onclick="deleteSubject(<?= $s['id'] ?>)">
                      ✕
                    </button>
                  </div>
                </div>
              <?php endwhile; ?>

              <div class="form-group mt-2">
                <input type="text" id="newSubject" placeholder="New subject">
              </div>

              <button type="button" onclick="addSubject()">Add</button>
            </div>
          </div>

          <input type="hidden" name="subject" id="subject_input">
        </div>

        <!-- TYPE DROPDOWN -->
        <div class="form-group">
          <label>Book Type</label>

          <div class="dropdown">
            <button type="button" data-ui-toggle="dropdown">
              Select Type
            </button>

            <div class="dropdown-menu block">
              <?php while ($t = $types->fetch_assoc()): ?>
                <div class="row mb-1">
                  <div class="col">
                    <span style="cursor:pointer"
                          onclick="selectType('<?= htmlspecialchars($t['type']) ?>')">
                      <?= htmlspecialchars($t['type']) ?>
                    </span>
                  </div>
                  <div>
                    <button type="button"
                            onclick="deleteType(<?= $t['id'] ?>)">
                      ✕
                    </button>
                  </div>
                </div>
              <?php endwhile; ?>

              <div class="form-group mt-2">
                <input type="text" id="newType" placeholder="New type">
              </div>

              <button type="button" onclick="addType()">Add</button>
            </div>
          </div>

          <input type="hidden" name="type" id="type_input">
        </div>

        <!-- ACTION BUTTONS -->
        <div class="row mt-4">
          <div>
            <button type="submit">Save</button>
          </div>
          <div>
            <button type="button" data-ui-dismiss="modal">
              Cancel
            </button>
          </div>
        </div>

      </form>

    </div>
  </div>
</div>

<script src="../js/script.js"></script>

<script>
function selectSubject(value) {
  document.getElementById("subject_input").value = value;
}

function selectType(value) {
  document.getElementById("type_input").value = value;
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
