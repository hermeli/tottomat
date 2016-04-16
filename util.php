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
* function CalculateFinalMatchPoints()
***********************************************************************/
function CalculateFinalMatchPoints($start,$end)
{
	global $mas;
	global $matches;	
	$teamList = array();
	$opponentDictionary = array();
	
	for ($i=$start; $i<=$end; $i++)
	{
		$m1 = $mas["Game" . $i . "_T" . '1'];			
		$m2 = $mas["Game" . $i . "_T" . '2'];	
		$mRes = $mas["Game" . $i];	
		
		array_push($teamList,$m1);
		array_push($teamList,$m2);
		$opponentDictionary["$m1-$m2"] = $mRes;
		$opponentDictionary["$m2-$m1"] = strrev($mRes);  // cross correlation games
	}
	
	for ($i=$start; $i<=$end; $i++)
		$matches[$i]->calculateFinalMatchPoints($teamList,$opponentDictionary);
}
/**********************************************************************
* function CalculatePlayerScore()
***********************************************************************/
function CalculatePlayerScore()
{
	global $player;
	global $matches;
	global $PlayerScore;
	global $mas;
		
	// *****************************************************************
	// Gruppenspiele
	// *****************************************************************
	for ($i=0; $i<tottomat::iEight; $i++)
	{
		$Game = "Game" . $i;
		$matches[$i]->calculateGroupMatchPoints($mas[$Game],$player->groupFavorite);
	}

	// Achtelfinalspiele
	CalculateFinalMatchPoints(tottomat::iEight,tottomat::iQuarter-1);

	// Viertelfinalspiele
	CalculateFinalMatchPoints(tottomat::iQuarter,tottomat::iHalf-1);
	
	// Halbfinalspiele
	CalculateFinalMatchPoints(tottomat::iHalf,tottomat::iFinal-1);
	
	// Final
	CalculateFinalMatchPoints(tottomat::iFinal,tottomat::iFinal);
		
	// ********************************************************************
	// Finalspiele - Bonuspunkte
	// ********************************************************************
	
	// Punkte für die richtigen Halbfinalverlierer
	$MHF1_m1 = $mas["Game" . tottomat::iHalf . "_T" . '1'];			
	$MHF1_m2 = $mas["Game" . tottomat::iHalf . "_T" . '2'];	
	$MHF1_mRes = $mas["Game" . tottomat::iHalf];	
	$MHF1_match = NEW match(tottomat::iHalf,"FF",GetTeam($MHF1_m1),GetTeam($MHF1_m2));
	$MHF1_match->matchRes = $MHF1_mRes;
	
	$MHF2_m1 = $mas["Game" . (tottomat::iHalf+1) . "_T" . '1'];			
	$MHF2_m2 = $mas["Game" . (tottomat::iHalf+1) . "_T" . '2'];	
	$MHF2_mRes = $mas["Game" . (tottomat::iHalf+1)];	
	$MHF2_match = NEW match(tottomat::iHalf+1,"FF",GetTeam($MHF2_m1),GetTeam($MHF2_m2));
	$MHF2_match->matchRes = $MHF2_mRes;
	
	if ( checkGame($mas["Game" . tottomat::iHalf]) && checkGame($mas["Game" . (tottomat::iHalf+1)]) && checkGame($matches[tottomat::iHalf]->matchRes) && checkGame($matches[tottomat::iHalf+1]->matchRes))
	{
		if ( GetLooser($matches[tottomat::iHalf]) == GetLooser($MHF1_match) )
			$matches[tottomat::iHalf]->matchResPts += 20;
	
		if ( GetLooser($matches[tottomat::iHalf+1]) == GetLooser($MHF2_match) )
			$matches[tottomat::iHalf+1]->matchResPts += 20;
	}
	
	// Punkte des Finalspiels
	$MF_m1 = $mas["Game" . tottomat::iFinal . "_T" . '1'];			
	$MF_m2 = $mas["Game" . tottomat::iFinal . "_T" . '2'];	
	$MF_mRes = $mas["Game" . tottomat::iFinal];	
	$MF_match = NEW match(tottomat::iFinal,"FF",GetTeam($MF_m1),GetTeam($MF_m2));
	$MF_match->matchRes = $MF_mRes;
	
	if ( checkGame($mas["Game".tottomat::iFinal]) && checkGame($matches[tottomat::iFinal]->matchRes) )
	{
		if ( GetLooser($matches[tottomat::iFinal]) == GetLooser($MF_match) )
			$matches[tottomat::iFinal]->matchResPts += 40;
	
		if ( GetWinner($matches[tottomat::iFinal]) == GetWinner($MF_match) )
			$matches[tottomat::iFinal]->matchResPts += 70;
	}
	
	$player->score = 0;
	DebugMsg("<center><b>Punkte von ".$player->username.":</b></br><table border='1'>");
	DebugMsg("<b><tr><td>Spielenummer</td><td>Punkte Team 1</td><td>Punkte Team 2</td><td>Punkte Resultat</td><td>Punktetotal Match</td></tr></b>");
	
	foreach ($matches as $match)
	{
		$player->score += $match->calculateMatchTotalPoints();
		DebugMsg("<tr><td>".(($match->matchNr)+1)."</td><td>".$match->team1Pts."</td><td>".$match->team2Pts."</td><td>".$match->matchResPts."</td><td>".$match->matchTotalPts."</td></tr>");
	}
	DebugMsg("</center></table>");
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
/***********************************************************************
* function Team1BetterThanTeam2_Direct($team1, $team1)
* Gibt 1 zurück, wenn Team1 besser ist als Team2, sonst -1
***********************************************************************/
function Team1BetterThanTeam2_Direct($team1, $team2)
{
	/* EM Modus
	========
	http://de.uefa.org/MultimediaFiles/Download/Regulations/uefaorg/Regulations/02/03/92/83/2039283_DOWNLOAD.pdf 
	*/
	global $matches;
				
	// 1. Punkte
	if ($team1->score > $team2->score)
		return 1;
	elseif ($team1->score == $team2->score)
	{
		// 2. Punkte aus Direktbegegnung
		if ($team1->score_d > $team2->score_d)
			return 1;
		elseif ($team1->score_d == $team2->score_d)
		{
			// 3. Tordifferenz aus Direktbegegnung
			if ($team1->diffgoals_d > $team2->diffgoals_d)
				return 1;
			elseif ($team1->diffgoals_d == $team2->diffgoals_d)
			{
				// 4. Tore aus Direktbegegnung 
				if ($team1->goals_d > $team2->goals_d)
					return 1;
				elseif ($team1->goals_d == $team2->goals_d)
				{
					// 5. Todifferenz alle Spiele
					if ($team1->diffgoals > $team2->diffgoals)
						return 1;
					elseif ($team1->diffgoals == $team2->diffgoals)	
					{
						// 6. Tore alle Spiele
						if ($team1->goals > $team2->goals)
						return 1;
					}
				}
			}	
		}
	}
	return -1;
}
/***********************************************************************
* function Team1BetterThanTeam2_NoDirect($team1, $team1)
* Gibt 1 zurück, wenn Team1 besser ist als Team2, sonst -1
***********************************************************************/
function Team1BetterThanTeam2_NoDirect($team1, $team2)
{
	/* EM Modus
	zur Berechnung von Rangfolgen unter Drittplatzierten
	*/
	global $matches;
				
	if ($team1->score > $team2->score)
		return 1;
	elseif ($team1->score == $team2->score)
	{
		if ($team1->diffgoals > $team2->diffgoals)
			return 1;
		elseif ($team1->diffgoals == $team2->diffgoals)
		{
			if ($team1->goals > $team2->goals)
				return 1;
		}	
	}
	return -1;
}
/***********************************************************************
* function GetTeamWithRank($groupName, $rank)
* Gibt das Team einer Gruppe mit dem angegebenen Rang zurück.
***********************************************************************/
function GetTeamWithRank($groupName,$rank)
{	
	global $teams;
	$groupTeams = array();
	
	foreach ($teams as $team)
		if ($team->group == $groupName)
			array_push($groupTeams,$team);
		
	usort($groupTeams,"Team1BetterThanTeam2_Direct");
	$groupTeams = array_reverse($groupTeams);
	
	return $groupTeams[$rank-1];
}
/***********************************************************************
* function Get4BestThirds()
* Gibt die 4 besten Gruppendritten zurück
***********************************************************************/
function Get4BestThirds()
{	
	global $teams;	
	global $groups;
	$thirds = array();
	
	foreach ($groups as $group)
	{
		$tmp = GetTeamWithRank($group->groupName,3);
		array_push($thirds,$tmp);
	}
	
	// $thirds: Hier haben wir ein Array mit Teams auf dem 3. Rang (pro Gruppe)	
	// Nach dem Vergleich is das letzte Element das Beste, deshalb 'reverse'!	
	usort($thirds,"Team1BetterThanTeam2_NoDirect");
	$thirds = array_reverse($thirds);
	return $thirds;
	
}
/***********************************************************************
* function FindBestOf3AndRemove()
* Sucht im Array der sortierten Drittplatzierten nach dem Besten von
* drei vorgegebenen Gruppen, gibt diesen zurück und entfernt ihn aus 
* dem Array. 
***********************************************************************/
/*
function FindBestOf3AndRemove(&$thirds,$group1,$group2,$group3)
{
	foreach ($thirds as $key => $third)
	{
		if ($third->group == $group1)
		{
			unset($thirds[$key]);
			return $third;
		}
		elseif ($third->group == $group2)
		{
			unset($thirds[$key]);
			return $third;
		}
		elseif ($third->group == $group3)
		{
			unset($thirds[$key]);
			return $third;
		}
	}
}
*/
/***********************************************************************
* function LookupThirdForWinnerOfGroup()
* Sucht aus der vorgegebenen FIFA Tabelle die Zuordnung des korrekten 
* Gruppendritten zu einem gegebenen Gruppengewinner. 
***********************************************************************/
function LookupThirdForWinnerOfGroup($group,$code,$thirds)
{	
	$TabA = array("ABCD" => "C", "ABCE" => "C", "ABCF" => "C", "ABDE" => "D", "ABDF" => "D", "ABEF" => "E", "ACDE" => "C", "ACDF" => "C", "ACEF" => "C", "ADEF" => "D", "BCDE" => "C", "BCDF" => "C", "BCEF" => "E", "BDEF" => "E", "CDEF" => "C");
	$TabB = array("ABCD" => "D", "ABCE" => "A", "ABCF" => "A", "ABDE" => "A", "ABDF" => "A", "ABEF" => "A", "ACDE" => "D", "ACDF" => "D", "ACEF" => "A", "ADEF" => "A", "BCDE" => "D", "BCDF" => "D", "BCEF" => "C", "BDEF" => "D", "CDEF" => "D");
	$TabC = array("ABCD" => "A", "ABCE" => "B", "ABCF" => "B", "ABDE" => "B", "ABDF" => "B", "ABEF" => "B", "ACDE" => "A", "ACDF" => "A", "ACEF" => "F", "ADEF" => "F", "BCDE" => "B", "BCDF" => "B", "BCEF" => "B", "BDEF" => "B", "CDEF" => "F");
	$TabD = array("ABCD" => "B", "ABCE" => "E", "ABCF" => "F", "ABDE" => "E", "ABDF" => "F", "ABEF" => "F", "ACDE" => "E", "ACDF" => "F", "ACEF" => "E", "ADEF" => "E", "BCDE" => "E", "BCDF" => "F", "BCEF" => "F", "BDEF" => "F", "CDEF" => "E");
	
	if ($group == "A")
		$lupGroup = $TabA[$code];
	elseif ($group == "B")
		$lupGroup = $TabB[$code];
	elseif ($group == "C")
		$lupGroup = $TabC[$code];
	elseif ($group == "D")
		$lupGroup = $TabD[$code];
	else
	{
		MessageBox("Programming error: LookupThirdForWinnerOfGroup($group,...) -> unknown $group!");
		$e = new Exception;
		var_dump($e->getTraceAsString());
	}
	foreach ($thirds as $team)
		if ($lupGroup == $team->group)
			return $team;
}
/***********************************************************************
* function CalculateLastSexteenFinals()
***********************************************************************/
function CalculateLastSixteenFinals()
{
	
	global $matches;
	global $player;
	global $teams;
	global $groups;
		
	if ($player->groupFavorite=="")
	{
		MessageBox("Bitte eine Jokergruppe angeben!");
		return;
	}
	
	// Prüfe alle Gruppenspiele und berechne die Punkte 
	for ($i=0; $i<tottomat::iEight; $i++)
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
	
	// Berechne die Punkte aus Direktbegegnungen
	foreach ($groups as $group)
	{
		foreach ($group->matches as $match)
		{
			if ($match->team1->score == $match->team2->score)
			{
				$ScoreParts = explode(":",$match->matchRes);
				// update score per team
				if ($ScoreParts[0] > $ScoreParts[1])
					$match->team1->score_d += 3;
				else if ($ScoreParts[0] < $ScoreParts[1])
					$match->team2->score_d += 3;
				else
				{
					$match->team1->score_d += 1;
					$match->team2->score_d += 1;
				}
				
				// update diffgoals per team
				$match->team1->diffgoals_d += $ScoreParts[0] - $ScoreParts[1]; 
				$match->team2->diffgoals_d += $ScoreParts[1] - $ScoreParts[0];
		
				// update goals per team
				$match->team1->goals_d += $ScoreParts[0];
				$match->team2->goals_d += $ScoreParts[1]; 		
			}
		}	
	}
	DebugMsg("<center><b>Punktetabelle der Gruppenspiele:</b></br><table border='1'>");
	DebugMsg("<b><tr><td>Gruppe/Rang</td><td>Team</td><td>Punkte</td><td>Punkte direkt</td><td>Tordifferenz direkt</td><td>Tore direkt</td><td>Tordifferenz</td><td>Tore</td></tr></b>");
	foreach ($groups as $group)
	{
		for ($i=1; $i<=4; $i++)
		{
			$team = GetTeamWithRank($group->groupName,$i);
			DebugMsg("<tr><td>".$team->group."/".$i."</td><td>".$team->name."</td><td>".$team->score."</td><td>".$team->score_d."</td><td>".$team->diffgoals_d."</td><td>".$team->goals_d."</td><td>".$team->diffgoals."</td><td>".$team->goals."</td></tr>");
		}
	}
	DebugMsg("</table></center><br>");
	
	$thirds = Get4BestThirds();
	DebugMsg("<center><b>Rangfolge unter den Gruppen-Dritten:</b></br><table border='1'>");
	DebugMsg("<b><tr><td>Rang</td><td>Gruppe</td><td>Team</td><td>Punkte</td><td>Tordifferenz</td><td>Tore</td></tr></b>");
	$i=1;
	foreach ($thirds as $team)
		DebugMsg("<tr><td>".$i++."</td><td>".$team->group."/3</td><td>".$team->name."</td><td>".$team->score."</td><td>".$team->diffgoals."</td><td>".$team->goals."</td></tr>");
	
	
	$FourBestThirdsGroups = array();
	$FourBestThirdsGroupsSorted = array();
	
	for ($i=0; $i<4; $i++)
		array_push($FourBestThirdsGroups,$thirds[$i]->group);
	
	asort($FourBestThirdsGroups);
	
	foreach ($FourBestThirdsGroups as $element)
		$FourBestCode = $FourBestCode . $element;
		
	DebugMsg("<tr><td colspan=6>Gruppencode der 4 besten Dritten: ".$FourBestCode."</td></tr></p>");
	DebugMsg("<tr><td colspan=6>A1 gegen ".LookupThirdForWinnerOfGroup("A",$FourBestCode,$thirds)->group."</td></tr></p>");
	DebugMsg("<tr><td colspan=6>B1 gegen ".LookupThirdForWinnerOfGroup("B",$FourBestCode,$thirds)->group."</td></tr></p>");
	DebugMsg("<tr><td colspan=6>C1 gegen ".LookupThirdForWinnerOfGroup("C",$FourBestCode,$thirds)->group."</td></tr></p>");
	DebugMsg("<tr><td colspan=6>D1 gegen ".LookupThirdForWinnerOfGroup("D",$FourBestCode,$thirds)->group."</td></tr></p>");
	DebugMsg("</table></center><br>");
	
	// load calculated finalists from group matches	
	$matches[tottomat::iEight]->team1 = GetTeamWithRank("A",2);
	$matches[tottomat::iEight]->team2 = GetTeamWithRank("C",2);
	$matches[tottomat::iEight]->matchRes = "";
	
	$matches[tottomat::iEight+1]->team1 = GetTeamWithRank("B",1);
	$matches[tottomat::iEight+1]->team2 = LookupThirdForWinnerOfGroup("B",$FourBestCode,$thirds);
	$matches[tottomat::iEight+1]->matchRes = "";
	
	$matches[tottomat::iEight+2]->team1 = GetTeamWithRank("D",1);
	$matches[tottomat::iEight+2]->team2 = LookupThirdForWinnerOfGroup("D",$FourBestCode,$thirds);
	$matches[tottomat::iEight+2]->matchRes = "";
	
	$matches[tottomat::iEight+3]->team1 = GetTeamWithRank("A",1);
	$matches[tottomat::iEight+3]->team2 = LookupThirdForWinnerOfGroup("A",$FourBestCode,$thirds);
	$matches[tottomat::iEight+3]->matchRes = "";
	
	$matches[tottomat::iEight+4]->team1 = GetTeamWithRank("C",1);
	$matches[tottomat::iEight+4]->team2 = LookupThirdForWinnerOfGroup("C",$FourBestCode,$thirds);
	$matches[tottomat::iEight+4]->matchRes = "";
	
	$matches[tottomat::iEight+5]->team1 = GetTeamWithRank("F",1);
	$matches[tottomat::iEight+5]->team2 = GetTeamWithRank("E",2);
	$matches[tottomat::iEight+5]->matchRes = "";
	
	$matches[tottomat::iEight+6]->team1 = GetTeamWithRank("E",1);
	$matches[tottomat::iEight+6]->team2 = GetTeamWithRank("D",2);
	$matches[tottomat::iEight+6]->matchRes = "";
	
	$matches[tottomat::iEight+7]->team1 = GetTeamWithRank("B",2);
	$matches[tottomat::iEight+7]->team2 = GetTeamWithRank("F",2);
	$matches[tottomat::iEight+7]->matchRes = "";
		
	// clear all following teams & matches
	for ($i=tottomat::iQuarter; $i<=tottomat::iFinal; $i++)
	{
		$matches[$i]->team1 = "";
		$matches[$i]->team2 = "";
		$matches[$i]->matchRes = "";
	}
	$player->champion = "";
}
/***********************************************************************
* function CalculateQuarterFinals()
***********************************************************************/
function CalculateQuarterFinals()
{	
	global $matches;
	global $player;
	global $teams;
			
	// Prüfe zuerst, ob alle Achtelfinalgegner i.O. sind
	for ($i=tottomat::iEight; $i<tottomat::iQuarter; $i++)
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
	if ( ($errGame < tottomat::iQuarter)&&($errGame!=-1) )
	{
		MessageBox("Eingabefehler bei Spiel $errGame!");
		return;
	}
	
	// Berechne die Viertefinalteilnehmer
	$matches[tottomat::iQuarter]->team1 = GetWinner($matches[tottomat::iEight]); 		// Sieger AF1
	$matches[tottomat::iQuarter]->team2 = GetWinner($matches[tottomat::iEight+2]);		// Sieger AF3
	$matches[tottomat::iQuarter]->matchRes = "";
	$matches[tottomat::iQuarter+1]->team1 = GetWinner($matches[tottomat::iEight+1]);	// AF2
	$matches[tottomat::iQuarter+1]->team2 = GetWinner($matches[tottomat::iEight+5]);	// AF6
	$matches[tottomat::iQuarter+1]->matchRes = "";
	$matches[tottomat::iQuarter+2]->team1 = GetWinner($matches[tottomat::iEight+4]);	// AF5
	$matches[tottomat::iQuarter+2]->team2 = GetWinner($matches[tottomat::iEight+6]);	// AF7
	$matches[tottomat::iQuarter+2]->matchRes = "";
	$matches[tottomat::iQuarter+3]->team1 = GetWinner($matches[tottomat::iEight+3]);	// AF4
	$matches[tottomat::iQuarter+3]->team2 = GetWinner($matches[tottomat::iEight+7]);	// AF8
	$matches[tottomat::iQuarter+3]->matchRes = "";
	
	// clear all following teams & matches
	for ($i=tottomat::iHalf; $i<=tottomat::iFinal; $i++)
	{
		$matches[$i]->team1 = "";
		$matches[$i]->team2 = "";
		$matches[$i]->matchRes = "";
	}
	$player->champion = "";
}

/***********************************************************************
* function CalculateHalfFinals()
***********************************************************************/
function CalculateHalfFinals()
{		
	global $matches;
	global $player;
	
	// Prüfe zuerst, ob alle Viertelfinalgegner i.O. sind
	for ($i=tottomat::iQuarter; $i<tottomat::iHalf; $i++)
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
	if ( ($errGame < tottomat::iHalf)&&($errGame!=-1) )
	{
		MessageBox("Eingabefehler bei Spiel $errGame!");
		return;
	}
	
	// Berechne die Halbfinalteilnehmer
	$matches[tottomat::iHalf]->team1 = GetWinner($matches[tottomat::iQuarter]);		// Sieger VF1
	$matches[tottomat::iHalf]->team2 = GetWinner($matches[tottomat::iQuarter+1]);	// Sieger VF2
	$matches[tottomat::iHalf]->matchRes = "";
	$matches[tottomat::iHalf+1]->team1 = GetWinner($matches[tottomat::iQuarter+2]);	// VF3
	$matches[tottomat::iHalf+1]->team2 = GetWinner($matches[tottomat::iQuarter+3]);	// VF4
	$matches[tottomat::iHalf+1]->matchRes = "";
	
	// clear all following teams & matches
	for ($i=tottomat::iFinal; $i<=tottomat::iFinal; $i++)
	{
		$matches[$i]->team1 = "";
		$matches[$i]->team2 = "";
		$matches[$i]->matchRes = "";
	}
	$player->champion = "";
}
/***********************************************************************
* function CalculateFinals()
***********************************************************************/
function CalculateFinals()
{	
	global $matches;
	global $player;
		
	// Prüfe zuerst, ob alle Halbfinalspiele i.O. sind
	for ($i=tottomat::iHalf; $i<tottomat::iFinal; $i++)
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
	if ( ($errGame < tottomat::iFinal)&&($errGame!=-1) )
	{
		MessageBox("Eingabefehler bei Spiel $errGame!");
		return;
	}
	
	// Berechne die Finalteilnehmer
	$matches[tottomat::iFinal]->team1 = GetWinner($matches[tottomat::iHalf]);
	$matches[tottomat::iFinal]->team2 = GetWinner($matches[tottomat::iHalf+1]);
	$matches[tottomat::iFinal]->matchRes = "";
	
	$player->champion = "";
}
/***********************************************************************
* function CalculateChampion()
***********************************************************************/
function CalculateChampion()
{	
	global $matches;
	global $player;
		
	// Prüfe zuerst, ob alle Finalspiele i.O. sind
	for ($i=tottomat::iFinal; $i<=tottomat::iFinal; $i++)
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
	if ( ($errGame <= tottomat::iFinal+1)&&($errGame != -1) )
	{
		// Info: Das fehlerhafte Eingabefeld wird später angezeigt.
		$player->champion = "";
		return;
	}
	
	// Berechne den Weltmeister
	$player->champion = GetWinner($matches[tottomat::iFinal])->name;
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
	global $db_table;
	global $db;
	
	
	$query = $db->query("SELECT * FROM ".$db_table." where PlayerName = '" .$player->username. "';");	
	$dbvals = $query->fetch(PDO::FETCH_ASSOC);
	
	// Update des bestehenden Datenbankeintrags für Spieler
	$db->exec("UPDATE ".$db_table." SET Score='".$player->score."' WHERE PlayerName='".$player->username."'");
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
	global $db_table;
	global $db;
		
	// prüfe, ob EditMode == 1 (Modifikation der DB erlaubt)
	// $query = $db->query("select * from config;");	
	// $dbresult = $query->fetch(PDO::FETCH_ASSOC);			
	// if ($dbresult['EditMode'] == 0)
	//{
	//	MessageBox("Speichern nicht mehr erlaubt!");
	//	return;
	//}
	
	// Teste, ob der Spieler bereits einen Datenbankeintrag hat
	$nRows = $db->query("select count(*) from ".$db_table." where PlayerName = '".$player->username."';")->fetchColumn();	
	
	if ($nRows < 1) 
	{
		// Erzeuge neuen Datenbankeintrag für Spieler		
		$sql = "insert into ".$db_table." (FormComplete,Email,RegisterDate,Score,Name,PlayerName,Champion,GroupFavorite,TotalGoals";
		
		for ($i=0; $i<=tottomat::iFinal; $i++)			
			$sql = $sql . ", Game$i";

		for ($i=tottomat::iEight; $i<=tottomat::iFinal; $i++)
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
	
		for ($i=0; $i<=tottomat::iFinal; $i++)
			$sql = $sql . ", '" . $matches[$i]->matchRes . "'";
			
		for ($i=tottomat::iEight; $i<=tottomat::iFinal; $i++)
			$sql = $sql . ",'" . $matches[$i]->team1->name . "','" . $matches[$i]->team2->name . "'";	
			$sql = $sql . ")";
		
		$db->exec($sql);
		
		if (isset($_POST['savetodb']))
			MessageBox("Die Spielresultate wurden gespeichert! (Neuer Eintrag)");
	}
	else 
	{
		// Update des bestehenden Datenbankeintrags für Spieler (Score wird später nachgeführt!)
		$formComplete = isFormComplete();	
		$sql = "update ".$db_table." set FormComplete='" . $formComplete . "'," .
							"Email='" . $player->email . "'," .
							"RegisterDate='" . $player->registerDate . "'," .
							"Name='" . $player->name . "'," .
							"Champion='" . $player->champion . "',".
							"GroupFavorite='" . $player->groupFavorite . "',".
							"TotalGoals='" . $player->totalGoals . "'";
		
		for ($i=0; $i<=tottomat::iFinal; $i++)
			$sql = $sql . ", Game$i='" . $matches[$i]->matchRes . "'";
			
		for ($i=tottomat::iEight; $i<=tottomat::iFinal; $i++)
		{
			$T1 = 'Game'.$i.'_T1';
			$T2 = 'Game'.$i.'_T2';
			$sql = $sql . ", Game".$i."_T1='" . $matches[$i]->team1->name .
			"', Game${i}_T2='" . $matches[$i]->team2->name . "' "; 
		}
		$sql = $sql . "where PlayerName='".$player->username."'";		

		// Nachfolgend kann der gesamte Update-Datensatz angezeigt werden 
		// print "$sql";		
		$db->exec($sql);

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
	global $db_table;
	global $db;
	
		
	if ($source == "DB")
	{
		// Alle Spielresultate aus Datenbank lesen
		$query = $db->query("select * from ".$db_table." where PlayerName = '" .$player->username. "';");	
		$dbvals = $query->fetch(PDO::FETCH_ASSOC);
		// var_dump($dbvals);
	
		// laden der Datenfelder in die Struktur	
		$player->groupFavorite = $dbvals['GroupFavorite'];
		$player->totalGoals = $dbvals['TotalGoals'];
		$player->champion = $dbvals['Champion'];

		for ($i=0; $i<=tottomat::iFinal; $i++){
			$index="Game" . $i;
			$matches[$i]->matchRes = $dbvals[$index];
		}

		for ($i=tottomat::iEight; $i<=tottomat::iFinal; $i++)
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

		for ($i=0; $i<=tottomat::iFinal; $i++){
			$index="Game" . $i;
			$matches[$i]->matchRes = $_POST[$index]; 
		}

		for ($i=tottomat::iEight; $i<=tottomat::iFinal; $i++)
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
* function checkAllGames(..)
* checkt alle Game-Eingabefelder und gibt die Game-Fehlernummer zurück
***********************************************************************/
function checkAllGames()
{
	global $matches;
	global $player;
		
	for ($i=0; $i<=tottomat::iFinal; $i++)
	{
		$j = $i+1;	// Fehlernummer ist 1-basiert (nicht Index)
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
		if ( ($j>=tottomat::iEight) && ($j<=tottomat::iFinal) && ($parts[0] == $parts[1]) ) 
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
	
	for ($i=tottomat::iEight; $i<=tottomat::iFinal; $i++)
	{
		//var_dump($matches[$i]);
		$team1 = $matches[$i]->team1->name;
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

?>
