<?php
// database
$db_host = "127.0.0.1:3306";
$db_user = "root";
$db_pass = "root";
$db_name = "books";

// For development or For production
$GLOBALS['JS_DEBUG'] = true;

/*themes:
  default        - seajs and lesscss
  seajs_standard - standard seajs without lesscss
  seajs_custom   - customseajs without lesscss
*/
$theme = 'default';

// SITE_DOMAIN
defined('SITE_DOMAIN') || define('SITE_DOMAIN', 'books.com');

?>