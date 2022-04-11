<?php
class Barcode extends TCPDF{
	public $pdf = NULL;
	public $style = NULL;
	public function __construct(){
		global $app;
				
		$appInfo = new AppInfo();
		$appData = $appInfo->getDetails();
		
		$this->pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LABEL', true, 'UTF-8', false);
		$this->pdf->SetCreator(PDF_CREATOR);
		$this->pdf->SetAuthor($app->siteName);
		$this->pdf->SetTitle($app->siteName);
		$this->pdf->SetSubject('Item Label');
		$this->pdf->SetPrintHeader(false);
		$this->pdf->SetPrintFooter(false);		
		$this->pdf->SetMargins(5, 2, 5);
		$this->pdf->SetHeaderMargin(0);
		$this->pdf->SetFooterMargin(0);
		$this->pdf->SetAutoPageBreak(FALSE, 20);
		$this->pdf->setBarcode(date('Y-m-d H:i:s'));
		$this->pdf->SetFont('helvetica', '', 10);
		$this->style = array(
			'position' => 'S',
			'align' => 'C',
			'stretch' => false,
			'fitwidth' => true,
			'border' => true,
			'padding' => 1,
			'fgcolor' => array(0,0,0),
			'bgcolor' => false, //array(255,255,255),
			'text' => true,
			'font' => 'helvetica',
			'fontsize' => 10,
			'stretchtext' => 4
		);		
		return $this;
	}
	
	public function addAssetCode($code="", $title="", $subTitle = ""){
		$this->pdf->AddPage();
		$this->pdf->Cell(0, 0, $title, 0, 1);
		$this->pdf->MultiCell(0, 10, $subTitle, 0, 1);
		$this->pdf->write1DBarcode($code, 'C128A', '', '', 60, 14, 0.4, $this->style, 'N');		
		$this->pdf->Ln();	
		return $this;		
	}
	
	public function printBarcode(){
		$this->pdf->Output('barcode-'.date('Y-m-d-H-i-s').'.pdf', 'I');
	}
	
	
}
?>