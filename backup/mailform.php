<?php
global $msg;

if (isset($_POST['send']))
{
	$error = 0;
	
	if (empty($_POST['name']))
		$error = 1;
	
	if (!strpos($_POST['myemail'],"@"))
		$error = 2;
	
	if (empty($_POST['street']))
		$error = 3;
		
	if (empty($_POST['plz']))
		$error = 4;
	
	if ( ($_POST['security'] != 2) && ($_POST['security']!="zwei") && ($_POST['security']!="Zwei"))
		$error = 5;
	
	switch ($error)
	{
		case 1:
			$msg = "Bitte Name und Vorname angeben!";
			break;
		case 2:
			$msg = "Bitte korrekte Email-Adresse eingeben!";
			break;
		case 3:
			$msg = "Bitte Strasse angeben!";
			break;
		case 4:
			$msg = "Bitte PLZ und Ort angeben!";
			break;
		case 5:
			$msg = "Die Sicherheitsfrage wurde falsch beantwortet (Tipp: schau in den Spiegel).";
			break;
		
		default:		
			$name = $_POST['name'];
			$sender = $_POST['myemail'];
			$street = $_POST['street'];
			$plz = $_POST['plz'];
			
			$empfaenger = "info@trikot-totto.ch";
			$betreff = "Anmeldung Trikot-Totto von $name";	
			$mailtext = "Spielername: $name\r\nStrasse: $street\r\nPLZ/Ort: $plz"; 
			mail($empfaenger, $betreff, $mailtext, "From: $sender "); 
			$msg = "Vielen Dank für deine Teilnahme. Du erhältst in Kürze eine Email mit den Zugangsdaten.";
	}
}
?>

<table>
<form method='post' action='http://www.trikot-totto.ch/index.php?option=com_content&view=article&id=45&Itemid=60'>
<tr>
	<td>Name und Vorname:</td>
	<td><input size='25' name='name' value='<?php print $_POST['name'];?>' type='text'></input></td>
</tr>
<tr>
	<td>Email:</td>
	<td><input size='25' type='text' name='myemail' value='<?php print $_POST[myemail];?>'></input></td>
</tr>
<tr>
	<td>Strasse:</td>
	<td><input size='25' name='street' value='<?php print $_POST['street'];?>' type='text'></input></td>
</tr>
<tr>
	<td>PLZ und Ort:</td>
	<td><input size='25' name='plz' value='<?php print $_POST['plz'];?>' type='text'></input></td>
</tr>
<tr>
	<td>Sicherheitsfrage: Wieviele Augen hat ein normaler Mensch?</td>
	<td><input size='25' name='security' value='<? print $_POST['security'];?>' type='text'/></td>
</tr>
<tr>
	<td colspan='2'><input align='right' style="width: 130px" name='send' type='submit' value='Anmeldung senden' /></td>
</tr>
<tr bgcolor='<? if ($error==0) print lightgreen; else print yellow;?>'>
	<td colspan=2><font color="#FF0000"><? print $msg; ?></font></td>
</tr>
</form> 
</table>
 

