<?php

defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
define('CONFIG_PATH', '..' . DS . 'app' . DS);

if (file_exists(CONFIG_PATH . 'config.php'))
{
 	require_once CONFIG_PATH . 'config.php';
}
