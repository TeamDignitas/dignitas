<?php

$ids = Request::getJson('ids', []);
$data = [];

foreach ($ids as $id) {
  $t = Tag::get_by_id($id);

  if ($t) {
    $data[] = [
      'id' => $t->id,
      'text' => $t->value,
    ];
  }
}

header('Content-Type: application/json');
print json_encode($data);
