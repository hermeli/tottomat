<?php
require_once('config.php'); 
require_once('classes.php');

/***********************************************************************
* function MessageBox($message)
***********************************************************************/
function MessageBox($message)
{
	print "<script type='text/javascript' language='javascript'>\n";
	print "<!--\n";
	print " alert('".$message."');\n";
	print "//-->\n";
	print "</script>";  
}
/***********************************************************************
* function DebugMsg($message)
***********************************************************************/
function DebugMsg($message)
{
	global $DEBUG;
	
	if ($DEBUG == 1)
		print $message;  
}

/***********************************************************************
* function CalculatePlayerScore()
***********************************************************************/
function CalculatePlayerScore()
{
	global $player;
	global $matches;
	
	//global $username;
	global $PlayerScore;
	global $mas;

	// Alle Spielresultate aus Datenbank lesen
	$query = mysql_query("select * from wmtotto2014 where PlayerName = '" .$player->username. "';") or die(mysql_error());
	$usr = mysql_fetch_array($query);	

	$DebugPlayer = "dummy";
	$PlayerName = $player->username; //$usr['PlayerName'];

	// *****************************************************************
	// Gruppenspiele
	// *****************************************************************
	for ($i=0; $i<48; $i++)
	{
		$Game = 'Game'.$i;
		$GroupMatchPoints = CalculateGroupMatchPoints($mas[$Game],$usr[$Game]);
		$matches[$i]->playPts = $GroupMatchPoints;

		// Wenn die aktuelle Spielnummer mit der Jokergruppe übereinstimmt: Punkte * 2	
		if ( (floor($i/6)) == array_search($usr['GroupFavorite'],array("A","B","C","D","E","F","G","H")) )
		{ 
			$GroupMatchPoints = $GroupMatchPoints * 2; 
		}
		$PlayerScore += $GroupMatchPoints;			

		// Speicher Punkte auch in Matchobjekt
		$matches[$i]->matchPts = $GroupMatchPoints;
		
		//if ($DebugPlayer == $PlayerName)
			//print "<p>Spieler $PlayerName hat nach Spiel " . $i . "=" . $PlayerScore . " Punkte</p>";
	}
		
	if ($DebugPlayer == $PlayerName)
		print "<p>Spieler $PlayerName hat nach Gruppenspielen $PlayerScore Punkte</p>";
	
	// *****************************************************************
	// Achtelfinalspiele - Mannschaftspunkte
	// *****************************************************************
	// mache eine Liste mit allen Mastergegnern, vergleiche alle 
	// Usergegner mit der Liste 
	$start = 48;
	$end = 55;
	
	$TeamList = array();
	for ($i=$start; $i<=$end; $i++)
	{
		$q1 = $mas["Game" . $i . "_T" . '1'];
		$q2 = $mas["Game" . $i . "_T" . '2'];	
		if ($q1 != "") array_push($TeamList,$q1);
		if ($q2 != "") array_push($TeamList,$q2);
	}
	
	for ($i=$start; $i<=$end; $i++)
	{
		if (in_array($usr["Game" . $i . "_T" . '1'],$TeamList)) 
		{ 
			$PlayerScore += 10;
			$matches[$i]->matchPts += 10;
			$matches[$i]->team1_hit = 1;
		}
		if (in_array($usr["Game" . $i . "_T" . '2'],$TeamList)) 
		{ 
			$PlayerScore += 10;
			$matches[$i]->matchPts += 10;
			$matches[$i]->team2_hit = 1;
		}
	}
	if ($DebugPlayer == $PlayerName)
	{
		print "<p>Spieler $PlayerName hat nach Achtelfinal - Mannschaftspunkte " . $PlayerScore . " Punkte </p>";	
	}	
	
	// ********************************************************************
	// Achtelfinalspiele - Partiepunkte
	// ********************************************************************
	// Durchlaufe alle Achtelfinals und berechne die Punkte, wenn die zwei 
	// getippten Gegner in der Korrelation einander gegenüberstehen. 
	$OpponentList = array();

	// mache eine Liste der Spiele des Masters
	for ($i=$start; $i<=$end; $i++)
	{
		$u1 = $mas["Game" . $i . "_T" . '1'];
		$u2 = $mas["Game" . $i . "_T" . '2'];
		
		if ( ($u1!="")&&($u2!="") )
			array_push($OpponentList,"$u1-$u2");
	}

	for ($i=$start; $i<=$end; $i++)
	{
		$m1 = $usr["Game" . $i . "_T" . '1'];
		$m2 = $usr["Game" . $i . "_T" . '2'];
		$uindex = array_search("$m1-$m2",$OpponentList);
		if (is_numeric($uindex))
		{		
			$j = $uindex + $start;
			$tmp = CalculateFinalMatchPoints($usr["Game".$i],$mas["Game".$j],"NORMAL");
			$matches[$i]->playPts = $tmp;
			$PlayerScore += $tmp;
			$matches[$i]->matchPts += $tmp;	
		} 
		else
		{
			$mindex = array_search("$m2-$m1",$OpponentList);
			if (is_numeric($mindex))
			{
				$j = $mindex + $start;
				$tmp = CalculateFinalMatchPoints($usr["Game".$i],$mas["Game".$j],"REVERSE");
				$matches[$i]->playPts = $tmp;
				$PlayerScore += $tmp;
				$matches[$i]->matchPts += $tmp;
			}
		}
	}
	
	if ($DebugPlayer == $PlayerName)
		print "<p>Spieler $PlayerName hat nach Achtelfinal - Partiepunkte " . $PlayerScore . " Punkte </p>";

	// *****************************************************************
	// Viertelfinalspiele - Mannschaftspunkte
	// *****************************************************************
	// mache eine Liste mit allen Viertelfinalgegnern, vergleiche alle Masterfelder mit der Liste 
	$start = 56;
	$end = 59;
	
	$TeamList = array();
	for ($i=$start; $i<=$end; $i++)
	{
		$q1 = $mas["Game" . $i . "_T" . '1'];
		$q2 = $mas["Game" . $i . "_T" . '2'];	
		if ($q1 != "") array_push($TeamList,$q1);
		if ($q2 != "") array_push($TeamList,$q2);
	}
	
	for ($i=$start; $i<=$end; $i++)
	{
		if (in_array($usr["Game" . $i . "_T" . '1'],$TeamList))
		{ 
			$PlayerScore += 10;
			$matches[$i]->matchPts += 10;
			$matches[$i]->team1_hit = 1;
		}
		if (in_array($usr["Game" . $i . "_T" . '2'],$TeamList)) 
		{ 
			$PlayerScore += 10;
			$matches[$i]->matchPts += 10;
			$matches[$i]->team2_hit = 1;
		}
	}
	if ($DebugPlayer == $PlayerName)
	{
		print "<p>Spieler $PlayerName hat nach Viertelfinal - Mannschaftspunkte " . $PlayerScore . " Punkte </p>";	
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
		$u1 = $mas["Game" . $i . "_T" . '1'];
		$u2 = $mas["Game" . $i . "_T" . '2'];
		
		if ( ($u1!="")&&($u2!="") )
			array_push($OpponentList,"$u1-$u2");
	}

	for ($i=$start; $i<=$end; $i++)
	{
		$m1 = $usr["Game" . $i . "_T" . '1'];
		$m2 = $usr["Game" . $i . "_T" . '2'];
		$uindex = array_search("$m1-$m2",$OpponentList);
		if (is_numeric($uindex))
		{		
			$j = $uindex + $start;
			$tmp = CalculateFinalMatchPoints($usr["Game".$i],$mas["Game".$j],"NORMAL");	
			$matches[$i]->playPts = $tmp;
			$PlayerScore += $tmp;
			$matches[$i]->matchPts += $tmp;
		} 
		/*
		else
		{
			$uindex = array_search("$m2-$m1",$OpponentList);
			if (is_numeric($uindex))
			{
				$j = $uindex + $start;
				$tmp = CalculateFinalMatchPoints($usr["Game".$i],$mas["Game".$j],"REVERSE");
				//$matches[$i]->playPts = $tmp;
				$PlayerScore += $tmp;
				$matches[$i]->matchPts += $tmp;	
			}
		}
		*/
	}
	
	if ($DebugPlayer == $PlayerName)
		print "<p>Spieler $PlayerName hat nach Viertelfinal - Partiepunkte " . $PlayerScore . " Punkte </p>";
	

	// ********************************************************************
	// Halbfinalspiele - Mannschaftspunkte
	// ********************************************************************
	// mache eine Liste mit allen Halbfinalgegnern, vergleiche alle Masterfelder mit der Liste 
	
	$TeamList = array();
	$start = 60;
	$end = 61;
	
	for ($i=$start; $i<=$end; $i++)
	{
		$q1 = $mas["Game" . $i . "_T" . '1'];
		$q2 = $mas["Game" . $i . "_T" . '2'];	
		if ($q1 != "") array_push($TeamList,$q1);
		if ($q2 != "") array_push($TeamList,$q2);
	}
	
	for ($i=$start; $i<=$end; $i++)
	{
		if (in_array($usr["Game" . $i . "_T" . '1'],$TeamList)) 
		{ 
			$PlayerScore += 10;
			$matches[$i]->matchPts += 10;
			$matches[$i]->team1_hit = 1;
		}
		if (in_array($usr["Game" . $i . "_T" . '2'],$TeamList)) 
		{ 
			$PlayerScore += 10;
			$matches[$i]->matchPts += 10;
			$matches[$i]->team2_hit = 1;
		}
	}
	if ($DebugPlayer == $PlayerName)		
		print "<p>Spieler $PlayerName hat nach Halbfinal - Mannschaftspunkte " . $PlayerScore . " Punkte </p>";	

	// ********************************************************************
	// Halbfinalspiele - Partiepunkte
	// ********************************************************************
	// Durchlaufe alle Halbfinalspiele und berechne die Punkte, wenn die zwei 
	// getippten Gegner einander direkt gegenüberstehen. 
	$OpponentList = array();
		
	// mache eine Liste der Gegner des Teilnehmers
	for ($i=$start; $i<=$end; $i++)
	{
		$u1 = $mas["Game" . $i . "_T" . '1'];
		$u2 = $mas["Game" . $i . "_T" . '2'];
		if ( ($u1!="")&&($u2!="") )
			array_push($OpponentList,"$u1-$u2");
	}

	for ($i=$start; $i<=$end; $i++)
	{
		$m1 = $usr["Game" . $i . "_T" . '1'];
		$m2 = $usr["Game" . $i . "_T" . '2'];
		$uindex = array_search("$m1-$m2",$OpponentList);
		if (is_numeric($uindex))
		{		
			$j = $uindex + $start;
			$tmp = CalculateFinalMatchPoints($usr["Game".$i],$mas["Game".$j],"NORMAL");	
			$matches[$i]->playPts = $tmp;
			$PlayerScore += $tmp;
			$matches[$i]->matchPts += $tmp;
		} 
	}
	
	if ($DebugPlayer == $PlayerName)
		print "<p>Spieler $PlayerName hat nach Halbfinal - Partiepunkte " . $PlayerScore . " Punkte </p>";
	
		
	// ********************************************************************
	// Finalspiele - Mannschaftspunkte
	// ********************************************************************
	// mache eine Liste mit allen Finalgegnern, vergleiche alle Masterfelder mit der Liste 
	
	$TeamList = array();
	$start = 62;
	$end = 63;
	
	for ($i=$start; $i<=$end; $i++)
	{
		$q1 = $mas["Game" . $i . "_T" . '1'];
		$q2 = $mas["Game" . $i . "_T" . '2'];	
		if ($q1 != "") array_push($TeamList,$q1);
		if ($q2 != "") array_push($TeamList,$q2);
	}
	
	for ($i=$start; $i<=$end; $i++)
	{
		if (in_array($usr["Game" . $i . "_T" . '1'],$TeamList)) 
		{ 
			$PlayerScore += 10;
			$matches[$i]->matchPts += 10;
			$matches[$i]->team1_hit = 1;
		}
		if (in_array($usr["Game" . $i . "_T" . '2'],$TeamList)) 
		{ 
			$PlayerScore += 10;
			$matches[$i]->matchPts += 10;
			$matches[$i]->team2_hit = 1;
		}
	}
	if ($DebugPlayer == $PlayerName)		
		print "<p>Spieler $PlayerName hat nach Final - Mannschaftspunkte " . $PlayerScore . " Punkte </p>";	

	// ********************************************************************
	// Finalspiele - Partiepunkte
	// ********************************************************************
	// Durchlaufe alle Finalspiele und berechne die Punkte, wenn die zwei 
	// getippten Gegner einander direkt gegenüberstehen. 
	$OpponentList = array();
		
	// mache eine Liste der Gegner des Teilnehmers
	for ($i=$start; $i<=$end; $i++)
	{
		$u1 = $mas["Game" . $i . "_T" . '1'];
		$u2 = $mas["Game" . $i . "_T" . '2'];
		if ( ($u1!="")&&($u2!="") )
			array_push($OpponentList,"$u1-$u2");
	}

	for ($i=$start; $i<=$end; $i++)
	{
		$m1 = $usr["Game" . $i . "_T" . '1'];
		$m2 = $usr["Game" . $i . "_T" . '2'];
		$uindex = array_search("$m1-$m2",$OpponentList);
		if (is_numeric($uindex))
		{		
			$j = $uindex + $start;
			$tmp = CalculateFinalMatchPoints($usr["Game".$i],$mas["Game".$j],"NORMAL");
			$matches[$i]->playPts = $tmp;
			$PlayerScore += $tmp;
			$matches[$i]->matchPts += $tmp;	
		} 
	}
	
	if ($DebugPlayer == $PlayerName)
		print "<p>Spieler $PlayerName hat nach Final - Partiepunkte " . $PlayerScore . " Punkte </p>";
	
	// ********************************************************************
	// Finalspiele - Finalpunkte
	// ********************************************************************

	if (	CheckGame($mas["Game62"]) &&	
			(GetLooser($mas["Game62_T1"], $mas["Game62_T2"], $mas["Game62"]) 
			== 	GetLooser($usr["Game62_T1"], $usr["Game62_T2"], $usr["Game62"])) )
	{
		$matches[62]->matchPts += 20;
		$PlayerScore += 20;	
	}
	
	if (	CheckGame($mas["Game62"]) &&	
			(GetWinner($mas["Game62_T1"], $mas["Game62_T2"], $mas["Game62"]) 
			== 	GetWinner($usr["Game62_T1"], $usr["Game62_T2"], $usr["Game62"])) )
	{
		$matches[62]->matchPts += 30;
		$PlayerScore += 30;	
	}
	if (	CheckGame($mas["Game63"]) &&	
			(GetLooser($mas["Game63_T1"], $mas["Game63_T2"], $mas["Game63"]) 
			== 	GetLooser($usr["Game63_T1"], $usr["Game63_T2"], $usr["Game63"])) )
	{
		$matches[63]->matchPts += 40;
		$PlayerScore += 40;	
	}
	if (	CheckGame($mas["Game63"]) &&	
			(GetWinner($mas["Game63_T1"], $mas["Game63_T2"], $mas["Game63"]) 
			== 	GetWinner($usr["Game63_T1"], $usr["Game63_T2"], $usr["Game63"])) )
	{
		$matches[63]->matchPts += 70;
		$PlayerScore += 70;	
	}
		
	if ($DebugPlayer == $PlayerName)		
		print "<p>Spieler $PlayerName hat nach Final - Finalpunkte " . $PlayerScore . " Punkte </p>";
		
	$PlayerList["$Name"] = $PlayerScore;
	
	if ($DebugPlayer == $PlayerName)
		foreach ($matches as $key)
			print "MatchNr: ".$key->matchNr." hit1:".$key->team1_hit." hit2:".$key->team2_hit." matchPts:".$key->matchPts." playPts:".$key->playPts."<br>";

	$player->score = $PlayerScore;
}

/***********************************************************************
* function CalculateLastSexteenFinals()
***********************************************************************/
function CalculateLastSixteenFinals()
{
	/*
	Regeln nach FIFA:
    1. höhere Anzahl Punkte
    2. bessere Tordifferenz
    3. höhere Anzahl erzielter Tore
    4. höhere Anzahl Punkte aus den Direktbegegnungen zwischen den punkt- und torgleichen Mannschaften
    5. bessere Tordifferenz aus den Direktbegegnungen zwischen den punkt- und torgleichen Mannschaften
    6. höhere Anzahl Tore aus den Direktbegegnungen zwischen den punkt- und torgleichen Mannschaften
    7. höhere Anzahl Auswärtstore aus den Direktbegegnungen zwischen den punkt- und torgleichen Mannschaften, 
    *  wenn nur zwei Mannschaften betroffen sind.
	*/
	global $matches;
	global $player;
	global $teams;
	
	if ($player->groupFavorite=="")
	{
		MessageBox("Bitte Jokergruppe angeben!");
		return;
	}
	
	for ($i=0; $i<48; $i++)
	{		
		if (checkGame($matches[$i]->matchRes) == 0)
		{
			MessageBox("Eingabefehler bei Gruppenspiel in Gruppe ".$matches[$i]->group);
			return;
		}
		
		$ScoreParts = explode(":",$matches[$i]->matchRes);
		
		// update score per team
		if ($ScoreParts[0] > $ScoreParts[1])
			$matches[$i]->team1->score += 3;
		else if ($ScoreParts[0] < $ScoreParts[1])
			$matches[$i]->team2->score += 3;
		else
		{
			$matches[$i]->team1->score += 1;
			$matches[$i]->team2->score += 1;
		}
		
		// update diffgoals per team
		$matches[$i]->team1->diffgoals += $ScoreParts[0] - $ScoreParts[1]; 
		$matches[$i]->team2->diffgoals += $ScoreParts[1] - $ScoreParts[0];
		
		// update goals per team
		$matches[$i]->team1->goals += $ScoreParts[0];
		$matches[$i]->team2->goals += $ScoreParts[1]; 
	}
	
	foreach ($teams as $team)
	{
		$msg =  $team->name . " score: " . $team->score . " diff: " . $team->diffgoals . " goals: " . $team->goals . "</br>";
		DebugMsg($msg);
	}
	
	// load calculated finalists from group matches
	$matches[48]->team1 = GetTeamWithRank("A",1);
	$matches[48]->team2 = GetTeamWithRank("B",2);
	$matches[49]->team1 = GetTeamWithRank("C",1);
	$matches[49]->team2 = GetTeamWithRank("D",2);
	$matches[50]->team1 = GetTeamWithRank("B",1);
	$matches[50]->team2 = GetTeamWithRank("A",2);
	$matches[51]->team1 = GetTeamWithRank("D",1);
	$matches[51]->team2 = GetTeamWithRank("C",2);
	$matches[52]->team1 = GetTeamWithRank("E",1);
	$matches[52]->team2 = GetTeamWithRank("F",2);
	$matches[53]->team1 = GetTeamWithRank("G",1);
	$matches[53]->team2 = GetTeamWithRank("H",2);
	$matches[54]->team1 = GetTeamWithRank("F",1);
	$matches[54]->team2 = GetTeamWithRank("E",2);
	$matches[55]->team1 = GetTeamWithRank("H",1);
	$matches[55]->team2 = GetTeamWithRank("G",2);
}
/***********************************************************************
* function CalculateQuarterFinals()
* 
***********************************************************************/
function CalculateQuarterFinals()
{	
	global $matches;
	global $player;
	global $teams;
		
	// Prüfe zuerst, ob alle Achtelfinalgegner i.O. sind
	for ($i=48; $i<56; $i++)
	{
		$team1 = $matches[$i]->team1->name;
		$team2 = $matches[$i]->team2->name;
		
		print "Team1:$content1 Team2:$team2";
		
		if ( ($team1=="")||($team2=="")||($team1==$team2) )
		{
			MessageBox("Bitte zuerst Achtelfinalgegner berechnen! ($i)");
			return;
		}
	}
	
	// Dann prüfe, ob die Spielresultate der Achtelfinalgegner i.O. sind
	$errGame = checkAllGames();
	if ( ($errGame < 56)&&($errGame!=-1) )
	{
		MessageBox("Eingabefehler bei Spiel $errGame!");
		return;
	}
	
	// Berechne die Viertefinalteilnehmer
	$matches[56]->team1 = GetWinner($matches[48]);
	$matches[56]->team2 = GetWinner($matches[49]);
	$matches[57]->team1 = GetWinner($matches[50]);
	$matches[57]->team2 = GetWinner($matches[51]);
	$matches[58]->team1 = GetWinner($matches[52]);
	$matches[58]->team2 = GetWinner($matches[53]);
	$matches[59]->team1 = GetWinner($matches[54]);
	$matches[59]->team2 = GetWinner($matches[55]);
}

/***********************************************************************
* function CalculateHalfFinals()
* 
***********************************************************************/
function CalculateHalfFinals()
{		
	global $matches;
	
	// Prüfe zuerst, ob alle Viertelfinalgegner i.O. sind
	for ($i=56; $i<60; $i++)
	{
		$team1 = $_POST['Game'.$i.'_T1'];
		$team2 = $_POST['Game'.$i.'_T2'];
		
		if ( ($team1=="")||($team2=="")||($team1==$team2) )
		{
			MessageBox("Bitte zuerst Viertelfinalgegner berechnen!");
			return;
		}
	}
	
	// Dann prüfe, ob die Spielresultate der Viertelfinalgegner i.O. sind
	$errGame = checkAllGames();
	if ( ($errGame < 60)&&($errGame!=-1) )
	{
		MessageBox("Eingabefehler bei Spiel $errGame!");
		return;
	}
	
	// Berechne die Halbfinalteilnehmer
	$matches[60]->team1 = GetWinner($matches[56]);
	$matches[60]->team2 = GetWinner($matches[57]);
	$matches[61]->team1 = GetWinner($matches[58]);
	$matches[61]->team2 = GetWinner($matches[59]);
}
/***********************************************************************
* function CalculateFinals()
* 
***********************************************************************/
function CalculateFinals()
{	
	global $matches;
		
	// Prüfe zuerst, ob alle Halbfinalspiele i.O. sind
	for ($i=60; $i<62; $i++)
	{
		$team1 = $_POST['Game'.$i.'_T1'];
		$team2 = $_POST['Game'.$i.'_T2'];
		
		if ( ($team1=="")||($team2=="")||($team1==$team2) )
		{
			MessageBox("Bitte zuerst Halbfinalgegner berechnen!");
			return;
		}
	}
	
	// Dann prüfe, ob die Spielresultate der Halbfinalgegner i.O. sind
	$errGame = checkAllGames();
	if ( ($errGame < 62)&&($errGame!=-1) )
	{
		MessageBox("Eingabefehler bei Spiel $errGame!");
		return;
	}
	
	// Berechne die Finalteilnehmer
	$matches[62]->team1 = GetLooser($matches[60]);
	$matches[62]->team2 = GetLooser($matches[61]);
	$matches[63]->team1 = GetWinner($matches[60]);
	$matches[63]->team2 = GetWinner($matches[61]);
}
/***********************************************************************
* function CalculateChampion()
* 
***********************************************************************/
function CalculateChampion()
{	
	global $matches;
	global $player;
		
	// Prüfe zuerst, ob alle Finalspiele i.O. sind
	for ($i=62; $i<64; $i++)
	{
		$team1 = $_POST['Game'.$i.'_T1'];
		$team2 = $_POST['Game'.$i.'_T2'];
		
		if ( ($team1=="")||($team2=="")||($team1==$team2) )
		{
			MessageBox("Bitte zuerst Finalgegner berechnen!");
			return;
		}
	}
	
	// Dann prüfe, ob die Spielresultate der Finalgegner i.O. sind
	$errGame = checkAllGames();
	if ( ($errGame < 65)&&($errGame != -1) )
	{
		MessageBox("Eingabefehler bei Spiel $errGame!");
		$player->champion = "";
		return;
	}
	
	// Berechne den Weltmeister
	$player->champion = GetWinner($matches[63])->name;
}
/***********************************************************************
* function GetTeam($tname)
* Liefert die Objekt-Referenz eines Teams ausgehend vom Teamnamen
***********************************************************************/
function GetTeam($tname)
{
	global $teams;
	
	if ($tname == "")
		return;
		
	foreach ($teams as $team)
	{
		if ($team->name == $tname)
		{
			return $team;
		}
	}
	
	MessageBox("Programming error GetTeam ($tname unknown)!");
}

/***********************************************************************
 * SavePlayerScoreToDB
 * 
 * Alle Formularwerte werden in der Datenbank gespeichert
  *********************************************************************/
function SavePlayerScoreToDB()
{	
	// Speichere Punktezahl in Datenbank
	global $player;
	
	// Update des bestehenden Datenbankeintrags für Spieler
	$sql = "UPDATE wmtotto2014 SET Score='".$player->score."' WHERE PlayerName='".$player->username."'";		

	mysql_query($sql) or die(mysql_error());
}
/***********************************************************************
 * SaveMatchesToDB
 * 
 * Alle Matchwerte werden in der Datenbank gespeichert
  *********************************************************************/
function SaveMatchesToDB()
{	
	// Speichere Spiele in Datenbank
	global $player;
	global $matches;
		
	// prüfe, ob EditMode == 1 (Modifikation der DB erlaubt)
	$query = mysql_query("select * from config;") or die(mysql_error());
	$dbresult = mysql_fetch_array($query);	
			
	if ($dbresult['EditMode'] == 0)
	{
		MessageBox("Speichern nicht mehr erlaubt!");
		return;
	}
	
	// Teste, ob der Spieler bereits einen Datenbankeintrag hat
	$sql = "SELECT COUNT(*) FROM wmtotto2014 WHERE PlayerName = '".$player->username."';";
	$query = mysql_query($sql) or die(mysql_error());
	$dbresult = mysql_fetch_array($query);		

	if ($dbresult[0] == 0) 
	{
		// Erzeuge neuen Datenbankeintrag für Spieler		
		$sql = "INSERT INTO wmtotto2014 (FormComplete,Score,Name,PlayerName,Champion,GroupFavorite,TotalGoals";
		
		for ($i=0; $i<64; $i++)			
			$sql = $sql . ", Game$i";

		for ($i=48; $i<64; $i++)
			$sql = $sql . ", Game${i}_T1" . ", Game${i}_T2"; 
		
		$formComplete = 0;	
		$sql = $sql . ") values ('" . $formComplete . "'," .
								"'" . $player->score . "'," .
								"'" . $player->name . "'," .
								"'" . $player->username . "'," .
								"'" . $player->champion . "'," . 
								"'" . $player->groupFavorite . "'," .
								"'" . $player->totalGoals . "'";
	
		for ($i=0; $i<64; $i++)
			$sql = $sql . ", '" . $matches[$i]->matchRes . "'";
			
		for ($i=48; $i<64; $i++)
			$sql = $sql . ",'" . $matches[$i]->team1->name . "','" . $matches[$i]->team2->name . "'";	
			$sql = $sql . ")";
		
		mysql_query($sql) or die(mysql_error());
		
		if (isset($_POST['savetodb']))
			MessageBox("Die Spielresultate wurden gespeichert! (Neuer Eintrag)");
	}
	else 
	{
		// Update des bestehenden Datenbankeintrags für Spieler (Score wird später nachgeführt!)
		$formComplete = isFormComplete();	
		$sql = "UPDATE wmtotto2014 SET FormComplete='" . $formComplete . "'," .
							"Name='" . $player->name . "'," .
							"Champion='" . $player->champion . "',".
							"GroupFavorite='" . $player->groupFavorite . "',".
							"TotalGoals='" . $player->totalGoals . "'";
		
		for ($i=0; $i<64; $i++)
			$sql = $sql . ", Game$i='" . $matches[$i]->matchRes . "'";
			
		for ($i=48; $i<64; $i++)
		{
			$T1 = 'Game'.$i.'_T1';
			$T2 = 'Game'.$i.'_T2';
			$sql = $sql . ", Game".$i."_T1='".$matches[$i]->team1->name.
			"', Game${i}_T2='" . $matches[$i]->team2->name . "' "; 
		}
		$sql = $sql . "WHERE PlayerName='".$player->username."'";		

		// print "$sql";
		mysql_query($sql) or die(mysql_error());
		
		if (isset($_POST['savetodb']))
			MessageBox("Die Spielresultate wurden gespeichert! (Update)");
	}	
}

/***********************************************************************
* function LoadMatchesFrom($source)
* 
***********************************************************************/
function LoadMatchesFrom($source)
{
	global $matches;
	global $player;
		
	if ($source == "DB")
	{
		// Alle Spielresultate aus Datenbank lesen
		$query = mysql_query("select * from wmtotto2014 where PlayerName = '" .$player->username. "';") or die(mysql_error());
		$dbvals = mysql_fetch_array($query);	
	
		// laden der Datenfelder in die Struktur	
		$player->groupFavorite = $dbvals['GroupFavorite'];
		$player->totalGoals = $dbvals['TotalGoals'];
		$player->champion = $dbvals['Champion'];

		for ($i=0; $i<64; $i++){
			$index="Game" . $i;
			$matches[$i]->matchRes = $dbvals[$index]; 
		}

		for ($i=48; $i<64; $i++)
		{
			$index1="Game" . $i . "_T1";
			$index2="Game" . $i . "_T2";
			$matches[$i]->team1 = GetTeam($dbvals[$index1]);
			$matches[$i]->team2 = GetTeam($dbvals[$index2]);
		}
	}
	elseif ($source == "POST")
	{
		$player->groupFavorite = $_POST['GroupFavorite'];
		$player->totalGoals = $_POST['TotalGoals'];
		$player->champion = $_POST['Champion'];

		for ($i=0; $i<64; $i++){
			$index="Game" . $i;
			$matches[$i]->matchRes = $_POST[$index]; 
		}

		for ($i=48; $i<64; $i++)
		{
			$index1="Game" . $i . "_T1";
			$index2="Game" . $i . "_T2";
			$matches[$i]->team1 = GetTeam($_POST[$index1]);
			$matches[$i]->team2 = GetTeam($_POST[$index2]);
		}
	}
	else
	{
		MessageBox("Programmfehler (LoadMatchesFrom): Bitte Administrator informieren!");
	}
}

/***********************************************************************
* function GetTeamWithRank($group, $rank)
* Gibt das Team einer Gruppe mit dem angegebenen Rang (1. oder 2.) zurück.
***********************************************************************/
function GetTeamWithRank($group,$rank)
{	
	global $teams;
	
	$first = NEW team(-1,"Z",NULL);
	$second = NEW team(-1,"Z",NULL);
	
	foreach ($teams as $team)
	{
		if ($team->group == $group)
		{
			if ($team->score >= $first->score)
			{
				if ($team->diffgoals >= $first->diffgoals)
					if ($team->goals >= $first->goals)
					{	
						$second = clone $first;
						$first = clone $team;
					}
			}
			elseif ($team->score >= $second->score)
				$second = clone $team;
		}
	}

	//print "Group $group first " . $first->name . " second " . $second->name . "</br>";

	if ($rank == 1)
		return $first;
	else
		return $second;
}

/***********************************************************************
* function checkAllGames(..)
* checkt alle Game-Eingabefelder und gibt die Game-Fehlernummer zurück
***********************************************************************/
function checkAllGames()
{	
	for ($i=0; $i<64; $i++)
	{
		$j = $i+1;
		$game = $_POST['Game'.$i];
		
		if ($game == "") return $j;
		if ($game == "-") return $j;
	
		$parts = explode(":", $game);
	
		if ( !is_numeric($parts[0]) ) return $j;
		if ( !is_numeric($parts[1]) ) return $j; 
		if ( $parts[0] < 0 ) return $j;
		if ( $parts[0] > 99) return $j;
		if ( $parts[1] < 0 ) return $j;
		if ( $parts[1] > 99) return $j;
		
		// check even final game
		if ( ($j>48) && ($j<65) && ($parts[0] == $parts[1]) ) 
			return $j;
	}
	return -1;
}
/***********************************************************************
* function checkAllTeams(..)
* checkt alle Team-Eingabefelder
***********************************************************************/
function checkAllTeams()
{	
	for ($i=48; $i<64; $i++)
	{
		$team1 = $_POST['Game'.$i.'_T1'];
		$team2 = $_POST['Game'.$i.'_T2'];
		
		if ( ($team1=="")||($team2=="")||($team1==$team2) )
			return $i;
	}	
	return -1;
}

/***********************************************************************
 * function checkFormComplete()
 * 
 * ********************************************************************/
function isFormComplete()
{
	$eGame=checkAllGames();
	$eTeam=checkAllTeams();
	if ( ($eGame!=-1) || ($eTeam!=-1) || $_POST['GroupFavorite']=="" || $_POST['TotalGoals']=="0" )
		return 0;
	else
		return 1;
}

/***********************************************************************
* function checkGame(..)
* checkt die Game-Eingabefelder
***********************************************************************/
function checkGame($game)
{	
	if ($game == "") return 0;
	if ($game == "-") return 0;
	
	$parts = explode(":", $game);
	
	if ( !is_numeric($parts[0]) ) return 0;
	if ( !is_numeric($parts[1]) ) return 0; 
	if ( $parts[0] < 0 ) return 0;
	if ( $parts[0] > 99) return 0;
	if ( $parts[1] < 0 ) return 0;
	if ( $parts[1] > 99) return 0;
	
	return 1;
}
/***********************************************************************
* function checkEvenFinal(..)
* checkt die Game-Eingabefelder
***********************************************************************/
function checkEvenFinal($game)
{	
	$parts = explode(":", $game);
	
	if ( $parts[0] == $parts[1] ) 
		return 0;
	else
		return 1;
}

/***********************************************************************
* function CalculateGroupMatchPoints(..)
* Berechnet die Spielpunkte eines Gruppenspiels anhand des Spielresultats 
***********************************************************************/
function CalculateGroupMatchPoints($marg,$uarg)
{
	//print "+CalculateGroupMatchPoints: marg=$marg, uarg=$uarg";
	
	if (!checkGame($marg)) return 0;
	if (!checkGame($uarg)) return 0;

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

/***********************************************************************
* function CalculateFinalMatchPoints(..)
* Berechnet die Spielpunkte eines Finals anhand des Spielresultats und 
* der Finalgegner.
***********************************************************************/
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
	
	if ( ($mparts[0] == $uparts[0]) && ($mparts[1] == $uparts[1]) ) return 12;
	
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

/***********************************************************************
* GetWinnerOrEqual(...)
* Gibt den Spielgewinner oder "equal" zurück
***********************************************************************/
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

/***********************************************************************
* GetWinner(...)
* Gibt den Spielgewinner zurück
***********************************************************************/
function GetWinner($match)
{
	$ScoreParts = explode(":", $match->matchRes);
	
	if ($ScoreParts[0] > $ScoreParts[1])
		return $match->team1;
	else
		return $match->team2;
}
/***********************************************************************
* GetLooser(...)
* Gibt den Spielverlierer zurück
***********************************************************************/
function GetLooser($match)
{
	$ScoreParts = explode(":", $match->matchRes);
	
	if ($ScoreParts[0] < $ScoreParts[1])
		return $match->team1;
	else
		return $match->team2;
}

?>
