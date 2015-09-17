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
    $b['app_menu'][] = '<div class="app-title"><a href="sportlog">Mahjongg</a></div>';
}


function sportlog_module() {}

function sportlog_content(&$a) {

$baseurl = $a->get_baseurl() . '/addon/sportlog';

$o .= <<< EOT
<br><br>
<p align="left">
<embed src="addon/sportlog/sportlog.swf" quality="high" bgcolor="#FFFFFF" width="800" height="600" name="sportlog" align="middle" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
<br><br>
<b>Simply locate the matching tiles and find a way to clear them from the board as quickly as possible.
A timer at the top of the screen keeps track of how you are doing.</b><br>
</p>
EOT;

return $o;
}
