<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * config.php
 *
 * Author: pixelcave
 *
 * Configuration file. It contains variables used in the template as well as the primary navigation array from which the navigation is created
 *
 */

/* Template variables */
$config['template'] = array(
	'name' => !empty($_ENV['APP_NAME']) ? $_ENV['APP_NAME'] : "Vuex + ci3 template",
	'version' => !empty($_ENV['APP_VERSION']) ? $_ENV['APP_VERSION'] : "1.0",
	'author' => !empty($_ENV['APP_AUTHOR']) ? $_ENV['APP_AUTHOR'] : "hao.nguyen",
	'robots' => 'noindex, nofollow',
	'title' => !empty($_ENV['APP_TITLE']) ? $_ENV['APP_TITLE'] : "Vuex + ci3 template",
	'description' => !empty($_ENV['APP_DESC']) ? $_ENV['APP_DESC'] : "Vuex + ci3 template by hao.nguyen",
	
	// 'active_page' => substr(empty(uri_string()) ? uri_string() : '/', 1),
);

/* Primary navigation array */
$config['primary_nav'] = array(
    array(
        'name'  => 'Home',
        'url'   => 'home',
        'icon'  => 'home'
    ),
    array(
        'name' => 'demo 2lv menu',
        'url' => 'header'
    ),
    array(
        'name'  => 'Menu lv1',
        'icon' => 'copy',
        'sub' => array(
            array(
                'name'  => 'Menu 1st',
                'url'   => 'menu/test1',
                'icon'  => 'box'
            ),
            array(
                'name'  => 'Menu 2nd',
                'url'   => 'menu/2nd',
                'icon'  => 'box'
            ),
            array(
                'name' => 'Menu lv2',
                'icon' => 'copy',
                'sub' => array(
                    array(
                        'name' => 'Menu lv3',
                        'url' => 'menu/3nd/test',
                        'icon' => 'copy'
                    )
                )
            )
        )
    )
);
