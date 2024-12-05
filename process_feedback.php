<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $feedback = $_POST['feedback'];

    if (empty($name) || empty($email) || empty($feedback)) {
        die("All fields are required.");
    }

    $conn = new mysqli('localhost', 'root', '', 'feedback_system');

    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO feedback (name, email, feedback) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $feedback);
    $stmt->execute();

    echo "Feedback submitted successfully!";
    $stmt->close();
    $conn->close();
}
?>
