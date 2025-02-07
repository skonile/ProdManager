<?php
declare(strict_types = 1);

define("DIR_SEP", DIRECTORY_SEPARATOR);

# Project information
define('SOFTWARE_NAME', 'ProdManger');
define('SOFTWARE_VERSION', 'v0.0.1');
define('SOFTWARE_ITEM_LIMIT', 10);

# Path information
define('PATH_ROOT', dirname(dirname(__FILE__)));
define('URL_ROOT', '/');
define('VIEWS_PATH', PATH_ROOT . DIR_SEP . "views" . DIR_SEP);
define("PLUGINS_PATH", PATH_ROOT . DIR_SEP . "plugins");
define('UPLOADS_PATH', dirname(dirname(__FILE__)) . DIR_SEP . 'uploads' . DIR_SEP);
define('PRODUCT_IMAGES_PATH', UPLOADS_PATH . 'product-imgs' . DIR_SEP);
define('TMP_UPLOADED_FILES', UPLOADS_PATH . 'tmp' . DIR_SEP);

# Database information
define('DB_HOSTNAME', '');
define('DB_USERNAME', '');
define('DB_PASSWORD', '');
define('DB_NAME', '');