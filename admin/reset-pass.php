<?php
$newPassword = 'admin123';
$hash = password_hash($newPassword, PASSWORD_DEFAULT);
$data = [
    'password_hash' => $hash,
    'installed_at'  => date('c')
];
file_put_contents(__DIR__ . '/config.json', json_encode($data, JSON_PRETTY_PRINT));
echo "Пароль успешно сброшен на: <b>$newPassword</b><br>";
echo "Новый хеш: <code>$hash</code><br>";
echo "<a href='index.php'>Перейти к входу</a>";