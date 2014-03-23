<?php
/***********************************************************************
 * ranking.php 
 *
 * Ranking form for Trikot-Totto PHP application. 
 * Written by Stefan Wyss in 2014
 **********************************************************************/
require_once('util.php'); 
require_once('config.php'); 

global $username;
global $Points;

// lade Benutzername aus Joomla
define( '_JEXEC', 1 );
define( 'JPATH_BASE', realpath(dirname(__FILE__).'/..' ));
define( 'DS', DIRECTORY_SEPARATOR );
require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );
JFactory::getApplication('site')->initialise();
$user =& JFactory::getUser();
$username = $user->username;
$name = $user->name;

global $mas;
$DebugPlayer = "swyss";
$Alpha = array(A,B,C,D,E,F,G,H,I,J,K);
	
//*****************************************************************************
// Berechnung der Punkte einzelner Spieler
//***************************************************************************** 
// Verbindung zum MySQL Server herstellen und Datenbank wählen
$db=mysql_connect($db_serv, $db_user, $db_pass) or die ('I cannot connect to the database because: ' . mysql_error()); 
mysql_select_db($db_name, $db) or die('ERROR!');

$query = mysql_query("select count(*) from wmtotto2014;") or die(mysql_error());
$qresult = mysql_fetch_array($query);

DebugMsg("<p>Die Datenbank hat $qresult[0] Eintraege (inkl. Master)</p>");

$queryList = mysql_query("select * from wmtotto2014 where PlayerName != 'Master';") or die(mysql_error());
$queryMaster = mysql_query("select * from wmtotto2014 where PlayerName = 'Master';") or die(mysql_error());
	
// Berechnungsschleife zur Berechnung der Punkte einzelner Spieler	
$mas = mysql_fetch_array($queryMaster);

do
{
	$qresult = mysql_fetch_array($queryList);
	$PlayerName = $qresult['PlayerName'];
	
	$FormComplete = $qresult['FormComplete'];
	if ($FormComplete != 1) continue;
	
	$Name = $qresult['Name'];
	
	$Points = 0;
	
	// *****************************************************************
	// Gruppenspiele
	// *****************************************************************
	for ($i=0; $i<48; $i++)
	{
		$Game = 'Game'.$i;
		$GroupMatchPoints = CalculateGroupMatchPoints($mas[$Game],$qresult[$Game]);

		// Wenn die aktuelle Spielnummer mit der Jokergruppe übereinstimmt: Punkte * 2	
		if ( ($i/6) == array_search($qresult['GroupFavorite'],$Alpha)) 
			$GroupMatchPoints = $GroupMatchPoints * 2; 
	
		$Points += $GroupMatchPoints;			

		//if ($DebugPlayer == $PlayerName)
			//print "<p>Spieler $PlayerName hat nach Spiel " . $i . "=" . $Points . " Punkte</p>";
	}
		
	if ($DebugPlayer == $PlayerName)
		print "<p>Spieler $PlayerName hat nach Gruppenspielen $Points Punkte</p>";
	
	// *****************************************************************
	// Achtelfinalspiele - Mannschaftspunkte
	// *****************************************************************
	// mache eine Liste mit allen Achtelfinalgegnern, vergleiche alle 
	// Masterfelder mit der Liste 
	$start = 48;
	$end = 55;
	
	$TeamList = array();
	for ($i=$start; $i<=$end; $i++)
	{
		$q1 = $qresult["Game" . $i . "_T" . '1'];
		$q2 = $qresult["Game" . $i . "_T" . '2'];	
		if ($q1 != "") array_push($TeamList,$q1);
		if ($q2 != "") array_push($TeamList,$q2);
	}
	
	for ($i=$start; $i<=$end; $i++)
	{
		if (in_array($mas["Game" . $i . "_T" . '1'],$TeamList)) { $Points += 10;}
		if (in_array($mas["Game" . $i . "_T" . '2'],$TeamList)) { $Points += 10;}
	}
	if ($DebugPlayer == $PlayerName)
	{
		print "<p>Spieler $PlayerName hat nach Achtelfinal - Mannschaftspunkte " . $Points . " Punkte </p>";	
	}	
	
	// ********************************************************************
	// Achtelfinalspiele - Partiepunkte
	// ********************************************************************
	// Durchlaufe alle Achtelfinals und berechne die Punkte, wenn die zwei 
	// getippten Gegner in der Korrelation einander gegenüberstehen. 
	$OpponentList = array();

	// mache eine Liste der Spiele des Teilnehmers
	for ($i=$start; $i<=$end; $i++)
	{
		$u1 = $qresult["Game" . $i . "_T" . '1'];
		$u2 = $qresult["Game" . $i . "_T" . '2'];
		
		if ( ($u1!="")&&($u2!="") )
			array_push($OpponentList,"$u1-$u2");
	}

	for ($i=$start; $i<=$end; $i++)
	{
		$m1 = $mas["Game" . $i . "_T" . '1'];
		$m2 = $mas["Game" . $i . "_T" . '2'];
		$uindex = array_search("$m1-$m2",$OpponentList);
		if (is_numeric($uindex))
		{		
			$j = $uindex + $start;
			$Points += CalculateFinalMatchPoints($mas["Game".$i],$qresult["Game".$j],"NORMAL");	
		} 
		else
		{
			$uindex = array_search("$m2-$m1",$OpponentList);
			if (is_numeric($uindex))
			{
				$j = $uindex + $start;
				$Points += CalculateFinalMatchPoints($mas["Game".$i],$qresult["Game".$j],"REVERSE");	
			}
		}
	}
	
	if ($DebugPlayer == $PlayerName)
		print "<p>Spieler $PlayerName hat nach Achtelfinal - Partiepunkte " . $Points . " Punkte </p>";

	// *****************************************************************
	// Viertelfinalspiele - Mannschaftspunkte
	// *****************************************************************
	// mache eine Liste mit allen Viertelfinalgegnern, vergleiche alle Masterfelder mit der Liste 
	$start = 56;
	$end = 59;
	
	$TeamList = array();
	for ($i=$start; $i<=$end; $i++)
	{
		$q1 = $qresult["Game" . $i . "_T" . '1'];
		$q2 = $qresult["Game" . $i . "_T" . '2'];	
		if ($q1 != "") array_push($TeamList,$q1);
		if ($q2 != "") array_push($TeamList,$q2);
	}
	
	for ($i=$start; $i<=$end; $i++)
	{
		if (in_array($mas["Game" . $i . "_T" . '1'],$TeamList)) { $Points += 10;}
		if (in_array($mas["Game" . $i . "_T" . '2'],$TeamList)) { $Points += 10;}
	}
	if ($DebugPlayer == $PlayerName)
	{
		print "<p>Spieler $PlayerName hat nach Viertelfinal - Mannschaftspunkte " . $Points . " Punkte </p>";	
	}	
	
	// ********************************************************************
	// Viertelfinalspiele - Partiepunkte
	// ********************************************************************
	// Durchlaufe alleViertel und berechne die Punkte, wenn die zwei getippten 
	// Gegner in der Korrelation einander gegenüberstehen. 
	$OpponentList = array();

	// mache eine Liste der Spiele des Teilnehmers
	for ($i=$start; $i<=$end; $i++)
	{
		$u1 = $qresult["Game" . $i . "_T" . '1'];
		$u2 = $qresult["Game" . $i . "_T" . '2'];
		
		if ( ($u1!="")&&($u2!="") )
			array_push($OpponentList,"$u1-$u2");
	}

	for ($i=$start; $i<=$end; $i++)
	{
		$m1 = $mas["Game" . $i . "_T" . '1'];
		$m2 = $mas["Game" . $i . "_T" . '2'];
		$uindex = array_search("$m1-$m2",$OpponentList);
		if (is_numeric($uindex))
		{		
			$j = $uindex + $start;
			$Points += CalculateFinalMatchPoints($mas["Game".$i],$qresult["Game".$j],"NORMAL");	
		} 
		else
		{
			$uindex = array_search("$m2-$m1",$OpponentList);
			if (is_numeric($uindex))
			{
				$j = $uindex + $start;
				$Points += CalculateFinalMatchPoints($mas["Game".$i],$qresult["Game".$j],"REVERSE");	
			}
		}
	}
	
	if ($DebugPlayer == $PlayerName)
		print "<p>Spieler $PlayerName hat nach Viertelfinal - Partiepunkte " . $Points . " Punkte </p>";
	

	// ********************************************************************
	// Halbfinalspiele - Mannschaftspunkte
	// ********************************************************************
	// mache eine Liste mit allen Halbfinalgegnern, vergleiche alle Masterfelder mit der Liste 
	
	$TeamList = array();
	$start = 60;
	$end = 61;
	
	for ($i=$start; $i<=$end; $i++)
	{
		$q1 = $qresult["Game" . $i . "_T" . '1'];
		$q2 = $qresult["Game" . $i . "_T" . '2'];	
		if ($q1 != "") array_push($TeamList,$q1);
		if ($q2 != "") array_push($TeamList,$q2);
	}
	
	for ($i=$start; $i<=$end; $i++)
	{
		if (in_array($mas["Game" . $i . "_T" . '1'],$TeamList)) { $Points += 10;}
		if (in_array($mas["Game" . $i . "_T" . '2'],$TeamList)) { $Points += 10;}
	}
	if ($DebugPlayer == $PlayerName)		
		print "<p>Spieler $PlayerName hat nach Halbfinal - Mannschaftspunkte " . $Points . " Punkte </p>";	

	// ********************************************************************
	// Halbfinalspiele - Partiepunkte
	// ********************************************************************
	// Durchlaufe alleHalbfinalspiele und berechne die Punkte, wenn die zwei 
	// getippten Gegner einander direkt gegenüberstehen. 
	$OpponentList = array();
		
	// mache eine Liste der Gegner des Teilnehmers
	for ($i=$start; $i<=$end; $i++)
	{
		$u1 = $qresult["Game" . $i . "_T" . '1'];
		$u2 = $qresult["Game" . $i . "_T" . '2'];
		if ( ($u1!="")&&($u2!="") )
			array_push($OpponentList,"$u1-$u2");
	}

	for ($i=$start; $i<=$end; $i++)
	{
		$m1 = $mas["Game" . $i . "_T" . '1'];
		$m2 = $mas["Game" . $i . "_T" . '2'];
		$uindex = array_search("$m1-$m2",$OpponentList);
		if (is_numeric($uindex))
		{		
			$j = $uindex + $start;
			$Points += CalculateFinalMatchPoints($mas["Game".$i],$qresult["Game".$j],"NORMAL");	
		} 
	}
	
	if ($DebugPlayer == $PlayerName)
		print "<p>Spieler $PlayerName hat nach Halbfinal - Partiepunkte " . $Points . " Punkte </p>";
	
		
	// ********************************************************************
	// Final - Mannschaftspunkte
	// ********************************************************************

	// mache eine Liste mit allen Finalgegnern, vergleiche alle Masterfelder mit der Liste	
	$List = array();
	
	$i=31;
	$q1 = $qresult["Game" . $i . "_T" . '1'];
	$q2 = $qresult["Game" . $i . "_T" . '2'];	
	if ($q1 != "") array_push($List,$q1);
	if ($q2 != "") array_push($List,$q2);
	
	if (in_array($mas["Game" . $i . "_T" . '1'],$List)) { $Points += 10;}
	if (in_array($mas["Game" . $i . "_T" . '2'],$List)) { $Points += 10;}
	
	if ($DebugPlayer == $PlayerName)		
		print "<p>Spieler $PlayerName hat nach Final - Mannschaftspunkte " . $Points . " Punkte </p>";	

	
	// ********************************************************************
	// Final - Partiepunkte
	// ********************************************************************
	$ulist = array();
	$u1 = $qresult["Game31_T" . '1'];
	$u2 = $qresult["Game31_T" . '2'];
	if ( ($u1!="")&&($u2!="") )
		array_push($ulist,"$u1-$u2");

	$m1 = $mas["Game31_T" . '1'];
	$m2 = $mas["Game31_T" . '2'];
	$uindex = array_search("$m1-$m2",$ulist);
	if (is_numeric($uindex))
		$Points += CalculateFinalMatchPoints($mas["Game31"],$qresult["Game31"],"NORMAL");	
	else
	{
		$uindex = array_search("$m2-$m1",$ulist);
		if (is_numeric($uindex))
			$Points += CalculateFinalMatchPoints($mas["Game31"],$qresult["Game31"],"REVERSE");	
	}
	
	if ($DebugPlayer == $PlayerName)
		print "<p>Spieler $PlayerName hat nach Final - Partiepunkte " . $Points . " Punkte </p>";
	
	// ********************************************************************
	// Finalspiele - Finalpunkte
	// ********************************************************************
	/*
	if (	CheckGame($mas["Game29"]) &&	
			(GetLooser($mas["Game29_T1"], $mas["Game29_T2"], $mas["Game29"]) 
			== 	GetLooser($qresult["Game29_T1"], $qresult["Game29_T2"], $qresult["Game29"])) )
		$Points += 20;	
	
	if (	CheckGame($mas["Game30"]) &&	
			(GetLooser($mas["Game30_T1"], $mas["Game30_T2"], $mas["Game30"]) 
			== 	GetLooser($qresult["Game30_T1"], $qresult["Game30_T2"], $qresult["Game30"])) )
		$Points += 20;	
	*/
	if (	CheckGame($mas["Game31"]) &&
			(GetWinner($mas["Game31_T1"], $mas["Game31_T2"], $mas["Game31"]) 
			== 	GetWinner($qresult["Game31_T1"], $qresult["Game31_T2"], $qresult["Game31"])) 
		)
		$Points += 50;
		
	if (	CheckGame($mas["Game31"]) &&	
			(GetLooser($mas["Game31_T1"], $mas["Game31_T2"], $mas["Game31"]) 
			== 	GetLooser($qresult["Game31_T1"], $qresult["Game31_T2"], $qresult["Game31"])) )
		$Points += 30;	
		
	if ($DebugPlayer == $PlayerName)		
		print "<p>Spieler $PlayerName hat nach Final - Finalpunkte " . $Points . " Punkte </p>";
		
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
print_r($PlayerList);
//echo "</pre>";

mysql_close($db);
?>		
