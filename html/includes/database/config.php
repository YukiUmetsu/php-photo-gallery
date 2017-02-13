<?php

// define("DB_SERVER", getenv('localhost'));
// define("DB_USER", getenv('root'));
// define("DB_PASS", getenv('pass'));
// define("DB_NAME", getenv('php-gallery'));

defined("DB_SERVER") ? null : define("DB_SERVER", getenv("DB_SERVER"));
defined("DB_USER") ? null : define("DB_USER", getenv("DB_USER"));
defined("DB_PASS") ? null : define("DB_PASS", getenv("DB_PASS"));
defined("DB_NAME") ? null : define("DB_NAME", getenv("DB_NAME"));