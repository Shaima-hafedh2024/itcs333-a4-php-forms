<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/validate.php';

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

$name = '';
$email = '';
$message = '';

$errors = [
    'name' => '',
    'email' => '',
    'message' => ''
];

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {

    $name = isset($_POST['name']) && is_string($_POST['name'])
        ? $_POST['name'] : '';

    $email = isset($_POST['email']) && is_string($_POST['email'])
        ? $_POST['email'] : '';

    $message = isset($_POST['message']) && is_string($_POST['message'])
        ? $_POST['message'] : '';

    if (!validate_required($name)) {
        $errors['name'] = 'Name is required.';
    } elseif (!validate_length(trim($name), 2, 60)) {
        $errors['name'] = 'Name must be between 2 and 60 characters.';
    }

    if (!validate_email(trim($email))) {
        $errors['email'] = 'Please enter a valid email address.';
    }

    if (!validate_required($message)) {
        $errors['message'] = 'Message is required.';
    } elseif (!validate_length(trim($message), 10, 1000)) {
        $errors['message'] = 'Message must be between 10 and 1000 characters.';
    }

    if (
        $errors['name'] === '' &&
        $errors['email'] === '' &&
        $errors['message'] === ''
    ) {
        header('Location: success.php', true, 303);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Us</title>
</head>
<body>

    <h1>Contact Us</h1>

    <form method="post" action="index.php" novalidate>

        <label for="name">Name</label><br>
        <input
            type="text"
            id="name"
            name="name"
            value="<?= e($name) ?>"
        >
        <?php if ($errors['name'] !== ''): ?>
            <p class="error"><?= e($errors['name']) ?></p>
        <?php endif; ?>
        <br>

        <label for="email">Email</label><br>
        <input
            type="email"
            id="email"
            name="email"
            value="<?= e($email) ?>"
        >
        <?php if ($errors['email'] !== ''): ?>
            <p class="error"><?= e($errors['email']) ?></p>
        <?php endif; ?>
        <br>

        <label for="message">Message</label><br>
        <textarea id="message" name="message"><?= e($message) ?></textarea>
        <?php if ($errors['message'] !== ''): ?>
            <p class="error"><?= e($errors['message']) ?></p>
        <?php endif; ?>
        <br>

        <button type="submit">Send</button>

    </form>

</body>
</html>
