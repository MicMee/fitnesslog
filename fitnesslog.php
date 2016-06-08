<?php

/**
 * Name: Fitness Log
 * Description: Track your sportive activities
 * Version: 0.01
 * Author: Michael Meer
 */


function fitness_load() {
  //called when admin enables this addon
  //logger('load');
  fitness_init();
  register_hook('app_menu', 'addon/fitnesslog/fitnesslog.php', 'fitnesslog_app_menu');
  //register_hook('load_pdl', 'addon/fitnesslog/fitnesslog.php', 'fitnesslog_load_pdl');
}

function fitnesslog_unload() {
  //called when admin disables this addon
  //logger('unload');
  unregister_hook('app_menu', 'addon/fitnesslog/fitnesslog.php', 'fitnesslog_app_menu');
}

function fitnesslg_app_menu($a,&$b) {
    $b['app_menu'][] = '<div class="app-title"><a href="fitnesslog">Fitness Log</a></div>';
    //wird aufgerufen
    //logger('app_menu');
}

function widget_fitnesslog_controls($a) {
    //logger('fitnesslog plugin: widget_fitnesslog_controls called');
    $channel = $a->get_channel();  // Get the channel information
    // Obtain the default permission settings of the channel

    $t = get_markup_template('fitnesslog_aside.tpl', 'addon/fitnesslog');
    // Initialize the ACL to the channel default permissions

    $a->page['aside'] .= replace_macros($t, array(
        '$asidetitle' => t('Fitnesslog Controls'),
        '$lockstate' => $x['lockstate'],
        '$acl' => $x['acl'],
        '$bang' => $x['bang']
    ));

    return $a->page['aside'];
}

function fitnesslog_init() {
	logger("init start");
}

function fitnesslog_install() {
	logger('install');
	require_once('include/dba/dba_driver.php');
	$r = q("CREATE TABLE if not exists `fitnessl_training` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
                `channel` CHAR(100) NOT NULL DEFAULT '',
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
		logger('Table Training not created');
	}
	$r = q("CREATE TABLE if not exists `fitnessl_channel` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `channel` CHAR( 100 ) NOT NULL DEFAULT '',
			    `gender` INT( 2 ) UNSIGNED NOT NULL DEFAULT '0',
                `birthd` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
                `prefweight` INT( 2 ) UNSIGNED NOT NULL DEFAULT '0',
                `preflength` INT( 2 ) UNSIGNED NOT NULL DEFAULT '0',
                `size` INT(10) UNSIGNED NOT NULL DEFAULT '0',
                PRIMARY KEY ( `id` )
                ) ENGINE = MYISAM DEFAULT CHARSET=utf8");
	if (! $r) {
		logger('Table Channel not created');
	}
	$r = q("CREATE TABLE if not exists `fitnessl_personaldata` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `channel` CHAR(100) NOT NULL DEFAULT '',
                `timestamp` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
                `weight` FLOAT(10) NOT NULL DEFAULT '0',
				`weightunit` CHAR(10) NOT NULL DEFAULT '',
                `hip` INT(10) UNSIGNED NOT NULL DEFAULT '0',
				`hipunit` CHAR(10) NOT NULL DEFAULT '',
                `waist` INT(10) UNSIGNED NOT NULL DEFAULT '0',
				`waistunit` CHAR(10) NOT NULL DEFAULT '',
                `chest` INT(10) UNSIGNED NOT NULL DEFAULT '0',
				`chestunit` CHAR(10) NOT NULL DEFAULT '',
				`bmi` FLOAT(10) NOT NULL DEFAULT '0',
                PRIMARY KEY ( `id` )
                ) ENGINE = MYISAM DEFAULT CHARSET=utf8");
	if (! $r) {
		logger('Table Personaldata not created');
	}
	logger('Fitnesslog_install ends'); 
}

function fitnesslog_uninstall() {
	logger('uninstall');
	$r = q('DROP TABLE IF EXISTS `fitnessl_channel`;');
	if (! $r){
		logger('table fitnessl_channel could not be deleted');
	};
	$r = q('DROP TABLE IF EXISTS `fitnessl_personaldata`;');
	if (! $r){
		logger('table fitnessl_personaldata could not be deleted');
	};
	$r = q('DROP TABLE IF EXISTS `fitnessl_training`;');
	if (! $r){
		logger('table fitnessl_training could not be deleted');
	};
}

function fitnesslog_module() {
  logger('module');
}

function fitnesslog_aside($a) {
	logger('aside');	
}

function fitnesslog_personaldata($uid) {
	logger('in function personaldata');
	//$o .= "<br>2 UID: " . $uid . "<br>";
	$r = q("SELECT * from fitnessl_channel WHERE channel = '$uid'");
	if ( $r ) {
		$gender = $r[0]['gender'];
		$preflength = $r[0]["preflength"];
		$size = $r[0][size];
		$dayofbirth = $r[0]['birthd'];
		$prefweight = $r[0]['prefweight'];
	}
	else {
		$r = q("INSERT INTO `fitnessl_channel` 
				( channel, birthd, gender, prefweight, preflength, size ) 
				VALUES ( '%s', 0, 0, 0, 0, 0 )",
				dbesc($uid)
				);
		$gender = 0;
		$preflength = 0;
		$prefweight = 0;
		$size = 0;
		$dayofbirth = 0;
		$weight = 0;
		$hip = 0;
		$waist = 0;
	}
	if ( $gender == "0") $female = "checked";
	if ( $gender == "1") $male = "checked";
	if ( $prefweight == "0" ) { 
		$kg = "checked";
		$weightunit = "kg";
	} else {
		$lbs = "checked";
		$weightunit = "lbs";
	}
	if ( $preflength == "0" ) {
		$meter = "checked";
		$sizeunit = "cm";
		$hipunit = "cm";
		$waistunit = "cm";
		$chestunit = "cm";
	} else {
		$mile = "checked";
		$sizeunit = "feet";
		$hipunit = "inch";
		$waistunit = "inch";
		$chestunit = "inch";
	}
	
	$o .= <<< EOT
  <form action="/fitnesslog" method="post">
  <h3>Personal Data</h3><br>
  <table>
  <tr><td>Your actual weight: </td><td><input type="number" name="weight" id="weight" value="$weight" step="0.1" /> $weightunit </td></tr>
  <tr><td>Your actual hip: </td><td><input type="number" name="hip" id="hip" value="$hip"/> $hipunit </td></tr>
  <tr><td>Your actual waist: </td><td><input type="number" name="waist" id="waist" value="$waist"/> $waistunit </td></tr>
  <tr><td>Your actual chest: </td><td><input type="number" name="chest" id="chest" value="$chest"/> $chestunit </td></tr>
  <tr><td>Your size : </td><td><input type="number" name="size" id="size" value="$size"/> $sizeunit </td><tr>
  <tr><td>Your Gender: </td>
  		<td>
		<input type="radio" name="gender" $female id="female" value="0"> female
		<input type="radio" name="gender" $male id="male" value="1"> male
		</td>
  </tr>
  <tr><td>Your day of birth: </td><td><input type="date" name="dayofbirth" id="dayofbirth" value="$dayofbirth" ></td></tr>
  </table>
  <button type="submit" name="action" value="updatepersonaldata">update</button><br>
  <br><br>
	
  <h4>Your Preference</h4>
  <table>
  <tr>
  <td>in weight: </td>
		<td><input type="radio" id="kg" name="prefweight" $kg value="0"> kg </td>
		<td><input type="radio" id="lbs" name="prefweight" $lbs value="1"> lbs </td>
  </tr>
  <tr>
  <td>in length: </td>
		<td><input type="radio" id="meter" name="preflength" $meter value="0"> cm/m/km </td>
		<td><input type="radio" id="mile" name="preflength" $mile value="1"> inch/feet/yard/mile </td>
  </tr>
  </table>
  <button type="submit" name="action" value="updatepreference">update</button><br>
  <br><br>
EOT;
	return $o;
}

function fitnesslog_showpersonaldata($uid){
	$r = q("SELECT * from fitnessl_personaldata WHERE channel = '$uid'");
	$num = count($r);
	$o .= "Anzahl:" . $num . "<br>";
	$i = 0;
	$o .= "<table>";
	$o .= "<tr><td> Date </td><td> Weight </td><td> Hip </td><td> Waist </td><td> Chest </td><td> BMI </td></tr>";
	while( $i < $num){
		$o .= "<tr>
				<td> " . $r[$i][timestamp] . " </td>
				<td> " . $r[$i][weight] . " " . $r[$i][weightunit] . " </td>
				<td> " . $r[$i][hip] . " " . $r[$i][hipunit] . " </td>
				<td> " . $r[$i][waist] . " " . $r[$i][waistunit] . " </td>
				<td> " . $r[$i][chest] . " " . $r[$i][chestunit] . " </td>
				<td> " . $r[$i][bmi] . " </td>
				<td> <form action='/fitnesslog' method='post'> 
				edit " . $r[$i][id] . " 
				<button type='submit' name='pdedit' value='" . $r[$i][id] . "'> edit </button>
				</form></td>
		</tr>";
		$i = $i + 1;
	}
	$o .= "<table>";
	return $o;
}

function fitnesslog_updatepreference($uid, $prefweight, $preflength){
	$r = q("UPDATE `fitnessl_channel` SET prefweight = %s, preflength = %s WHERE channel = '$uid'", 
			intval($prefweight),
			intval($preflength)
			);
}

function fitnesslog_updatepersonaldata($uid, $dayofbirth, $gender, $size, $prefweight, $preflength, $weight, $hip, $waist, $chest, $bmi){
	logger('in function updatepersonaldata');
	$timestamp = time();
	$today = date("d.m.Y",$timestamp);
	//$now = strtotime('now');
	if ( $prefweight == "0" ) {
		$weightunit = "kg";
	} else {
		$weightunit = "lbs";
	}
	if ( $preflength == "0" ) { 
		$hipunit = "cm";
		$waistunit = "cm";
		$chestunit = "cm";
	} else {
		$hipunit = "in";
		$waistunit = "in";
		$chestunit = "in";
	}
	//$o .= "<br> UID: " . $uid . "<br>";
	$r = q("UPDATE fitnessl_channel SET birthd = '$dayofbirth' WHERE channel = '$uid'");
	$r = q("UPDATE fitnessl_channel SET gender = '$gender' WHERE channel = '$uid'");
	$r = q("UPDATE fitnessl_channel SET size = '$size' WHERE channel = '$uid'");
	$r = q("UPDATE fitnessl_channel SET prefweight = '$prefweight' WHERE channel = '$uid'");
	$r = q("UPDATE fitnessl_channel SET preflength = '$preflength' WHERE channel = '$uid'");
	$r = q("SELECT 'id', 'timestamp' from fitnessl_channel WHERE 'channel' = '$uid' AND 'timestamp' = '$timestamp'");
	if (! $r ) {
		logger("does not exist. Today is: " . $today );
	} else {
		logger("exist $timestamp as " . $r[0][timestamp] . " !");
	}
	$r = q("INSERT INTO `fitnessl_personaldata` 
			( channel, timestamp, weight, weightunit, hip, hipunit, waist, waistunit, chest, chestunit, bmi ) 
			VALUES 
			( '%s', '%s', '%f', '%s', '%f', '%s', '%f', '%s', '%f', '%s', '%f') ",
			dbesc($uid),
			dbesc(datetime_convert('UTC','UTC','$today')),
			floatval($weight),
			dbesc($weightunit),
			floatval($hip),
			dbesc($hipunit),
			floatval($waist),
			dbesc($waistunit),
			floatval($chest),
			dbesc($chestunit),
			floatval($bmi)
			);
}

function fitnesslog_newtraining() {
	//`id` INT(10) 
	//`channel` CHAR(100) NOT NULL DEFAULT '',
	//`timestamp` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	//`duration` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	//`kindofsport` CHAR( 255 ) NOT NULL DEFAULT '',
	//`distance` CHAR( 255 ) NOT NULL DEFAULT '',
	//`distanceunit` CHAR( 255 ) NOT NULL DEFAULT '',
	//`burnedkcals` INT(10) NOT NULL DEFAULT '0',
	//`avheartrate` INT(10) NOT NULL DEFAULT '0',
	//`avsteps` INT(10) NOT NULL DEFAULT '0',
	//`equipment` CHAR( 255 ) NOT NULL DEFAULT '',
	//$now = strtotime('now');
	$now = datetime_convert('UTC','UTC','$today');
	$o .= <<< EOT
  <form action="/fitnesslog" method="post">
  <h3>Training Data</h3>
  <table>
  <tr><td>date of your activity:</td><td> <input type="date" name="date" id="date" value="$now"/></td></tr>
  <tr><td>kind of sport: </td><td><input type="text" name="kindofsport" id="kindofsport" value="$kindofsport"/></td></tr>
  <tr><td>duration: </td><td><input type="time" name="duartion" id="duration" value="00:00:00" /></td></tr>
  <tr><td>distance: </td><td><input type="float" name="distance" id="distance"> $distanceunit</td></tr>
  <tr><td>burned KCals: </td><td><input type="int" name="burnedkcals" id="burnedkcals"/></td></tr>
  <tr><td>average heartrate:  </td><td><input type="int" name="avheartrate" id="avheartrate"/> bpm</td></tr>
  <tr><td>average steps:  </td><td><input type="int" name="avsteps" id="avsteps"/> spm</td></tr>
  <tr><td><input type="checkbox" name="publish" id="publish"/> publish your trainings to </td>
      <td><input type="text" name="kindofsport" list=" $kindofsport"/></td>
  </tr>
  </table>		
  <button type="submit" name="action" value="updatetrainingdata">update</button><br><br>
  <br><br>
  </form>
EOT;
	return $o;
}

function fitnesslog_updatetrainingdata($uid){
	$r = q("INSERT INTO `fitnessl_training` ( channel, timestamp, kindofsport, distance, distanceunit, burnedkcals, avheartrate, avsteps, equipment) 
			VALUES ( %S , 0, 0, 0, 0, 0, 0, 0, 0) ",
			dbesc($uid) 
			);
}

function fitnesslog_nutrition(){
	$o .= <<< EOT
	<h3>Nutrition</h3>
	track your food and kcals to live healthy <br>
	coming soon <br>
	<button type="submit" name="action" value="updatenutrition">update</button>
EOT;
	return $o; 
}

function fitnesslog_report() {
	$o .= <<< EOT
  <h3>Report</h3>
  <button type="submit" name="action" value="updatereport">update</button><br>
  <br><br>
  
</p>
EOT;
	return $o;
}

function fitnesslog_menue(){
	logger("in menue");
	$o .= <<< EOT
  <form action="/fitnesslog" method="post">
  <h2>Fitness Log</h2><br>
  Welcome $name, this will become the Hubzilla addon to track your trainings, your effort and your body. <br>
  <button type="submit" name="action" value="personaldata">Personal Data</button>
  <button type="submit" name="action" value="newtraining">New Training</button>
  <button type="submit" name="action" value="nutrition">Nutrition</button>
  <button type="submit" name="action" value="report">Report</button><br>
  <br><br>
  </form>
	
EOT;
	logger("in menue ende");
	return $o;
}

function fitnesslog_content(&$a) {
  //wird aufgerufen
  logger('function content');
  $observer = App::get_observer(); 
  $uid = $observer['xchan_guid'];
  $name = $observer['xchan_name'];
  $size = 1 ;
  $action = "";
  //var_dump($r);
  //require_once('include/comanche.php');
  //if(! local_channel()){
	//ToDo: Message for local user olny
	//$o .= "this service is for local user only";
    //return;
  //}
  if ($_SERVER['REQUEST_METHOD'] === 'GET'){
	$o .= t('GET Methode ');
	//var_dump($_GET);
  }
  if ($_SERVER['REQUEST_METHOD'] === 'POST'){
	//$o .= t('POST Methode <br>');
	$gender = $_POST['gender'];
	$weight = $_POST['weight'];
	$hip = $_POST['hip'];
	$waist = $_POST['waist'];
	$chest = $_POST['chest'];
	$bmi = $_POST['bmi'];
	$size = $_POST['size'];
	$action = $_POST['action'];
	$dayofbirth = $_POST['dayofbirth'] ;
	$prefweight = $_POST['prefweight'];
	$preflength = $_POST['preflength'];
	
  }

  //$baseurl = $a->get_baseurl() . '/fitnesslog';
  $baseurl = App::get_baseurl() . '/fitnesslog';
  
  //[region=aside]
  //menu
  //[/region]
  //a->page['htmlhead'] .= $o;
  //$o .= "<br> UID: " . $uid . "<br>";

  //1 inch = 1/12 Fuß = 1/36 yard
  //1 inch = 25,4 mm
  //1 feet = 304,8 mm
  //1 yard = 914,4 mm
  //1 mile = 1,609 km = 1760 yards

  //$o .= "<br> Action is: " . $action . "---<br>";
  //logger("wird dies auch 2mal ausgeführt?");
  
  $o .= fitnesslog_menue();
  switch ($action){
  	case "personaldata":
  		logger('function content - personaldata');
  		$o .= fitnesslog_personaldata($uid);
  		$o .= fitnesslog_showpersonaldata($uid) ;
  		break;
  	case "newtraining":
  		logger('function content - newtraining');
  		$o .= fitnesslog_newtraining();
  		break;
  	case "report":
  		logger('function content - report');
  		$o .= fitnesslog_report();
  		break;
  	case "updatepersonaldata":
  		logger('function content - updatepersonaldata');
  		fitnesslog_updatepersonaldata($uid, $dayofbirth, $gender, $size, $prefweight, $preflength, $weight, $hip, $waist, $chest, $bmi);
  		$o .= fitnesslog_personaldata($uid);
  		$o .= fitnesslog_showpersonaldata($uid);
  		break;
  	case "updatepreference":
  		logger('function content - updatepreference');
  		fitnesslog_updatepreference($uid, $prefweight, $preflength);
  		$o .= fitnesslog_personaldata($uid);
  		break;
  	case "nutrition":
  		logger('function content - nutrition');
  		$o .= fitnesslog_nutrition();
  		break;
  	default:
  		logger("Warning: API not used!");
  		break;
  }
  
    
return $o;
}
