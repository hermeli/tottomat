<?php
setlocale(LC_ALL, 'UTF-8');
/***********************************************************************
* Trikot-Totto Tottomat (Tippspiel für die Fussball EM/WM) 
* ----------------------------------------------------------------------
* Datei: ranking.php
* 
* Ausgabe der Rangliste der Spieler
*
* Email: wyss@superspider.net
***********************************************************************/
require_once('util.php'); 
require_once('config.php'); 
require_once('classes.php');
require_once('teaminit.php');

global $username;
global $mas;
global $IsGroupMember;
global $GroupFilter;

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

//*****************************************************************************
// Berechnung der Punkte aller Spieler
//***************************************************************************** 
// Verbindung zum MySQL Server herstellen und Datenbank wählen
$db = new PDO('mysql:host='.$db_serv.';dbname='.$db_name.';charset=utf8', $db_user, $db_pass);

$query = $db->query("select count(*) from ".$db_table.";");
$qresult = $query->fetch(PDO::FETCH_ASSOC);
DebugMsg("<p>Die Datenbank hat ".$qresult["count(*)"]. " Einträge (inkl. Master)</p>");

// Laden der Matches des master-Eintrags
$query = $db->query("select * from ".$db_table." where PlayerName = 'master';");
$mas = $query->fetch(PDO::FETCH_ASSOC);

$query = $db->query("select * from ".$db_table." where PlayerName != 'master';");
while ($row = $query->fetch(PDO::FETCH_ASSOC))
{
	if ($row["FormComplete"] != "1") continue;
	
	// erstellen aller Teams and Matches
	$teams = array();
	$matches = array();
	InitTeamsAndMatches();
		
	$player = NEW player();
	$player->score = 0;
	$player->username = $row['PlayerName'];
	$player->name = $row['Name'];
	$player->groupField = $row['GroupField'];
	
	// Lade die Spielresultate aus der DB und berechne Punkte für diesen Spieler
	LoadMatchesFrom("DB");
	CalculatePlayerScore();
	
	// Speichere Punkte für diesen Spieler in der Datenbank
	SavePlayerScoreToDB();
	
	// $PlayerTable[] = $player;	
	$ScoreTable[$player->name] = array($player->score,$player->username,$player->groupField);
}
//*****************************************************************************
// Buttons auswerten (POST-Variablen)
//***************************************************************************** 
if (isset($_POST['loadteam']))
{
	$GroupFilter =  $_POST['loadteam'];	
	if ($GroupFilter == "(kein Filter)")
		$GroupFilter = "";
}
//*****************************************************************************
// Prüfe, ob der User ein Gruppenfeldeintrag hat
//*****************************************************************************
$query = $db->query("select * from ".$db_table." where PlayerName = '".$username."';");
$row = $query->fetch(PDO::FETCH_ASSOC);
if ($row['GroupField'] != "")
{
	$TeamName = $row['GroupField'];
	DebugMsg("<p>Der Spieler ist in der Gruppe: ".$TeamName);
	$IsGroupMember = true;
} else {
	$IsGroupMember = false;
}
//*****************************************************************************
// Anzeigen der Spielertabelle 
//***************************************************************************** 
print "<form action='".$_SERVER['PHP_SELF']."' method='post'>";
print "<table align='center' width='800px' border='0' cellspacing='0' cellpadding='1'>";
print "<col style='width:10%'>";
print "<col style='width:35%'>";
print "<col style='width:25%'>";
print "<col style='width:25%'>";

if ($IsGroupMember)
{
	print "<tr><td colspan=3><b>Gruppenfilter auswählen: </b>";
	print "<select name='loadteam' onchange='this.form.submit()'>";
	print "<option></option>";
	print "<option>".$TeamName."</option>";
	print "<option>(kein Filter)</option>";
	print "</td><td align=right><a href='http://www.trikot-totto.ch'>[Zurück zur Hauptseite]</a></td></tr>";
	
} else {
	print "<tr><td colspan=4 align=right><a href='http://www.trikot-totto.ch'>[Zurück zur Hauptseite]</a></td></tr>";
}
print "<tr bgcolor='gold'> <td><b>Rang</b></td><td><b>Name</b></td><td><b>Gruppe</b></td><td><b>Punkte</b></td></tr>";

$bg = 0;
$color[0] = "#DDDDDD";
$color[1] = "#CCCCCC";
$color[2] = "lightgreen";

$rank = 1;
$ctr = 1;
arsort($ScoreTable);
$old_score = 0;

foreach ($ScoreTable as $Player => $PlayerData) 
{		
	$score = $PlayerData[0];
	$uname = $PlayerData[1];
	$gfield = $PlayerData[2];
	
	// Berechne die Rangliste
	if ($old_score != $score) {
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
	
	// Anzeige
	if ($GroupFilter == "")
	{
		if ($Player == $user->name)
			$bgcolor = $color[2];
		else
			$bgcolor=$color[$bg];	
		print "<tr bgcolor='".$bgcolor."'><td>$rank</td><td>$Player (".$uname.")</td><td>".$gfield."</td><td>".$score."</td></tr>";
	}
	else
	{
		if ($GroupFilter == $gfield)
		{
			if ($Player == $user->name)
				$bgcolor = $color[2];
			else
				$bgcolor=$color[$bg];	
			print "<tr bgcolor='".$bgcolor."'><td>$rank</td><td>$Player (".$uname.")</td><td>".$gfield."</td><td>".$score."</td></tr>";
		}
		
	}
	$old_score = $score;
}
print "</table></form>";
?>		
