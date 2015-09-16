<?php

/**
 * Name: Sportlog
 * Description: to track your sportive activities
 * Version: 1.0
 * Author: Michael Meer
 */


function sportlog_load() {
    register_hook('app_menu', 'addon/sportlog/sportlog.php', 'sportlog_app_menu');
    register_hook('post_local', 'addon/sportlog/sportlog.php', 'sportlog_post_hook');
    logger('SportLog Addon initiated');
}

function sportlog_unload() {
    unregister_hook('app_menu', 'addon/sportlog/sportlog.php', 'sportlog_app_menu');
    unregister_hook('post_local', 'addon/sportlog/sportlog.php', 'sportlog_post_hook');
    logger('SportLog Addon unloaded');
}

function sportlog_app_menu($a,&$b) {
    $b['app_menu'][] = '<div class="app-title"><a href="sportlog">Sportlog</a></div>';
}

function sportlog_post_hook() {}

function sportlog_module() {}

function sportlog_content(&$a) {

$baseurl = $a->get_baseurl() . '/addon/sportlog';

}
