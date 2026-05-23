<?php
require_once 'config.php';

$query = "SELECT * FROM events ORDER BY year, FIELD(month, 'Май', 'Июнь', 'Июль'), day";
$result = mysqli_query($connect, $query);
$events = [];

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $events[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Афиша | Концертный зал "Аккорд"</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo">
                <h1>Концертный зал "Аккорд"</h1>
                <p>Лучшая концертная площадка города</p>
            </div>
            <nav>
                <ul>
                    <li><a href="index.html">Главная</a></li>
                    <li><a href="schedule.php" class="active">Афиша</a></li>
                    <li><a href="hall.php">Зал</a></li>
                    <li><a href="contacts.php">Контакты</a></li>
                    <li><a href="booking.php">Бронирование</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section class="page-header">
            <div class="container">
                <h1>Афиша мероприятий</h1>
                <p>Выберите событие и забронируйте билеты</p>
                <?php if (isset($_SESSION['booking_success'])): ?>
                    <div class="success-message"><?php echo $_SESSION['booking_success']; unset($_SESSION['booking_success']); ?></div>
                <?php endif; ?>
            </div>
        </section>

        <section class="schedule">
            <div class="container">
                <div class="schedule-filter">
                    <button class="filter-btn active" data-filter="all">Все</button>
                    <button class="filter-btn" data-filter="classic">Классика</button>
                    <button class="filter-btn" data-filter="jazz">Джаз</button>
                    <button class="filter-btn" data-filter="rock">Рок</button>
                    <button class="filter-btn" data-filter="pop">Поп</button>
                </div>

                <div class="schedule-list">
                    <?php if (count($events) > 0): ?>
                        <?php foreach ($events as $event): ?>
                            <div class="schedule-item" data-type="<?php echo htmlspecialchars($event['event_type']); ?>">
                                <div class="schedule-date">
                                    <span class="day"><?php echo htmlspecialchars($event['day']); ?></span>
                                    <span class="month"><?php echo htmlspecialchars($event['month']); ?></span>
                                    <span class="year"><?php echo htmlspecialchars($event['year']); ?></span>
                                </div>
                                <div class="schedule-info">
                                    <h3><?php echo htmlspecialchars($event['title']); ?></h3>
                                    <p><?php echo htmlspecialchars($event['description']); ?></p>
                                    <div class="schedule-details">
                                        <span>Начало: <?php echo date('H:i', strtotime($event['event_time'])); ?></span>
                                        <span>Цена: от <?php echo number_format($event['price_from'], 0, '', ' '); ?> руб.</span>
                                    </div>
                                </div>
                                <div class="schedule-action">
                                    <a href="booking.php?event_id=<?php echo $event['id']; ?>" class="btn-small">Забронировать</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="no-events">На данный момент нет запланированных мероприятий.</div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="footer-container">
            <div class="footer-info">
                <p><strong>Концертный зал "Аккорд"</strong></p>
                <p>г. Стерлитамак, ул. Ленина, д. 15</p>
                <p>Телефон: +7 (495) 123-45-67</p>
                <p>Email: info@akkord.ru</p>
            </div>
            <div class="footer-hours">
                <p><strong>Часы работы кассы:</strong></p>
                <p>Пн-Пт: 10:00 - 20:00</p>
                <p>Сб-Вс: 11:00 - 19:00</p>
            </div>
            <div class="footer-links">
                <p><strong>Быстрые ссылки:</strong></p>
                <p><a href="index.html">Главная</a></p>
                <p><a href="schedule.php">Афиша</a></p>
                <p><a href="hall.php">О зале</a></p>
                <p><a href="contacts.php">Контакты</a></p>
                <p><a href="booking.php">Бронирование</a></p>
            </div>
            <div class="footer-copyright">
                <p>&copy; 2026 Концертный зал "Аккорд"</p>
                <p>Все права защищены</p>
            </div>
        </div>
    </footer>

    <script>
        const filterBtns = document.querySelectorAll('.filter-btn');
        const scheduleItems = document.querySelectorAll('.schedule-item');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const filter = btn.dataset.filter;
                filterBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                scheduleItems.forEach(item => {
                    if (filter === 'all' || item.dataset.type === filter) {
                        item.style.display = 'flex';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    </script>
</body>
</html>