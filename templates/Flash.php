<?php foreach ($flash->getMessage('errors') as $message): ?>
    <br><?= html($message) ?>
<?php endforeach; ?>