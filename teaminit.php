<?php
setlocale(LC_ALL, 'UTF-8');
/***********************************************************************
* Trikot-Totto Tottomat (Tippspiel für die Fussball EM/WM) 
* ----------------------------------------------------------------------
* Datei: teaminit.php
* 
* Initialisierung der Spieltabelle
*
* Email: wyss@superspider.net
***********************************************************************/

require_once('config.php');
require_once('classes.php');
require_once('view.php'); 
require_once('util.php');

function InitTeamsAndMatches()
{

	global $teams;
	global $groups;
	global $matches;

	/***********************************************************************
	* Initialisiere ein Array aller Teaminstanzen
	***********************************************************************/
	$teams[0] = NEW team(0,"A","Frankreich");
	$teams[1] = NEW team(1,"A","Rumänien");
	$teams[2] = NEW team(2,"A","Albanien");
	$teams[3] = NEW team(3,"A","Schweiz");
	$teams[4] = NEW team(4,"B","Wales");
	$teams[5] = NEW team(5,"B","Slowakei");
	$teams[6] = NEW team(6,"B","England");
	$teams[7] = NEW team(7,"B","Russland");
	$teams[8] = NEW team(8,"C","Polen");
	$teams[9] = NEW team(9,"C","Nordirland");
	$teams[10] = NEW team(10,"C","Deutschland");
	$teams[11] = NEW team(11,"C","Ukraine");
	$teams[12] = NEW team(12,"D","Türkei");
	$teams[13] = NEW team(13,"D","Kroatien");
	$teams[14] = NEW team(14,"D","Spanien");
	$teams[15] = NEW team(15,"D","Tschechien");
	$teams[16] = NEW team(16,"E","Irland");
	$teams[17] = NEW team(17,"E","Schweden");
	$teams[18] = NEW team(18,"E","Belgien");
	$teams[19] = NEW team(19,"E","Italien");
	$teams[20] = NEW team(20,"F","Österreich");
	$teams[21] = NEW team(21,"F","Ungarn");
	$teams[22] = NEW team(22,"F","Portugal");
	$teams[23] = NEW team(23,"F","Island");

	/***********************************************************************
	* Initialisiere ein Array aller Spielinstanzen
	***********************************************************************/
	// Gruppenspiele
	$matches[0]  = NEW match(0,"A",GetTeam("Frankreich"),GetTeam("Rumänien"));
	$matches[1]  = NEW match(1,"A",GetTeam("Albanien"),GetTeam("Schweiz"));
	$matches[2]  = NEW match(2,"A",GetTeam("Rumänien"),GetTeam("Schweiz"));
	$matches[3]  = NEW match(3,"A",GetTeam("Frankreich"),GetTeam("Albanien"));
	$matches[4]  = NEW match(4,"A",GetTeam("Schweiz"),GetTeam("Frankreich"));
	$matches[5]  = NEW match(5,"A",GetTeam("Rumänien"),GetTeam("Albanien"));
	$matches[6]  = NEW match(6,"B",GetTeam("Wales"),GetTeam("Slowakei"));
	$matches[7]  = NEW match(7,"B",GetTeam("England"),GetTeam("Russland"));
	$matches[8]  = NEW match(8,"B",GetTeam("Russland"),GetTeam("Slowakei"));
	$matches[9]  = NEW match(9,"B",GetTeam("England"),GetTeam("Wales"));
	$matches[10] = NEW match(10,"B",GetTeam("Slowakei"),GetTeam("England"));
	$matches[11] = NEW match(11,"B",GetTeam("Russland"),GetTeam("Wales"));
	$matches[12] = NEW match(12,"C",GetTeam("Polen"),GetTeam("Nordirland"));
	$matches[13] = NEW match(13,"C",GetTeam("Deutschland"),GetTeam("Ukraine"));
	$matches[14] = NEW match(14,"C",GetTeam("Ukraine"),GetTeam("Nordirland"));
	$matches[15] = NEW match(15,"C",GetTeam("Deutschland"),GetTeam("Polen"));
	$matches[16] = NEW match(16,"C",GetTeam("Ukraine"),GetTeam("Polen"));
	$matches[17] = NEW match(17,"C",GetTeam("Nordirland"),GetTeam("Deutschland"));
	$matches[18] = NEW match(18,"D",GetTeam("Türkei"),GetTeam("Kroatien"));
	$matches[19] = NEW match(19,"D",GetTeam("Spanien"),GetTeam("Tschechien"));
	$matches[20] = NEW match(20,"D",GetTeam("Tschechien"),GetTeam("Kroatien"));
	$matches[21] = NEW match(21,"D",GetTeam("Spanien"),GetTeam("Türkei"));
	$matches[22] = NEW match(22,"D",GetTeam("Kroatien"),GetTeam("Spanien"));
	$matches[23] = NEW match(23,"D",GetTeam("Tschechien"),GetTeam("Türkei"));
	$matches[24] = NEW match(24,"E",GetTeam("Irland"),GetTeam("Schweden"));
	$matches[25] = NEW match(25,"E",GetTeam("Belgien"),GetTeam("Italien"));
	$matches[26] = NEW match(26,"E",GetTeam("Italien"),GetTeam("Schweden"));
	$matches[27] = NEW match(27,"E",GetTeam("Belgien"),GetTeam("Irland"));
	$matches[28] = NEW match(28,"E",GetTeam("Italien"),GetTeam("Irland"));
	$matches[29] = NEW match(29,"E",GetTeam("Schweden"),GetTeam("Belgien"));
	$matches[30] = NEW match(30,"F",GetTeam("Österreich"),GetTeam("Ungarn"));
	$matches[31] = NEW match(31,"F",GetTeam("Portugal"),GetTeam("Island"));
	$matches[32] = NEW match(32,"F",GetTeam("Island"),GetTeam("Ungarn"));
	$matches[33] = NEW match(33,"F",GetTeam("Portugal"),GetTeam("Österreich"));
	$matches[34] = NEW match(34,"F",GetTeam("Ungarn"),GetTeam("Portugal"));
	$matches[35] = NEW match(35,"F",GetTeam("Island"),GetTeam("Österreich"));
	
	// Achtelfinalspiele
	$matches[36] = NEW match(36,"AF",GetTeam(""),GetTeam(""));
	$matches[37] = NEW match(37,"AF",GetTeam(""),GetTeam(""));
	$matches[38] = NEW match(38,"AF",GetTeam(""),GetTeam(""));
	$matches[39] = NEW match(39,"AF",GetTeam(""),GetTeam(""));
	$matches[40] = NEW match(40,"AF",GetTeam(""),GetTeam(""));
	$matches[41] = NEW match(41,"AF",GetTeam(""),GetTeam(""));
	$matches[42] = NEW match(42,"AF",GetTeam(""),GetTeam(""));
	$matches[43] = NEW match(43,"AF",GetTeam(""),GetTeam(""));
	
	// Viertelfinalspiele
	$matches[44] = NEW match(44,"VF",GetTeam(""),GetTeam(""));
	$matches[45] = NEW match(45,"VF",GetTeam(""),GetTeam(""));
	$matches[46] = NEW match(46,"VF",GetTeam(""),GetTeam(""));
	$matches[47] = NEW match(47,"VF",GetTeam(""),GetTeam(""));
	
	// Halbfinalspiele
	$matches[48] = NEW match(48,"HF",GetTeam(""),GetTeam(""));
	$matches[49] = NEW match(49,"HF",GetTeam(""),GetTeam(""));
	
	// Finalspiele
	$matches[50] = NEW match(50,"FF",GetTeam(""),GetTeam(""));

	/***********************************************************************
	* Initialisiere ein Array aller Gruppen
	***********************************************************************/
	$groups[0] = NEW group($teams,$matches,"A");
	$groups[1] = NEW group($teams,$matches,"B");
	$groups[2] = NEW group($teams,$matches,"C");
	$groups[3] = NEW group($teams,$matches,"D");
	$groups[4] = NEW group($teams,$matches,"E");
	$groups[5] = NEW group($teams,$matches,"F");
}