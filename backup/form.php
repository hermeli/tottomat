<?php
/******************************************************************************
 * Gruppeneinteilung der Nationen
  ****************************************************************************/
// Teams | Gesamtpunkte * 100 + Gesamttore
$GroupA = array("Südafrika", "Mexiko", "Uruguay", "Frankreich", 0, 0, 0, 0);
$GroupB = array("Argentinien", "Nigeria", "Südkorea", "Griechenland", 0, 0, 0, 0);
$GroupC = array("England", "USA", "Algerien", "Slowenien",0,0,0,0);
$GroupD = array("Deutschland", "Australien", "Serbien", "Ghana",0,0,0,0);
$GroupE = array("Holland", "Dänemark", "Japan", "Kamerun",0,0,0,0);
$GroupF = array("Italien", "Paraguay", "Neuseeland", "Slowakei",0,0,0,0);
$GroupG = array("Brasilien", "Nordkorea", "Elfenbeinküste", "Portugal",0,0,0,0);
$GroupH = array("Spanien", "Schweiz", "Honduras", "Chile",0,0,0,0);

$AllGroups = array();
array_push($AllGroups, $GroupA);
array_push($AllGroups, $GroupB);
array_push($AllGroups, $GroupC);
array_push($AllGroups, $GroupD);
array_push($AllGroups, $GroupE);
array_push($AllGroups, $GroupF);
array_push($AllGroups, $GroupG);
array_push($AllGroups, $GroupH);
 
require_once('util.php'); 
$ShowForm = 1;

/******************************************************************************
 * Printet ein Gruppenspiel mitsamt HTML Formatierung. 
  ****************************************************************************/
function PrintGroupGameHtml($team1,$team2,$game,$gameNr)
{
	$out = "";
	$out = $out . "<td valign='top'>$team1-$team2</td>";
	$name = "Game" . $gameNr;
	$out = $out . "<td valign='top'><input size='5' name='$name' type='text' maxlength='50' value='$game'/></td>";
	print $out;
}
/******************************************************************************
 * Funktions-Handler für den Button 'rangliste'
 * -> die Rangliste aller Spieler wird angezeigt
  ****************************************************************************/
if (isset($_POST['rangliste'])){
	printHeader();
	CalculatePlayerList($_POST["PlayerName"]);
	$ShowForm = 0;
}
/******************************************************************************
 * Funktions-Handler für den Button 'calc_finals'
 * -> Die Finalgegner werden berechnet
  ****************************************************************************/
if (isset($_POST['calc_finals']))
{
	// prüfe, ob Halbfinalspiel unentschieden ist oder Eingabefehler hat
	for ($i=61; $i<63; $i++)
	{
		$index="Game" . $i;
		if (checkEvenFinal($_POST["$index"]) == 0)
		{
			$error = $i;
			$errormsg = "[!] Finalspiel $error darf nicht unentschieden sein!";
			$ShowForm = 1;
			break;
		} 
		if (checkGame($_POST["$index"]) == 0)
		{
			$error = $i;
			$errormsg = "[!] Eingabefehler bei Spielresultat von Spiel $error!";
			$ShowForm = 1;
			break;
		} 
	}
		
	// berechne Finalspiele
	if ($error == 0)
	{
		${"Game" . 63 . "_T1"} = GetLooser($_POST['Game61_T1'],$_POST['Game61_T2'],$_POST['Game61']);
		${"Game" . 63 . "_T2"} = GetLooser($_POST['Game62_T1'],$_POST['Game62_T2'],$_POST['Game62']);
	
		${"Game" . 64 . "_T1"} = GetWinner($_POST['Game61_T1'],$_POST['Game61_T2'],$_POST['Game61']);
		${"Game" . 64 . "_T2"} = GetWinner($_POST['Game62_T1'],$_POST['Game62_T2'],$_POST['Game62']);
	}
	
	// laden der restlichen Datenfelder im Formular
	$PlayerName = $_POST["PlayerName"];
	$GroupFavorite = $_POST["GroupFavorite"];
	$TotalGoals = $_POST["TotalGoals"];

	for ($i=1; $i<=64; $i++){
		$index="Game" . $i;
		${"Game" . $i} =$_POST["$index"]; 
	}
	
	for ($i=49; $i<=62; $i++)
	{
		$index1="Game" . $i . "_T1";
		$index2="Game" . $i . "_T2";
		${"Game" . $i . "_T1"} = $_POST["$index1"]; 
		${"Game" . $i . "_T2"} = $_POST["$index2"]; 
	}
}
/******************************************************************************
 * Funktions-Handler für den Button 'calc_half'
 * -> Die Halbfinalgegner werden berechnet
  ****************************************************************************/
if (isset($_POST['calc_half']))
{
	// prüfe, ob Achtelfinalspiel unentschieden ist oder Eingabefehler hat
	for ($i=57; $i<61; $i++)
	{
		$index="Game" . $i;
		if (checkEvenFinal($_POST["$index"]) == 0)
		{
			$error = $i;
			$errormsg = "[!] Finalspiel $error darf nicht unentschieden sein!";
			$ShowForm = 1;
			break;
		} 
		if (checkGame($_POST["$index"]) == 0)
		{
			$error = $i;
			$errormsg = "[!] Eingabefehler bei Spielresultat von Spiel $error!";
			$ShowForm = 1;
			break;
		} 
	}

	// berechne Halbfinalgegner
	if ($error==0)
	{
		${"Game" . 61 . "_T1"} = GetWinner($_POST['Game57_T1'],$_POST['Game57_T2'],$_POST['Game57']);
		${"Game" . 61 . "_T2"} = GetWinner($_POST['Game58_T1'],$_POST['Game58_T2'],$_POST['Game58']);
		
		${"Game" . 62 . "_T1"} = GetWinner($_POST['Game59_T1'],$_POST['Game59_T2'],$_POST['Game59']);
		${"Game" . 62 . "_T2"} = GetWinner($_POST['Game60_T1'],$_POST['Game60_T2'],$_POST['Game60']);
	}
	
	// laden der restlichen Datenfelder im Formular
	$PlayerName = $_POST["PlayerName"];
	$GroupFavorite = $_POST["GroupFavorite"];
	$TotalGoals = $_POST["TotalGoals"];

	for ($i=1; $i<=64; $i++){
		$index="Game" . $i;
		${"Game" . $i} =$_POST["$index"]; 
	}
	
	for ($i=49; $i<=60; $i++)
	{
		$index1="Game" . $i . "_T1";
		$index2="Game" . $i . "_T2";
		${"Game" . $i . "_T1"} = $_POST["$index1"]; 
		${"Game" . $i . "_T2"} = $_POST["$index2"]; 
	}
}
/******************************************************************************
 * Funktions-Handler für den Button 'calc_fourth'
 * -> Die Viertelfinalgegner werden berechnet
  ****************************************************************************/
if (isset($_POST['calc_fourth']))
{
	$error = 0;
	
	// prüfe, ob Achtelfinalspiel unentschieden ist oder Eingabefehler hat
	for ($i=49; $i<57; $i++)
	{
		$index="Game" . $i;
		if (checkEvenFinal($_POST["$index"]) == 0)
		{
			$error = $i;
			$errormsg = "[!] Finalspiel $error darf nicht unentschieden sein!";
			$ShowForm = 1;
			break;
		} 
		if (checkGame($_POST["$index"]) == 0)
		{
			$error = $i;
			$errormsg = "[!] Eingabefehler bei Spielresultat von Spiel $error!";
			$ShowForm = 1;
			break;
		} 
	}
	
	// berechne die Viertelfinalgegener 
	if ($error == 0)
	{
		${"Game" . 57 . "_T1"} = GetWinner($_POST['Game49_T1'],$_POST['Game49_T2'],$_POST['Game49']);
		${"Game" . 57 . "_T2"} = GetWinner($_POST['Game50_T1'],$_POST['Game50_T2'],$_POST['Game50']);
		
		${"Game" . 58 . "_T1"} = GetWinner($_POST['Game53_T1'],$_POST['Game53_T2'],$_POST['Game53']);
		${"Game" . 58 . "_T2"} = GetWinner($_POST['Game54_T1'],$_POST['Game54_T2'],$_POST['Game54']);
		
		${"Game" . 59 . "_T1"} = GetWinner($_POST['Game51_T1'],$_POST['Game51_T2'],$_POST['Game51']);
		${"Game" . 59 . "_T2"} = GetWinner($_POST['Game52_T1'],$_POST['Game52_T2'],$_POST['Game52']);
		
		${"Game" . 60 . "_T1"} = GetWinner($_POST['Game55_T1'],$_POST['Game55_T2'],$_POST['Game55']);
		${"Game" . 60 . "_T2"} = GetWinner($_POST['Game56_T1'],$_POST['Game56_T2'],$_POST['Game56']);
	}
	// laden der restlichen Datenfelder im Formular
	$PlayerName = $_POST["PlayerName"];
	$GroupFavorite = $_POST["GroupFavorite"];
	$TotalGoals = $_POST["TotalGoals"];

	for ($i=1; $i<=64; $i++){
		$index="Game" . $i;
		${"Game" . $i} =$_POST["$index"]; 
	}
	
	for ($i=49; $i<=56; $i++)
	{
		$index1="Game" . $i . "_T1";
		$index2="Game" . $i . "_T2";
		${"Game" . $i . "_T1"} = $_POST["$index1"]; 
		${"Game" . $i . "_T2"} = $_POST["$index2"]; 
	}
}
/******************************************************************************
 * Funktions-Handler für den Button 'calc_eight'
 * -> Die Achtelfinalgegner werden berechnet
  ****************************************************************************/
if (isset($_POST['calc_eight'])){
	
	$Ind1s = array(0,2,0,3,3,1);
	$Ind2s = array(1,3,2,1,0,2);
	$Grps = array("A","B","C","D","E","F","G","H");
	
	// prüfe auf Gruppenspiel mit Eingabefehler
	for ($i=0; $i<48; $i++)
	{
		$index="Game" . ($i+1);
		if (checkGame($_POST["$index"]) == 0)
		{
			$GrpIndex = ($i%4+(floor($i/24)*4));
			$Ind1 = $Ind1s[floor($i/4)%6];			//  TeamIndex1 von GruppeA,B,...
			$Ind2 = $Ind2s[floor($i/4)%6];			//  TeamIndex2	
			$Team1 = $AllGroups[$GrpIndex][$Ind1];	// Schweiz
			$Team2 = $AllGroups[$GrpIndex][$Ind2];	// Griechenland	
			
			$error = $i+1;
			$errormsg = "[!] Eingabefehler bei Gruppenspiel $Team1 gegen $Team2!";
			$ShowForm = 1;
			break;
		} 
	}
	
	// berechne die Achtelfinalgegner
	if ($error == 0)
	{
		// erzeuge eine Tabelle der Spielresultate
		for ($i=0; $i<48; $i++)
		{
			$GrpIndex = ($i%4+(floor($i/24)*4));
			
			$Grp = $Grps[$GrpIndex];	// Gruppe A,B,...
			$Ind1 = $Ind1s[floor($i/4)%6];			//  TeamIndex1 von GruppeA,B,...
			$Ind2 = $Ind2s[floor($i/4)%6];			//  TeamIndex2
			$GameNr = "Game" . ($i+1);				//  Game1..48
			$Result = $_POST["$GameNr"];			//  2:3
			$ResultParts = explode(":", $Result);	// [2],[3]
			$Team1 = $AllGroups[$GrpIndex][$Ind1];	// Schweiz
			$Team2 = $AllGroups[$GrpIndex][$Ind2];	// Griechenland
			
			if (checkGame($Result) == 0)
				continue;
			
			// Summiere erzielte Tore
			$AllGroups[$GrpIndex][$Ind1+4] += ($ResultParts[0]-$ResultParts[1]);
			$AllGroups[$GrpIndex][$Ind2+4] += ($ResultParts[1]-$ResultParts[0]);
			
			// Summiere Punkte 
			$WinIndex = GetWinnerOrEqual($Ind1,$Ind2,$Result);
			if ($WinIndex == 99) 
			{	
				$AllGroups[$GrpIndex][$Ind1+4] += 1*100;
				$AllGroups[$GrpIndex][$Ind2+4] += 1*100;
			}
			else
				$AllGroups[$GrpIndex][$WinIndex+4] += 3*100;
		}
		
		//print_r($AllGroups);
		
		// laden der berechneten Achtelfinalgegner 
		${"Game" . 49 . "_T1"} = GetTeamWithRank($AllGroups[0],1);
		${"Game" . 49 . "_T2"} = GetTeamWithRank($AllGroups[1],2);
		${"Game" . 50 . "_T1"} = GetTeamWithRank($AllGroups[2],1);
		${"Game" . 50 . "_T2"} = GetTeamWithRank($AllGroups[3],2);
		${"Game" . 51 . "_T1"} = GetTeamWithRank($AllGroups[1],1);
		${"Game" . 51 . "_T2"} = GetTeamWithRank($AllGroups[0],2);
		${"Game" . 52 . "_T1"} = GetTeamWithRank($AllGroups[3],1);
		${"Game" . 52 . "_T2"} = GetTeamWithRank($AllGroups[2],2);
		${"Game" . 53 . "_T1"} = GetTeamWithRank($AllGroups[4],1);
		${"Game" . 53 . "_T2"} = GetTeamWithRank($AllGroups[5],2);
		${"Game" . 54 . "_T1"} = GetTeamWithRank($AllGroups[6],1);
		${"Game" . 54 . "_T2"} = GetTeamWithRank($AllGroups[7],2);
		${"Game" . 55 . "_T1"} = GetTeamWithRank($AllGroups[5],1);
		${"Game" . 55 . "_T2"} = GetTeamWithRank($AllGroups[4],2);
		${"Game" . 56 . "_T1"} = GetTeamWithRank($AllGroups[7],1);
		${"Game" . 56 . "_T2"} = GetTeamWithRank($AllGroups[6],2);
	}
	
	// laden der restlichen Datenfelder im Formular
	$PlayerName = $_POST["PlayerName"];
	$GroupFavorite = $_POST["GroupFavorite"];
	$TotalGoals = $_POST["TotalGoals"];

	for ($i=1; $i<=64; $i++){
		$index="Game" . $i;
		${"Game" . $i} =$_POST["$index"]; 
	}
}
/******************************************************************************
 * Funktions-Handler für den Button 'anzeigen'
 * -> die aktuellen Spiele des Benutzers '$PlayerName' werden angezeigt
  ****************************************************************************/
if (isset($_POST['anzeigen'])){
	$Player = $_POST['PlayerName'];
	//print "-> lade Spieler $Player aus Datenbank...";
	
	if ($Player == "Random")
	{
		// laden der restlichen Datenfelder im Formular
		$PlayerName = $_POST["PlayerName"];
		$GroupFavorite = $_POST["GroupFavorite"];
		$TotalGoals = $_POST["TotalGoals"];
			
		for ($i=1; $i<=64; $i++)
		{
			$index="Game" . $i;
			${"Game" . $i} = rand(0,5) . ":" . rand(0,5); 
		}
		$ShowForm = 1;
	} 
	else if ($Player == "Rangliste")
	{
		printHeader();
		CalculatePlayerList($_POST["PlayerName"]);
		$ShowForm = 0;
	}
	else
	{
		require_once('config/config.php');

		// Verbindung zum MySQL Server herstellen und Datenbank wählen
		$db=mysql_connect($db_serv, $db_user, $db_pass) or die ('I cannot connect to the database because: ' . mysql_error()); 
		mysql_select_db($db_name, $db) or die('ERROR!');

		// Alle Spielresultate für Spieler lesen
		$query = mysql_query("select * from wmtotto2010 where PlayerName = '" . mysql_real_escape_string($Player) . "';") or die(mysql_error());
		$row = mysql_fetch_array($query);	

		// laden der Datenfelder im Formular
		$PlayerName = $row['PlayerName'];
		
		if ($Player == $PlayerName)
		{
			$GroupFavorite = $row['GroupFavorite'];
			$TotalGoals = $row['TotalGoals'];

			for ($i=1; $i<=64; $i++){
				$index="Game" . $i;
				${"Game" . $i} = $row["$index"]; 
			}

			for ($i=49; $i<=64; $i++)
			{
				$index1="Game" . $i . "_T1";
				$index2="Game" . $i . "_T2";
				${"Game" . $i . "_T1"} = $row["$index1"]; 
				${"Game" . $i . "_T2"} = $row["$index2"]; 
			}
		}
		else
		{
			$errormsg = "[!] Spieler $Player unbekannt.";
			$error = 998;
			
			// laden der bestehenden Datenfelder im Formular
			$PlayerName = $_POST["PlayerName"];
			$GroupFavorite = $_POST["GroupFavorite"];
			$TotalGoals = $_POST["TotalGoals"];

			for ($i=1; $i<=64; $i++){
				$index="Game" . $i;
				${"Game" . $i} =$_POST["$index"]; 
			}
			
			for ($i=49; $i<=64; $i++)
			{
				$index1="Game" . $i . "_T1";
				$index2="Game" . $i . "_T2";
				${"Game" . $i . "_T1"} = $_POST["$index1"]; 
				${"Game" . $i . "_T2"} = $_POST["$index2"]; 
			}
		}
		$ShowForm = 1;
	}
	
}

/******************************************************************************
 * Funktions-Handler für den Button 'exportieren'
  ****************************************************************************/
if (isset($_POST['exportieren']))
{
	$error = 0;
	
	$CrcVal = sprintf("%u",crc32($_POST['PlayerName']));
	$PlayerName = $_POST["PlayerName"];
	$Password = $_POST["CrcVal"];

	require_once('config/config.php');
	$db=mysql_connect($db_serv, $db_user, $db_pass) or die ('I cannot connect to the database because: ' . mysql_error()); 
	mysql_select_db($db_name, $db) or die('ERROR!');
	
	if ( ($PlayerName == "Schreibschutz ein") && ($Password == $db_pass) )
	{
		mysql_query("UPDATE config SET EditMode=0;") or die(mysql_error());
		
		$error = 995;
		$errormsg = "[!] Schreibschutz ist aktiv!";
		$ShowForm = 1;
	}
	else if ( ($PlayerName == "Schreibschutz aus") && ($Password == $db_pass) )
	{
		mysql_query("UPDATE config SET EditMode='1';") or die(mysql_error());
		
		$error = 995;
		$errormsg = "[!] Schreibschutz ist ausgeschaltet!";
		$ShowForm = 1;
	}
	else
	{
		// prüfe, ob EditMode == 1 (Modifikation der DB erlaubt)
		$query = mysql_query("select * from config;") or die(mysql_error());
		$row = mysql_fetch_array($query);	
			
		if ( ($row['EditMode'] == 0) && ($PlayerName != "Master"))
		{
			$errormsg = "[!] Verändern der Spieleinträge nicht mehr erlaubt!";
			$ShowForm = 1;
			$error = 998;
		}
	}
	
	if ($error == 0)
	{
		$UserVal = $Password;
		
		//print "UserVal=$UserVal, CrcVal=$CrcVal, $PlayerName";
		
		if (0 != strcmp($UserVal,$CrcVal))
		{
			$errormsg = "[!] Das Passwort oder der Spielername stimmt nicht (Gross-/Kleinschreibung beachten!)";
			$CrcVal = "";
			$ShowForm = 1;
			$error = 997;
		}
	}

	if ($error == 0)
	{
		// check if player must be deleted
		if(strncmp($PlayerName, "-", strlen("-")) == 0)
		{
			// Lösche Spieler aus Datenbank
			$query = mysql_query("delete from wmtotto2010 where PlayerName = '" . mysql_real_escape_string(substr($PlayerName, 1)) . "';") or die(mysql_error());		
			$errormsg = "[!] Spieler " . substr($PlayerName, 1) . " wurde gelöscht!";
			$error = 995;
			$ShowForm = 1;
		}
	}
	
	if ( ($error == 0) && ($PlayerName != "Master"))
	{
		// check the TotalGoals field
		$_POST['TotalGoals'] = preg_replace('/\s+/','', $_POST['TotalGoals']);		
		if (!is_numeric($_POST['TotalGoals']))
		{
			$error = 994;
			$errormsg = "[!] Eingabefehler bei Anzahl Toren (Muster: 145)";
			$ShowForm = 1;
		}
	}

	if (($error == 0) && ($PlayerName != "Master"))
	{
		// check the GroupFavorite field
		if ($_POST['GroupFavorite'] == "")
		{
			$error = 994;
			$errormsg = "[!] Bitte Jokergruppe angeben.";
			$ShowForm = 1;
		}
	}	

	if (($error == 0) && ($PlayerName != "Master"))
	{
		// check all Game results and report error
		for ($i=1; $i<65; $i++){
			
			$index="Game" . $i;
			$_POST["$index"] = preg_replace('/\s+/','',$_POST["$index"]);
			${"Game" . $i} = $_POST["$index"];
			
			if (checkGame($_POST["$index"]) == 0)
			{
				$error = $i;
				$errormsg = "[!] Eingabefehler bei Spielresultat von Spiel $error! (Muster: Schweiz-Österreich 25:0)";
				$ShowForm = 1;
			} 
		}
	}	
			
	if ( ($error == 0) && ($PlayerName != "Master"))
	{		
		// check all game players and report error			
		for ($i=49; $i<=64; $i++){
			$index1="Game" . $i . "_T1";
			$index2="Game" . $i . "_T2";
			if ( ($_POST["$index1"] == "") || ($_POST["$index2"] == "")) 
			{
				$error = $i;
				$errormsg = "[!] Eingabefehler bei Gegnern von Spiel $error! (Muster: Schweiz-Österreich 25:0)";
				$ShowForm = 1;
			}
		}	
	}

	if (($error == 0)&& ($PlayerName != "Master"))
	{
		// check all finals if results are even
		for ($i=49; $i<65; $i++){
			
			$index="Game" . $i;
			$_POST["$index"] = preg_replace('/\s+/','',$_POST["$index"]);
			${"Game" . $i} = $_POST["$index"];
			
			if (checkEvenFinal($_POST["$index"]) == 0)
			{
				$error = $i;
				$errormsg = "[!] Finalspiel $error darf nicht unentschieden sein!";
				$ShowForm = 1;
			} 
		}		
	}

	if ($error == 0)
	{	
		// Verifikation ok. Trage die Spielerliste in die Datenbank ein
		// Verbindung zum MySQL Server herstellen und Datenbank wählen
		$db=mysql_connect($db_serv, $db_user, $db_pass) or die ('I cannot connect to the database because: ' . mysql_error()); 
		mysql_select_db($db_name, $db) or die('ERROR!');
		
		$data = array();
		array_push($data,mysql_real_escape_string($_POST['PlayerName']));
		array_push($data,mysql_real_escape_string($_POST['GroupFavorite']));
		array_push($data,mysql_real_escape_string($_POST['TotalGoals']));
		
		for ($i=1; $i<49; $i++)
		{
			$Game = 'Game' . $i;
			array_push($data,mysql_real_escape_string($_POST[$Game]));
		}
		
		for ($i=49; $i<65; $i++)
		{
			$T1 = 'Game'.$i.'_T1';
			$T2 = 'Game'.$i.'_T2';
			$Game = 'Game' . $i;
			array_push($data,mysql_real_escape_string($_POST[$T1]));
			array_push($data,mysql_real_escape_string($_POST[$T2]));
			array_push($data,mysql_real_escape_string($_POST[$Game]));
		}

		$sql = "SELECT COUNT(*) FROM wmtotto2010 WHERE PlayerName = '$data[0]';";
		$query = mysql_query($sql) or die(mysql_error());
		$row = mysql_fetch_array($query);		

		if ($row[0] == 0) 
		{		
			$sql = "INSERT INTO wmtotto2010 (PlayerName,GroupFavorite,TotalGoals";

			for ($i=1; $i<49; $i++)			
				$sql = $sql . ", Game$i";

			for ($i=49; $i<65; $i++)
				$sql = $sql . ", Game${i}_T1" . ", Game${i}_T2" . ", Game${i}"; 
			
			$sql = $sql . ") values ('" . $data[0] . "'"; 
	
			for ($i=1; $i<99; $i++)
				$sql = $sql . ", '" . $data[$i] . "'";
			$sql = $sql . ")";

			mysql_query($sql) or die(mysql_error());

			$error = 995;
			$errormsg = "[!] Spielresultate wurden erfolgreich gespeichert.";
			$ShowForm = 1;
		}
		else 
		{
			$sql = "UPDATE wmtotto2010 SET GroupFavorite='$data[1]',TotalGoals='$data[2]' ";

			for ($i=1; $i<49; $i++)
				$sql = $sql . ", Game$i='" . $data[$i+2] . "'";
			

			$j=51;
			for ($i=49; $i<65; $i++)
			{
				$sql = $sql . ", Game${i}_T1='" . $data[$j++] . "', Game${i}_T2='" . $data[$j++] . "', Game${i}='".$data[$j++]."'"; 
			}
		
			$sql = $sql . "WHERE PlayerName='$data[0]'";		

			mysql_query($sql) or die(mysql_error());

			$error = 995;
			$errormsg = "[!] Spielresultate in Datenbank erfolgreich aktualisiert.";
			$ShowForm = 1;
		}
			
	}
	
	// laden der Datenfelder im Formular
	$GroupFavorite = $_POST["GroupFavorite"];
	$TotalGoals = $_POST["TotalGoals"];

	for ($i=1; $i<=64; $i++){
		$index="Game" . $i;
		${"Game" . $i} =$_POST["$index"]; 
	}

	for ($i=49; $i<=64; $i++)
	{
		$index1="Game" . $i . "_T1";
		$index2="Game" . $i . "_T2";
		${"Game" . $i . "_T1"} = $_POST["$index1"]; 
		${"Game" . $i . "_T2"} = $_POST["$index2"]; 
	}
	
}
?>

<?php if ($ShowForm == 1){
/******************************************************************************
 * Haupt HTML Seite
 * -> Ausgabe der Datenfelder auf dem Bildschirm
  ****************************************************************************/
?>
<?php printHeader(); ?>
<form action="form.php" method="post">
	<table align="center" width="804px" border="0" cellspacing="0" cellpadding="1">
		<tr> 
        <td bgcolor="#999999">
		<table width="802px" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
			<tr> 
			<td>
			<table width="800px" border="0" cellspacing="0" cellpadding="3">
				<tr bgcolor="#BBBBBB"> 
					<td width="120px"/>
					<td width="60px"/>					
					<td width="120px"/>
					<td width="60px"/>
					<td width="120px"/>
					<td width="60px"/>					
					<td width="120px"/>
					<td width="60px"/>
                </tr>
				<tr bgcolor="#BBBBBB"> 
					<!--<image valign="top" align="right" height="80px" src="pictures/tipp-kick.jpg"/> -->
					<td colspan=3><p class='heading1'>Tippzettel Trikot-Totto WM 2010 Südafrika</p></td>
					<td colspan=3 align='right'><a href='http://www.trikot-totto.ch'>[zurück zur Hauptseite]</a></td>
					<td colspan=2 align='right'><a href='javascript:window.print()'> [Seite drucken] </a></td>
					<!--<td align='right'><a href='./documents/trikot-totto06.pdf'>[?] Hilfe</a></td> -->
                </tr>
				<tr> 
					<td>Spielername:</td>
					<td colspan=2><input size="20" name="PlayerName" type="text" maxlength="32" value="<? print $PlayerName; ?>" /></td>
					<td colspan=1><input style="width: 75px" name='anzeigen' type='submit' value='Laden' /></td>
					<td colspan=4>Tippzettel des eingegebenen Spielers anzeigen</td>
				</tr>
				<tr>
					<td>Passwort:</td>
					<td colspan=2><input size="20" name="CrcVal" type="text" maxlength="32" value="<? print $CrcVal; ?>" /></td>
					<td colspan=1><input style="width: 75px" name='exportieren' type='submit' value='Speichern' /></td>
					<td colspan=4>Tippzettel einsenden (benötigt Passwort)</td>
				</tr>
				<!--
				<tr>
					<td colspan=3></td>
					<td><input style="width: 75px" name='rangliste' type='submit' value='Rangliste' /></td>
					<td colspan=4>Rangliste aller Mitspieler anzeigen</td>
				</tr>
				-->
				<tr>
					<td bgcolor="<? if ($error==995) print lightgreen; else print yellow;?>" colspan=8><font color="#FF0000"><? print $errormsg; ?></font></td>
				</tr>
				<tr bgcolor="#BBBBBB"> 
					<td colspan=8><div class='heading1'>Allgemein</p></td>
                </tr>
				<tr>
					<td>Tore insgesamt:</td>
					<td colspan=1 valign="top"><input size="5" name="TotalGoals" type="text" maxlength="5" value="<? print $TotalGoals; ?>" /></td>
					<td colspan=6>(inkl. Verlängerungen und Elfmeterschiessen)</td>
				</tr>
				<tr bgcolor="#BBBBBB">
					<td align="center" bgcolor="#DDAACC" colspan=4>Spielresultate mit Doppelpunkt eingeben (z.B. <b>3:4</b>).</td><td align="center" bgcolor="#DDAACC" colspan=4>In der Jokergruppe zählen die Punkte doppelt!</td>
				</tr>
				<!--
				<tr bgcolor="#BBBBBB">
					
					<td align="center" bgcolor="#DDAACC" colspan=8>Pro SpielerIn kann eine Jokergruppe ausgewählt werden. In dieser zählen die Punkte doppelt.</td>
				</tr>
				-->
				<tr bgcolor="#BBBBBB">
					<td colspan=2><div class='heading1'>Gruppe A</p></td>
					<td colspan=2><div class='heading1'>Gruppe B</p></td>
					<td colspan=2><div class='heading1'>Gruppe C</p></td>
					<td colspan=2><div class='heading1'>Gruppe D</p></td>
				</tr>
				<tr bgcolor="#BBBBBB"> 
					<td colspan=2><input type="radio" name="GroupFavorite" value="A" <? if ($GroupFavorite == "A") print 'checked="yes"'?> /> Jokergruppe</p></td>
					<td colspan=2><input type="radio" name="GroupFavorite" value="B" <? if ($GroupFavorite == "B") print 'checked="yes"'?> /> Jokergruppe</p></td>
					<td colspan=2><input type="radio" name="GroupFavorite" value="C" <? if ($GroupFavorite == "C") print 'checked="yes"'?> /> Jokergruppe</p></td>
					<td colspan=2><input type="radio" name="GroupFavorite" value="D" <? if ($GroupFavorite == "D") print 'checked="yes"'?> /> Jokergruppe</p></td>
				</tr>
				
                <tr> 
					<? 	PrintGroupGameHtml($AllGroups[0][0],$AllGroups[0][1],$Game1,1); 
						PrintGroupGameHtml($AllGroups[1][0],$AllGroups[1][1],$Game2,2);
						PrintGroupGameHtml($AllGroups[2][0],$AllGroups[2][1],$Game3,3);
						PrintGroupGameHtml($AllGroups[3][0],$AllGroups[3][1],$Game4,4);?>
				</tr>
				<tr>
					<?	PrintGroupGameHtml($AllGroups[0][2],$AllGroups[0][3],$Game5,5); 
						PrintGroupGameHtml($AllGroups[1][2],$AllGroups[1][3],$Game6,6);
						PrintGroupGameHtml($AllGroups[2][2],$AllGroups[2][3],$Game7,7);
						PrintGroupGameHtml($AllGroups[3][2],$AllGroups[3][3],$Game8,8);?>
				</tr>
				<tr> 
					<? 	PrintGroupGameHtml($AllGroups[0][0],$AllGroups[0][2],$Game9,9); 
						PrintGroupGameHtml($AllGroups[1][0],$AllGroups[1][2],$Game10,10);
						PrintGroupGameHtml($AllGroups[2][0],$AllGroups[2][2],$Game11,11);
						PrintGroupGameHtml($AllGroups[3][0],$AllGroups[3][2],$Game12,12);?>
				</tr>
				<tr>
					<? 	PrintGroupGameHtml($AllGroups[0][3],$AllGroups[0][1],$Game13,13); 
						PrintGroupGameHtml($AllGroups[1][3],$AllGroups[1][1],$Game14,14);
						PrintGroupGameHtml($AllGroups[2][3],$AllGroups[2][1],$Game15,15);
						PrintGroupGameHtml($AllGroups[3][3],$AllGroups[3][1],$Game16,16);?>
				</tr>
				<tr>
					<? 	PrintGroupGameHtml($AllGroups[0][3],$AllGroups[0][0],$Game17,17); 
						PrintGroupGameHtml($AllGroups[1][3],$AllGroups[1][0],$Game18,18);
						PrintGroupGameHtml($AllGroups[2][3],$AllGroups[2][0],$Game19,19);
						PrintGroupGameHtml($AllGroups[3][3],$AllGroups[3][0],$Game20,20);?>
				</tr>
				<tr>
					<? 	PrintGroupGameHtml($AllGroups[0][1],$AllGroups[0][2],$Game21,21); 
						PrintGroupGameHtml($AllGroups[1][1],$AllGroups[1][2],$Game22,22);
						PrintGroupGameHtml($AllGroups[2][1],$AllGroups[2][2],$Game23,23);
						PrintGroupGameHtml($AllGroups[3][1],$AllGroups[3][2],$Game24,24);?>
				</tr>
				
				<tr bgcolor="#BBBBBB">
					<td colspan=2><div class='heading1'>Gruppe E</p></td>
					<td colspan=2><div class='heading1'>Gruppe F</p></td>
					<td colspan=2><div class='heading1'>Gruppe G</p></td>
					<td colspan=2><div class='heading1'>Gruppe H</p></td>
				</tr>
				<tr bgcolor="#BBBBBB"> 
					<td colspan=2><input type="radio" name="GroupFavorite" value="E" <? if ($GroupFavorite == "E") print 'checked="yes"'?> /> Jokergruppe</p></td>
					<td colspan=2><input type="radio" name="GroupFavorite" value="F" <? if ($GroupFavorite == "F") print 'checked="yes"'?> /> Jokergruppe</p></td>
					<td colspan=2><input type="radio" name="GroupFavorite" value="G" <? if ($GroupFavorite == "G") print 'checked="yes"'?> /> Jokergruppe</p></td>
					<td colspan=2><input type="radio" name="GroupFavorite" value="H" <? if ($GroupFavorite == "H") print 'checked="yes"'?> /> Jokergruppe</p></td>
				</tr>
                <tr> 
					<? 	PrintGroupGameHtml($AllGroups[4][0],$AllGroups[4][1],$Game25,25); 
						PrintGroupGameHtml($AllGroups[5][0],$AllGroups[5][1],$Game26,26);
						PrintGroupGameHtml($AllGroups[6][0],$AllGroups[6][1],$Game27,27);
						PrintGroupGameHtml($AllGroups[7][0],$AllGroups[7][1],$Game28,28);?>
				</tr>
				<tr>
					<? 	PrintGroupGameHtml($AllGroups[4][2],$AllGroups[4][3],$Game29,29); 
						PrintGroupGameHtml($AllGroups[5][2],$AllGroups[5][3],$Game30,30);
						PrintGroupGameHtml($AllGroups[6][2],$AllGroups[6][3],$Game31,31);
						PrintGroupGameHtml($AllGroups[7][2],$AllGroups[7][3],$Game32,32);?>
				</tr>
				<tr> 
					<? 	PrintGroupGameHtml($AllGroups[4][0],$AllGroups[4][2],$Game33,33); 
						PrintGroupGameHtml($AllGroups[5][0],$AllGroups[5][2],$Game34,34);
						PrintGroupGameHtml($AllGroups[6][0],$AllGroups[6][2],$Game35,35);
						PrintGroupGameHtml($AllGroups[7][0],$AllGroups[7][2],$Game36,36);?>
				</tr>
				<tr>
					<? 	PrintGroupGameHtml($AllGroups[4][3],$AllGroups[4][1],$Game37,37); 
						PrintGroupGameHtml($AllGroups[5][3],$AllGroups[5][1],$Game38,38);
						PrintGroupGameHtml($AllGroups[6][3],$AllGroups[6][1],$Game39,39);
						PrintGroupGameHtml($AllGroups[7][3],$AllGroups[7][1],$Game40,40);?>
				</tr>
				<tr> 
					<? 	PrintGroupGameHtml($AllGroups[4][3],$AllGroups[4][0],$Game41,41); 
						PrintGroupGameHtml($AllGroups[5][3],$AllGroups[5][0],$Game42,42);
						PrintGroupGameHtml($AllGroups[6][3],$AllGroups[6][0],$Game43,43);
						PrintGroupGameHtml($AllGroups[7][3],$AllGroups[7][0],$Game44,44);?>  
				</tr>
				<tr>
					<? 	PrintGroupGameHtml($AllGroups[4][1],$AllGroups[4][2],$Game45,45); 
						PrintGroupGameHtml($AllGroups[5][1],$AllGroups[5][2],$Game46,46);
						PrintGroupGameHtml($AllGroups[6][1],$AllGroups[6][2],$Game47,47);
						PrintGroupGameHtml($AllGroups[7][1],$AllGroups[7][2],$Game48,48);?>  
				</tr>
			</table>
			
			<table width="800px" border="0" cellspacing="0" cellpadding="3">
				<tr bgcolor="#BBBBBB"> 
					<td width="133px"/>
					<td width="133px"/>					
					<td width="133px"/>
					<td width="133px"/>
					<td width="133px"/>
					<td width="133px"/>					
                </tr>
				<!-- ************ ACHTELFINAL ********************************* -->
				<tr bgcolor="#BBBBBB"> 
					<td colspan=1><div class='heading1'>Achtelfinal</p></td>
					<td colspan=4 align='right'>Achtelfinalgegner ausrechnen -> </td>
					<td><input name='calc_eight' type='submit' value='x'/></td>
				</tr>
				<tr>
					<td>Erster A</td><td>Zweiter B</td><td>Spiel 49</td><td>Erster C</td><td>Zweiter D</td><td>Spiel 50</td>
				</tr>
				<tr>
					<td><select width="90px" name="Game49_T1">
							<option selected="yes"><? print $Game49_T1; ?></option>
							<? printGroupOptions('A'); ?> 
					</td>
					<td><select name="Game49_T2">
							<option selected="yes"><? print $Game49_T2; ?></option>
							<? printGroupOptions('B'); ?> 
					</td>
					<td><input name="Game49" type="text" size="5" value="<? print $Game49; ?>" /></td>
					<td><select name="Game50_T1">
							<option selected="yes"><? print $Game50_T1; ?></option>
							<? printGroupOptions('C'); ?> 
					</td>
					<td><select name="Game50_T2">
							<option selected="yes"><? print $Game50_T2; ?></option>
							<? printGroupOptions('D'); ?> 
					</td>
					<td><input name="Game50" type="text" size="5" value="<? print $Game50; ?>" /></td>
				</tr>
				<tr>
					<td>Erster B</td><td>Zweiter A</td><td>Spiel 51</td><td>Erster D</td><td>Zweiter C</td><td>Spiel 52</td>
				</tr>
				<tr>
					<td><select name="Game51_T1">
							<option selected="yes"><? print $Game51_T1; ?></option>
							<? printGroupOptions('B'); ?> 
					</td>
					<td><select name="Game51_T2">
							<option selected="yes"><? print $Game51_T2; ?></option>
							<? printGroupOptions('A'); ?> 
					</td>
					<td><input name="Game51" type="text" size="5" value="<? print $Game51; ?>" /></td>
					<td><select name="Game52_T1">
							<option selected="yes"><? print $Game52_T1; ?></option>
							<? printGroupOptions('D'); ?> 
					</td>
					<td><select name="Game52_T2">
							<option selected="yes"><? print $Game52_T2; ?></option>
							<? printGroupOptions('C'); ?> 
					</td>
					<td><input name="Game52" type="text" size="5" value="<? print $Game52; ?>" /></td>
				</tr>
				<tr>
					<td>Erster E</td><td>Zweiter F</td><td>Spiel 53</td><td>Erster G</td><td>Zweiter H</td><td >Spiel 54</td>
				</tr>
				<tr>
					<td><select name="Game53_T1">
							<option selected="yes"><? print $Game53_T1; ?></option>
							<? printGroupOptions('E'); ?> 
					</td>
					<td><select name="Game53_T2">
							<option selected="yes"><? print $Game53_T2; ?></option>
							<? printGroupOptions('F'); ?> 
					</td>
					<td ><input name="Game53" type="text" size="5" value="<? print $Game53; ?>" /></td>
					<td><select name="Game54_T1">
							<option selected="yes"><? print $Game54_T1; ?></option>
							<? printGroupOptions('G'); ?> 
					</td>
					<td><select name="Game54_T2">
							<option selected="yes"><? print $Game54_T2; ?></option>
							<? printGroupOptions('H'); ?> 
					</td>
					<td ><input name="Game54" type="text" size="5" value="<? print $Game54; ?>" /></td>
				</tr>
				<tr>
					<td>Erster F</td><td>Zweiter E</td><td >Spiel 55</td><td>Erster H</td><td>Zweiter G</td><td >Spiel 56</td>
				</tr>
				<tr>
					<td><select name="Game55_T1">
							<option selected="yes"><? print $Game55_T1; ?></option>
							<? printGroupOptions('F'); ?> 
					</td>
					<td><select name="Game55_T2">
							<option selected="yes"><? print $Game55_T2; ?></option>
							<? printGroupOptions('E'); ?> 
					</td>
					<td ><input name="Game55" type="text" size="5" value="<? print $Game55; ?>" /></td>
					<td><select name="Game56_T1">
							<option selected="yes"><? print $Game56_T1; ?></option>
							<? printGroupOptions('H'); ?> 
					</td>
					<td><select name="Game56_T2">
							<option selected="yes"><? print $Game56_T2; ?></option>
							<? printGroupOptions('G'); ?> 
					</td>
					<td ><input name="Game56" type="text" size="5" value="<? print $Game56; ?>" /></td>
				</tr>
				
				<!-- ************ ViertelFINAL ********************************* -->
				<tr bgcolor="#BBBBBB"> 
					<td colspan=1><div class='heading1'>Viertelfinal</p></td>
					<td colspan=4 align='right'>Viertelfinalgegner ausrechnen -></td>
					<td colspan=1><input name='calc_fourth' type='submit' value='x'/></td>
				</tr>
				<tr>
					<td>Sieger 49</td><td>Sieger 50</td><td >Spiel 57</td><td>Sieger 53</td><td>Sieger 54</td><td >Spiel 58</td>
				</tr>
				<tr>
					<td><select name="Game57_T1">
							<option selected="yes"><? print $Game57_T1; ?></option>
							<? printGroupOptions('A'); ?> 
							<? printGroupOptions('B'); ?> 
					</td>
					<td><select name="Game57_T2">
							<option selected="yes"><? print $Game57_T2; ?></option>
							<? printGroupOptions('C'); ?> 
							<? printGroupOptions('D'); ?> 
					</td>
					<td ><input name="Game57" type="text" size="5" value="<? print $Game57; ?>" /></td>
					
					<td><select name="Game58_T1">
							<option selected="yes"><? print $Game58_T1; ?></option>
							<? printGroupOptions('E'); ?> 
							<? printGroupOptions('F'); ?> 
					</td>
					<td><select name="Game58_T2">
							<option selected="yes"><? print $Game58_T2; ?></option>
							<? printGroupOptions('G'); ?> 
							<? printGroupOptions('H'); ?> 
					</td>
					<td ><input name="Game58" type="text" size="5" value="<? print $Game58; ?>" /></td>
				</tr>
                <tr>
					<td>Sieger 51</td><td>Sieger 52</td><td >Spiel 59</td><td>Sieger 55</td><td>Sieger 56</td><td >Spiel 60</td>
				</tr>
				<tr>
					<td><select name="Game59_T1">
							<option selected="yes"><? print $Game59_T1; ?></option>
							<? printGroupOptions('A'); ?> 
							<? printGroupOptions('B'); ?> 
					</td>
					<td><select name="Game59_T2">
							<option selected="yes"><? print $Game59_T2; ?></option>
							<? printGroupOptions('C'); ?> 
							<? printGroupOptions('D'); ?> 
					</td>
					<td ><input name="Game59" type="text" size="5" value="<? print $Game59; ?>" /></td>
					
					<td><select name="Game60_T1">
							<option selected="yes"><? print $Game60_T1; ?></option>
							<? printGroupOptions('E'); ?> 
							<? printGroupOptions('F'); ?> 
					</td>
					<td><select name="Game60_T2">
							<option selected="yes"><? print $Game60_T2; ?></option>
							<? printGroupOptions('G'); ?> 
							<? printGroupOptions('H'); ?> 
					</td>
					<td ><input name="Game60" type="text" size="5" value="<? print $Game60; ?>" /></td>
				</tr> 
				<!-- ************ Halbfinal ********************************* -->
				<tr bgcolor="#BBBBBB"> 
					<td colspan=1><div class='heading1'>Halbfinal</p></td>
					<td colspan=4 align='right'>Halbfinalgegner ausrechnen -></td>
					<td colspan=1><input name='calc_half' type='submit' value='x'/></td>
				</tr>
				<tr>
					<td>Sieger 57</td><td>Sieger 58</td><td >Spiel 61</td><td>Sieger 59</td><td>Sieger 60</td><td >Spiel 62</td>
				</tr>
				<tr>
					<td><select name="Game61_T1">
							<option selected="yes"><? print $Game61_T1; ?></option>
							<? printGroupOptions('A'); ?> 
							<? printGroupOptions('B'); ?> 
							<? printGroupOptions('C'); ?> 
							<? printGroupOptions('D'); ?> 
					</td>
					<td><select name="Game61_T2">
							<option selected="yes"><? print $Game61_T2; ?></option>
							<? printGroupOptions('E'); ?> 
							<? printGroupOptions('F'); ?> 
							<? printGroupOptions('G'); ?> 
							<? printGroupOptions('H'); ?> 
					</td>
					<td ><input name="Game61" type="text" size="5" value="<? print $Game61; ?>" /></td>
					
					<td><select name="Game62_T1">
							<option selected="yes"><? print $Game62_T1; ?></option>
							<? printGroupOptions('A'); ?> 
							<? printGroupOptions('B'); ?> 
							<? printGroupOptions('C'); ?> 
							<? printGroupOptions('D'); ?> 
					</td>
					<td><select name="Game62_T2">
							<option selected="yes"><? print $Game62_T2; ?></option>
							<? printGroupOptions('E'); ?> 
							<? printGroupOptions('F'); ?> 
							<? printGroupOptions('G'); ?> 
							<? printGroupOptions('H'); ?> 
					</td>
					<td ><input name="Game62" type="text" size="5" value="<? print $Game62; ?>" /></td>
				</tr>
				<!-- ************ Finalspiele ********************************* -->
				<tr bgcolor="#BBBBBB"> 
					<td colspan=2><div class='heading1'>Spiel um Platz 3 und 4</p></td>
					<td colspan=3 align='right'>Finalgegner ausrechnen -></td>
					<td colspan=1><input name='calc_finals' type='submit' value='x'/></td>
				</tr>
				<tr>
					<td></td><td>Verlierer 61</td><td>Verlierer 62</td><td colspan=3>Spiel 63</td>
				</tr>
				<tr>
					<td></td>
					<td><select name="Game63_T1">
							<option selected="yes"><? print $Game63_T1; ?></option>
							<? printGroupOptions('A'); ?> 
							<? printGroupOptions('B'); ?> 
							<? printGroupOptions('C'); ?> 
							<? printGroupOptions('D'); ?> 
							<? printGroupOptions('E'); ?> 
							<? printGroupOptions('F'); ?> 
							<? printGroupOptions('G'); ?> 
							<? printGroupOptions('H'); ?> 
					</td>
					<td><select name="Game63_T2">
							<option selected="yes"><? print $Game63_T2; ?></option>
							<? printGroupOptions('A'); ?> 
							<? printGroupOptions('B'); ?> 
							<? printGroupOptions('C'); ?> 
							<? printGroupOptions('D'); ?> 
							<? printGroupOptions('E'); ?> 
							<? printGroupOptions('F'); ?> 
							<? printGroupOptions('G'); ?> 
							<? printGroupOptions('H'); ?> 
					</td>
					<td colspan=3><input name="Game63" type="text" size="5" value="<? print $Game63; ?>" /></td>
				</tr>
				<tr bgcolor="#BBBBBB"> 
					<td colspan=6><div class='heading1'>WM Final</p></td>
				</tr>
				<tr>
					<td></td><td>Sieger 61</td><td>Sieger 62</td><td colspan=3>Spiel 64</td>
				</tr>	
					<td></td>
					<td><select name="Game64_T1">
							<option selected="yes"><? print $Game64_T1; ?></option>
							<? printGroupOptions('A'); ?> 
							<? printGroupOptions('B'); ?> 
							<? printGroupOptions('C'); ?> 
							<? printGroupOptions('D'); ?> 
							<? printGroupOptions('E'); ?> 
							<? printGroupOptions('F'); ?> 
							<? printGroupOptions('G'); ?> 
							<? printGroupOptions('H'); ?> 
					</td>
					<td><select name="Game64_T2">
							<option selected="yes"><? print $Game64_T2; ?></option>
							<? printGroupOptions('A'); ?> 
							<? printGroupOptions('B'); ?> 
							<? printGroupOptions('C'); ?> 
							<? printGroupOptions('D'); ?> 
							<? printGroupOptions('E'); ?> 
							<? printGroupOptions('F'); ?> 
							<? printGroupOptions('G'); ?> 
							<? printGroupOptions('H'); ?> 
					</td>
					<td colspan=3><input name="Game64" type="text" size="5" value="<? print $Game64; ?>" /></td>
				</tr>
				<tr bgcolor="#BBBBBB">
					<td align="center" bgcolor="#DDAACC" colspan=6>Um den Tippzettel abzuspeichern, bitte zuoberst Name und Passwort eingeben und den Button "Speichern" betätigen!</td>
				</tr>
                </table></td>
                </tr>
            </table>
</form>
</body>
<?php } else { } ?>
