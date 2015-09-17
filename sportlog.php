<?php

/**
 * Name: Sport Log
 * Description: Track your sportive activities
 * Version: 1.0
 * Author: Michael Meer
 */


function sportlog_load() {
  register_hook('app_menu', 'addon/sportlog/sportlog.php', 'sportlog_app_menu');
}

function sportlog_unload() {
    unregister_hook('app_menu', 'addon/sportlog/sportlog.php', 'sportlog_app_menu');

}

function sportlog_app_menu($a,&$b) {
    $b['app_menu'][] = '<div class="app-title"><a href="sportlog">Sport Log</a></div>';
}


function sportlog_module() {}

function sportlog_content(&$a) {

$baseurl = $a->get_baseurl() . '/addon/sportlog';

$o .= <<< EOT
<h3>Sport Log</h3><br>
Welcome, this will become the Hubzilla addon to track your trainings, your effort and your body. <br>

<br><br>
</p>
EOT;

return $o;
}
