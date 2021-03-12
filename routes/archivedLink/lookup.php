<?php

$url = Request::get('url');

$al = ArchivedLink::get_by_url_status($url, ArchivedLink::STATUS_ARCHIVED);

$archivedUrl = $al ? $al->getArchivedUrl() : false;

$resp = [
  'archivedUrl' => $archivedUrl,
];

header('Content-Type: application/json');
print json_encode($resp);
