<?php
setlocale(LC_ALL, 'UTF-8');
require_once('util.php');

/******************************************************************************
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
		<title>Trikot-Totto WM 2014 Brasilien</title>
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
/******************************************************************************
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
	
	$out = "";
	$out = $out . "<td valign='top'>$team1-$team2</td>";
	$name = "Game" . "$gameNr";
	
	if ( (($matches[$gameNr]->matchPts) == 5)||(($matches[$gameNr]->matchPts) == 10))
		$bgcolor = "lightgreen";
	else if ((($matches[$gameNr]->matchPts) == 12)||(($matches[$gameNr]->matchPts) == 24))
		$bgcolor = "green";
	else
		$bgcolor = "white";
	
	$content = $matches[$gameNr]->matchRes; //$_POST[$name];
	$out = $out . "<td valign='top'><input STYLE='background: $bgcolor;' size='5' name='$name' type='text' maxlength='50' value='$content'/></td>";
	print $out;
}
/******************************************************************************
 * Printet das komplette Finalspiel mitsamt HTML Formatierung. 
  ****************************************************************************/
function PrintFinalMatch($game)
{
	global $mas;
	global $matches;
	
	$bgcolor1 = "lightgray";
	$bgcolor2 = "lightgray";
	$bgcolor3 = "white";
	
	$content1 = $matches[$game]->team1->name;
	$content2 = $matches[$game]->team2->name;
	
	if ( ($matches[$game]->team1_hit) == 1 )
		$bgcolor1 = "green";
	if ( ($matches[$game]->team2_hit) == 1 )
		$bgcolor2 = "green";
 
	if (($matches[$game]->playPts) == 5)
		$bgcolor3 = "lightgreen";
	elseif (($matches[$game]->playPts) == 12)
		$bgcolor3 = "green";
	else
		$bgcolor3 = "white";
	
	$name = "Game".$game;
	$nameT1 = "Game".$game."_T1";
	$nameT2 = "Game".$game."_T2";
	
	$result = $matches[$game]->matchRes;
	
	print "<td><input readonly='readonly' STYLE='background-color: $bgcolor1;' size='13' name='$nameT1' value='$content1'></td>";
	print "<td><input readonly='readonly' STYLE='background-color: $bgcolor2;' size='13' name='$nameT2' value='$content2'></td>";
	print "<td valign='top'><input STYLE='background: $bgcolor3;' size='5' name='$name' type='text' maxlength='50' value='$result'/></td>";
}
?>
