<?php

$id = Request::get('id');
$fileName = Request::get('fileName');

Img::renderThumb('User', $id, $fileName);
