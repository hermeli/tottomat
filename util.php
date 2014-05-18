<?php
setlocale(LC_ALL, 'UTF-8');

/***********************************************************************
* Trikot-Totto Tottomat (Tippspiel für die Fussball EM/WM) 
* ----------------------------------------------------------------------
* Datei: util.php
* 
* Auswertungsfunktionen und Berechnungsfunktionen
*
* Email: wyss@superspider.net
***********************************************************************/

require_once('config.php'); 
require_once('classes.php');
/**********************************************************************
* function CalculatePlayerScore()
***********************************************************************/
function CalculatePlayerScore()
{
	global $player;
	global $matches;
	global $PlayerScore;
	global $mas;

	// Alle Spielresultate aus Datenbank lesen
	//$query = mysql_query("select * from wmtotto2014 where PlayerName = '" .$player->username. "';") or die(mysql_error());
	//$usr = mysql_fetch_array($query);	

	$DebugPlayer = "dummy";
	$PlayerName = $player->username;

	// *****************************************************************
	// Gruppenspiele
	// *****************************************************************
	for ($i=0; $i<48; $i++)
	{
		$Game = "Game" . $i;
		$matches[$i]->calculateGroupMatchPoints($mas[$Game],$player->groupFavorite);
	}

	// *****************************************************************
	// Achtelfinalspiele
	// *****************************************************************
	// mache eine Liste mit allen Mastergegnern, vergleiche alle 
	// Usergegner mit der Liste 
	$start = 48;
	$end = 55;
	
	$teamList = array();
	$opponentDictionary = array();
	
	for ($i=$start; $i<=$end; $i++)
	{
		$m1 = utf8_encode($mas["Game" . $i . "_T" . '1']);
		if ($m1 == "") continue;		
		
		$m2 = utf8_encode($mas["Game" . $i . "_T" . '2']);
		if ($m2 == "") continue;
		
		$mRes = $mas["Game" . $i];	
		if ($m2 == "") continue;
		
		array_push($teamList,$m1);
		array_push($teamList,$m2);
		$opponentDictionary["$m1-$m2"] = $mRes;
		$opponentDictionary["$m2-$m1"] = strrev($mRes);  // cross correlation games
	}
	
	for ($i=$start; $i<=$end; $i++)
		$matches[$i]->calculateFinalMatchPoints($teamList,$opponentDictionary);

	// *****************************************************************
	// Viertelfinalspiele
	// *****************************************************************
	// mache eine Liste mit allen Viertelfinalgegnern, vergleiche alle Masterfelder mit der Liste 
	$start = 56;
	$end = 59;
	
	$teamList = array();
	$opponentDictionary = array();
	
	for ($i=$start; $i<=$end; $i++)
	{
		$m1 = utf8_encode($mas["Game" . $i . "_T" . '1']);
		if ($m1 == "") continue;		
		
		$m2 = utf8_encode($mas["Game" . $i . "_T" . '2']);
		if ($m2 == "") continue;
		
		$mRes = $mas["Game" . $i];	
		if ($m2 == "") continue;
		
		array_push($teamList,$m1);
		array_push($teamList,$m2);
		$opponentDictionary["$m1-$m2"] = $mRes;
		$opponentDictionary["$m2-$m1"] = strrev($mRes);  // cross correlation games
	}
	
	for ($i=$start; $i<=$end; $i++)
		$matches[$i]->calculateFinalMatchPoints($teamList,$opponentDictionary);
	
	// ********************************************************************
	// Halbfinalspiele
	// ********************************************************************
	// mache eine Liste mit allen Halbfinalgegnern, vergleiche alle Masterfelder mit der Liste 
	$start = 60;
	$end = 61;
	
	$teamList = array();
	$opponentDictionary = array();
	
	for ($i=$start; $i<=$end; $i++)
	{
		$m1 = utf8_encode($mas["Game" . $i . "_T" . '1']);
		if ($m1 == "") continue;		
		
		$m2 = utf8_encode($mas["Game" . $i . "_T" . '2']);
		if ($m2 == "") continue;
		
		$mRes = $mas["Game" . $i];	
		if ($m2 == "") continue;
		
		array_push($teamList,$m1);
		array_push($teamList,$m2);
		$opponentDictionary["$m1-$m2"] = $mRes;
		$opponentDictionary["$m2-$m1"] = strrev($mRes);  // cross correlation games
	}
	
	for ($i=$start; $i<=$end; $i++)
		$matches[$i]->calculateFinalMatchPoints($teamList,$opponentDictionary);
		
	// ********************************************************************
	// Spiel um Platz 3
	// ******************************************************************** 
	$start = 62;
	$end = 62;
	
	$teamList = array();
	$opponentDictionary = array();
	
	for ($i=$start; $i<=$end; $i++)
	{
		$m1 = utf8_encode($mas["Game" . $i . "_T" . '1']);
		if ($m1 == "") continue;		
		
		$m2 = utf8_encode($mas["Game" . $i . "_T" . '2']);
		if ($m2 == "") continue;
		
		$mRes = $mas["Game" . $i];	
		if ($m2 == "") continue;
		
		array_push($teamList,$m1);
		array_push($teamList,$m2);
		$opponentDictionary["$m1-$m2"] = $mRes;
		$opponentDictionary["$m2-$m1"] = strrev($mRes);  // cross correlation games
	}
	
	for ($i=$start; $i<=$end; $i++)
		$matches[$i]->calculateFinalMatchPoints($teamList,$opponentDictionary);
	
	// ********************************************************************
	// Final
	// ********************************************************************
	$start = 63;
	$end = 63;
	
	$teamList = array();
	$opponentDictionary = array();
	
	for ($i=$start; $i<=$end; $i++)
	{
		$m1 = utf8_encode($mas["Game" . $i . "_T" . '1']);
		if ($m1 == "") continue;		
		
		$m2 = utf8_encode($mas["Game" . $i . "_T" . '2']);
		if ($m2 == "") continue;
		
		$mRes = $mas["Game" . $i];	
		if ($m2 == "") continue;
		
		array_push($teamList,$m1);
		array_push($teamList,$m2);
		$opponentDictionary["$m1-$m2"] = $mRes;
		$opponentDictionary["$m2-$m1"] = strrev($mRes);  // cross correlation games
	}
	
	for ($i=$start; $i<=$end; $i++)
		$matches[$i]->calculateFinalMatchPoints($teamList,$opponentDictionary);
		
	// ********************************************************************
	// Finalspiele - Bonuspunkte
	// ********************************************************************

	$master_match62 = NEW match(62,"FF",GetTeam(utf8_encode($mas["Game62_T1"])),GetTeam(utf8_encode($mas["Game62_T2"])));
	$master_match62->matchRes = $mas["Game62"];
	
	$master_match63 = NEW match(63,"FF",GetTeam(utf8_encode($mas["Game63_T1"])),GetTeam(utf8_encode($mas["Game63_T2"])));
	$master_match63->matchRes = $mas["Game63"];
	
	if ( checkGame($mas["Game62"]) && checkGame($matches[62]->matchRes) )
	{
		if ( GetLooser($matches[62]) == GetLooser($master_match62) )
			$matches[62]->matchResPts += 20;
	
		if ( GetWinner($matches[62]) == GetWinner($master_match62) )
			$matches[62]->matchResPts += 30;
	}
	if ( checkGame($mas["Game63"]) && checkGame($matches[63]->matchRes) )
	{	
		if ( GetLooser($matches[63]) == GetLooser($master_match63) )
			$matches[63]->matchResPts += 40;
	
		if ( GetWinner($matches[63]) == GetWinner($master_match63) )
			$matches[63]->matchResPts += 70;	
	}
	
	$player->score = 0;
	foreach ($matches as $match)
	{
		$player->score += $match->calculateMatchTotalPoints();
		if ($DebugPlayer == $PlayerName)
			print "MatchNr: ".$match->matchNr." team1Pts:".$match->team1Pts." team2Pts:".$match->team2Pts." matchResPts:".$match->matchResPts." matchTotalPts:".$match->matchTotalPts."<br>";
	}
	$PlayerList["$Name"] = $player->score;
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
		MessageBox("Bitte eine Jokergruppe anklicken (Auswahl A bis H)!");
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
	$matches[48]->matchRes = "";
	$matches[49]->team1 = GetTeamWithRank("C",1);
	$matches[49]->team2 = GetTeamWithRank("D",2);
	$matches[49]->matchRes = "";
	$matches[50]->team1 = GetTeamWithRank("B",1);
	$matches[50]->team2 = GetTeamWithRank("A",2);
	$matches[50]->matchRes = "";
	$matches[51]->team1 = GetTeamWithRank("D",1);
	$matches[51]->team2 = GetTeamWithRank("C",2);
	$matches[51]->matchRes = "";
	$matches[52]->team1 = GetTeamWithRank("E",1);
	$matches[52]->team2 = GetTeamWithRank("F",2);
	$matches[52]->matchRes = "";
	$matches[53]->team1 = GetTeamWithRank("G",1);
	$matches[53]->team2 = GetTeamWithRank("H",2);
	$matches[53]->matchRes = "";
	$matches[54]->team1 = GetTeamWithRank("F",1);
	$matches[54]->team2 = GetTeamWithRank("E",2);
	$matches[54]->matchRes = "";
	$matches[55]->team1 = GetTeamWithRank("H",1);
	$matches[55]->team2 = GetTeamWithRank("G",2);
	$matches[55]->matchRes = "";
	
	// clear all following teams & matches
	for ($i=56; $i<64; $i++)
	{
		$matches[$i]->team1 = "";
		$matches[$i]->team2 = "";
		$matches[$i]->matchRes = "";
	}
	$player->champion = "";
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
	$matches[56]->team1 = GetWinner($matches[47+5]); 	// AF5
	$matches[56]->team2 = GetWinner($matches[47+6]);	// AF6
	$matches[56]->matchRes = "";
	$matches[57]->team1 = GetWinner($matches[47+1]);	// AF1
	$matches[57]->team2 = GetWinner($matches[47+2]);	// AF2
	$matches[57]->matchRes = "";
	$matches[58]->team1 = GetWinner($matches[47+7]);	// AF7
	$matches[58]->team2 = GetWinner($matches[47+8]);	// AF8
	$matches[58]->matchRes = "";
	$matches[59]->team1 = GetWinner($matches[47+3]);	// AF3
	$matches[59]->team2 = GetWinner($matches[47+4]);	// AF4
	$matches[59]->matchRes = "";
	
	// clear all following teams & matches
	for ($i=60; $i<64; $i++)
	{
		$matches[$i]->team1 = "";
		$matches[$i]->team2 = "";
		$matches[$i]->matchRes = "";
	}
	$player->champion = "";
}

/***********************************************************************
* function CalculateHalfFinals()
* 
***********************************************************************/
function CalculateHalfFinals()
{		
	global $matches;
	global $player;
	
	// Prüfe zuerst, ob alle Viertelfinalgegner i.O. sind
	for ($i=56; $i<60; $i++)
	{
		$team1 = $matches[$i]->team1->name;
		$team2 = $matches[$i]->team2->name;
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
	$matches[60]->team1 = GetWinner($matches[55+1]);	// VF1
	$matches[60]->team2 = GetWinner($matches[55+2]);	// VF2
	$matches[60]->matchRes = "";
	$matches[61]->team1 = GetWinner($matches[55+4]);	// VF4
	$matches[61]->team2 = GetWinner($matches[55+3]);	// VF3
	$matches[61]->matchRes = "";
	
	// clear all following teams & matches
	for ($i=62; $i<64; $i++)
	{
		$matches[$i]->team1 = "";
		$matches[$i]->team2 = "";
		$matches[$i]->matchRes = "";
	}
	$player->champion = "";
}
/***********************************************************************
* function CalculateFinals()
* 
***********************************************************************/
function CalculateFinals()
{	
	global $matches;
	global $player;
		
	// Prüfe zuerst, ob alle Halbfinalspiele i.O. sind
	for ($i=60; $i<62; $i++)
	{
		$team1 = $matches[$i]->team1->name;
		$team2 = $matches[$i]->team2->name;
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
	$matches[62]->matchRes = "";
	$matches[63]->team1 = GetWinner($matches[60]);
	$matches[63]->team2 = GetWinner($matches[61]);
	$matches[63]->matchRes = "";
	
	$player->champion = "";
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
		$team1 = $matches[$i]->team1->name;
		$team2 = $matches[$i]->team2->name;
		if ( ($team1=="")||($team2=="")||($team1==$team2) )
		{
			// Info: Das fehlerhafte Eingabefeld wird später angezeigt.
			return;
		}
	}
	
	// Dann prüfe, ob die Spielresultate der Finalgegner i.O. sind
	$errGame = checkAllGames();
	if ( ($errGame < 65)&&($errGame != -1) )
	{
		// Info: Das fehlerhafte Eingabefeld wird später angezeigt.
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
	MessageBox("Programming error GetTeam ($tname) unknown)!");
	$e = new Exception;
	var_dump($e->getTraceAsString());
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
		$sql = "INSERT INTO wmtotto2014 (FormComplete,Email,RegisterDate,Score,Name,PlayerName,Champion,GroupFavorite,TotalGoals";
		
		for ($i=0; $i<64; $i++)			
			$sql = $sql . ", Game$i";

		for ($i=48; $i<64; $i++)
			$sql = $sql . ", Game${i}_T1" . ", Game${i}_T2"; 
		
		$formComplete = 0;	
		$sql = $sql . ") values ('" . $formComplete . "'," .
								"'" . $player->email . "'," .
								"'" . $player->registerDate . "'," .
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
							"Email='" . $player->email . "'," .
							"RegisterDate='" . $player->registerDate . "'," .
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
			$sql = $sql . ", Game".$i."_T1='".utf8_decode($matches[$i]->team1->name).
			"', Game${i}_T2='" . utf8_decode($matches[$i]->team2->name) . "' "; 
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
			$matches[$i]->team1 = GetTeam(utf8_encode($dbvals[$index1]));
			$matches[$i]->team2 = GetTeam(utf8_encode($dbvals[$index2]));
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
* function GetBetterTeam($teamA, $teamB)
* Gibt das Team mit der besseren Punktetabelle zurück.
***********************************************************************/
function GetBetterTeam($teamA, $teamB)
{
	if ($teamA->score > $teamB->score)
	{
		return $teamA;
	}
	elseif ($teamA->score == $teamB->score)
	{
		// same score, check diffgoals
		if ($teamA->diffgoals > $teamB->diffgoals)
		{
			return $teamA;
		}
		elseif ($teamA->diffgoals == $teamB->diffgoals)
		{
			// same diffgoals, check goals
			if ($teamA->goals > $teamB->goals)
			{
				return $teamA;
			}
			return $teamB;
		}
		return $teamB;
	}
	return $teamB;
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
			// check if better than second
			$tmp = GetBetterTeam($team,$second);
			
			if ($tmp->name == $team->name)
			{
				// check if better than first
				$tmp = GetBetterTeam($team,$first);
				if ($tmp->name == $team->name)
				{
					// better than first
					$second = clone $first;
					$first = clone $team;
				}
				else
				{
					// worse than first
					$second = clone $team;
				}
			}
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
	global $matches;
	global $player;
		
	for ($i=0; $i<64; $i++)
	{
		$j = $i+1;
		//$game = utf8_encode($_POST['Game'.$i]);
		$game = $matches[$i]->matchRes;
		
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
	global $matches;
	
	for ($i=48; $i<64; $i++)
	{
		//$team1 = utf8_encode($_POST['Game'.$i.'_T1']);
		$team1 = $matches[$i]->team1->name;
		//$team2 = utf8_encode($_POST['Game'.$i.'_T2']);
		$team2 = $matches[$i]->team2->name;
		
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
	{
		// MessageBox("eGame:$eGame,eTeam:$eTeam");
		// $e = new Exception;
		// var_dump($e->getTraceAsString());
		return 0;
	}
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
