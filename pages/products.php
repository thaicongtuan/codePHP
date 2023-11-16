
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "product_management";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Thêm sản phẩm
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_product"])) {
    $name = mysqli_real_escape_string($conn, $_POST["name"]);
    $description = mysqli_real_escape_string($conn, $_POST["description"]);
    $price = $_POST["price"];

    $stmt = $conn->prepare("INSERT INTO products (name, description, price) VALUES (?, ?, ?)");
    $stmt->bind_param("ssd", $name, $description, $price);

    if ($stmt->execute()) {
        echo '<div class="success-message">Product added successfully</div>';
    } else {
        echo '<div class="error-message">Error adding product: ' . $stmt->error . '</div>';
    }
    $stmt->close();
}

// Sửa sản phẩm
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_product"])) {
    $id = $_POST["product_id"];
    $name = mysqli_real_escape_string($conn, $_POST["name"]);
    $description = mysqli_real_escape_string($conn, $_POST["description"]);
    $price = $_POST["price"];

    $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=? WHERE id=?");
    $stmt->bind_param("ssdi", $name, $description, $price, $id);

    if ($stmt->execute()) {
        echo '<div class="success-message">Product updated successfully</div>';
    } else {
        echo '<div class="error-message">Error updating product: ' . $stmt->error . '</div>';
    }
    $stmt->close();
}

// Xoá sản phẩm
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_product"])) {
    $id = $_POST["product_id"];

    // Hiển thị xác nhận trước khi xoá
    echo '<div class="confirm-message">Are you sure you want to delete this product?';
    echo '<form method="post" action="' . $_SERVER["PHP_SELF"] . '">';
    echo '<input type="hidden" name="confirm_delete" value="true">';
    echo '<input type="hidden" name="product_id" value="' . $id . '">';
    echo '<button type="submit" name="confirm" class="confirm-button">Yes</button>';
    echo '<button type="submit" name="cancel" class="cancel-button">No</button>';
    echo '</form>';
    echo '</div>';
}

// Xác nhận xoá sản phẩm
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["confirm_delete"])) {
    $id = $_POST["product_id"];

    $stmt = $conn->prepare("DELETE FROM products WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo '<div class="success-message">Product deleted successfully</div>';
    } else {
        echo '<div class="error-message">Error deleting product: ' . $stmt->error . '</div>';
    }
    $stmt->close();
}

// Lấy danh sách sản phẩm
$result = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h2>Product Management</h2>

        <!-- Add Product Form -->
        <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>" class="product-form">
            <label for="name">Name:</label>
            <input type="text" name="name" required>

            <label for="description">Description:</label>
            <textarea name="description"></textarea>

            <label for="price">Price:</label>
            <input type="number" name="price" step="0.01" required>

            <div class="button-group">
                <!-- Nút Thêm -->
                <button type="submit" name="add_product" class="button add-button">Add Product</button>

                <!-- Nút Cập Nhật và Xoá -->
                <button type="submit" name="update_product" class="button update-button">Update</button>
                <button type="submit" name="delete_product" class="button delete-button">Delete</button>
            </div>
        </form>

        <!-- Product List -->
        <h3>Product List</h3>
        <?php
        if ($result->num_rows > 0) {
            echo "<table>";
            echo "<tr><th>ID</th><th>Name</th><th>Description</th><th>Price</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["name"] . "</td>";
                echo "<td>" . $row["description"] . "</td>";
                echo "<td>$" . $row["price"] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "No products found";
        }
        ?>
    </div>
</body>
</html>