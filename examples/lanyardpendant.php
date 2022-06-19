<?php

require_once('../src/LanyardPendant.class.php');

$pdfName = 'Namensschild.pdf';

$pdf = LanyardPendant($pdfName);

$pdf->SetCreator("Creator");
$pdf->SetAuthor("Author");

//Nur zu Testzwecken
$pdf->setPDFBackgroundDocumentPath(dirname(__FILE__).'/LanyardPendantBackground-sample.pdf'); 
	
$pdf->AddPage();

$pdf->drawHolePunch(); //Nur zu Testzwecken

$pdf->writeName('Max');
$pdf->writeName('Mustermann');
//$pdf->writeName('Mustermann von Musterhausenstein'); //Automatische Anpassung der Schriftgröße an Textlänge (-> Immer nur eine Zeile)

$pdf->writePersonalCompany('Meine Musterfirma');
$pdf->writePersonalPosition('Event Manager');

$pdf->writePersonalTextField('<b>Sprich mich an, wenn...</b><br>du dich für Co-Workingspaces, Events, Hackathons & Networking interessierst');

$pdf->writePersonalQRCode('https://example.com/link/to/my/profile');


//PDF IN FTP VERZEICHNIS HOCHLADEN

//$filepath = __DIR__.'/../dyn/Namensschild.pdf';
//$server_file = '../path/to/server/dir/Namensschild-'.time();
//$pdf->Output($filepath, 'F'); //PDF abspeichern

//$ftp_server_ip = 'XXX.XXX.XXX.XXX';
//$ftp_user_name = 'username';
//$ftp_user_pass = 'password';

//$ftp_connection = ftp_connect($ftp_server_ip);
//$login_result = ftp_login($ftp_connection, $ftp_user_name, $ftp_user_pass);
//$pdf->upload2FTPServer($ftp_connection, $server_file, $filepath);


//ODER PDF DIREKT AUSGEBEN
$pdf->Output($pdfName.'.pdf', 'I');








?>