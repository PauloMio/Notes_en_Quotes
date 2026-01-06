<?php
require_once "../database/db_connection.php";

/* =========================
   ACTION HANDLER
========================= */
$action = $_POST['action'] ?? null;

/* ---------- SAVE BOOK ---------- */
if ($action === "save") {
  $id = $_POST['id'] ?? null;

  if ($id) {
    $stmt = $conn->prepare(
      "UPDATE books SET book_title=?, author=?, description=?, copyrightyear=?, subject=?, type=? WHERE id=?"
    );
    $stmt->bind_param(
      "sssissi",
      $_POST['book_title'],
      $_POST['author'],
      $_POST['description'],
      $_POST['copyrightyear'],
      $_POST['subject'],
      $_POST['type'],
      $id
    );
  } else {
    $stmt = $conn->prepare(
      "INSERT INTO books (book_title, author, description, copyrightyear, subject, type)
       VALUES (?,?,?,?,?,?)"
    );
    $stmt->bind_param(
      "sssiss",
      $_POST['book_title'],
      $_POST['author'],
      $_POST['description'],
      $_POST['copyrightyear'],
      $_POST['subject'],
      $_POST['type']
    );
  }

  $stmt->execute();
}

/* ---------- DELETE BOOK ---------- */
if ($action === "delete") {
  $stmt = $conn->prepare("DELETE FROM books WHERE id=?");
  $stmt->bind_param("i", $_POST['id']);
  $stmt->execute();
}

/* ---------- SUBJECT CRUD ---------- */
if ($action === "add_subject") {
  $stmt = $conn->prepare("INSERT INTO subject (subject) VALUES (?)");
  $stmt->bind_param("s", $_POST['subject']);
  $stmt->execute();
}

if ($action === "delete_subject") {
  $stmt = $conn->prepare("DELETE FROM subject WHERE id=?");
  $stmt->bind_param("i", $_POST['id']);
  $stmt->execute();
}

/* ---------- TYPE CRUD ---------- */
if ($action === "add_type") {
  $stmt = $conn->prepare("INSERT INTO book_types (type) VALUES (?)");
  $stmt->bind_param("s", $_POST['type']);
  $stmt->execute();
}

if ($action === "delete_type") {
  $stmt = $conn->prepare("DELETE FROM book_types WHERE id=?");
  $stmt->bind_param("i", $_POST['id']);
  $stmt->execute();
}

/* =========================
   BOOKS TABLE OUTPUT
========================= */
$result = $conn->query("SELECT * FROM books ORDER BY created_at DESC");
?>

<table>
  <thead>
    <tr>
      <th>Title</th>
      <th>Author</th>
      <th>Subject</th>
      <th>Type</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
  <?php while ($b = $result->fetch_assoc()): ?>
    <tr>
      <td><?= htmlspecialchars($b['book_title']) ?></td>
      <td><?= htmlspecialchars($b['author']) ?></td>
      <td><?= htmlspecialchars($b['subject']) ?></td>
      <td><?= htmlspecialchars($b['type']) ?></td>
      <td>
        <form method="POST" action="books_script.php" data-ui-ajax="true" data-ui-target="#booksTable" style="display:inline">
          <input type="hidden" name="action" value="delete">
          <input type="hidden" name="id" value="<?= $b['id'] ?>">
          <button type="submit">Delete</button>
        </form>
      </td>
    </tr>
  <?php endwhile; ?>
  </tbody>
</table>
