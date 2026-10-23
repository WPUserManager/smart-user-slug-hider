<?php

/**
 * Plugin Foundation Loader
 *
 * Peter's Plugins Foundation 09
 *
 * The class prefix was PPF09_ until October 2026. Other plugins (404page, and
 * Peter's other plugins) ship their own, different copies of PPF09, and whichever
 * loaded first supplied the classes for all of them. The WP User Manager copy
 * has its own prefix so its changes always apply.
 *
 * @package    WPUMPPF1
 * @author     Peter Raschendorfer
 * @license    GPL2+
 */
 
require_once ( __DIR__ . '/ppf-class.php' );
require_once ( __DIR__ . '/ppf-subclass.php' );
require_once ( __DIR__ . '/ppf-plugin.php' );
require_once ( __DIR__ . '/ppf-settings.php' );
require_once ( __DIR__ . '/ppf-admin.php' );

require_once ( __DIR__ . '/ppf-plugin-addon.php' );

?>