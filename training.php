<?php

function setup-tbl-training (){
	$r = q("CREATE TABLE if not exists `sportl_training` (
                `id` INT(10) UNSIGNED NOT NULL ,
                `channel` INT(10) UNSIGNED NOT NULL DEFAULT '0',
                `kindofsport` CHAR( 32 ) NOT NULL DEFAULT '',
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

}

class Training
{
	private $who;
	private $timestamp;
	private $duration;
	private $kindofsport;
	private $distance;
	private $distanceunit;
	private $heightunit;
	private $burnedkcals;
	private $avheartrate;
	private $avsteps;
	private $equipment;

	public function store() {}
	public function get() {}	
	public function edit() {}
	public function del() {}
}
?>
