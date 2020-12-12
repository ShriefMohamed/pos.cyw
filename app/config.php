<?php

use Framework\Lib\AbstractModel;
use Framework\Lib\Database;
use Framework\Lib\FrontController;
use Framework\Lib\Session;


ob_start();

// set displaying error to 1 (1 display) (0 don't display)
ini_set('display_errors', 1);
ini_set('session.cookie_httponly', 1);

ini_set("xdebug.var_display_max_children", -1);
ini_set("xdebug.var_display_max_data", -1);
ini_set("xdebug.var_display_max_depth", -1);

// define some of the necessary directories and paths so it be easier later to call them
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

defined('APP_PATH') ? null : define('APP_PATH', realpath(dirname(__file__)) .DS);
defined('HOST_NAME') ? null : define('HOST_NAME', 'http://' . $_SERVER['HTTP_HOST'] . '/');
defined('CURRENT_URI') ? null : define('CURRENT_URI', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
defined('WEBSITE_NAME') ? null : define('WEBSITE_NAME', 'Compute Your World');


define('LIB_PATH', APP_PATH . 'lib' .DS);
define('MODELS_PATH', APP_PATH . 'models' .DS);
define('CONTROLLERS_PATH', APP_PATH . 'controllers' .DS);
define('VIEWS_PATH', APP_PATH . 'views' .DS);
define('TEMPLATE_PATH', VIEWS_PATH . '_template' .DS);
define('PARTIALS_PATH', VIEWS_PATH . '_partials' .DS);

define('SESSIONS_PATH', APP_PATH . 'sessions' . DS);

define('LOGS_PATH', APP_PATH . 'logs' . DS);

define('PUBLIC_PATH', APP_PATH . '..' . DS . 'public' . DS);
//define('PUBLIC_PATH', APP_PATH . '..' . DS . 'public_html' . DS);
define('IMAGES_PATH', PUBLIC_PATH . 'images' .DS);
define('ATTACHMENTS_PATH', PUBLIC_PATH . 'job_attachments' .DS);
define('INSURANCE_REPORTS_PATH', PUBLIC_PATH . 'insurance_reports' .DS);
define('SALES_RECEIPTS_PATH', PUBLIC_PATH . 'sales_receipts/');
define('SIGNATURES_PATH', PUBLIC_PATH . 'signatures' .DS);
define('LEADER_DATAFEED_PATH', PUBLIC_PATH . 'leader_datafeed' .DS);
define('QUOTES_PATH', PUBLIC_PATH . 'quotes_docs/');
define('QUOTES_PO_PATH', PUBLIC_PATH . 'quotes_po/');
define('EMAIL_TEMPLATES_PATH', APP_PATH . 'templates' . DS);
define('DIGITAL_LICENCES_TEMPLATES_PATH', EMAIL_TEMPLATES_PATH . 'digital-licenses-templates' . DS);


define('PUBLIC_DIR', HOST_NAME);
define('CSS_DIR', PUBLIC_DIR . 'stylesheets/');
define('JS_DIR', PUBLIC_DIR . 'javascript/');
define('IMAGES_DIR', PUBLIC_DIR . 'images/');
define('EMAIL_IMAGES_DIR', PUBLIC_DIR . 'images/email_images');
define('ATTACHMENTS_DIR', PUBLIC_DIR . 'job_attachments/');
define('INSURANCE_REPORTS_DIR', PUBLIC_DIR . 'insurance_reports/');
define('SALES_RECEIPTS_DIR', PUBLIC_DIR . 'sales_receipts/');
define('QUOTES_DIR', PUBLIC_DIR . 'quotes_docs/');
define('QUOTES_PO_DIR', PUBLIC_DIR . 'quotes_po/');
define('SIGNATURES_DIR', PUBLIC_DIR . 'signatures/');
define('VENDOR_DIR', PUBLIC_DIR . 'vendor/');



// define server timestamp & DateTime format
defined('SERVER_TIMESTAMP') ? null : define('SERVER_TIMESTAMP', $_SERVER['REQUEST_TIME']);

defined('DATE_TIME_FORMAT') ? null : define('DATE_TIME_FORMAT', 'd-m-Y h:i A');
defined('DATE_FORMAT') ? null : define('DATE_FORMAT', 'd-m-Y');
defined('TIME_FORMAT') ? null : define('TIME_FORMAT', 'H:i:s');

// define encryption key and algorithm for openssl & hash_hmac
define('CIPHER_KEY', '?$@MK"<2B;V\)*#*');
define('CIPHER_ALGORITHM', 'aes-128-cbc');
define('HMAC_ALGORITHM', 'sha256');
define('HMAC_KEY', '?$@MK"<2B;V\)*#*');


define('XERO_KEY', '58E07B039E6A4260B59410193BFBD6C0');
define('XERO_SEC', '77r3qbwunPpSm1nR1MUUTUk8QfZnrbwwJPx-IZWWBkOFg7GH');
define('XERO_REDIRECT_URI', 'https://jobs.cyw.net.au/xero/callback');

define('NETO_API_USERNAME', 'shrief');
define('NETO_API_KEY', 'Axhjwx2pVkgsjGEB6KHEorIYFawG7tNZ');


// Mobile API Key
define('MOBILE_API_KEY', '99e55cf9-81a0-4fe4-a2d1-7efc51f0b33c');

// define the database credentials to use later at the database class
define('DB_HOST', 'localhost');
define('DB_NAME', 'cyworld_jobs');

define('DB_USER', 'root');
define('DB_PASSWORD', 'root');

//define('DB_USER', 'cyworld_db');
//define('DB_PASSWORD', 'kao45h4rAeF!');

// define SMTP configuration
define('SMTP_SERVER', 'exchange');
define('SMTP_BACKUP_SERVER', 'exchange');
define('SMTP_USERNAME', 'username');
define('SMTP_PASSWORD', 'smtp');

//define('SMTP_SERVER', 'exchange.cyw.net.au');
//define('SMTP_BACKUP_SERVER', 'exchange.cyw.net.au');
//define('SMTP_USERNAME', 'ticket@computeyourworld.com.au');
//define('SMTP_PASSWORD', 'Ticket@Compute!2100.');

define('SMTP_ENCRYPTION', 'tls');
define('SMTP_PORT', 587);

define('CONTACT_EMAIL', 'support@computeyourworld.com.au');
define('CONTACT_NAME', 'Compute Your World');



// require autoload so the Classes get called automatically without the need of "require" or "include"
if (file_exists(APP_PATH . DS . 'lib' . DS . 'autoload.php')) {
	require_once APP_PATH . DS . 'lib' . DS . 'autoload.php';
}

// start the session for the whole website so we can use $_SESSION anywhere in the website without starting it again
$session = new Session();
$session->Initiate();

// put the database connection in the AbstractModel class
// the abstract model is the main model that all the other model classes will extend,
// and the models are the only classes that interacts with the database so by adding the database connection at
// the abstract model then every model class will have access to the database connection
AbstractModel::$db = Database::CreateConnection();

// call the FrontController class which is the class that will take the url and then require the right files and classes
$FrontController = new FrontController;

ob_end_flush();
