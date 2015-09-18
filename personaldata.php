<?php


function setup-tbl-personaldata (){
        $r = q("CREATE TABLE if not exists `sportl_personaldata` (
                `id` INT(10) UNSIGNED NOT NULL ,
                `channel` INT(10) UNSIGNED NOT NULL DEFAULT '0',
                `age` INT(10) NOT NULL DEFAULT '0',
                `timestamp` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
                `weight` INT(10) UNSIGNED NOT NULL DEFAULT '0',
                `waist` INT(10) NOT NULL DEFAULT '0',
                `hip` INT(10) NOT NULL DEFAULT '0',
                PRIMARY KEY ( `id` )
                ) ENGINE = MYISAM DEFAULT CHARSET=utf8");

}


class PersonalData
{
	private $who;
	private $timestamp;
	private $weight;
	private $weightunit;
	private $age;
	private $gender;
	private $hrmax;

	public function store() {}
	public function get() {}	
	public function edit() {}
	public function del() {}
}
?>
