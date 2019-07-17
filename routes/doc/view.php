<?php

$fileName = Request::get('fileName');

Doc::render($fileName);
