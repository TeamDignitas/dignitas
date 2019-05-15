<?php

$id = Request::get('id');
$fileName = Request::get('fileName');

Img::renderThumb('Entity', $id, $fileName);
