<?php
require_once "../database/db_connection.php";

header('Content-Type: application/json');

$action = $_POST['action'] ?? '';
$id = $_POST['id'] ?? '';
$book_title = $_POST['book_title'] ?? '';
$author = $_POST['author'] ?? '';
$description = $_POST['description'] ?? '';
$copyrightyear = $_POST['copyrightyear'] ?? '';
$subject = $_POST['subject'] ?? '';
$type = $_POST['type'] ?? '';

$response = ['success' => false, 'message' => ''];

switch($action){
    case 'add':
        $stmt = $conn->prepare("INSERT INTO books (book_title, author, description, copyrightyear, subject, type, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssiss", $book_title, $author, $description, $copyrightyear, $subject, $type);
        $response['success'] = $stmt->execute();
        $response['message'] = $response['success'] ? 'Book added successfully' : $stmt->error;
        break;

    case 'edit':
        $stmt = $conn->prepare("UPDATE books SET book_title=?, author=?, description=?, copyrightyear=?, subject=?, type=?, updated_at=NOW() WHERE id=?");
        $stmt->bind_param("sssissi", $book_title, $author, $description, $copyrightyear, $subject, $type, $id);
        $response['success'] = $stmt->execute();
        $response['message'] = $response['success'] ? 'Book updated successfully' : $stmt->error;
        break;

    case 'delete':
        $stmt = $conn->prepare("DELETE FROM books WHERE id=?");
        $stmt->bind_param("i", $id);
        $response['success'] = $stmt->execute();
        $response['message'] = $response['success'] ? 'Book deleted successfully' : $stmt->error;
        break;
}

if($response['success']){
    // Return updated table rows for AJAX refresh
    $books = $conn->query("
        SELECT b.id, b.book_title, b.author, b.description, b.copyrightyear, s.subject, bt.type
        FROM books b
        LEFT JOIN subject s ON b.subject = s.subject
        LEFT JOIN book_types bt ON b.type = bt.type
        ORDER BY b.book_title ASC
    ")->fetch_all(MYSQLI_ASSOC);

    ob_start();
    foreach($books as $book){
        echo '<tr data-id="'.$book['id'].'">
                <td>'.htmlspecialchars($book['book_title']).'</td>
                <td>'.htmlspecialchars($book['author']).'</td>
                <td>'.htmlspecialchars($book['description']).'</td>
                <td>'.htmlspecialchars($book['copyrightyear']).'</td>
                <td>'.htmlspecialchars($book['subject']).'</td>
                <td>'.htmlspecialchars($book['type']).'</td>
                <td>
                    <button class="editBookBtn" data-ui-toggle="modal" data-ui-target="#editBookModal" data-id="'.$book['id'].'">Edit</button>
                    <button class="deleteBookBtn" data-ui-toggle="modal" data-ui-target="#deleteBookModal" data-id="'.$book['id'].'">Delete</button>
                </td>
              </tr>';
    }
    $response['html'] = ob_get_clean();
}

echo json_encode($response);
