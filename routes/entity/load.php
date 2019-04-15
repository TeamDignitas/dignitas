<?php

$ids = Request::getJson('ids', []);
$data = [];

foreach ($ids as $id) {
  $e = Entity::get_by_id($id);

  if ($e) {
    $data[] = [
      'id' => $e->id,
      'text' => $e->name,
    ];
  }
}

header('Content-Type: application/json');
print json_encode($data);
