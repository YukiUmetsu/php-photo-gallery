<?php

// define the core paths
defined('DS') ? : define('DS', DIRECTORY_SEPARATOR);
defined('SITE_ROOT') ? null: define('SITE_ROOT', DS.'var'.DS.'www'.DS.'html');
defined('LIB_PATH') ? null: define('LIB_PATH', SITE_ROOT.DS.'includes'.DS);

require_once(LIB_PATH."database/config.php");
require_once(LIB_PATH."functions.php");
require_once(LIB_PATH."session.php");
require_once(LIB_PATH."database".DS."database.php");
require_once(LIB_PATH."pagination.php");
require_once(LIB_PATH."model".DS."user.php");
require_once(LIB_PATH."model".DS."admin.php");
require_once(LIB_PATH."model".DS."image.php");
require_once(LIB_PATH."model".DS."comment.php");
