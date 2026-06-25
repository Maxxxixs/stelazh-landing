<?php
declare(strict_types=1);

$config = require __DIR__ . '/config.php';
$token = trim((string) ($config['bot_token'] ?? ''));

header('Content-Type: text/html; charset=utf-8');

if ($token === '') {
    echo '<p>Спочатку додайте bot_token у config.php</p>';
    exit;
}

$url = 'https://api.telegram.org/bot' . $token . '/getUpdates';
$response = @file_get_contents($url);
$data = json_decode((string) $response, true);
?>
<!DOCTYPE html>
<html lang="uk">
<head>
  <meta charset="UTF-8">
  <title>Налаштування Telegram chat_id</title>
</head>
<body>
  <h1>Налаштування chat_id</h1>
  <ol>
    <li>Напишіть вашому боту повідомлення <strong>/start</strong> у Telegram.</li>
    <li>Оновіть цю сторінку.</li>
    <li>Скопіюйте <strong>chat_id</strong> у <code>config.php</code>.</li>
    <li>Видаліть цей файл з хостингу після налаштування.</li>
  </ol>
<?php if (!empty($data['ok']) && !empty($data['result'])): ?>
  <ul>
    <?php foreach ($data['result'] as $update): ?>
      <?php
        $chat = $update['message']['chat'] ?? $update['callback_query']['message']['chat'] ?? null;
        if (!$chat) continue;
      ?>
      <li>
        <strong><?= htmlspecialchars((string) ($chat['first_name'] ?? 'Chat'), ENT_QUOTES, 'UTF-8') ?></strong>
        — chat_id: <code><?= htmlspecialchars((string) $chat['id'], ENT_QUOTES, 'UTF-8') ?></code>
      </li>
    <?php endforeach; ?>
  </ul>
<?php else: ?>
  <p>Повідомлень поки немає. Напишіть боту /start і оновіть сторінку.</p>
<?php endif; ?>
</body>
</html>
