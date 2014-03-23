<?php
require_once('util.php');

/******************************************************************************
 * Funktions-Handler für den Button 'Speichern'
 * Die Formulareingaben werden in der Datenbank gespeichert
  ****************************************************************************/
if (isset($_POST['savetodb']))
{
	// Die Spielresultate werden später gespeichert	
}
/******************************************************************************
 * Handler für die Buttons
 * 'Achtelfinalgegner neu berechnen'
 * 'Viertelfinalgegner neu berechnen'
 * 'Halbfinalgegner neu berechnen'
 * 'Finalgegner neu berechnen'
  ****************************************************************************/
if (isset($_POST['calculateeight']))
{
	CalculateLastSixteenFinals();
}

if (isset($_POST['calculatequarter']))
{
	CalculateQuarterFinals();
}

if (isset($_POST['calculatehalf']))
{
	CalculateHalfFinals();
}

if (isset($_POST['calculatefinal']))
{
	CalculateFinals();
}
?>
