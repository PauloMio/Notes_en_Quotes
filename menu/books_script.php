<?php
include "../database/db_connection.php";

// Handle AJAX requests
$action = $_REQUEST['action'] ?? '';

if($action == 'add'){
    $book_title = $_POST['book_title'];
    $author = $_POST['author'];
    $description = $_POST['description'];
    $copyrightyear = $_POST['copyrightyear'];
    $subject = $_POST['subject'];
    $type = $_POST['type'];

    $stmt = $conn->prepare("INSERT INTO books (book_title, author, description, copyrightyear, subject, type, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("sssiss", $book_title, $author, $description, $copyrightyear, $subject, $type);
    $stmt->execute();
    $stmt->close();

    echo listBooks();
    exit;
}

if($action == 'edit'){
    $id = $_POST['id'];
    $book_title = $_POST['book_title'];
    $author = $_POST['author'];
    $description = $_POST['description'];
    $copyrightyear = $_POST['copyrightyear'];
    $subject = $_POST['subject'];
    $type = $_POST['type'];

    $stmt = $conn->prepare("UPDATE books SET book_title=?, author=?, description=?, copyrightyear=?, subject=?, type=?, updated_at=NOW() WHERE id=?");
    $stmt->bind_param("sssissi", $book_title, $author, $description, $copyrightyear, $subject, $type, $id);
    $stmt->execute();
    $stmt->close();

    echo listBooks();
    exit;
}

if($action == 'delete'){
    $id = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM books WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    echo listBooks();
    exit;
}

if($action == 'list'){
    echo listBooks();
    exit;
}

// ==========================
// Function to return books table HTML
// ==========================
function listBooks(){
    global $conn;
    $result = $conn->query("SELECT * FROM books ORDER BY created_at DESC");
    $html = '<table>
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
                <tbody>';
    while($row = $result->fetch_assoc()){
        $json = htmlspecialchars(json_encode($row));
        $html .= '<tr>
                    <td>'.htmlspecialchars($row['book_title']).'</td>
                    <td>'.htmlspecialchars($row['author']).'</td>
                    <td>'.htmlspecialchars($row['description']).'</td>
                    <td>'.htmlspecialchars($row['copyrightyear']).'</td>
                    <td>'.htmlspecialchars($row['subject']).'</td>
                    <td>'.htmlspecialchars($row['type']).'</td>
                    <td>
                        <button onclick="editBook(`'.$json.'`)">Edit</button>
                        <button onclick="deleteBook('.$row['id'].')">Delete</button>
                    </td>
                  </tr>';
    }
    $html .= '</tbody></table>';
    return $html;
}
?>
