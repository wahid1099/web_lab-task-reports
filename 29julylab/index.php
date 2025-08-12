<?php
$conn = mysqli_connect("localhost", "root", "", "booksstorephp");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'add') {
            $title = mysqli_real_escape_string($conn, $_POST['title']);
            $author = mysqli_real_escape_string($conn, $_POST['author']);
            $description = mysqli_real_escape_string($conn, $_POST['description']);
            $bestseller = isset($_POST['bestseller']) ? 1 : 0;
            $genre = mysqli_real_escape_string($conn, $_POST['genre']);

            $sql = "INSERT INTO books (Title, author, description, bestseller, genre) VALUES ('$title', '$author', '$description', $bestseller, '$genre')";
            
            if (mysqli_query($conn, $sql)) {
                $message = "✅ Book added successfully!";
            } else {
                $message = "❌ Error: " . mysqli_error($conn);
            }
        } elseif ($_POST['action'] == 'delete') {
            $id = mysqli_real_escape_string($conn, $_POST['id']);
            $sql = "DELETE FROM books WHERE id = '$id'";
            mysqli_query($conn, $sql);
        } elseif ($_POST['action'] == 'edit') {
            $id = mysqli_real_escape_string($conn, $_POST['id']);
            $title = mysqli_real_escape_string($conn, $_POST['title']);
            $author = mysqli_real_escape_string($conn, $_POST['author']);
            $description = mysqli_real_escape_string($conn, $_POST['description']);
            $bestseller = isset($_POST['bestseller']) ? 1 : 0;
            $genre = mysqli_real_escape_string($conn, $_POST['genre']);

            $sql = "UPDATE books SET Title='$title', author='$author', description='$description', bestseller=$bestseller, genre='$genre' WHERE id='$id'";
            mysqli_query($conn, $sql);
        }
    }
}

$result = mysqli_query($conn, "SELECT * FROM books");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Books Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #6366f1;
            --primary-hover: #4f46e5;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --text-color: #1f2937;
            --border-color: #e5e7eb;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f9fafb;
            padding: 2rem;
            color: var(--text-color);
            line-height: 1.5;
            margin: 0;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 350px 1fr;
            gap: 2rem;
        }

        .form-container, .table-container {
            background: white;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        h2 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: var(--text-color);
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-color);
        }

        input[type="text"],
        textarea,
        select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            transition: all 0.2s;
            font-size: 0.875rem;
        }

        input[type="text"]:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        textarea {
            min-height: 120px;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        button {
            width: 100%;
            padding: 0.75rem;
            border: none;
            border-radius: 0.5rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        button.primary {
            background: var(--primary-color);
            color: white;
        }

        button.primary:hover {
            background: var(--primary-hover);
        }

        .message {
            margin-top: 1rem;
            padding: 0.75rem;
            border-radius: 0.5rem;
            background: #ecfdf5;
            color: #059669;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        th {
            background: #f8fafc;
            padding: 1rem;
            text-align: left;
            font-weight: 500;
            color: var(--text-color);
            border-bottom: 1px solid var(--border-color);
        }

        td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        tr:hover {
            background: #f8fafc;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .edit-btn, .delete-btn {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            width: auto;
        }

        .edit-btn {
            background: var(--warning-color);
            color: white;
        }

        .delete-btn {
            background: var(--danger-color);
            color: white;
        }

        .edit-btn:hover, .delete-btn:hover {
            filter: brightness(110%);
        }
    </style>
</head>
<body>

<div class="container">
    <div class="form-container">
        <h2>📚 Add New Book</h2>
        <form method="POST">
            <input type="hidden" name="action" value="add">
            <div class="form-group">
                <label for="title">Book Title</label>
                <input type="text" name="title" id="title" required placeholder="Enter book title">
            </div>
            <div class="form-group">
                <label for="author">Author Name</label>
                <input type="text" name="author" id="author" required placeholder="Enter author name">
            </div>
            <div class="form-group">
                <label for="description">Book Description</label>
                <textarea name="description" id="description" required placeholder="Enter book description"></textarea>
            </div>
            <div class="form-group">
                <label for="genre">Genre</label>
                <select name="genre" id="genre" required>
                    <option value="">Select Genre</option>
                    <option value="Fiction">Fiction</option>
                    <option value="Non-Fiction">Non-Fiction</option>
                    <option value="Mystery">Mystery</option>
                    <option value="Science Fiction">Science Fiction</option>
                    <option value="Romance">Romance</option>
                    <option value="Fantasy">Fantasy</option>
                    <option value="Biography">Biography</option>
                </select>
            </div>
            <div class="checkbox-group">
                <input type="checkbox" name="bestseller" id="bestseller">
                <label for="bestseller">Best Seller</label>
            </div>
            <button type="submit" class="primary">
                <i class="fas fa-plus"></i> Add Book
            </button>
        </form>
        <?php if (!empty($message)) echo "<p class='message'>$message</p>"; ?>
    </div>

    <div class="table-container">
        <h2>📖 Books List</h2>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Description</th>
                    <th>Genre</th>
                    <th>Best Seller</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['Title']); ?></td>
                    <td><?php echo htmlspecialchars($row['author']); ?></td>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                    <td><?php echo htmlspecialchars($row['genre']); ?></td>
                    <td><?php echo $row['bestseller'] ? '✅' : ''; ?></td>
                    <td class="action-buttons">
                        <button class="edit-btn" onclick="editBook(<?php echo $row['id']; ?>, '<?php echo addslashes($row['Title']); ?>', '<?php echo addslashes($row['author']); ?>', '<?php echo addslashes($row['description']); ?>', '<?php echo addslashes($row['genre']); ?>', <?php echo $row['bestseller']; ?>)">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="delete-btn" onclick="return confirm('Are you sure you want to delete this book?')">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function editBook(id, title, author, description, genre, bestseller) {
    let newTitle = prompt("Enter new title:", title);
    let newAuthor = prompt("Enter new author:", author);
    let newDescription = prompt("Enter new description:", description);
    let newGenre = prompt("Enter new genre (Fiction, Non-Fiction, Mystery, Science Fiction, Romance, Fantasy, Biography):", genre);
    let newBestseller = confirm("Is this book a bestseller?");
    
    if (newTitle && newAuthor && newDescription && newGenre) {
        let form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = `
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" value="${id}">
            <input type="hidden" name="title" value="${newTitle}">
            <input type="hidden" name="author" value="${newAuthor}">
            <input type="hidden" name="description" value="${newDescription}">
            <input type="hidden" name="genre" value="${newGenre}">
            ${newBestseller ? '<input type="hidden" name="bestseller" value="1">' : ''}
        `;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

</body>
</html>
<?php mysqli_close($conn); ?>
