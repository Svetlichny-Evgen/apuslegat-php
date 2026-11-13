<?php
require __DIR__ . '/auth.php';
adminLogout();
header('Location: index.php');
exit;