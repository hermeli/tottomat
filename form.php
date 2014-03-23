<?php
/***********************************************************************
 * form.php 
 *
 * Hauptdatei für Trikot-Totto PHP Applikation. 
 * 
 * Die Formularfelder bestehen aus POST-Werten, welche aus der 
 * Datenbank eines Benutzers geladen werden.
 * 
 * Während der Bearbeitung des Formulars kann der Benutzer Aktionen
 * wie z.B. das Berechnen der Achtelfinalteilnehmer auslösen. Der 
 * Ablauf dieser Aktionen ist wie folgt definiert:
 * 
 * 1. Teams und Gruppenspiele initialisieren.
 * 2. handler.php abarbeiten, wurde ein Button gedrückt?
 * 3. Handler aufrufen, POST-Werte (z.B. Achtelfinalgegner) berechnen.
 * 4. Alle (inkl. modifizierte) POST-Werte in DB speichern.
 * 5. Alle POST Werte aus DB laden.
 * 6. HTML-Formular mit POST-Werten anzeigen. 
 *
 * Geschrieben von Stefan Wyss (wyss@superspider.net), Januar 2014
 **********************************************************************/
//setlocale(LC_TIME, 'ISO-8859-1');

require_once('config.php');
require_once('classes.php');
require_once('view.php'); 
require_once('util.php');

/***********************************************************************
* Initialize an array of all teams
***********************************************************************/
$teams[0] = NEW team(0,"A","Brasilien");
$teams[1] = NEW team(1,"A","Kroatien");
$teams[2] = NEW team(2,"A","Mexiko");
$teams[3] = NEW team(3,"A","Kamerun");
$teams[4] = NEW team(4,"B","Spanien");
$teams[5] = NEW team(5,"B","Niederlande");
$teams[6] = NEW team(6,"B","Chile");
$teams[7] = NEW team(7,"B","Australien");
$teams[8] = NEW team(8,"C","Kolumbien");
$teams[9] = NEW team(9,"C","Griechenland");
$teams[10] = NEW team(10,"C","Elfenbeink.");
$teams[11] = NEW team(11,"C","Japan");
$teams[12] = NEW team(12,"D","Uruguay");
$teams[13] = NEW team(13,"D","Costa Rica");
$teams[14] = NEW team(14,"D","England");
$teams[15] = NEW team(15,"D","Italien");
$teams[16] = NEW team(16,"E","Schweiz");
$teams[17] = NEW team(17,"E","Ecuador");
$teams[18] = NEW team(18,"E","Frankreich");
$teams[19] = NEW team(19,"E","Honduras");
$teams[20] = NEW team(20,"F","Argentinien");
$teams[21] = NEW team(21,"F","Bosnien-H.");
$teams[22] = NEW team(22,"F","Iran");
$teams[23] = NEW team(23,"F","Nigeria");
$teams[24] = NEW team(24,"G","Deutschland");
$teams[25] = NEW team(25,"G","Portugal");
$teams[26] = NEW team(26,"G","Ghana");
$teams[27] = NEW team(27,"G","USA");
$teams[28] = NEW team(28,"H","Belgien");
$teams[29] = NEW team(29,"H","Algerien");
$teams[30] = NEW team(30,"H","Russland");
$teams[31] = NEW team(31,"H","Südkorea");

/***********************************************************************
* Initialize an array of all matches
***********************************************************************/
$matches[0]  = NEW match(0,"A",GetTeam("Brasilien"),GetTeam("Kroatien"));
$matches[1]  = NEW match(1,"A",GetTeam("Mexiko"),GetTeam("Kamerun"));
$matches[2]  = NEW match(2,"A",GetTeam("Brasilien"),GetTeam("Mexiko"));
$matches[3]  = NEW match(3,"A",GetTeam("Kamerun"),GetTeam("Kroatien"));
$matches[4]  = NEW match(4,"A",GetTeam("Kamerun"),GetTeam("Brasilien"));
$matches[5]  = NEW match(5,"A",GetTeam("Kroatien"),GetTeam("Mexiko"));
$matches[6]  = NEW match(6,"B",GetTeam("Spanien"),GetTeam("Niederlande"));
$matches[7]  = NEW match(7,"B",GetTeam("Chile"),GetTeam("Australien"));
$matches[8]  = NEW match(8,"B",GetTeam("Australien"),GetTeam("Niederlande"));
$matches[9]  = NEW match(9,"B",GetTeam("Spanien"),GetTeam("Chile"));
$matches[10] = NEW match(10,"B",GetTeam("Australien"),GetTeam("Spanien"));
$matches[11] = NEW match(11,"B",GetTeam("Niederlande"),GetTeam("Chile"));
$matches[12] = NEW match(12,"C",GetTeam("Kolumbien"),GetTeam("Griechenland"));
$matches[13] = NEW match(13,"C",GetTeam("Elfenbeink."),GetTeam("Japan"));
$matches[14] = NEW match(14,"C",GetTeam("Kolumbien"),GetTeam("Elfenbeink."));
$matches[15] = NEW match(15,"C",GetTeam("Japan"),GetTeam("Griechenland"));
$matches[16] = NEW match(16,"C",GetTeam("Japan"),GetTeam("Kolumbien"));
$matches[17] = NEW match(17,"C",GetTeam("Griechenland"),GetTeam("Elfenbeink."));
$matches[18] = NEW match(18,"D",GetTeam("Uruguay"),GetTeam("Costa Rica"));
$matches[19] = NEW match(19,"D",GetTeam("England"),GetTeam("Italien"));
$matches[20] = NEW match(20,"D",GetTeam("Uruguay"),GetTeam("England"));
$matches[21] = NEW match(21,"D",GetTeam("Italien"),GetTeam("Costa Rica"));
$matches[22] = NEW match(22,"D",GetTeam("Costa Rica"),GetTeam("England"));
$matches[23] = NEW match(23,"D",GetTeam("Italien"),GetTeam("Uruguay"));
$matches[24] = NEW match(24,"E",GetTeam("Schweiz"),GetTeam("Ecuador"));
$matches[25] = NEW match(25,"E",GetTeam("Frankreich"),GetTeam("Honduras"));
$matches[26] = NEW match(26,"E",GetTeam("Schweiz"),GetTeam("Frankreich"));
$matches[27] = NEW match(27,"E",GetTeam("Honduras"),GetTeam("Ecuador"));
$matches[28] = NEW match(28,"E",GetTeam("Honduras"),GetTeam("Schweiz"));
$matches[29] = NEW match(29,"E",GetTeam("Ecuador"),GetTeam("Frankreich"));
$matches[30] = NEW match(30,"F",GetTeam("Argentinien"),GetTeam("Bosnien-H."));
$matches[31] = NEW match(31,"F",GetTeam("Iran"),GetTeam("Nigeria"));
$matches[32] = NEW match(32,"F",GetTeam("Argentinien"),GetTeam("Iran"));
$matches[33] = NEW match(33,"F",GetTeam("Nigeria"),GetTeam("Bosnien-H."));
$matches[34] = NEW match(34,"F",GetTeam("Nigeria"),GetTeam("Argentinien"));
$matches[35] = NEW match(35,"F",GetTeam("Bosnien-H."),GetTeam("Iran"));
$matches[36] = NEW match(36,"G",GetTeam("Deutschland"),GetTeam("Portugal"));
$matches[37] = NEW match(37,"G",GetTeam("Ghana"),GetTeam("USA"));
$matches[38] = NEW match(38,"G",GetTeam("Deutschland"),GetTeam("Ghana"));
$matches[39] = NEW match(39,"G",GetTeam("USA"),GetTeam("Portugal"));
$matches[40] = NEW match(40,"G",GetTeam("Portugal"),GetTeam("Ghana"));
$matches[41] = NEW match(41,"G",GetTeam("USA"),GetTeam("Deutschland"));
$matches[42] = NEW match(42,"H",GetTeam("Belgien"),GetTeam("Algerien"));
$matches[43] = NEW match(43,"H",GetTeam("Russland"),GetTeam("Südkorea"));
$matches[44] = NEW match(44,"H",GetTeam("Belgien"),GetTeam("Russland"));
$matches[45] = NEW match(45,"H",GetTeam("Südkorea"),GetTeam("Algerien"));
$matches[46] = NEW match(46,"H",GetTeam("Algerien"),GetTeam("Russland"));
$matches[47] = NEW match(47,"H",GetTeam("Südkorea"),GetTeam("Belgien"));
$matches[48] = NEW match(48,"AF",GetTeam(""),GetTeam(""));
$matches[49] = NEW match(49,"AF",GetTeam(""),GetTeam(""));
$matches[50] = NEW match(50,"AF",GetTeam(""),GetTeam(""));
$matches[51] = NEW match(51,"AF",GetTeam(""),GetTeam(""));
$matches[52] = NEW match(52,"AF",GetTeam(""),GetTeam(""));
$matches[53] = NEW match(53,"AF",GetTeam(""),GetTeam(""));
$matches[54] = NEW match(54,"AF",GetTeam(""),GetTeam(""));
$matches[55] = NEW match(55,"AF",GetTeam(""),GetTeam(""));
$matches[56] = NEW match(56,"VF",GetTeam(""),GetTeam(""));
$matches[57] = NEW match(57,"VF",GetTeam(""),GetTeam(""));
$matches[58] = NEW match(58,"VF",GetTeam(""),GetTeam(""));
$matches[59] = NEW match(59,"VF",GetTeam(""),GetTeam(""));
$matches[60] = NEW match(60,"HF",GetTeam(""),GetTeam(""));
$matches[61] = NEW match(61,"HF",GetTeam(""),GetTeam(""));
$matches[62] = NEW match(62,"FF",GetTeam(""),GetTeam(""));
$matches[63] = NEW match(63,"FF",GetTeam(""),GetTeam(""));

// the player
$player = NEW player();
 
// Indices der Finalspiele 
$lastEight = array(48,49,50,51,52,53,54,55);
$lastFour = array(56,57,58,59);
$lastSemi = array(60,61);
$lastFinal = array(62,63);
	 
// Ausgabe des HTML Headers mit CSS Styles
printHeader();

// Globale Variablen deklarieren
global $mas;

// lade Benutzername aus Joomla
define( '_JEXEC', 1 );
define( 'JPATH_BASE', realpath(dirname(__FILE__).'/..' ));
define( 'DS', DIRECTORY_SEPARATOR );
require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );
JFactory::getApplication('site')->initialise();
$user =& JFactory::getUser();
$player->username = $user->username;
$player->name = $user->name;

// Verbindung zum MySQL Server herstellen und Datenbank wählen
$db=mysql_connect($db_serv, $db_user, $db_pass) or die ('Cannot connect to the database because: ' . mysql_error()); 
mysql_select_db($db_name, $db) or die('ERROR!');

// Alle Spielresultate aus Master-Eintrag lesen
$query = mysql_query("select * from wmtotto2014 where PlayerName = 'Master';") or die(mysql_error());
$mas = mysql_fetch_array($query);	

// Execute Form Button Handlers
// require_once('handler.php');

if (isset($_POST['savetodb']))
{
	LoadMatchesFrom("POST");
	SaveMatchesToDB();
}
elseif (isset($_POST['calculateeight']))
{
	LoadMatchesFrom("POST");
	CalculateLastSixteenFinals();
	SaveMatchesToDB();	
}
elseif (isset($_POST['calculatequarter']))
{
	LoadMatchesFrom("POST");
	CalculateQuarterFinals();
	SaveMatchesToDB();
}
elseif (isset($_POST['calculatehalf']))
{
	LoadMatchesFrom("POST");
	CalculateHalfFinals();
	SaveMatchesToDB();
}
elseif (isset($_POST['calculatefinal']))
{
	LoadMatchesFrom("POST");
	CalculateFinals();
	SaveMatchesToDB();	
}
elseif (isset($_POST['calculatechampion']))
{
	LoadMatchesFrom("POST");
	CalculateChampion();
	SaveMatchesToDB();
}
else
{
	LoadMatchesFrom("DB");
}
CalculatePlayerScore();
SavePlayerScoreToDB();
?>
<center>
<form action="http://trikot-totto.ch/tottomat/form.php" method="post">

<table border="0" cellspacing="0" cellpadding="3">
	<col style="width:16%">
    <col style="width:8%">
    <col style="width:16%">
    <col style="width:8%">
    <col style="width:16%">
    <col style="width:8%">
    <col style="width:16%">
    <col style="width:8%">	
				
	<tr bgcolor="#BBBBBB"> 
		<td colspan=6>Tippzettel Trikot-Totto WM 2014. <? print "Spieler: $player->username ($player->name), Punkte: $player->score"; ?>
		</td>
		<td colspan=2><a href='javascript:window.print()'>[Seite drucken]</a></td>
    </tr>
	<tr>
		<td colspan=8><input style="width: 75px" name='savetodb' type='submit' value='Speichern' /></td>
		<!-- <td colspan=1><input style="width: 75px" name='random' type='submit' value='Zufall' /></td> -->
	</tr>
	<tr>
		<td colspan=4>Tippzettel vollstaendig und korrekt?</td>
		
		<? 
			$eGame = -1;
			$eTeam = -1;
			foreach ($matches as $match)
			{
				if ($match->checkScore() == 0)
				{
					$eGame = $match->matchNr + 1;
					break;
				}
				if ($match->checkTeams() == 0)
				{
					$eTeam = $match->matchNr + 1;
					break;
				}
			}
					
			if ($eGame!=-1)
			{
				print "<td><img style='float:middle' width=32 height=32 src='/tottomat/pictures/smiley-sad.jpg'/></td>";
				print "<td colspan=3 bgcolor='#AB0AB0'>Fehler bei Ergebnis Spiel $eGame!</td>";
				
			}
			elseif ($eTeam!=-1)
			{
				print "<td><img width=32 height=32 src='/tottomat/pictures/smiley-sad.jpg'/></td>";
				print "<td colspan=3 class=Checker>Teamfehler (oder 2x gleiches Team) bei Spiel $eTeam!</td>";
			}
			elseif ($player->groupFavorite=="")
			{
				print "<td><img width=32 height=32 src='/tottomat/pictures/smiley-sad.jpg'/></td>";
				print "<td colspan=3 class=Checker>Jokergruppe fehlt!</td>"; 
			}
			elseif ($player->totalGoals=="0")
			{
				print "<td><img width=32 height=32 src='/tottomat/pictures/smiley-sad.jpg'/></td>";
				print "<td colspan=3 bgcolor='#BBBBBB'>'Tore insgesamt' fehlt!</td>";  
			}
			else 
			{
				print "<td><img width=32 height=32 src='/tottomat/pictures/smiley-happy.jpg'/></td>";
				print "<td colspan=3>Ja</td>";
			}
		?>
	</tr>
	<tr bgcolor="#BBBBBB"> 
		<td colspan=8>Allgemein</td>
    </tr>
	<tr>
		<td>Tore insgesamt:</td>
		<td colspan=1 valign="top"><input size="5" name="TotalGoals" type="text" maxlength="5" value="<? print $player->totalGoals; ?>" /></td>
		<td colspan=6>(inkl. Verlaengerungen und Elfmeterschiessen)</td>
	</tr>
	<tr bgcolor="#BBBBBB">
		<td align="center" bgcolor="#DDAACC" colspan=8>Spielresultate mit Doppelpunkt eingeben (z.B. <b>3:4</b>). Jokergruppe = Punkte verdoppeln!</td>
	</tr>
	<!-- ************ GRUPPEN A-D ********************************* -->
	<tr bgcolor="#BBBBBB">
		<td colspan=2><div class='heading1'>Gruppe A (Spiele 1-6)</td>
		<td colspan=2><div class='heading1'>Gruppe B (Spiele 7-12)</td>
		<td colspan=2><div class='heading1'>Gruppe C (Spiele 13-18)</td>
		<td colspan=2><div class='heading1'>Gruppe D (Spiele 19-24)</td>
	</tr>
	<tr bgcolor="#BBBBBB"> 
		<td colspan=2><input type="radio" name="GroupFavorite" value="A" <? if ($player->groupFavorite=="A") print 'checked="yes"'?> /> Jokergruppe</p></td>
		<td colspan=2><input type="radio" name="GroupFavorite" value="B" <? if ($player->groupFavorite=="B") print 'checked="yes"'?> /> Jokergruppe</p></td>
		<td colspan=2><input type="radio" name="GroupFavorite" value="C" <? if ($player->groupFavorite=="C") print 'checked="yes"'?> /> Jokergruppe</p></td>
		<td colspan=2><input type="radio" name="GroupFavorite" value="D" <? if ($player->groupFavorite=="D") print 'checked="yes"'?> /> Jokergruppe</p></td>
	</tr>
	<? 	
		for ($row=0; $row<6; $row++)
		{
			print "<tr>";
			for ($col=0;$col<4*6;$col+=6)
				PrintGroupMatchHtml($row+$col);
			print "</tr>";
		}
	?>		
	<!-- ************ GRUPPEN E-H ********************************* -->
	<tr bgcolor="#BBBBBB">
		<td colspan=2><div class='heading1'>Gruppe E (Spiele 25-30)</td>
		<td colspan=2><div class='heading1'>Gruppe F (Spiele 31-36)</td>
		<td colspan=2><div class='heading1'>Gruppe G (Spiele 37-42)</td>
		<td colspan=2><div class='heading1'>Gruppe H (Spiele 43-48)</td>
	</tr>
	<tr bgcolor="#BBBBBB"> 
		<td colspan=2><input type="radio" name="GroupFavorite" value="E" <? if ($player->groupFavorite=="E") print 'checked="yes"'?> /> Jokergruppe</p></td>
		<td colspan=2><input type="radio" name="GroupFavorite" value="F" <? if ($player->groupFavorite=="F") print 'checked="yes"'?> /> Jokergruppe</p></td>
		<td colspan=2><input type="radio" name="GroupFavorite" value="G" <? if ($player->groupFavorite=="G") print 'checked="yes"'?> /> Jokergruppe</p></td>
		<td colspan=2><input type="radio" name="GroupFavorite" value="H" <? if ($player->groupFavorite=="H") print 'checked="yes"'?> /> Jokergruppe</p></td>
	</tr>
	<? 	
		for ($row=0; $row<6; $row++)
		{
			print "<tr>";
			for ($col=0;$col<4*6;$col+=6)
				PrintGroupMatchHtml(24+$row+$col);
			print "</tr>";
		}
	?>
</table>

<table border="0" cellspacing="0" cellpadding="3">
	<col style="width:16%">
    <col style="width:16%">
    <col style="width:10%">
    <col style="width:16%">
    <col style="width:16%">
    <col style="width:16%">
    <col style="width:10%">
	
	<!-- ************ ACHTELFINAL ********************************* -->
	<tr bgcolor="#BBBBBB"> 
		<td colspan=3><div class='heading1'>Achtelfinal</p></td><td></td>
		<td colspan=3><input style="width:250px" name='calculateeight' type='submit' value='Achtelfinalgegner neu ausrechnen'/></td>
	</tr>
	<tr>
		<td>Erster A</td><td>Zweiter B</td><td>Spiel 49</td><td></td>
		<td>Erster C</td><td>Zweiter D</td><td>Spiel 50</td>
	</tr>
	<tr>
		<? PrintFinalMatch(48); ?><td></td>					
		<? PrintFinalMatch(49); ?>
	</tr>
	<tr>
		<td>Erster B</td><td>Zweiter A</td><td>Spiel 51</td><td></td>
		<td>Erster D</td><td>Zweiter C</td><td>Spiel 52</td>
	</tr>
	<tr>
		<? PrintFinalMatch(50); ?><td></td>					
		<? PrintFinalMatch(51); ?>
	</tr>	
	<tr>
		<td>Erster E</td><td>Zweiter F</td><td>Spiel 53</td><td></td>
		<td>Erster G</td><td>Zweiter H</td><td>Spiel 54</td>
	</tr>
	<tr>
		<? PrintFinalMatch(52); ?><td></td>					
		<? PrintFinalMatch(53); ?>
	</tr>
	<tr>
		<td>Erster F</td><td>Zweiter E</td><td>Spiel 55</td><td></td>
		<td>Erster H</td><td>Zweiter G</td><td>Spiel 56</td>
	</tr>
	<tr>
		<? PrintFinalMatch(54); ?><td></td>					
		<? PrintFinalMatch(55); ?>
	</tr>	
	
	<!-- ************ VIERTELFINAL ********************************* -->
	<tr bgcolor="#BBBBBB"> 
		<td colspan=3><div class='heading1'>Viertelfinal</p></td><td></td>
		<td colspan=3><input style="width:250px" name='calculatequarter' type='submit' value='Viertelfinalgegner neu ausrechnen'/></td>
	</tr>
	<tr>
		<td>Sieger 49</td><td>Sieger 50</td><td>Spiel 57</td><td></td>
		<td>Sieger 51</td><td>Sieger 52</td><td>Spiel 58</td>
	</tr>
	<tr>
		<? PrintFinalMatch(56); ?><td></td>					
		<? PrintFinalMatch(57); ?>
	</tr>
	<tr>
		<td>Sieger 53</td><td>Sieger 54</td><td>Spiel 59</td><td></td>
		<td>Sieger 55</td><td>Sieger 56</td><td>Spiel 60</td>
	</tr>
	<tr>
		<? PrintFinalMatch(58); ?><td></td>					
		<? PrintFinalMatch(59); ?>
	</tr>								
	
	<!-- ************ HALBFINAL ********************************* -->
	<tr bgcolor="#BBBBBB"> 
		<td colspan=3><div class='heading1'>Halbfinal</p></td>
		<td></td>
		<td colspan=3><input style="width:250px" name='calculatehalf' type='submit' value='Halbfinalgegner neu ausrechnen'/></td>
	</tr>
	<tr>
		<td>Sieger 57</td><td>Sieger 58</td><td >Spiel 61</td><td></td>
		<td>Sieger 59</td><td>Sieger 60</td><td >Spiel 62</td>
	</tr>
	<tr>
		<? PrintFinalMatch(60); ?><td></td>					
		<? PrintFinalMatch(61); ?>
	</tr>

	<!-- ************ FINALSPIELE ********************************* -->
	<tr bgcolor="#BBBBBB"> 
		<td colspan=3><div class='heading1'>Final</p></td>
		<td></td>
		<td colspan=3><input style="width:250px" name='calculatefinal' type='submit' value='Finalgegner neu ausrechnen'/></td>
	</tr>
	<tr>
		<td>Verlierer 61</td><td>Verlierer 62</td><td>Spiel 63</td><td></td>
		<td>Sieger 61</td><td>Sieger 62</td><td>Spiel 64</td>			
	</tr>
	<tr>
		<? PrintFinalMatch(62); ?><td></td>					
		<? PrintFinalMatch(63); ?>
	</tr>
	<tr bgcolor="#BBBBBB">
		<td colspan=3><div class='heading1'>Weltmeister 2014</p></td>
		<td></td>
		<td colspan=3><input style="width:250px" name='calculatechampion' type='submit' value='Weltmeister berechnen'/></td>
	</tr>
	<tr>
		<?
			/*
			$eGame=checkAllGames();
			$eTeam=checkAllTeams();
			if (! (($eGame!=-1)||($eTeam!=-1)||($player->groupFavorite=="")||($player->totalGoals=="0") ))
			{
				$T1 = $_POST['Game63_T1'];
				$T2 = $_POST['Game63_T2'];
				$Game = $_POST['Game63'];
				$champion = GetWinner($T1,$T2,$Game);
			}
			else
				$champion="";
			*/
			print "<td valign='top'>Weltmeister 2014:</td><td colspan=6><input STYLE='background-color: lightgray' readonly='readonly' size='13' name='Champion' value='$player->champion'></td>";
		?>
	</tr>
</table>

</form>
</center>
</body>
