<?php
/***********************************************************************
* Trikot-Totto Tottomat (Tippspiel für die Fussball EM/WM) 
* ----------------------------------------------------------------------
* Datei: config.php
* 
* Verbindungsparameter und Einstellungen 
* 
* Email: wyss@superspider.net
***********************************************************************/

/***********************************************************************
* Datenbankverbindung
***********************************************************************/
$db_serv = 'superspi.mysql.db.hostpoint.ch';
$db_name = 'superspi_tottomat';
$db_user = 'superspi_master';
$db_pass = 'little miss staromat';
$db_table = 'emtotto2016';

/***********************************************************************
* Optionale Debugausgabe im Formular
***********************************************************************/
$DEBUG_PLAYER = 'hmuster';
error_reporting(E_ALL & ~E_NOTICE);
?>
