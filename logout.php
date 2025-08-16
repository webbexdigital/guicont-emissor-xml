<?php

session_start();

session_destroy();

require_once 'vendor/autoload.php';

header("Location: ".url()." ");