<?php
$db = new PDO('sqlite:' . __DIR__ . '/../database/database.sqlite');
echo "TERMS:\n";
foreach ($db->query('SELECT id, name, level_id, `order` FROM terms') as $r) {
    echo "  id={$r[0]} name={$r[1]} level={$r[2]} order={$r[3]}\n";
}
