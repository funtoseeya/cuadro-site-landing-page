<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $to = "matias.fontecilla@hotmail.com";
    $subject = "New User";
    $message = "New early access user email: " . $email;
    $headers = "From: noreply@cuadro.io";

    if (mail($to, $subject, $message, $headers)) {
        http_response_code(200); // Success response
    } else {
        http_response_code(500); // Server error response
    }
} else {
    http_response_code(403); // Forbidden if accessed without POST
}
?>
