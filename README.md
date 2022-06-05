# lanyardpendant
LanyardPendant is a PHP class for generating PDF lanyard pendants

## Installation with [Composer](https://packagist.org/packages/kibi/lanyardpendant)

```composer require kibi/lanyardpendant```

## Sample usage

```php

require_once('../src/LanyardPendant.class.php');

$pdfName = 'Namensschild.pdf';

$pdf = LanyardPendant($pdfName);

$pdf->SetCreator("Norddeutsch Gesund");
$pdf->SetAuthor("Norddeutsch Gesund");

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

//PDF DIREKT AUSGEBEN
$pdf->Output($pdfName.'.pdf', 'I');

```
