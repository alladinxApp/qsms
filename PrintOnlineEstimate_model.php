<?
	require_once('fpdf.php');
	class PrintEstimate extends FPDF{
		public function setCompanyInfo($companyname,$companyaddress,$companyno){
			$this->companyname = $companyname;
			$this->companyaddress = $companyaddress;
			$this->companyno = $companyno;
		}
		public function setDetails($details){
			$this->details = $details;
		}
		public function setMaster($master){
			$this->master = $master;
		}
		public function Header(){
			$this->Image('images/logo1.png', 90, 0, 30);
			
			$this->SetFont('Courier','',8);
			$this->Cell(190,4,$this->companyaddress,0,0,'C');
			$this->Ln();
			$this->SetFont('Courier','',8);
			$this->Cell(190,4,$this->companyno,0,0,'C');
			$this->Ln();
			
			$this->SetFont('Courier','B',8);
			$this->Cell(190,4,'JOB QUOTATION',0,0,'C');
			$this->Ln();
			
			$this->SetFont('Courier','B',9);
			$this->Cell(20,4,'OE#','TRBL',0,'L');
			$this->SetFont('Courier','',8);
			$this->Cell(75,4,$this->master[0]['oe_id'],'TRBL',0,'L');
			$this->SetFont('Courier','B',9);
			$this->Cell(30,4,'Date','TRBL',0,'L');
			$this->SetFont('Courier','',8);
			$this->Cell(65,4,date("F d, Y",strtotime($this->master[0]['transaction_date'])),'TRBL',0,'L');
			$this->Ln();
			
			$this->SetFont('Courier','B',9);
			$this->Cell(20,4,'Customer','TRBL',0,'L');
			$this->SetFont('Courier','',8);
			$this->Cell(75,4,$this->master[0]['customer'],'TRBL',0,'L');
			$this->SetFont('Courier','B',9);
			$this->Cell(30,4,'Contact No','TRBL',0,'L');
			$this->SetFont('Courier','',8);
			$this->Cell(65,4,$this->master[0]['contactno'],'TRBL',0,'L');
			$this->Ln();

			$this->SetFont('Courier','B',9);
			$this->Cell(20,4,'Address','TRBL',0,'L');
			$this->SetFont('Courier','',8);
			$this->Cell(75,4,$this->master[0]['address'],'TRBL',0,'L');
			$this->SetFont('Courier','B',9);
			$this->Cell(30,4,'Email Address','TRBL',0,'L');
			$this->SetFont('Courier','',8);
			$this->Cell(65,4,$this->master[0]['emailaddress'],'TRBL',0,'L');
			$this->Ln();
			
			$this->SetFont('Courier','B',9);
			$this->Cell(20,4,'Model','TRBL',0,'L');
			$this->SetFont('Courier','',8);
			$this->Cell(75,4,$this->master[0]['model'],'TRBL',0,'L');
			$this->SetFont('Courier','B',9);
			$this->Cell(30,4,'Plate#','TRBL',0,'L');
			$this->SetFont('Courier','',8);
			$this->Cell(65,4,$this->master[0]['plateno'],'TRBL',0,'L');
			$this->Ln();
			
			$this->SetFont('Courier','B',9);
			$this->Cell(70,4,'Description','TRBL',0,'C');
			$this->Cell(30,4,'Labor','TRBL',0,'C');
			$this->Cell(30,4,'Lubricants','TRBL',0,'C');
			$this->Cell(30,4,'Sublet/Others','TRBL',0,'C');
			$this->Cell(30,4,'Spare Parts','TRBL',0,'C');
			$this->Ln();
		}
		
		public function ImprovedTable(){
			$this->totallabor = 0;
			$this->totallubricants = 0;
			$this->totalsublet = 0;
			$this->totalparts = 0;
			$this->grandtotal = 0;
			
			$this->SetFont('Courier','',9);
			for($i=0;$i<count($this->details);$i++){
				$desc = $this->details[$i]['itemname'];
				$qty = $this->details[$i]['qty'];
				
				if($qty > 1){
					$qty .= " pcs";
				}else{
					$qty .= " pc";
				}
				
				$this->Cell(70,4, $desc . ' - ' . $qty ,'TRBL',0,'L');
				
				switch($this->details[$i]['type']){
					case "job": 
							$this->labor = $this->details[$i]['amount'];
							$this->lubricants = null;
							$this->sublet = null;
							$this->parts = null;
						break;
					case "parts": 
							$this->labor = null;
							$this->lubricants = null;
							$this->sublet = null;
							$this->parts = $this->details[$i]['amount'];
						break;
					case "material": 
							$this->labor = null;
							$this->lubricants = null;
							$this->sublet = $this->details[$i]['amount'];
							$this->parts = null;
						break;
					case "accessory": 
							$this->labor = null;
							$this->lubricants = $this->details[$i]['amount'];
							$this->sublet = null;
							$this->parts = null;
						break;
					default: break;
				}
				
				$this->Cell(30,4,$this->labor,'TRBL',0,'R');
				$this->Cell(30,4,$this->lubricants,'TRBL',0,'R');
				$this->Cell(30,4,$this->sublet,'TRBL',0,'R');
				$this->Cell(30,4,$this->parts,'TRBL',0,'R');
				$this->Ln();
				
				$this->totallabor += $this->labor;
				$this->totallubricants += $this->lubricants;
				$this->totalsublet += $this->sublet;
				$this->totalparts += $this->parts;
			}
			
			$this->SetFont('Courier','B',9);
			$this->Cell(70,4,'TOTAL','TRBL',0,'R');
			$this->Cell(30,4,number_format($this->totallabor,2),'TRBL',0,'R');
			$this->Cell(30,4,number_format($this->totallubricants,2),'TRBL',0,'R');
			$this->Cell(30,4,number_format($this->totalsublet,2),'TRBL',0,'R');
			$this->Cell(30,4,number_format($this->totalparts,2),'TRBL',0,'R');
			$this->Ln();
			
			$this->grandtotal += $this->totallabor;
			$this->grandtotal += $this->totallubricants;
			$this->grandtotal += $this->totalsublet;
			$this->grandtotal += $this->totalparts;
			
			$this->Cell(130,4,"SUB TOTAL",'TRBL',0,'R');
			$this->Cell(60,4,number_format($this->grandtotal,2),'TRBL',0,'R');
			$this->Ln(10);
			
			$this->Cell(160,4,"Total Labor",0,0,'R');
			$this->Cell(30,4,number_format($this->totallabor,2),'TRBL',0,'R');
			$this->Ln();
			$this->Cell(160,4,"Total Parts",0,0,'R');
			$this->Cell(30,4,number_format($this->totalparts,2),'TRBL',0,'R');
			$this->Ln();
			$this->Cell(160,4,"Total Lubricants",0,0,'R');
			$this->Cell(30,4,number_format($this->totallubricants,2),'TRBL',0,'R');
			$this->Ln();
			$this->Cell(160,4,"Total Sublet/Others",0,0,'R');
			$this->Cell(30,4,number_format($this->totalsublet,2),'TRBL',0,'R');
			$this->Ln();
			
			$this->discountedprice = $this->grandtotal - $this->master[0]['discount'];
			$this->vat = ($this->discountedprice) * 0.12;
			$this->totalamount = $this->discountedprice + $this->vat;
			
			$this->Cell(160,4,"Discount",0,0,'R');
			$this->Cell(30,4,number_format($this->master[0]['discount'],2),'TRBL',0,'R');
			$this->Ln();
			$this->Cell(160,4,"VAT 12%",0,0,'R');
			$this->Cell(30,4,number_format($this->vat,2),'TRBL',0,'R');
			$this->Ln();
			$this->Cell(160,4,"Grand Total",0,0,'R');
			$this->Cell(30,4,number_format($this->totalamount,2),'TRBL',0,'R');
			$this->Ln();
		}
		
		public function Footer(){
			$this->SetY(-50);
			$this->SetFont('Courier','B',9);
			$this->Cell(190,4,'NOTE:',0,0,'L');
			$this->Ln();
			
			$this->SetFont('Courier','B',9);
			$this->Cell(190,4,'1. Cost of spare parts and materials are subject to change without prior notice given.',0,0,'L');
			$this->Ln();
			$this->Cell(190,4,'2. Other repairs not included in this job estimate will be charged as additional cost.',0,0,'L');
			$this->Ln(10);
			$this->Cell(15,4,null,0,0,'L');
			$this->Cell(80,4,'Prepared By:',0,0,'L');
			$this->Cell(15,4,null,0,0,'L');
			$this->Cell(80,4,'Conforme:',0,0,'L');
			$this->Ln();
			$this->Cell(25,4,null,0,0,'L');
			$this->Cell(70,4,null,'B',0,'L');
			$this->Cell(25,4,null,0,0,'L');
			$this->Cell(70,4,null,'B',0,'L');
			$this->Ln();
		}
	}
?>