<?php

$locale = Request::get('locale');
$name = Request::get('name');

if ($locale == StaticResource::ALL_LOCALE) {
  $locale = '';
}

$sr = StaticResource::get_by_locale_name($locale, $name);

if ($sr) {
  $sr->render();
} else {
  http_response_code(404);
}
