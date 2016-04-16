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
$groups = array();
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
$db = new PDO('mysql:host='.$db_serv.';dbname='.$db_name.';charset=utf8', $db_user, $db_pass);
$query = $db->query("SELECT * FROM ".$db_table." where PlayerName = 'Master';");
$mas = $query->fetch(PDO::FETCH_ASSOC);

// prüfe, ob EditMode == 1 (Modifikation der DB erlaubt)
$query = $db->query("select * from config;");
$dbresult = $query->fetch(PDO::FETCH_ASSOC);
$saveok = ($dbresult['EditMode'] == 1)||($player->username == "master");

//*****************************************************************************
// Buttons auswerten (POST-Variablen)
//***************************************************************************** 
if ($saveok)
{
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
} else {
	if (isset($_POST['loadplayer']))
	{
		$player->name = $_POST['loadplayer'];	
		$query = $db->query("select PlayerName from ".$db_table." where Name='".$player->name."';");
		$playerdb = $query->fetch(PDO::FETCH_ASSOC);
		$player->username = $playerdb['PlayerName'];
	}
}
LoadMatchesFrom("DB");
CalculatePlayerScore();
SavePlayerScoreToDB();	
?>
<!-- ************************************************************************
//-- Tabelle der Gruppenspiele anzeigen
//-- ************************************************************************--> 
<center>
<form action="<?php $_SERVER['PHP_SELF']?>" method="post">

<table border="0" cellspacing="0" cellpadding="3">
	<col style="width:16%">
    <col style="width:8%">
    <col style="width:16%">
    <col style="width:8%">
    <col style="width:16%">
    <col style="width:8%">
	
	<?
		if (!$saveok)
		{
			print "<tr><td colspan=6 STYLE='background-color: lightgray; border:2px solid #ff0000; border-color:red'><b>Bitte Benutzer auswählen:</b>";
			print "<select name='loadplayer' onchange='this.form.submit()'>";
			$query = $db->query("select Name from ".$db_table." where PlayerName != 'master';");
			$Names=array();
			while ($row = $query->fetch(PDO::FETCH_ASSOC))
				array_push($Names,$row['Name']);
			asort($Names);
			print "<option></option>";
			foreach ($Names as $Name)
				print "<option>$Name</option>";
		
			print "</select> (Tipp: Liste öffnen und Benutzername mit Tastatur eingeben!)</td></tr><tr><td colspan=6></td></tr>";
		}	
	?>
				
	<tr bgcolor="#BBBBBB"> 
		<td colspan=3><div class='heading1'>Tippzettel Trikot-Totto EM 2016 </div><? print "Spieler: $player->username ($player->name), Punkte: $player->score"; ?>
		</td>
		<td colspan=2><a href='javascript:window.print()'>[Seite drucken]</a></td>
		<td colspan=1><a href='http://www.trikot-totto.ch'>[Zurück zur Hauptseite]</a></td>
    </tr>
	<tr>
		<td colspan=1>
		<? 
			if ($saveok)
				print "<input style='width: 75px' name='savetodb' type='submit' value='Speichern' />"; 
		?>
		</td>
		<!-- <td colspan=1><input style="width: 75px" name='random' type='submit' value='Zufall' /></td> -->
		<td colspan=2>Tippzettel vollständig und korrekt?</td>
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
				print "<td align='center' colspan=2 bgcolor='red'>Fehler bei Ergebnis Spiel $eGame!</td>";
				
			}
			elseif ($eTeam!=-1)
			{
				print "<td><img width=32 height=32 src='/tottomat/pictures/smiley-sad.jpg'/></td>";
				print "<td align='center' colspan=2 bgcolor='red'>Teamfehler (oder 2x gleiches Team) bei Spiel $eTeam!</td>";
			}
			elseif ($player->groupFavorite=="")
			{
				print "<td><img width=32 height=32 src='/tottomat/pictures/smiley-sad.jpg'/></td>";
				print "<td align='center' colspan=2 bgcolor='red'>Jokergruppe fehlt!</td>"; 
			}
			elseif ($player->totalGoals=="0" || $player->totalGoals=="")
			{
				print "<td><img width=32 height=32 src='/tottomat/pictures/smiley-sad.jpg'/></td>";
				print "<td align='center' colspan=2 bgcolor='red'>'Tore insgesamt' fehlt!</td>";  
			}
			else 
			{
				print "<td><img width=32 height=32 src='/tottomat/pictures/smiley-happy.jpg'/></td>";
				print "<td align='center' colspan=2 bgcolor='forestgreen'>Ja, alles i.O.</td>";
			}
		?>
	</tr>
	<tr bgcolor="gold"> 
		<td colspan=6>Allgemein</td>
    </tr>
	<tr>
		<td>Tore insgesamt:</td>
		<td colspan=1 valign="top"><input size="5" name="TotalGoals" type="text" maxlength="5" value="<? print $player->totalGoals; ?>" /></td>
		<td colspan=4>(inkl. Verlaengerungen und Elfmeterschiessen)</td>
	</tr>
	<tr bgcolor="#DDAACC">
		<td align="center" colspan=6><b>ACHTUNG:</b> Bis EM-Beginn (10. Juni 2016) muss <b>DIE GESAMTE EM</b> bis und mit Europameister von dir getippt sein! </td>
	</tr>
	<tr bgcolor="#DDAACC">
		<td align="center" colspan=6>Spielresultate mit Doppelpunkt eingeben (z.B. <b>3:4</b>). Jokergruppe = Punkte verdoppeln!</td>
	</tr>
	<!-- ************ GRUPPEN A-C ********************************* -->
	<tr bgcolor="gold">
		<td colspan=2><div class='heading1'>Gruppe A (Spiele 1-6)</td>
		<td colspan=2><div class='heading1'>Gruppe B (Spiele 7-12)</td>
		<td colspan=2><div class='heading1'>Gruppe C (Spiele 13-18)</td>
	</tr>
	<tr bgcolor="gold"> 
		<td colspan=2><input type="radio" name="GroupFavorite" value="A" <? if ($player->groupFavorite=="A") print 'checked="yes"'?> /> Jokergruppe</p></td>
		<td colspan=2><input type="radio" name="GroupFavorite" value="B" <? if ($player->groupFavorite=="B") print 'checked="yes"'?> /> Jokergruppe</p></td>
		<td colspan=2><input type="radio" name="GroupFavorite" value="C" <? if ($player->groupFavorite=="C") print 'checked="yes"'?> /> Jokergruppe</p></td>
	</tr>
	<? 	
		for ($row=0; $row<6; $row++)
		{
			print "<tr>";
			for ($col=0;$col<3*6;$col+=6)
				PrintGroupMatchHtml($row+$col);
			print "</tr>";
		}
	?>		
	<!-- ************ GRUPPEN D-F ********************************* -->
	<tr bgcolor="gold">
		<td colspan=2><div class='heading1'>Gruppe D (Spiele 19-24)</td>
		<td colspan=2><div class='heading1'>Gruppe E (Spiele 25-30)</td>
		<td colspan=2><div class='heading1'>Gruppe F (Spiele 31-36)</td>
	</tr>
	<tr bgcolor="gold"> 
		<td colspan=2><input type="radio" name="GroupFavorite" value="D" <? if ($player->groupFavorite=="D") print 'checked="yes"'?> /> Jokergruppe</p></td>		
		<td colspan=2><input type="radio" name="GroupFavorite" value="E" <? if ($player->groupFavorite=="E") print 'checked="yes"'?> /> Jokergruppe</p></td>
		<td colspan=2><input type="radio" name="GroupFavorite" value="F" <? if ($player->groupFavorite=="F") print 'checked="yes"'?> /> Jokergruppe</p></td>
	</tr>
	<? 	
		for ($row=0; $row<6; $row++)
		{
			print "<tr>";
			for ($col=0;$col<3*6;$col+=6)
				PrintGroupMatchHtml(18+$row+$col);
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
		<td colspan=3>
		<? 
			if ($saveok)
				print "<input style='width:250px' name='calculateeight' type='submit' value='Achtelfinalgegner neu ausrechnen'/>";
		?>
		</td>
	</tr>
	<tr>
		<td>Zweiter A</td><td>Zweiter C</td><td>Spiel 37</td><td></td>
		<td>Erster B</td><td>Dritter A/C/D</td><td>Spiel 38</td>
	</tr>
	<tr>
		<? PrintFinalMatch(36); ?><td></td>					
		<? PrintFinalMatch(37); ?>
	</tr>
	<tr>
		<td>Erster D</td><td>Dritter B/E/F</td><td>Spiel 39</td><td></td>
		<td>Erster A</td><td>Dritter C/D/E</td><td>Spiel 40</td>
	</tr>
	<tr>
		<? PrintFinalMatch(38); ?><td></td>					
		<? PrintFinalMatch(39); ?>
	</tr>	
	<tr>
		<td>Erster C</td><td>Dritter A/B/F</td><td>Spiel 41</td><td></td>
		<td>Erster F</td><td>Zweiter E</td><td>Spiel 42</td>
	</tr>
	<tr>
		<? PrintFinalMatch(40); ?><td></td>					
		<? PrintFinalMatch(41); ?>
	</tr>
	<tr>
		<td>Erster E</td><td>Zweiter D</td><td>Spiel 43</td><td></td>
		<td>Zweiter B</td><td>Zweiter F</td><td>Spiel 44</td>
	</tr>
	<tr>
		<? PrintFinalMatch(42); ?><td></td>					
		<? PrintFinalMatch(43); ?>
	</tr>	
	
	<!-- ************ VIERTELFINAL ********************************* -->
	<tr bgcolor="gold"> 
		<td colspan=3><div class='heading1'>Viertelfinal</p></td><td></td>
		<td colspan=3>
		<? 
			if ($saveok)
				print "<input style='width:250px' name='calculatequarter' type='submit' value='Viertelfinalgegner neu ausrechnen'/>";
		?>	
		</td>
	</tr>
	<tr>
		<td>Sieger 37</td><td>Sieger 39</td><td>Spiel 45</td><td></td>
		<td>Sieger 38</td><td>Sieger 42</td><td>Spiel 46</td>
	</tr>
	<tr>
		<? PrintFinalMatch(44); ?><td></td>					
		<? PrintFinalMatch(45); ?>
	</tr>
	<tr>
		<td>Sieger 41</td><td>Sieger 43</td><td>Spiel 47</td><td></td>
		<td>Sieger 40</td><td>Sieger 44</td><td>Spiel 48</td>
	</tr>
	<tr>
		<? PrintFinalMatch(46); ?><td></td>					
		<? PrintFinalMatch(47); ?>
	</tr>								
	
	<!-- ************ HALBFINAL ********************************* -->
	<tr bgcolor="gold"> 
		<td colspan=3><div class='heading1'>Halbfinal</p></td>
		<td></td>
		<td colspan=3>
		<? 
			if ($saveok)
				print "<input style='width:250px' name='calculatehalf' type='submit' value='Halbfinalgegner neu ausrechnen'/>";
		?>	
		</td>
	</tr>
	<tr>
		<td>Sieger 45</td><td>Sieger 46</td><td >Spiel 49</td><td></td>
		<td>Sieger 47</td><td>Sieger 48</td><td >Spiel 50</td>
	</tr>
	<tr>
		<? PrintFinalMatch(48); ?><td></td>					
		<? PrintFinalMatch(49); ?>
	</tr>

	<!-- ************ FINALSPIELE ********************************* -->
	<tr bgcolor="gold"> 
		<td colspan=3><div class='heading1'>Final</p></td>
		<td></td>
		<td colspan=3>
		<? 
			if ($saveok)
				print "<input style='width:250px' name='calculatefinal' type='submit' value='Finalgegner neu ausrechnen'/>";
		?>			
		</td>
	</tr>
	<tr>
		<td colspan=4></td><td>Sieger 49</td><td>Sieger 50</td><td>Spiel 51</td>
	</tr>
	<tr>	
		<td colspan=3></td><td><div class='heading1'>Das grosse Finale</div></td><? PrintFinalMatch(50); ?><td></td>
	</tr>
	<tr bgcolor="gold">
		<td colspan=3></p></td>
		<td></td>
		<td colspan=3>
		<? 
			if ($saveok)
				print "<input style='width:250px' name='calculatechampion' type='submit' value='Europameister anzeigen'/>";
		?>			
		</td>
	</tr>
	<tr>
		<?
			print "<td colspan=3></td><td colspan=1><div class='heading1'>Europameister 2016</td><td colspan=3><input STYLE='background-color: lightgray; border:2px solid #ff0000; border-color:red' readonly='readonly' size='34' name='Champion' value='$player->champion'></td>";
		?>
	</tr>
</table>

</form>
</center>
</body>
