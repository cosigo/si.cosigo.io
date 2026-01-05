<?php
require __DIR__ . '/../_config/mail.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(403);
    exit;
}

$name    = trim($_POST["name"] ?? "");
$email   = trim($_POST["email"] ?? "");
$subject = trim($_POST["subject"] ?? "");
$message = trim($_POST["message"] ?? "");

if (!$name || !$email || !$subject || !$message) {
    exit("Missing required fields.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit("Invalid email address.");
}

try {
    $mail = cosigo_mailer();

    $mail->setFrom('sales@cosigo.io', 'Laredo Satellite');
    $mail->addAddress('sales@cosigo.io');
    $mail->addReplyTo($email, $name);

    $mail->Subject = "[Laredo Satellite] " . $subject;
    $mail->Body =
        "Name: $name\n" .
        "Email: $email\n\n" .
        $message;

    $mail->send();
    header("Location: /#admin");
    exit;

} catch (Exception $e) {
    error_log("Mail error: " . $e->getMessage());
    exit("Mail delivery failed.");
}

