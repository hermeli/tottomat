<?php
setlocale(LC_ALL, 'UTF-8');
/***********************************************************************
* Trikot-Totto Tottomat (Tippspiel für die Fussball EM/WM) 
* ----------------------------------------------------------------------
* Datei: util.php
* 
* Hilfsdatei für die Anzeige von HTML Elementen. 
*
* Email: wyss@superspider.net
***********************************************************************/

require_once('util.php');
/*****************************************************************************
* function printHeader(..)
* gibt den HTML Header aus (inkl. Styles) 
******************************************************************************/
function printHeader()
{
	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>Trikot-Totto EM 2016 Frankreich</title>
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
			table {table-layout: fixed; width: 800px;}
		-->
		</STYLE>
		
	</head>
	<body background="pictures/bg.gif">
	<?php
}
/****************************************************************************
* function MessageBox($message)
 ****************************************************************************/
function MessageBox($message)
{
	print "<script type='text/javascript' language='javascript'>\n";
	print "<!--\n";
	print " alert('".$message."');\n";
	print "//-->\n";
	print "</script>";  
}
/****************************************************************************
* function DebugMsg($message)
 ****************************************************************************/
function DebugMsg($message)
{
	global $DEBUG_PLAYER;
	global $player;
	
	if ($player->username == $DEBUG_PLAYER)
		print $message;  
}
/****************************************************************************
 * function PrintGroupMatchHtml($gameNr)
 * 
 * Printet ein Gruppenspiel mitsamt HTML Formatierung.
 * Die Matches werden in max. vier Spalten abgebildet. 
 ****************************************************************************/
function PrintGroupMatchHtml($gameNr)
{
	global $matches;
	global $mas;
	
	foreach ($matches as $cur) 
	{
		if ($cur->matchNr == $gameNr)
		{
			$team1 = $cur->team1->name;
			$team2 = $cur->team2->name;
		}
	}
	
	$out = "<td valign='top'>$team1-$team2</td>";
	$name = "Game" . "$gameNr";
		
	$bgcolor = $matches[$gameNr]->matchResBgColor;
	$content = $matches[$gameNr]->matchRes;
	
	$out = $out . "<td valign='top'><input STYLE='background: $bgcolor;' size='5' name='$name' type='text' maxlength='50' value='$content'/></td>";
	print $out;
}
/*****************************************************************************
 * Printet das komplette Finalspiel mitsamt HTML Formatierung. 
  ****************************************************************************/
function PrintFinalMatch($game)
{
	global $mas;
	global $matches;
		
	if ($matches[$game]->team1 != NULL)
	{
		$team1 = $matches[$game]->team1->name;
		$team1BgColor = $matches[$game]->team1BgColor;
	}
	else
	{
		$team1 = "";
		$team1BgColor = "lightgray";
	}	 
	if ($matches[$game]->team2 != NULL)
	{
		$team2 = $matches[$game]->team2->name;
		$team2BgColor = $matches[$game]->team2BgColor;
	}
	else
	{
		$team2 = "";
		$team2BgColor = "lightgray";
	}
		
	$matchRes = $matches[$game]->matchRes;
	$matchResBgColor = $matches[$game]->matchResBgColor;
	
	$name = "Game".$game;
	$nameT1 = "Game".$game."_T1";
	$nameT2 = "Game".$game."_T2";
	
	print "<td><input readonly='readonly' STYLE='background-color: $team1BgColor;' size='13' name='$nameT1' value='$team1'></td>";
	print "<td><input readonly='readonly' STYLE='background-color: $team2BgColor;' size='13' name='$nameT2' value='$team2'></td>";
	print "<td valign='top'><input STYLE='background: $matchResBgColor;' size='5' name='$name' type='text' maxlength='50' value='$matchRes'/></td>";
	
}
?>
