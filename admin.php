<?php
require_once 'config.php';
if (!isset($_SESSION['admin_logged'])) {
    if ($_SERVER['REQUEST_METHOD']=='POST' && $_POST['admin_password']=='admin123') $_SESSION['admin_logged']=true;
    else { echo '<form method="POST"><input type="password" name="admin_password"><button type="submit">Войти</button></form>'; exit(); }
}
if (isset($_POST['add_event'])) {
    mysqli_query($connect, "INSERT INTO events (title, day, month, year, event_time, price_from, event_type, description) VALUES ('{$_POST['title']}',{$_POST['day']},'{$_POST['month']}',{$_POST['year']},'{$_POST['event_time']}',{$_POST['price_from']},'{$_POST['event_type']}','{$_POST['description']}')");
}
if (isset($_GET['delete_id'])) mysqli_query($connect, "DELETE FROM events WHERE id={$_GET['delete_id']}");
$events = mysqli_query($connect, "SELECT * FROM events ORDER BY year, day");
?>
<!DOCTYPE html><html><head><title>Админ-панель</title><link rel="stylesheet" href="style.css"></head>
<body><div class="container"><h1>Админ-панель</h1>
<h2>Добавить мероприятие</h2>
<form method="POST"><div class="form-row"><input type="text" name="title" placeholder="Название" required><select name="event_type"><option value="classic">Классика</option><option value="jazz">Джаз</option><option value="rock">Рок</option><option value="pop">Поп</option></select></div>
<textarea name="description" placeholder="Описание" rows="3"></textarea>
<div class="form-row"><input type="number" name="day" placeholder="День" required><select name="month"><option>Май</option><option>Июнь</option><option>Июль</option></select><input type="number" name="year" placeholder="Год" value="2026"><input type="time" name="event_time"><input type="number" name="price_from" placeholder="Цена"></div>
<button type="submit" name="add_event">Добавить</button></form>
<h2>Список мероприятий</h2>
<table border="1"><tr><th>ID</th><th>Название</th><th>Дата</th><th>Время</th><th>Цена</th><th>Действия</th></tr>
<?php while($row=mysqli_fetch_assoc($events)): ?><tr><td><?=$row['id']?></td><td><?=$row['title']?></td><td><?=$row['day'].' '.$row['month']?></td><td><?=$row['event