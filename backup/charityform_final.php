<?php
global $msg;

if (isset($_POST['send']))
{
	$error = 0;
	
	if (empty($_POST['name']))
		$error = 1;
	
	if ( ($_POST['security'] != 2) && ($_POST['security']!="zwei") && ($_POST['security']!="Zwei"))
		$error = 5;
	
	$name = $_POST['name'];
	$sel1 = $_POST['charity1'];
		
	switch ($error)
	{
		case 1:
			$msg = "Bitte Spielername angeben!";
			break;
		case 5:
			$msg = "Die Sicherheitsfrage wurde falsch beantwortet (Tipp: schau in den Spiegel).";
			break;
		
		default:		
			$empfaenger = "info@trikot-totto.ch";
			$betreff = "Charity-WM Final-Beitrag von $name";	
			$mailtext = "Spielername: $name\r\nWahl: $sel1\r\n"; 
			mail($empfaenger, $betreff, $mailtext, ""); 
			$msg = "Deine Stimme wurde erfolgreich abgegeben. Vielen Dank.";
	}
}
?>

<table>
<form method='post' action='http://www.trikot-totto.ch/index.php?option=com_content&view=article&id=52&Itemid=64'>
<tr>
	<td>Spielername:</td>
	<td><input size='25' name='name' value='<?php print $_POST['name'];?>' type='text'></input></td>
</tr>
<tr>
	<td>Meine Wahl:</td>
	<td>
		<select name="charity1">
		<option selected="yes"><? print "bitte waehlen..."; ?></option>
		<option value='Wunderlampe'>Wunderlampe</option>
		<option value='Clowns fuer Spital-Kinder'>Clowns fuer Spital-Kinder</option>
		<option value='Kinderspitex plus'>Kinderspitex plus</option>
		<option value='Plauschkickers'>Plauschkickers</option>
		</select>
	</td>
</tr>
<tr>
	<td>Sicherheitsfrage: Wieviele Augen hat ein Mensch?</td>
	<td><input size='25' name='security' value='<? print $_POST['security'];?>' type='text'/></td>
</tr>
<tr>
	<td colspan='2'><input align='right' style="width: 130px" name='send' type='submit' value='Stimme abgeben' /></td>
</tr>
<tr bgcolor='<? if ($error==0) print lightgreen; else print yellow;?>'>
	<td colspan=2><font color="#FF0000"><? print $msg; ?></font></td>
</tr>
</form> 
</table>
 

