<?php
/***********************************************************************
 * ranking.php 
 *
 * Ranking form for Trikot-Totto PHP application. 
 * Written by Stefan Wyss in 2014
 **********************************************************************/
require_once('util.php'); 
require_once('config.php'); 
require_once('classes.php');
require_once('teaminit.php');

global $username;
global $Points;

// Ausgabe des HTML Headers mit CSS Styles
printHeader();

// lade Benutzername aus Joomla
define( '_JEXEC', 1 );
define( 'JPATH_BASE', realpath(dirname(__FILE__).'/..' ));
define( 'DS', DIRECTORY_SEPARATOR );
require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );
JFactory::getApplication('site')->initialise();
$user =& JFactory::getUser();
$username = $user->username;
if ($username == "") die ('Der Benutzer ist nicht angemeldet!');

global $mas;
//*****************************************************************************
// Berechnung der Punkte aller Spieler
//***************************************************************************** 
// Verbindung zum MySQL Server herstellen und Datenbank wählen
$db=mysql_connect($db_serv, $db_user, $db_pass) or die ('I cannot connect to the database because: ' . mysql_error()); 
mysql_select_db($db_name, $db) or die('ERROR!');

$query = mysql_query("select count(*) from wmtotto2014;") or die(mysql_error());
$qresult = mysql_fetch_array($query);

//DebugMsg("<p>Die Datenbank hat $qresult[0] Eintraege (inkl. Master)</p>");

$queryList = mysql_query("select * from wmtotto2014 where PlayerName != 'Master';") or die(mysql_error());
$queryMaster = mysql_query("select * from wmtotto2014 where PlayerName = 'Master';") or die(mysql_error());
	
// Berechnungsschleife zur Berechnung der Punkte einzelner Spieler	
$mas = mysql_fetch_array($queryMaster);

do
{
	$qresult = mysql_fetch_array($queryList);

	$FormComplete = $qresult['FormComplete'];
	if ($FormComplete != 1) continue;
	
	// the teams and matches
	$teams = array();
	$matches = array();
	InitTeamsAndMatches();
		
	$player = NEW player();
	$player->score = 0;
	$PlayerScore = 0;
	$player->username = $qresult['PlayerName'];
	
	// Berechne Punkte fŸr diesen Spieler
	CalculatePlayerScore();
	$nme = $player->username;
	$pts = $player->score;
	
	// Speichere Punkte fŸr diesen Spieler in der Datenbank
	SavePlayerScoreToDB();
	
	$Name = $player->username;
	$Points = $player->score;	
	$PlayerList["$Name"] = $Points;
}
while ( !empty($qresult) );

print "<form action='form.php' method='post'>";
print "<table align='center' width='800px' border='0' cellspacing='0' cellpadding='1'>";

//print "<input type='button' value='zurück' onClick='history.go(-1);'>";
//print "<a href='#' onClick='history.go(-1);'>Back</a>";
print "<tr bgcolor='#BBBBBB'> <td><b>Rang</b></td><td>Name</td><td>Punkte</td></tr>";

$bg = 0;
$color[0] = "#DDDDDD";
$color[1] = "#CCCCCC";

$rank = 1;
$ctr = 1;
arsort($PlayerList);
$old_value = 0;

foreach ($PlayerList as $key => $value) {		
	
	if ($old_value != $value) {
		$rank = $ctr++;
		if ($bg == 0){
			$bg = 1;
		} else {
			$bg = 0;
		}
	}
	else
	{
		$ctr++;
	}
	if ($key == $username )
		print "<tr bgcolor='lightgreen'><td>$rank</td><td>$key</td><td>$value</td></tr>";
	else
		print "<tr bgcolor=$color[$bg]><td>$rank</td><td>$key</td><td>$value</td></tr>";
	$old_value = $value;
}

print "</table></form>";

//echo "<pre>";
//print_r($PlayerList);
//echo "</pre>";

mysql_close($db);
?>		
