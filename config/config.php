<?php
declare(strict_types = 1);

define("DIR_SEP", DIRECTORY_SEPARATOR);

# Project information
define('SOFTWARE_NAME', 'ProdManager');
define('SOFTWARE_VERSION', 'v0.0.1');
define('SOFTWARE_ITEM_LIMIT', 10);

# Path information
define('PATH_ROOT', dirname(__DIR__));
define('URL_ROOT', '/');
define('VIEWS_PATH', PATH_ROOT . DIR_SEP . "views" . DIR_SEP);
define("PLUGINS_PATH", PATH_ROOT . DIR_SEP . "plugins");
define('UPLOADS_PATH', PATH_ROOT . DIR_SEP . 'uploads' . DIR_SEP);
define('PRODUCT_IMAGES_PATH', UPLOADS_PATH . 'product-imgs' . DIR_SEP);
define('TMP_UPLOADED_FILES', UPLOADS_PATH . 'tmp' . DIR_SEP);
define('TMP_FILES', PATH_ROOT . DIR_SEP . 'tmp' . DIR_SEP);

# Database and API information
require_once PATH_ROOT . DIR_SEP . 'config' . DIR_SEP . 'database.php';
require_once PATH_ROOT . DIR_SEP . 'config' . DIR_SEP . 'api.php';