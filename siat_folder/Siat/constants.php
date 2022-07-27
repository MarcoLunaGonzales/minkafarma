<?php
define('MOD_SIAT_VER', '1.0.4');
defined('MOD_SIAT_DIR') or define('MOD_SIAT_DIR', dirname(__FILE__));
defined('SB_DS') or define('SB_DS', DIRECTORY_SEPARATOR);
define('MOD_SIAT_TEMP_DIR', MOD_SIAT_DIR . SB_DS . 'temp');

if( !is_dir(MOD_SIAT_TEMP_DIR) )
	mkdir(MOD_SIAT_TEMP_DIR);