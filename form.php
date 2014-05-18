<?php
setlocale(LC_ALL, 'UTF-8');

/***********************************************************************
* Trikot-Totto Tottomat (Tippspiel für die Fussball EM/WM) 
* ----------------------------------------------------------------------
* Datei: form.php
* 
* Hauptformular für Trikot-Totto PHP Applikation. Diese Seite ist als
* Startseite zu definieren. Die Werte der Formularfelder werden als
* POST-Variablen übergeben.  
* 
* Email: wyss@superspider.net
***********************************************************************/
require_once('config.php');
require_once('classes.php');
require_once('view.php'); 
require_once('util.php');
require_once('teaminit.php');

// the teams and matches
$teams = array();
$matches = array();
InitTeamsAndMatches();

// the player
$player = NEW player();
 	 
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
//print_r($user);
$player->username = $user->username;
$player->name = $user->name;
$player->email = $user->email;
$player->registerDate = $user->registerDate;

if ($player->username == "") die ('Der Benutzer ist nicht angemeldet!');

// Verbindung zum MySQL Server herstellen und Datenbank wählen
$db=mysql_connect($db_serv, $db_user, $db_pass) or die ('Cannot connect to the database because: ' . mysql_error()); 
mysql_select_db($db_name, $db) or die('ERROR!');

// Alle Spielresultate aus Master-Eintrag lesen
$query = mysql_query("select * from wmtotto2014 where PlayerName = 'Master';") or die(mysql_error());
$mas = mysql_fetch_array($query);	

//*****************************************************************************
// Buttons auswerten (POST-Variablen)
//***************************************************************************** 
if (isset($_POST['savetodb']))
{
	LoadMatchesFrom("POST");
	CalculateChampion();
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

<!-- ************************************************************************
//-- Tabelle der Gruppenspiele anzeigen
//-- ************************************************************************--> 
<center>
<form action="<?php $_SESSION['PHP_SELF']?>" method="post">

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
		<td colspan=4><div class='heading1'>Tippzettel Trikot-Totto WM 2014 </div><? print "Spieler: $player->username ($player->name), Punkte: $player->score"; ?>
		</td>
		<td colspan=2><a href='javascript:window.print()'>[Seite drucken]</a></td>
		<td colspan=2><a href='http://www.trikot-totto.ch'>[Zurück zur Hauptseite]</a></td>
    </tr>
	<tr>
		<td colspan=1><input style="width: 75px" name='savetodb' type='submit' value='Speichern' /></td>
		<!-- <td colspan=1><input style="width: 75px" name='random' type='submit' value='Zufall' /></td> -->
		<td colspan=3>Tippzettel vollständig und korrekt?</td>
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
				print "<td><img width=32 height=32 src='/tottomat/pictures/smiley-sad.jpg'/></td>";
				print "<td align='center' colspan=3 bgcolor='red'>Fehler bei Ergebnis Spiel $eGame!</td>";
				
			}
			elseif ($eTeam!=-1)
			{
				print "<td><img width=32 height=32 src='/tottomat/pictures/smiley-sad.jpg'/></td>";
				print "<td align='center' colspan=3 bgcolor='red'>Teamfehler (oder 2x gleiches Team) bei Spiel $eTeam!</td>";
			}
			elseif ($player->groupFavorite=="")
			{
				print "<td><img width=32 height=32 src='/tottomat/pictures/smiley-sad.jpg'/></td>";
				print "<td align='center' colspan=3 bgcolor='red'>Jokergruppe fehlt!</td>"; 
			}
			elseif ($player->totalGoals=="0" || $player->totalGoals=="")
			{
				print "<td><img width=32 height=32 src='/tottomat/pictures/smiley-sad.jpg'/></td>";
				print "<td align='center' colspan=3 bgcolor='red'>'Tore insgesamt' fehlt!</td>";  
			}
			else 
			{
				print "<td><img width=32 height=32 src='/tottomat/pictures/smiley-happy.jpg'/></td>";
				print "<td align='center' colspan=3 bgcolor='forestgreen'>Ja, alles i.O.</td>";
			}
		?>
	</tr>
	<tr bgcolor="gold"> 
		<td colspan=8>Allgemein</td>
    </tr>
	<tr>
		<td>Tore insgesamt:</td>
		<td colspan=1 valign="top"><input size="5" name="TotalGoals" type="text" maxlength="5" value="<? print $player->totalGoals; ?>" /></td>
		<td colspan=6>(inkl. Verlaengerungen und Elfmeterschiessen)</td>
	</tr>
	<tr bgcolor="#DDAACC">
		<td align="center" colspan=8><b>ACHTUNG:</b> Bis WM-Beginn am 12. Juni 2014 muss <b>DIE GESAMTE WM</b> bis und mit Weltmeister von dir getippt sein! </td>
	</tr>
	<tr bgcolor="#DDAACC">
		<td align="center" colspan=8>Spielresultate mit Doppelpunkt eingeben (z.B. <b>3:4</b>). Jokergruppe = Punkte verdoppeln!</td>
	</tr>
	<!-- ************ GRUPPEN A-D ********************************* -->
	<tr bgcolor="gold">
		<td colspan=2><div class='heading1'>Gruppe A (Spiele 1-6)</td>
		<td colspan=2><div class='heading1'>Gruppe B (Spiele 7-12)</td>
		<td colspan=2><div class='heading1'>Gruppe C (Spiele 13-18)</td>
		<td colspan=2><div class='heading1'>Gruppe D (Spiele 19-24)</td>
	</tr>
	<tr bgcolor="gold"> 
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
	<tr bgcolor="gold">
		<td colspan=2><div class='heading1'>Gruppe E (Spiele 25-30)</td>
		<td colspan=2><div class='heading1'>Gruppe F (Spiele 31-36)</td>
		<td colspan=2><div class='heading1'>Gruppe G (Spiele 37-42)</td>
		<td colspan=2><div class='heading1'>Gruppe H (Spiele 43-48)</td>
	</tr>
	<tr bgcolor="gold"> 
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

<!-- ************************************************************************
//-- Tabelle der Finalspiele anzeigen
//-- ************************************************************************--> 
<table border="0" cellspacing="0" cellpadding="3">
	<col style="width:16%">
    <col style="width:16%">
    <col style="width:10%">
    <col style="width:16%">
    <col style="width:16%">
    <col style="width:16%">
    <col style="width:10%">
	
	<!-- ************ ACHTELFINAL ********************************* -->
	<tr bgcolor="gold"> 
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
	<tr bgcolor="gold"> 
		<td colspan=3><div class='heading1'>Viertelfinal</p></td><td></td>
		<td colspan=3><input style="width:250px" name='calculatequarter' type='submit' value='Viertelfinalgegner neu ausrechnen'/></td>
	</tr>
	<tr>
		<td>Sieger 53</td><td>Sieger 54</td><td>Spiel 57</td><td></td>
		<td>Sieger 49</td><td>Sieger 50</td><td>Spiel 58</td>
	</tr>
	<tr>
		<? PrintFinalMatch(56); ?><td></td>					
		<? PrintFinalMatch(57); ?>
	</tr>
	<tr>
		<td>Sieger 55</td><td>Sieger 56</td><td>Spiel 59</td><td></td>
		<td>Sieger 51</td><td>Sieger 52</td><td>Spiel 60</td>
	</tr>
	<tr>
		<? PrintFinalMatch(58); ?><td></td>					
		<? PrintFinalMatch(59); ?>
	</tr>								
	
	<!-- ************ HALBFINAL ********************************* -->
	<tr bgcolor="gold"> 
		<td colspan=3><div class='heading1'>Halbfinal</p></td>
		<td></td>
		<td colspan=3><input style="width:250px" name='calculatehalf' type='submit' value='Halbfinalgegner neu ausrechnen'/></td>
	</tr>
	<tr>
		<td>Sieger 57</td><td>Sieger 58</td><td >Spiel 61</td><td></td>
		<td>Sieger 60</td><td>Sieger 59</td><td >Spiel 62</td>
	</tr>
	<tr>
		<? PrintFinalMatch(60); ?><td></td>					
		<? PrintFinalMatch(61); ?>
	</tr>

	<!-- ************ FINALSPIELE ********************************* -->
	<tr bgcolor="gold"> 
		<td colspan=3><div class='heading1'>Final</p></td>
		<td></td>
		<td colspan=3><input style="width:250px" name='calculatefinal' type='submit' value='Finalgegner neu ausrechnen'/></td>
	</tr>
	<tr>
		<td colspan=4></td><td>Verlierer 61</td><td>Verlierer 62</td><td>Spiel 63</td>
	</tr>
	<tr>	
		<td colspan=3></td><td><div class='heading1'>Spiel um Platz 3</div></td><? PrintFinalMatch(62); ?><td></td>
	</tr>
	<tr>
		<td colspan=4></td><td>Sieger 61</td><td>Sieger 62</td><td>Spiel 64</td>
	</tr>
	<tr>	
		<td colspan=3></td><td><div class='heading1'>Das grosse Finale</div></td><? PrintFinalMatch(63); ?><td></td>
	</tr>
	<tr bgcolor="gold">
		<td colspan=3></p></td>
		<td></td>
		<td colspan=3><input style="width:250px" name='calculatechampion' type='submit' value='Weltmeister berechnen'/></td>
	</tr>
	<tr>
		<?
			print "<td colspan=3></td><td colspan=1><div class='heading1'>Weltmeister 2014</td><td colspan=3><input STYLE='background-color: lightgray; border:2px solid #ff0000; border-color:red' readonly='readonly' size='34' name='Champion' value='$player->champion'></td>";
		?>
	</tr>
</table>

</form>
</center>
</body>
