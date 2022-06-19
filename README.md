# lanyardpendant
LanyardPendant is a PHP class for generating PDF lanyard pendants

## Installation 

with [Composer](https://packagist.org/packages/kibi/lanyardpendant)

```composer require kibi/lanyardpendant```


via require (download current release manually)

```php
require_once('path/to/src/LanyardPendant.class.php');
```

## Sample usage

```php

require_once('vendor/autoload.php');

$pdfName = 'Namensschild.pdf';

$pdf = LanyardPendant($pdfName);

$pdf->SetCreator("Creator name");
$pdf->SetAuthor("Author name");

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

## Print lanyard pendants using Druckerwolke (Cloud Printing Service)

### Setup your Account 
1. Create your account at https://druckerwolke.de 
2. Create your API username and password in the login area at https://durckerwolke.de.
3. Request your API key at support@druckerwolke.de

### Install [Druckerwolke](https://github.com/paulkirchbichler/druckerwolke) 
with Composer

```composer require kibi/druckerwolke```

via require (download current release manually)

```php
require_once('path/to/src/Druckerwolke.class.php');
```
Then add the following section to your code:

```php

$file_content = $pdf->Output($pdfName.'.pdf', 'S');

$username = 'XXX';
$password = 'XXXXXXXXXX';
$api_key = 'XXXXXXXX-XXXX-MXXX-NXXX-XXXXXXXXXXXX';

$druckerwolke = Druckerwolke($username, $password, $api_key);

$printers = $druckerwolke->printers();

// SELECT THE PRINTER
$printer_id = $printers[0]->Id; //we are using the first printer

$data = [
	'FileName' => $pdfName,
	'MimeType' => 'application/pdf',
	'FileDataBase64' => base64_encode($file_content),
	'JobName' => 'Printing: '.$pdfName,
	'DocumentVersion' => 0,
	'InputQueueId' => $printer_id,
	'FileSize' => 0,
	'JobSettings' => [
		'PageOrientation' => 0
	],
	'AdditionalParameters' => []
];

$result = $druckerwolke->add_document($data);

```


