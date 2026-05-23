<?php
require_once 'config.php';

$event_id = isset($_GET['event_id']) ? (int)$_GET['event_id'] : 0;
$selected_event = null;
if ($event_id > 0) {
    $result = mysqli_query($connect, "SELECT * FROM events WHERE id = $event_id");
    if ($result && mysqli_num_rows($result) > 0) $selected_event = mysqli_fetch_assoc($result);
}

$events_list = [];
$events_result = mysqli_query($connect, "SELECT id, title, day, month, year, event_time FROM events ORDER BY year, day");
while ($row = mysqli_fetch_assoc($events_result)) $events_list[] = $row;

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $booking_event_id = (int)$_POST['event_id'];
    $event_title = mysqli_real_escape_string($connect, $_POST['event_title']);
    $sector = mysqli_real_escape_string($connect, $_POST['sector']);
    $seat_row = (int)$_POST['row'];
    $seat_number = (int)$_POST['seat'];
    $tickets_count = (int)$_POST['tickets_count'];
    $customer_name = mysqli_real_escape_string($connect, $_POST['fullname']);
    $phone = mysqli_real_escape_string($connect, $_POST['phone']);
    $email = mysqli_real_escape_string($connect, $_POST['email']);
    $comment = mysqli_real_escape_string($connect, $_POST['comment']);
    
    if (empty($customer_name) || empty($phone) || empty($email)) {
        $error = 'Заполните все поля';
    } else {
        $query = "INSERT INTO bookings (event_id, event_title, sector, seat_row, seat_number, tickets_count, customer_name, phone, email, comment) VALUES ($booking_event_id, '$event_title', '$sector', $seat_row, $seat_number, $tickets_count, '$customer_name', '$phone', '$email', '$comment')";
        if (mysqli_query($connect, $query)) {
            $_SESSION['booking_success'] = 'Бронирование успешно создано!';
            header('Location: schedule.php');
            exit();
        } else {
            $error = 'Ошибка: ' . mysqli_error($connect);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8"><title>Бронирование | Концертный зал "Аккорд"</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>...</header>
    <main>
        <section class="page-header"><div class="container"><h1>Бронирование билетов</h1></div></section>
        <section class="booking-form-section">
            <div class="container">
                <div class="booking-grid">
                    <div class="booking-form">
                        <?php if ($error): ?><div class="error-message"><?php echo $error; ?></div><?php endif; ?>
                        <form method="POST">
                            <div class="form-group"><label>Мероприятие</label>
                                <select name="event_id" required>
                                    <option value="">-- Выберите --</option>
                                    <?php foreach ($events_list as $event): ?>
                                        <option value="<?php echo $event['id']; ?>" <?php echo ($event_id == $event['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($event['title']) . ' (' . $event['day'] . ' ' . $event['month'] . ')'; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <input type="hidden" name="event_title" id="event_title">
                            <div class="form-group"><label>Сектор</label><select name="sector" required><option>Партер</option><option>Бельэтаж</option><option>Балкон</option><option>VIP ложа</option></select></div>
                            <div class="form-row"><div class="form-group half"><label>Ряд</label><input type="number" name="row" min="1" max="20" required></div>
                            <div class="form-group half"><label>Место</label><input type="number" name="seat" min="1" max="10" required></div></div>
                            <div class="form-group"><label>Количество билетов</label><input type="number" name="tickets_count" min="1" max="10" value="1" required></div>
                            <div class="form-group"><label>Ваше имя</label><input type="text" name="fullname" required></div>
                            <div class="form-row"><div class="form-group half"><label>Телефон</label><input type="tel" name="phone" required></div>
                            <div class="form-group half"><label>Email</label><input type="email" name="email" required></div></div>
                            <div class="form-group"><label>Комментарий</label><textarea name="comment" rows="3"></textarea></div>
                            <div class="form-group"><label class="checkbox-label"><input type="checkbox" name="agree" required> Согласен с условиями</label></div>
                            <button type="submit" class="btn-primary btn-block">Забронировать</button>
                        </form>
                    </div>
                    <div class="booking-info">...</div>
                </div>
            </div>
        </section>
    </main>
    <footer>...</footer>
    <script>const eventSelect=document.querySelector('select[name="event_id"]'),eventTitle=document.getElementById('event_title'),eventsData=<?php echo json_encode($events_list); ?>;eventSelect.addEventListener('change',function(){const id=parseInt(this.value),ev=eventsData.find(e=>e.id===id);if(ev)eventTitle.value=ev.title;});</script>
</body>
</html>