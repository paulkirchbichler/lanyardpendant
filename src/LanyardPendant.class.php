<?php

//	Via Composer:
//	composer require tecnickcom/tcpdf
//	composer require setasign/fpdi

namespace kibi\\Lanyard\\

// TCPDF Library laden
require_once(__DIR__.'/../libraries/tcpdf/tcpdf.php');
require_once(__DIR__.'/../libraries/fpdi/src/autoload.php');
use setasign\Fpdi;

function LanyardPendant($PDFName){
	return new LanyardPendant($PDFName);
}

class LanyardPendant extends Fpdi\TcpdfFpdi{
	
	protected $PDFBackgroundDocumentPath = NULL;
	protected $BackgroundTemplatePage;
		
	public function __construct($PDFName){

		$orientation = 'P';
		$unit = 'mm';
		$format = 'A6';
		$unicode = true;
		$encoding = 'UTF-8';
		$diskcache = false;
		$pdfa = false;
		
		parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa);
			
		$this->SetCreator("Norddeutsch Gesund");
		$this->SetAuthor("Norddeutsch Gesund");
		
		$this->SetTitle($PDFName);
		$this->SetSubject($PDFName);
		
		// Header und Footer Informationen
		$this->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$this->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// Auswahl des Font (Schriftart)
		$this->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// Auswahl der Margins (Ränder)
		$this->SetMargins(10, 18, 10, false);
		
		$FooterMargin = 6.8;
		$this->SetFooterMargin($FooterMargin);

		// Automatisches Autobreak der Seiten
//		$this->SetAutoPageBreak(TRUE, $FooterMargin);
		
		// KEIN Automatisches Autobreak der Seiten
		$this->SetAutoPageBreak(FALSE, $FooterMargin);

		// Image Scale 
		/*$this->setImageScale(PDF_IMAGE_SCALE_RATIO);*/
//		$fontname = TCPDF_FONTS::addTTFfont(__DIR__.'\..\fonts\arial\arial.ttf', '', '', 32);
		
		// Schriftart
		$this->SetFont('helvetica', '', 9);
		
		$this->setCellPadding(0);
	}
	
	public function setPDFBackgroundDocumentPath($relative_path = NULL){
		$this->PDFBackgroundDocumentPath = $relative_path;
		return $this;
	}
	
	//Page header
	public function Header() {
		
		if(!empty($this->PDFBackgroundDocumentPath)){

			if(is_null($this->BackgroundTemplatePage)) {

				$file = realpath($this->PDFBackgroundDocumentPath);

				$this->setSourceFile($file);
				$this->BackgroundTemplatePage = $this->importPage(1);
			}

			$this->useTemplate($this->BackgroundTemplatePage, null, null, $this->getPageWidth(), $this->getPageHeight(), true);
		}
	}
	
	public function Footer(){
		//need to be empty, to override Standard
	}
	
	public function drawHolePunch(){
		$radius = 2.5;
		$page_width = parent::getPageWidth();
		
		$position_x = ($page_width)/2; 
		$position_y = 10;
		
		$style = [
			'width' => 0.25,
			'dash' => 0,
			'color' => array(255, 255, 255), //weißer Rand
//			'color' => array(0, 0, 0),	//schwarzer Rand
		];
		
		$this->Circle($position_x, $position_y, $radius, 0, 360, 'DF', $style, array(255, 255, 255));
		return $this;
	}
	
	public function writeName($name){
		
		$font_size = 25;
		
		// Schriftgröße automatisch an Namenslänge anpassen
		if(mb_strlen($name) > 30 ){
			$font_size = 10;
		}elseif(mb_strlen($name) > 15 ){
			$font_size = $font_size - (0.5 * (mb_strlen($name) - 4));
		}
		
		$this->writeHTML('<p style="font-size: '.$font_size.'; line-height: 1.4;">'.strtoupper($name).'</p>', true, false, true, false, '');
		return $this;
	}
	
	public function writePersonalCompany($company){
		$this->writeHTML('<br><p style="font-size: 13; line-height: 1.4; font-weight: bold">'.strtoupper($company).'</p>', true, false, true, false, '');
		return $this;
	}
	
	public function writePersonalPosition($position){
		$this->writeHTML('<p style="font-size: 10; line-height: 2;">'.$position.'</p>', true, false, true, false, '');
		return $this;
	}
	
	public function writePersonalTextField($content){
		$this->writeBreak();
		$this->writeBreak();
		$this->writeText($content);
	}
	
	public function writePersonalQRCode($url){
		$qr_code_width = 25;
		$qr_code_height = 25;
			
		$page_width = parent::getPageWidth();
		$page_height = parent::getPageheight();
		
		// Mittig unten positionieren
//		$position_x = ($page_width - $qr_code_width)/2; 
//		$position_y = 102;
		
		// Rechts unten positionieren
		$position_x = $page_width - parent::getMargins()['right'] - $qr_code_width; 
		$position_y = $page_height - parent::getMargins()['bottom'] - $qr_code_height;
		
		$style = array(
			'border' => 0,
			'vpadding' => 0,
			'hpadding' => 0,
			'fgcolor' => array(0,0,0),
			'bgcolor' => false, //array(255,255,255)
			'module_width' => 1, // width of a single module in points
			'module_height' => 1 // height of a single module in points
		);
		
		$this->write2DBarcode($url, 'QRCODE,M', $position_x, $position_y, $qr_code_width, $qr_code_height, $style, 'N');
		return $this;
	}
	
	public function writeText($text, $font_size = 10){
		$this->writeHTML('<p style="line-height: 1.5; font-size: '.$font_size.';">'.nl2br($text).'<br></p>', true, false, true, false, '');
		return $this;
	}
	
	public function writeBreak(){
		$this->writeHTML('<br><br>', false, false, true, false, '');
		return $this;
	}
	
	public function upload2FTPServer($ftp_connection, $server_file, $filepath){
		return ftp_put($ftp_connection, $server_file, $filepath, FTP_ASCII);
	}

}

?>
