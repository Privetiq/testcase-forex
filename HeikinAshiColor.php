<?php
//Очистка полученых данных
$form_field = $_GET['stock'];
$form_field = trim($form_field);
$form_field = stripslashes($form_field);
$form_field = strip_tags($form_field);
$form_field = htmlspecialchars($form_field);
//проверка символов на валидность
if (!preg_match("/^[0-9a-zA-Z]*$/",$form_field)) {
    header('Location: /?error=symbols_val');
    die();
}
//проверка на количество символов
if (mb_strlen($form_field) < 2 || mb_strlen($form_field) > 25) {
    header('Location: /?error=symbols_count');
    die();
}
//если проверки прошли, значаит у нас в инпуте от 2 до 25 символов [0-9a-zA-Z]. Зададим строку которую будем парсить.
$parse_url = 'https://finance.yahoo.com/quote/' . $form_field . '/history?p=' . $form_field . '&.tsrc=fin-srch';

//$html = file_get_contents($parse_url); //можно еще так получить HTML но я предпочитаю cURL
$parse_request = curl_init();
curl_setopt($parse_request, CURLOPT_URL, $parse_url);
curl_setopt($parse_request, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($parse_request, CURLOPT_TIMEOUT, 10);

$html    = curl_exec($parse_request);
$err     = curl_errno($parse_request);
$errmsg  = curl_error($parse_request);
$header  = curl_getinfo($parse_request);
curl_close($parse_request);

if ($err > 0) { //если при парсинге была ошибка, вернём её. Но на Yahoo почти всегда 200. Эта заплатка на случай отсутствия интернета например.
    echo '<p>К сожалению спарсить не вышло.</p>';
    echo '<a href="/" class="logo">На главную</a><br>';
    die($errmsg);
}

include_once 'phpQuery.php'; //Если спарсили без ошибок, подключаем phpQuery

$document = phpQuery::newDocument($html); //Создаём документ phpQuery
$document_table = $document->find('#Col1-1-HistoricalDataTable-Proxy tbody'); //ищем в нём нужную таблицу

$row_1_tds = $document_table->find('tr:first-child td')->elements; //берём первый row, и выбираем в нём все ячейки td
$row_2_tds = $document_table->find('tr:nth-child(2) td')->elements; //берём второй row

//Ну а теперь у нас есть 2 массива, которые хранят значения ячеек (как обьекты! Нас интересует nodeValue). Тут нужно быть аккуратнее, значения в массивах - string!
$nao = ((float)$row_2_tds[1]->nodeValue + (float)$row_2_tds[3]->nodeValue) / 2; //Сначала берём вторую строку (вчерашний день) выщитываем по формуле из ТЗ
$nas = ((float)$row_1_tds[1]->nodeValue + (float)$row_1_tds[2]->nodeValue + (float)$row_1_tds[3]->nodeValue + (float)$row_1_tds[4]->nodeValue) / 4; //Вторая формула для первого ряда
//Завершающий этап
if ($nas > $nao) {
    $color = 'green';
    echo $color;
    require_once 'sendmail.php';
} elseif ($nas < $nao) {
    $color = 'red';
    echo $color;
    require_once 'sendmail.php';
} else { //Если значения равны - будет коричневый :D
    $color = 'brown';
    echo $color;
    require_once 'sendmail.php';
}
