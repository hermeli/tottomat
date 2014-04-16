<?php
setlocale(LC_ALL, 'UTF-8');
/***********************************************************************
 * teaminint.php 
 *
 * Ititialisiert die Teams und Matches
 **********************************************************************/

require_once('config.php');
require_once('classes.php');
require_once('view.php'); 
require_once('util.php');

function InitTeamsAndMatches()
{

	global $teams;
	global $matches;

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

}