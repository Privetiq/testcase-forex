<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ttest case | Bilanenko N.S.</title>
</head>
<body>
<h1>Тестовое задание от Биланенко Н.С.</h1>
<form action="HeikinAshiColor.php" method="GET">
    <label for="stock">Введите название компании (например TQQQ)</label><br>
    <input type="text" name="stock" id="stock" value="TQQQ"><br>
    <?php if (isset($_GET['error'])) : ?>
        <p class="error_msg">
        <?php
        switch($_GET['error']) {
            case 'symbols_val':
                echo 'Доступны только цыфры от 0 до 9 и латинские буквы';
                break;
            case 'symbols_count':
                echo 'Введите от 2 до 25 символов латинского алфавита! Допускаются цырфы.';
                break;
            default:
                echo 'Ошибка! ' . $_GET['error'];
        } ?>
        </p>
    <?php endif ?>
    <button type="submit">Узнать цвет!</button>
</form>
</body>
</html>

