<?php
if (isset($_POST['anzeigen'])){
	$CrcVal = sprintf("%u",crc32($_POST['PlayerName']));
	$PlayerName = $_POST['PlayerName'];
}
?>

<form action="crc.php" method="post">
	<table align="center" width="800px" border="0" cellspacing="0" cellpadding="1">
		<tr> 
        <td bgcolor="#999999">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
			<tr> 
			<td>
			<table width="100%" border="0" cellspacing="0" cellpadding="3">
				<tr bgcolor="#BBBBBB"> 
					<td colspan=7 width="80%"><p class='heading1'>CRC Berechnung</p></td>
                </tr>
				<tr> 
					<td colspan=2>Spielername:</td>
					<td><input width="8em" name="PlayerName" type="text" maxlength="32" value="<? print $PlayerName; ?>" /></td>
					<td><input name='anzeigen' type='submit' value='berechnen' /></td>
					<td colspan=2>Prüfsumme generieren</td>
				</tr>
				<tr>
					<td colspan=2>Prüfwert</td>
					<td><input width="8em" name="CrcVal" type="text" maxlength="32" value="<? print $CrcVal; ?>" /></td>
				</tr>
			
            </table></td>
			</tr>
        </table>
</form>
</body>
