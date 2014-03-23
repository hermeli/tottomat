<?php
/******************************************************************************
* function GetTeamWithRank($group, $rank)
* gibt das Team einer Gruppe zurück, welches den angegebenen Rang (1. oder 2.) hat.
******************************************************************************/
function GetTeamWithRank($group,$rank)
{
	// 610 304  313  611
	
	$first = 0;
	$firstIndex = 0;
	$second = 0;
	$secondIndex = 0;
	
	for($i=0; $i<4; $i++)
	{
		$key = $group[$i+4]; 
		if ($key>$first)
		{
			$second = $first;
			$secondIndex = $firstIndex;
			$first = $key;
			$firstIndex = $i;	
		}
		else if ($key>$second)
		{
			$second = $key;
			$secondIndex = $i;
		}
	}
	if ($rank==1)
		return $group[$firstIndex];
	else
		return $group[$secondIndex];
}

/******************************************************************************
* function printFinalGame(..)
* gibt das Finalspiel <td>-Element aus (inkl. Styles) 
******************************************************************************/
function printFinalGame($team,$list)
{
	$out = "<select ";
	if (is_numeric(array_search($team,$list))) $out = $out . "style='background-color: lightgreen;'";
	$out = $out . "width='90px' name='$team'><option selected='yes' >$team</option>";
	print $out;
}

/******************************************************************************
* function printGroupGame(..)
* gibt das Gruppenspiel <td>-Element aus (inkl. Styles) 
******************************************************************************/
function printGroupGame($group,$team1,$team2)
{	
	global $GroupA;
	global $GroupB;
	global $GroupC;
	global $GroupD;
	global $GroupE;
	global $GroupF;
	global $GroupG;
	global $GroupH;
	
	switch ($group)
	{
		case "A":
			$Group = $GroupA; break; 
		case "B":
			$Group = $GroupB; break; 
		case "C":
			$Group = $GroupC; break; 
		case "D":
			$Group = $GroupD; break; 
		case "E":
			$Group = $GroupE; break; 
		case "F":
			$Group = $GroupF; break; 
		case "G":
			$Group = $GroupG; break; 
		case "H":
			$Group = $GroupH; break; 
	}
	
	print "<td width='12%' valign='top'>$Group[$team1]-$Group[$team2]</td>";
}

/******************************************************************************
* function printHeader(..)
* gibt den HTML Header aus (inkl. Styles) 
******************************************************************************/
function printHeader()
{
	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<title>Trikot-Totto WM 2010 S�dafrika</title>
		<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
		<meta name="generator" content="Online Tottomat" />
		
		<STYLE TYPE="text/css">
		<!--
			body, p, input, td {font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; color:#333333;}
			body {padding:0px; margin:7px;}
			div.title { font-size:20px; color:#0071B8; font-weight:bold; padding-top:0px; padding-bottom:15px; }
			div.heading1{font-weight:bold; font-size: 12px; color:#0071B8; }
			p.heading1 {font-weight:bold; font-size: 12px; color:#0071B8; }
			p.heading2 {font-weight:bold; font-size: 12px; color:#0071B8; }
			p.heading3 {font-weight:bold; font-size: 12px; color:#0071B8; }
			p.heading4 {font-weight:bold; font-size: 12px; color:#0071B8; }
			td.bggray {background-color: #DDDDDD;}
			td.bgdarkgray {background-color: #CCCCCC;}
			td.bgwhite {background-color: #FFFFFF;}
			td.footer { padding:5px; text-align:center; color:#BBBBBB; font-size:11px; background-color:#FFFFFF;}
			td.header {background-color: #FFFFFF; text-align:right; padding:5px; font-size:11px;}
			A:link {text-decoration:none; COLOR: #0071B8; }
			A:visited {text-decoration:none; COLOR: #0071B8; }
			A:hover {text-decoration:none; COLOR: #00A1D8; }
			A:unknown {text-decoration:none; COLOR: #0071B8; }
			A:active {text-decoration:none; COLOR: #00A1D8; }
			div.menuitem {font-size:11px; padding:3px; padding-left:10px; margin-bottom:0px; text-align:left;}
			#input.search {width:80px; border:1px solid #0071B8;}	
			#news p {font-size:11px;}
			input {size: 15;} 
			select {width: 125px;} 
		-->
		</STYLE>
		
	</head>
	<body background="pictures/bg.gif">
	<?php
}
/******************************************************************************
* function checkGame(..)
* checkt die Game-Eingabefelder
******************************************************************************/
function checkGame($game)
{	
	if ($game == "") return 0;
	if ($game == "NP") return 1;
	
	$parts = explode(":", $game);
	
	if ( !is_numeric($parts[0]) ) return 0;
	if ( !is_numeric($parts[1]) ) return 0; 
	if ( $parts[0] < 0 ) return 0;
	if ( $parts[0] > 99) return 0;
	if ( $parts[1] < 0 ) return 0;
	if ( $parts[1] > 99) return 0;
	
	return 1;
}
/******************************************************************************
* function checkEvenFinal(..)
* checkt die Game-Eingabefelder
******************************************************************************/
function checkEvenFinal($game)
{	
	$parts = explode(":", $game);
	
	if ( $parts[0] == $parts[1] ) 
		return 0;
	else
		return 1;
}
/******************************************************************************
* function printGroupOptions(..)
* gibt die optionen des Dropdown-Elements aus
******************************************************************************/
function printGroupOptions($group)
{
	global $GroupA;
	global $GroupB;
	global $GroupC;
	global $GroupD;
	global $GroupE;
	global $GroupF;
	global $GroupG;
	global $GroupH;
	
	switch ($group)
	{
		case "A":
			$Group = $GroupA; break; 
		case "B":
			$Group = $GroupB; break; 
		case "C":
			$Group = $GroupC; break; 
		case "D":
			$Group = $GroupD; break; 
		case "E":
			$Group = $GroupE; break; 
		case "F":
			$Group = $GroupF; break; 
		case "G":
			$Group = $GroupG; break; 
		case "H":
			$Group = $GroupH; break; 
	}
	print "<option value='" . $Group[0] . "'>" . $Group[0] . "</option>";
	print "<option value='" . $Group[1] . "'>" . $Group[1] . "</option>";
	print "<option value='" . $Group[2] . "'>" . $Group[2] . "</option>";
	print "<option value='" . $Group[3] . "'>" . $Group[3] . "</option>";
	
}
/******************************************************************************
* function CalculateGroupMatchPoints(..)
* Berechnet die Spielpunkte eines Gruppenspiels anhand des Spielresultats 
******************************************************************************/
function CalculateGroupMatchPoints($marg,$uarg)
{
	//print "+CalculateGroupMatchPoints: marg=$marg, uarg=$uarg";
	
	if ($marg == "-") return 0;
	if ($uarg == "-") return 0;

	if ($marg == $uarg) return 12;
	
	$mparts = explode(":", $marg);
	$uparts = explode(":", $uarg);	
	
	if ( ($mparts[0] > $mparts[1]) && ($uparts[0] > $uparts[1]) ){
		return 5;
	} elseif ( ($mparts[0] == $mparts[1]) && ($uparts[0] == $uparts[1]) ){
		return 5;
	} else if ( ($mparts[0] < $mparts[1]) && ($uparts[0] < $uparts[1]) ){
		return 5;
	} else {
		return 0;
	}
}

/******************************************************************************
* function CalculateFinalMatchPoints(..)
* Berechnet die Spielpunkte eines Finals anhand des Spielresultats und der Finalgegner
******************************************************************************/
function CalculateFinalMatchPoints($marg,$uarg,$mode)
{
	//print "+CalculateFinalMatchPoints $marg,$uarg";
	
	if ( $marg == "-" ) return 0;	// Achtung: auf == "" checken funktioniert nicht!
	if ( $uarg == "-" ) die("Interner Programmfehler. Bitte Spielleitung kontaktieren!");
	
	$mparts = explode(":", $marg);
	$uparts = explode(":", $uarg);
	
	if ($mode == "REVERSE")
	{
		$tmp0 = $uparts[0];
		$tmp1 = $uparts[1];
		$uparts[0] = "$tmp1";
		$uparts[1] = "$tmp0";
	}
	
	if ( ($mparts[0] == $uparts[0]) && ($mparts[1] == $uparts[1]) ) return 20;
	
	if ( ($mparts[0] > $mparts[1]) && ($uparts[0] > $uparts[1]) ){
		return 10;
	} elseif ( ($mparts[0] == $mparts[1]) && ($uparts[0] == $uparts[1]) ){
		return 10;
	} else if ( ($mparts[0] < $mparts[1]) && ($uparts[0] < $uparts[1]) ){
		return 10;
	} else {
		return 0;
	}
}
/******************************************************************************
* GetWinnerOrEqual(...)
* Gibt den Spielgewinner oder "equal" zurück
******************************************************************************/
function GetWinnerOrEqual($T1,$T2,$Score)
{
	$ScoreParts = explode(":", $Score);
	
	if ($ScoreParts[0] > $ScoreParts[1])
		return $T1;
	else if ($ScoreParts[0] < $ScoreParts[1])
		return $T2;
	else
		return 99;
}	

/******************************************************************************
* GetWinner(...)
* Gibt den Spielgewinner zurück
******************************************************************************/
function GetWinner($T1,$T2,$Score)
{
	$ScoreParts = explode(":", $Score);
	
	if ($ScoreParts[0] > $ScoreParts[1])
		return $T1;
	else
		return $T2;
}
/******************************************************************************
* GetLooser(...)
* Gibt den Spielverlierer zurück
******************************************************************************/
function GetLooser($T1,$T2,$Score)
{
	$ScoreParts = explode(":", $Score);
	
	if ($ScoreParts[0] > $ScoreParts[1])
		return $T2;
	else
		return $T1;
}
/******************************************************************************
* CalculatePlayerList
******************************************************************************/
function CalculatePlayerList($player)
{
	require_once('config/config.php');

	$DebugPlayer = "Hirni";
	$Alpha = array(A,B,C,D,E,F,G,H,I,J,K);
	
	// Verbindung zum MySQL Server herstellen und Datenbank wählen
	$db=mysql_connect($db_serv, $db_user, $db_pass) or die ('I cannot connect to the database because: ' . mysql_error()); 
	mysql_select_db($db_name, $db) or die('ERROR!');

	// Alle Spielresultate aus Master-Eintrag lesen
	$query = mysql_query("select * from wmtotto2010 where PlayerName = 'Master';") or die(mysql_error());
	$mas = mysql_fetch_array($query);	

	//*****************************************************************************
	// Berechnung der Punkte einzelner Spieler
	//***************************************************************************** 

	$query = mysql_query("select count(*) as users from wmtotto2010;") or die(mysql_error());
	$row = mysql_fetch_array($query);
	$users = $row['users'];

	//print "<p>Die Datenbank hat $users Eintraege (inkl. Master)</p>";

	$query = mysql_query("select * from wmtotto2010 where PlayerName != 'Master';") or die(mysql_error());
	
	// Berechnungsschleife zur Berechnung der Punkte einzelner Spieler	
	$row = mysql_fetch_array($query);
	
	while ( !empty($row) )
	{
		$PlayerName = $row['PlayerName'];
		$Points = 0;
	
		// ********************************************************************
		// Gruppenspiele
		// ********************************************************************
		for ($i=1; $i<49; $i++)
		{
			$Game = 'Game'.$i;
			$GroupMatchPoints = CalculateGroupMatchPoints($mas[$Game],$row[$Game]);
	
			// Wenn die aktuelle Spielnummer mit der Jokergruppe übereinstimmt: Punkte * 2	

			if ($i<25)
			{
				if ( ( ($i-1) % 4) == array_search($row['GroupFavorite'],$Alpha)) 
				$GroupMatchPoints = $GroupMatchPoints * 2; 
			}
			else
			{			
				if ( ( ($i-1) % 4 + 4) == array_search($row['GroupFavorite'],$Alpha)) 
				$GroupMatchPoints = $GroupMatchPoints * 2;  
			}

			$Points += $GroupMatchPoints;			
		
			//if ($DebugPlayer == $PlayerName)
				//print "<p>Spieler $PlayerName hat nach Spiel " . $i . "=" . $Points . " Punkte</p>";
		}
		if ($DebugPlayer == $PlayerName)
			print "<p>Spieler $PlayerName hat nach Gruppenspielen $Points Punkte</p>";
		
		// ********************************************************************
		// Achtelfinalspiele - Mannschaftspunkte
		// ********************************************************************

		// mache eine Liste mit allen Achtelfinalgegnern, vergleiche alle Masterfelder mit der Liste und
		// gib 10 Punkte bei Übereinstimmung
		
		$EighthList = array();
		for ($i=49; $i<57; $i++)
		{
			array_push($EighthList,$row["Game" . $i . "_T" . '1']);
			array_push($EighthList,$row["Game" . $i . "_T" . '2']);
		}
		
		for ($i=49; $i<57; $i++)
		{
			if (in_array($mas["Game" . $i . "_T" . '1'],$EighthList)) { $Points += 10;}
			if (in_array($mas["Game" . $i . "_T" . '2'],$EighthList)) { $Points += 10;}
		}
		if ($DebugPlayer == $PlayerName)		
			print "<p>Spieler $PlayerName hat nach Achtelfinalspielen - Mannschaftspunkte " . $Points . " Punkte </p>";

		// ********************************************************************
		// Viertelfinalspiele - Mannschaftspunkte
		// ********************************************************************

		// mache eine Liste mit allen Viertelfinalgegnern, vergleiche alle Masterfelder mit der Liste 
		
		$EighthList = array();
		for ($i=57; $i<61; $i++)
		{
			array_push($EighthList,$row["Game" . $i . "_T" . '1']);
			array_push($EighthList,$row["Game" . $i . "_T" . '2']);
		}
		
		for ($i=57; $i<61; $i++)
		{
			if (in_array($mas["Game" . $i . "_T" . '1'],$EighthList)) { $Points += 10;}
			if (in_array($mas["Game" . $i . "_T" . '2'],$EighthList)) { $Points += 10;}
		}
		if ($DebugPlayer == $PlayerName)		
			print "<p>Spieler $PlayerName hat nach Viertelfinalspielen - Mannschaftspunkte " . $Points . " Punkte </p>";	

		// ********************************************************************
		// Halbfinalspiele - Mannschaftspunkte
		// ********************************************************************

		// mache eine Liste mit allen Halbfinalgegnern, vergleiche alle Masterfelder mit der Liste 
		
		$EighthList = array();
		for ($i=61; $i<63; $i++)
		{
			array_push($EighthList,$row["Game" . $i . "_T" . '1']);
			array_push($EighthList,$row["Game" . $i . "_T" . '2']);
		}
		
		for ($i=61; $i<63; $i++)
		{
			if (in_array($mas["Game" . $i . "_T" . '1'],$EighthList)) { $Points += 10;}
			if (in_array($mas["Game" . $i . "_T" . '2'],$EighthList)) { $Points += 10;}
		}
		if ($DebugPlayer == $PlayerName)		
			print "<p>Spieler $PlayerName hat nach Halbfinalgegnern - Mannschaftspunkte " . $Points . " Punkte </p>";	

		// ********************************************************************
		// KleinerFinal - Mannschaftspunkte
		// ********************************************************************

		// mache eine Liste mit allen Finalgegnern, vergleiche alle Masterfelder mit der Liste
		
		$EighthList = array();
		
		$i=63;
		array_push($EighthList,$row["Game" . $i . "_T" . '1']);
		array_push($EighthList,$row["Game" . $i . "_T" . '2']);
		
		if (in_array($mas["Game" . $i . "_T" . '1'],$EighthList)) { $Points += 10;}
		if (in_array($mas["Game" . $i . "_T" . '2'],$EighthList)) { $Points += 10;}
		
		if ($DebugPlayer == $PlayerName)		
			print "<p>Spieler $PlayerName hat nach KleinerFinal - Mannschaftspunkte " . $Points . " Punkte </p>";	

		// ********************************************************************
		// GrosserFinal - Mannschaftspunkte
		// ********************************************************************

		// mache eine Liste mit allen Finalgegnern, vergleiche alle Masterfelder mit der Liste	
		$EighthList = array();
		
		$i=64;
		array_push($EighthList,$row["Game" . $i . "_T" . '1']);
		array_push($EighthList,$row["Game" . $i . "_T" . '2']);
		
		if (in_array($mas["Game" . $i . "_T" . '1'],$EighthList)) { $Points += 10;}
		if (in_array($mas["Game" . $i . "_T" . '2'],$EighthList)) { $Points += 10;}
		
		if ($DebugPlayer == $PlayerName)		
			print "<p>Spieler $PlayerName hat nach GrosserFinal - Mannschaftspunkte " . $Points . " Punkte </p>";	

		// ********************************************************************
		// Achtelfinalspiele - Partiepunkte
		// ********************************************************************
		// Durchlaufe alle Achtelfinalspiele und berechne die Punkte, wenn die zwei getippten Gegner einander gegen�berstehen. 
		
		$ulist = array();
		$start = 49;
		$end = 56;
		
		// mache eine Liste der Spiele des Teilnehmers
		for ($i=$start; $i<=$end; $i++)
		{
			$u1 = $row["Game" . $i . "_T" . '1'];
			$u2 = $row["Game" . $i . "_T" . '2'];
			array_push($ulist,"$u1-$u2");
		}

		for ($i=$start; $i<=$end; $i++)
		{
			$m1 = $mas["Game" . $i . "_T" . '1'];
			$m2 = $mas["Game" . $i . "_T" . '2'];
			$uindex = array_search("$m1-$m2",$ulist);
			if (is_numeric($uindex))
			{		
				$j = $uindex + $start;
				$Points += CalculateFinalMatchPoints($mas["Game".$i],$row["Game".$j],"NORMAL");	
			} 
			else
			{
				$uindex = array_search("$m2-$m1",$ulist);
				if (is_numeric($uindex))
				{
					$j = $uindex + $start;
					$Points += CalculateFinalMatchPoints($mas["Game".$i],$row["Game".$j],"REVERSE");	
				}
			}
		}
		
		if ($DebugPlayer == $PlayerName)
			print "<p>Spieler $PlayerName hat nach Achtelfinalspiele - Partiepunkte " . $Points . " Punkte </p>";
		
		// ********************************************************************
		// Viertelfinalspiele - Partiepunkte
		// ********************************************************************
		// Durchlaufe alleViertel und berechne die Punkte, wenn die zwei getippten Gegner in der Korrelation einander gegenüberstehen. 
	
		$ulist = array();
		$start = 57;
		$end = 60;
		
		// mache eine Liste der Spiele des Teilnehmers
		for ($i=$start; $i<=$end; $i++)
		{
			$u1 = $row["Game" . $i . "_T" . '1'];
			$u2 = $row["Game" . $i . "_T" . '2'];
			array_push($ulist,"$u1-$u2");
		}

		for ($i=$start; $i<=$end; $i++)
		{
			$m1 = $mas["Game" . $i . "_T" . '1'];
			$m2 = $mas["Game" . $i . "_T" . '2'];
			$uindex = array_search("$m1-$m2",$ulist);
			if (is_numeric($uindex))
			{		
				$j = $uindex + $start;
				$Points += CalculateFinalMatchPoints($mas["Game".$i],$row["Game".$j],"NORMAL");	
			} 
		}
		
		if ($DebugPlayer == $PlayerName)
			print "<p>Spieler $PlayerName hat nach Viertelfinalspiele - Partiepunkte " . $Points . " Punkte </p>";
		
		// ********************************************************************
		// Halbfinalspiele - Partiepunkte
		// ********************************************************************
		// Durchlaufe alleHalbfinalspiele und berechne die Punkte, wenn die zwei getippten Gegner in der Korrelation einander gegenüberstehen. 
	
		$ulist = array();
		$start = 61;
		$end = 62;
		
		// mache eine Liste der Spiele des Teilnehmers
		for ($i=$start; $i<=$end; $i++)
		{
			$u1 = $row["Game" . $i . "_T" . '1'];
			$u2 = $row["Game" . $i . "_T" . '2'];
			array_push($ulist,"$u1-$u2");
		}

		for ($i=$start; $i<=$end; $i++)
		{
			$m1 = $mas["Game" . $i . "_T" . '1'];
			$m2 = $mas["Game" . $i . "_T" . '2'];
			$uindex = array_search("$m1-$m2",$ulist);
			if (is_numeric($uindex))
			{		
				$j = $uindex + $start;
				$Points += CalculateFinalMatchPoints($mas["Game".$i],$row["Game".$j],"NORMAL");	
			} 
		}
		
		if ($DebugPlayer == $PlayerName)
			print "<p>Spieler $PlayerName hat nach Halbfinalspiele - Partiepunkte " . $Points . " Punkte </p>";
		
		// ********************************************************************
		// Kleiner Final - Partiepunkte
		// ********************************************************************
		
		$ulist = array();
		$u1 = $row["Game63_T" . '1'];
		$u2 = $row["Game63_T" . '2'];
		array_push($ulist,"$u1-$u2");

		$m1 = $mas["Game63_T" . '1'];
		$m2 = $mas["Game63_T" . '2'];
		$uindex = array_search("$m1-$m2",$ulist);
		if (is_numeric($uindex))
			$Points += CalculateFinalMatchPoints($mas["Game63"],$row["Game63"],"NORMAL");	
		else
		{
			$uindex = array_search("$m2-$m1",$ulist);
			if (is_numeric($uindex))
				$Points += CalculateFinalMatchPoints($mas["Game63"],$row["Game63"],"REVERSE");	
		}
		
		if ($DebugPlayer == $PlayerName)
			print "<p>Spieler $PlayerName hat nach Kleiner Final - Partiepunkte " . $Points . " Punkte </p>";
		
		// ********************************************************************
		// Grosser Final - Partiepunkte
		// ********************************************************************
		$ulist = array();
		$u1 = $row["Game64_T" . '1'];
		$u2 = $row["Game64_T" . '2'];
		array_push($ulist,"$u1-$u2");

		$m1 = $mas["Game64_T" . '1'];
		$m2 = $mas["Game64_T" . '2'];
		$uindex = array_search("$m1-$m2",$ulist);
		if (is_numeric($uindex))
			$Points += CalculateFinalMatchPoints($mas["Game64"],$row["Game64"],"NORMAL");	
		else
		{
			$uindex = array_search("$m2-$m1",$ulist);
			if (is_numeric($uindex))
				$Points += CalculateFinalMatchPoints($mas["Game64"],$row["Game64"],"REVERSE");	
		}
		
		if ($DebugPlayer == $PlayerName)
			print "<p>Spieler $PlayerName hat nach Grosser Final - Partiepunkte " . $Points . " Punkte </p>";
		
		// ********************************************************************
		// Finalspiele - Finalpunkte
		// ********************************************************************
		if (	CheckGame($mas["Game64"]) &&
				(GetWinner($mas["Game64_T1"], $mas["Game64_T2"], $mas["Game64"]) 
				== 	GetWinner($row["Game64_T1"], $row["Game64_T2"], $row["Game64"])) 
			)
			$Points += 70;
			
		if (	CheckGame($mas["Game64"]) &&	
				(GetLooser($mas["Game64_T1"], $mas["Game64_T2"], $mas["Game64"]) 
				== 	GetLooser($row["Game64_T1"], $row["Game64_T2"], $row["Game64"])) )
			$Points += 40;	
			
		if (	CheckGame($mas["Game63"]) &&	
				(GetWinner($mas["Game63_T1"], $mas["Game63_T2"], $mas["Game63"]) 
				== 	GetWinner($row["Game63_T1"], $row["Game63_T2"], $row["Game63"])) )
			$Points += 30;	
		if (	CheckGame($mas["Game63"]) &&	
				(GetLooser($mas["Game63_T1"], $mas["Game63_T2"], $mas["Game63"]) 
				== 	GetLooser($row["Game63_T1"], $row["Game63_T2"], $row["Game63"])) )
			$Points += 20;	
	
		if ($DebugPlayer == $PlayerName)		
			print "<p>Spieler $PlayerName hat nach Finalspiele - Finalpunkte " . $Points . " Punkte </p>";
	
		$PlayerList["$PlayerName"] = $Points;
		
		$row = mysql_fetch_array($query);
	}
	
	print "<form action='form.php' method='post'>";
	print "<table align='center' width='800px' border='0' cellspacing='0' cellpadding='1'>";
	print "<tr><td bgcolor='#999999'>";
	print "<table width='100%' border='0' cellpadding='0' cellspacing='0' bgcolor='#CCCCCC'><tr><td>";
	print "<table width='100%' border='0' cellspacing='0' cellpadding='3'>";
	
	print "<input type='button' value='zur�ck' onClick='history.go(-1);'>";
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
		if ($key == $player)
			print "<tr bgcolor='lightgreen'><td>$rank</td><td>$key</td><td>$value</td></tr>";
		else
			print "<tr bgcolor=$color[$bg]><td>$rank</td><td>$key</td><td>$value</td></tr>";
		$old_value = $value;
	}
	
	print "</table></table></table></form>";
	
	//echo "<pre>";
	//print_r($PlayerList);
	//echo "</pre>";

	mysql_close($db);		
}  
?>
