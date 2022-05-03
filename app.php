<?php
require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
$dotenv->load();

require_once __DIR__ . '/libs/User.php';
require_once __DIR__ . '/libs/RifNews.php';
require_once __DIR__ . '/libs/NewsItem.php';

session_start();

const NEWS_PER_PAGE = 4;

$user = new User();






