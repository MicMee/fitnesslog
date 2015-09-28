<?php

/**
 * Name: Sport Log
 * Description: Track your sportive activities
 * Version: 1.0
 * Author: Michael Meer
 */


function sportlog_load() {
  logger('load');
  register_hook('app_menu', 'addon/sportlog/sportlog.php', 'sportlog_app_menu');
}

function sportlog_unload() {
  logger('unload');
  unregister_hook('app_menu', 'addon/sportlog/sportlog.php', 'sportlog_app_menu');
}

function sportlog_app_menu($a,&$b) {
    $b['app_menu'][] = '<div class="app-title"><a href="sportlog">Sport Log</a></div>';
    //wird aufgerufen
    logger('app_menu');
}

function sportlog_init() {
    logger('init');
    require_once('include/dba/dba_driver.php');
    $r = q("CREATE TABLE if not exists `sportl_training` (
                `id` INT(10) UNSIGNED NOT NULL ,
                `channel` INT(10) UNSIGNED NOT NULL DEFAULT '0',
                `timestamp` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
                `duration` INT(10) UNSIGNED NOT NULL DEFAULT '0',
                `kindofsport` CHAR( 255 ) NOT NULL DEFAULT '',
                `distance` CHAR( 255 ) NOT NULL DEFAULT '',
                `distanceunit` CHAR( 255 ) NOT NULL DEFAULT '',
                `burnedkcals` INT(10) NOT NULL DEFAULT '0',
                `avheartrate` INT(10) NOT NULL DEFAULT '0',
                `avsteps` INT(10) NOT NULL DEFAULT '0',
                `equipment` CHAR( 255 ) NOT NULL DEFAULT '',
                PRIMARY KEY ( `id` )
                ) ENGINE = MYISAM DEFAULT CHARSET=utf8");
     if (! $r) {
	logger('Table Training nicht angelegt');
     } 
     $r = q("CREATE TABLE if not exists `sportl_channel` (
                `id` INT(10) UNSIGNED NOT NULL ,
                `channel` INT(10) UNSIGNED NOT NULL DEFAULT '0',
                `birthd` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
                `preferedWeight` CHAR( 25 ) NOT NULL DEFAULT '',
                `preferedMeasure` CHAR( 25 ) NOT NULL DEFAULT '',
                `size` INT(10) UNSIGNED NOT NULL DEFAULT '0',
                PRIMARY KEY ( `id` )
                ) ENGINE = MYISAM DEFAULT CHARSET=utf8");
     if (! $r) {
	logger('Table Channel nicht angelegt');
     }
      $r = q("CREATE TABLE if not exists `sportl_personaldata` (
                `id` INT(10) UNSIGNED NOT NULL ,
                `channel` INT(10) UNSIGNED NOT NULL DEFAULT '0',
                `timestamp` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
                `weight` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
                `hip` INT(10) UNSIGNED NOT NULL DEFAULT '0',
                `waist` INT(10) UNSIGNED NOT NULL DEFAULT '0',
                `chest` INT(10) UNSIGNED NOT NULL DEFAULT '0',
                PRIMARY KEY ( `id` )
                ) ENGINE = MYISAM DEFAULT CHARSET=utf8");
     if (! $r) {
	logger('Table Personaldata nicht angelegt');
     }
}

function sportlog_module() {
  logger('module');
}

function sportlog_content(&$a) {
  //wird aufgerufen
  logger('content');

$baseurl = $a->get_baseurl() . '/addon/sportlog';

$o .= <<< EOT
<h3>Sport Log</h3><br>
Welcome, this will become the Hubzilla addon to track your trainings, your effort and your body. <br>

<br><br>
</p>
EOT;

return $o;
}
