<?php
// Configuration
$to = "abderr94600@gmail.com";
$subject = "Contact Portfolio - " . date('d/m/Y H:i');

// Sécurité anti-injection
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Vérification méthode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Récupération et nettoyage des données
    $name = sanitize_input($_POST['name']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $message = sanitize_input($_POST['message']);
    
    // Validation email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: index.php?error=invalid_email");
        exit();
    }
    
    // Validation longueur message
    if (strlen($message) < 10) {
        header("Location: index.php?error=message_too_short");
        exit();
    }
    
    // Construction du message
    $email_body = "Nouveau message depuis le portfolio\n\n";
    $email_body .= "Nom: $name\n";
    $email_body .= "Email: $email\n\n";
    $email_body .= "Message:\n$message\n";
    
    // Headers
    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    // Envoi
    if (mail($to, $subject, $email_body, $headers)) {
        header("Location: index.php?success=1");
    } else {
        header("Location: index.php?error=send_failed");
    }
    
} else {
    // Redirection si accès direct
    header("Location: index.php");
}
exit();
?>