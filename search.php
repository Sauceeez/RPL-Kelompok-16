<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pc_store";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$budget = $_GET['budget'] ?? 0;

$sql = "SELECT * FROM products WHERE price <= ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("d", $budget);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search | PC Component Store</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="index.php">PC Store</a>
    <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="store.php">Store</a></li>
        <li class="nav-item"><a class="nav-link" href="search.php">Search</a></li>
        <li class="nav-item"><a class="nav-link" href="about.php">About Us</a></li>
    </ul>
</nav>

    <div class="container">
        <h2 class="my-4">Search Products by Budget</h2>
        <form action="search.php" method="get">
            <label for="budget">Enter your budget ($):</label>
            <input type="number" name="budget" class="form-control" placeholder="Enter your budget" value="<?php echo $budget; ?>" required>
            <button type="submit" class="btn btn-primary my-3">Search</button>
        </form>

        <div class="row">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <div class="col-md-4">
                        <div class="card">
                            <img src="uploads/<?php echo $row['image']; ?>" class="card-img-top" alt="<?php echo $row['name']; ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $row['name']; ?></h5>
                                <p class="card-text">$<?php echo $row['price']; ?></p>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<p>No products found within your budget.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
