<?php

use Template\Template;
require 'vendor/autoload.php';

$template = new Template();

$a = 1;

$template->view('index', compact('a'));