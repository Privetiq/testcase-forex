<?php
/**
 * @var string $color  Мы получили после парсинга скриптом HeikinAshiColor.php
 */
$email = 'yurius86@gmail.com';
$name = 'Nikita Bilanenko';
$body = "<p style='background-color: $color'>$color</p>";
$subject = 'Test Case Bilanenko';
$headers = array(
    'Authorization: Bearer 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',
    'Content-Type: application/json'
);

$data = array(
    'personalizations' => array(
        array(
            'to' => array(
                array(
                    'email' => $email,
                    'name'  => $name
                )
            )
        )
    ),
    'from' => array(
        'email' => 'nbilanenko@gmail.com'
    ),
    'subject' => $subject,
    'content' => array(
        array(
            'type'  => 'text/html',
            'value' => $body
        )
    )
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.sendgrid.com/v3/mail/send');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$response = curl_exec($ch);
curl_close($ch);
