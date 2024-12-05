<?php

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'feedback_system');

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Initialize variables
$total_feedback = 0;
$search = '';
$result = null;

// Count total feedback entries
$query_total = "SELECT COUNT(*) as total FROM feedback";
$result_total = $conn->query($query_total);

if ($result_total && $row = $result_total->fetch_assoc()) {
    $total_feedback = $row['total'];
}

// Check if a search query was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['search'])) {
    $search = $conn->real_escape_string($_POST['search']);
    $query_feedback = "SELECT * FROM feedback WHERE name LIKE '%$search%' OR email LIKE '%$search%' OR feedback LIKE '%$search%'";
} else {
    // Default query to fetch all feedback
    $query_feedback = "SELECT * FROM feedback";
}

// Execute the query
$result = $conn->query($query_feedback);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Reports</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Feedback Reports</h1>

    <!-- Display total feedback count -->
    <p><strong>Total Feedback Entries:</strong> <?php echo $total_feedback; ?></p>

    <!-- Search form -->
    <form method="POST" action="reports.php">
        <label for="search">Search Feedback:</label>
        <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Enter a name, email, or feedback">
        <button type="submit">Filter</button>
    </form>

    <!-- Display feedback table -->
    <h2>Feedback Records</h2>
    <?php if ($result && $result->num_rows > 0): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Feedback</th>
                    <th>Submitted At</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['feedback']); ?></td>
                        <td><?php echo $row['submitted_at']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No feedback records found.</p>
    <?php endif; ?>

    <?php
    // Close the connection
    $conn->close();
    ?>
</body>
</html>
