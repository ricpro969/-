<?php
require_once 'config.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($connect, $_POST['name']);
    $email = mysqli_real_escape_string($connect, $_POST['email']);
    $phone = mysqli_real_escape_string($connect, $_POST['phone']);
    $subject = mysqli_real_escape_string($connect, $_POST['subject']);
    $message = mysqli_real_escape_string($connect, $_POST['message']);
    
    if (empty($name) || empty($email) || empty($message)) {
        $error = 'Пожалуйста, заполните обязательные поля';
    } else {
        $query = "INSERT INTO contacts (name, email, phone, subject, message) VALUES ('$name', '$email', '$phone', '$subject', '$message')";
        if (mysqli_query($connect, $query)) {
            $success = 'Сообщение отправлено! Мы свяжемся с вами.';
        } else {
            $error = 'Ошибка: ' . mysqli_error($connect);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Контакты | Концертный зал "Аккорд"</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>...</header>
    <main>
        <section class="page-header"><div class="container"><h1>Контакты</h1><p>Как нас найти и связаться с нами</p></div></section>
        <section class="contacts">
            <div class="container">
                <?php if ($success): ?><div class="success-message"><?php echo $success; ?></div><?php endif; ?>
                <?php if ($error): ?><div class="error-message"><?php echo $error; ?></div><?php endif; ?>
                <div class="contacts-grid">
                    <div class="contacts-info">
                        <div class="contact-block"><h3>Адрес</h3><p>г. Стерлитамак, ул. Ленина, д. 15</p></div>
                        <div class="contact-block"><h3>Телефоны</h3><p>Касса: +7 (495) 123-45-67</p><p>Администрация: +7 (495) 123-45-68</p></div>
                        <div class="contact-block"><h3>Email</h3><p>info@akkord.ru</p><p>booking@akkord.ru</p></div>
                    </div>
                    <div class="contacts-form">
                        <h3>Написать нам</h3>
                        <form method="POST">
                            <div class="form-group"><input type="text" name="name" placeholder="Ваше имя" required></div>
                            <div class="form-group"><input type="email" name="email" placeholder="Email" required></div>
                            <div class="form-group"><input type="tel" name="phone" placeholder="Телефон"></div>
                            <div class="form-group"><input type="text" name="subject" placeholder="Тема"></div>
                            <div class="form-group"><textarea name="message" rows="4" placeholder="Сообщение" required></textarea></div>
                            <button type="submit" class="btn-primary">Отправить</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <footer>...</footer>
</body>
</html>