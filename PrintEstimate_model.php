<?
	require_once('fpdf.php');
	class PrintEstimate extends FPDF{
		public function setCompanyInfo($companyname,$companyaddress,$companyno){
			$this->companyname = $companyname;
			$this->companyaddress = $companyaddress;
			$this->companyno = $companyno;
		}
		public function setCustomerInfo($custname,$custaddress,$custnumber,$custfax,$custmobile){
			$this->customername = $custname;
			$this->customeraddress = $custaddress;
			$this->customernumber = $custnumber;
			$this->customerfax = $custfax;
			$this->customermobile = $custmobile;
		}
		public function setEstimateRefNo($estimaterefno,$transdate){
			$this->estimaterefno = $estimaterefno;
			$this->transdate = $transdate;
		}
		public function setVehicleInfo($plateno='',$model='',$variant='',$make='',$color='',$year=''){
			$this->plateno = $plateno;
			$this->model = $model;
			$this->variant = $variant;
			$this->make = $make;
			$this->color = $color;
			$this->year = $year;
		}
		public function setDetails($details){
			$this->details = $details;
		}
		public function setMaster($master){
			$this->discount = 0;
			if(!empty($master['discount']) && $master['discount'] > 0){
				$this->discount += $master['discount'];
			}
			// if(!empty($master['discounted_price']) && $master['discounted_price'] > 0){
			// 	$this->discount += $master['discounted_price'];
			// }
			$this->totalamount = $master['total_amount'];
		}
		public function Header(){
			// $this->SetFont('Courier','B',16);
			// $this->SetTextColor(0,0,0);
			// $this->Cell(190,4,'<img src="images/logo1.jpg" border="0" width="300">',0,0,'C');
			// $this->Ln();
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
			$this->Cell(20,4,'Customer','TRBL',0,'L');
			$this->SetFont('Courier','',8);
			$this->Cell(75,4,$this->customername,'TRBL',0,'L');
			$this->SetFont('Courier','B',9);
			$this->Cell(20,4,'Date','TRBL',0,'L');
			$this->SetFont('Courier','',8);
			$this->Cell(75,4,date("F d, Y",strtotime($this->transdate)),'TRBL',0,'L');
			$this->Ln();
			
			$this->SetFont('Courier','B',9);
			$this->Cell(20,4,'Address','TRBL',0,'L');
			$this->SetFont('Courier','',8);
			$this->Cell(75,4,$this->customeraddress,'TRBL',0,'L');
			$this->SetFont('Courier','B',9);
			$this->Cell(20,4,'Plate#','TRBL',0,'L');
			$this->SetFont('Courier','',8);
			$this->Cell(75,4,$this->plateno,'TRBL',0,'L');
			$this->Ln();
			
			$this->SetFont('Courier','B',9);
			$this->Cell(20,4,'Model','TRBL',0,'L');
			$this->SetFont('Courier','',8);
			$this->Cell(75,4,$this->model,'TRBL',0,'L');
			$this->SetFont('Courier','B',9);
			$this->Cell(20,4,'Tel/Fax No','TRBL',0,'L');
			$this->SetFont('Courier','',8);
			$this->Cell(75,4,$this->customernumber . ' / ' . $this->customerfax . ' / ' . $this->customermobile,'TRBL',0,'L');
			$this->Ln();
			
			$this->SetFont('Courier','B',9);
			$this->Cell(60,4,'Estimated Day/s of Completion','TRBL',0,'L');
			$this->SetFont('Courier','',8);
			$this->Cell(35,4,null,'TRBL',0,'L');
			$this->SetFont('Courier','B',9);
			$this->Cell(20,4,'Mileage','TRBL',0,'L');
			$this->SetFont('Courier','',8);
			$this->Cell(75,4,null,'TRBL',0,'L');
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
				$desc = $this->details[$i]['desc'];
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
			
			$this->vat = $this->grandtotal * 0.12;
			
			$this->Cell(160,4,"Discount",0,0,'R');
			$this->Cell(30,4,number_format($this->discount,2),'TRBL',0,'R');
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