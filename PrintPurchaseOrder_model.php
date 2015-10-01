<?
	require_once('fpdf.php');
	class PrintPurchaseOrder extends FPDF{
		public function setCompanyInfo($companyname,$companyaddress,$companyno){
			$this->companyname = $companyname;
			$this->companyaddress = $companyaddress;
			$this->companyno = $companyno;
		}
		public function setImageLogo($logo){
			$this->logo = $logo;
		}
		public function setPORefNo($porefno,$transdate){
			$this->porefno = $porefno;
			$this->transdate = $transdate;
		}
		public function setCustomerInfo($custname,$custaddress,$custnumber,$custfax,$custmobile){
			$this->customername = $custname;
			$this->customeraddress = $custaddress;
			$this->customernumber = $custnumber;
			$this->customerfax = $custfax;
			$this->customermobile = $custmobile;
		}
		public function setAccessoryData($accessorydata){
			$this->accessorydata = $accessorydata;
			$this->cntaccessory = count($accessorydata);
		}
		public function setJobData($jobdata){
			$this->jobdata = $jobdata;
			$this->cntjob = count($jobdata);
		}
		public function setMakeData($makedata){
			$this->makedata = $makedata;
			$this->cntmake = count($makedata);
		}
		public function setMaterialData($materialdata){
			$this->materialdata = $materialdata;
			$this->cntmaterial = count($materialdata);
		}
		public function setPartsData($partsdata){
			$this->partsdata = $partsdata;
			$this->cntparts = count($partsdata);
		}
		public function setEstimateData($remarks,$discount){
			$this->remarks = $remarks;
			$this->discount = $discount;
		}
		public function setVehicleInfo($plateno='',$model='',$variant='',$make='',$color='',$year=''){
			$this->plateno = $plateno;
			$this->model = $model;
			$this->variant = $variant;
			$this->make = $make;
			$this->color = $color;
			$this->year = $year;
		}
		private function TableHeader(){
			// Column headings
			$this->SetFont('Courier','B',10);
			$this->Cell(20,6,'UNIT','LRB',0,'C');
			$this->Cell(110,6,'DESCRIPTION','LRB',0,'C');
			$this->Cell(30,6,'UNIT PRICE','LRB',0,'C');
			$this->Cell(30,6,'TOTAL PRICE','LRB',0,'C');
		}
		public function Header(){
			// $this->Image($this->logo, 10, 10, 25);
			
			// $this->Cell(25,6,'',0,0,'L');
			// $this->SetFont('Courier','B',20);
			// $this->SetTextColor(0,0,0);
			// $this->Cell(115,6,$this->companyname,0,0,'L');
			// $this->SetFont('Courier','B',16);
			// $this->SetTextColor(0,0,0);
			// $this->Cell(50,6,'PURCHASE ORDER',0,0,'L');
			// $this->Ln();
			$this->Image('images/logo1.png', 90, 0, 30);
			
			$this->Cell(25,6,'',0,0,'L');
			$this->SetFont('Courier','B',11);
			$this->SetTextColor(0,0,0);
			$this->Cell(115,6,$this->companyaddress,0,0,'L');
			$this->SetFont('Courier','B',16);
			$this->SetTextColor(255,0,0);
			$this->Cell(50,6,'No. ' . $this->porefno,0,0,'L');
			$this->Ln();
			
			$this->Cell(25,6,'',0,0,'L');
			$this->SetFont('Courier','B',11);
			$this->SetTextColor(0,0,0);
			$this->Cell(115,6,$this->companyno,0,0,'L');
			$this->SetFont('Courier','B',10);
			$this->SetTextColor(0,0,0);
			$this->Cell(50,6,'Date: ' . dateFormat($this->transdate,"M d, Y"),0,0,'L');
			$this->Ln();
			// HEADER
			$this->SetFont('Courier','B',10);
			$this->Cell(7,6,'TO: ','TL',0,'L');
			$this->SetFont('Courier','',10);
			$this->Cell(83,6,$this->customername,'TR',0,'L');
			$this->SetFont('Courier','B',10);
			$this->Cell(18,6,'Plate#: ','TLB',0,'L');
			$this->SetFont('Courier','',10);
			$this->Cell(44,6,$this->plateno,'TRB',0,'L');
			$this->SetFont('Courier','B',10);
			$this->Cell(13,6,'Make: ','TLB',0,'L');
			$this->SetFont('Courier','',10);
			$this->Cell(25,6,$this->make,'TRB',0,'L');
			$this->Ln();
			
			$this->SetFont('Courier','B',10);
			$this->Cell(17,6,'ADDRESS: ','L',0,'L');
			$this->SetFont('Courier','',10);
			$this->Cell(73,6,$this->customeraddress,'R',0,'L');
			$this->SetFont('Courier','B',10);
			$this->Cell(18,6,'Model: ','LB',0,'L');
			$this->SetFont('Courier','',10);
			$this->Cell(44,6,$this->model,'RB',0,'L');
			$this->SetFont('Courier','B',10);
			$this->Cell(13,6,'Color: ','LB',0,'L');
			$this->SetFont('Courier','',10);
			$this->Cell(25,6,$this->color,'RB',0,'L');
			$this->Ln();
			
			$this->Cell(90,6,'','LRB',0,'L');
			$this->SetFont('Courier','B',10);
			$this->Cell(18,6,'Variant: ','LB',0,'L');
			$this->SetFont('Courier','',10);
			$this->Cell(44,6,substr($this->variant,0,20),'RB',0,'L');
			$this->SetFont('Courier','B',10);
			$this->Cell(13,6,'Year: ','LB',0,'L');
			$this->SetFont('Courier','',10);
			$this->Cell(25,6,$this->year,'RB',0,'L');
			$this->Ln();
			
			$this->SetFont('Courier','B',10);
			$this->Cell(27,6,'TELEPHONE NO: ','L',0,'L');
			$this->SetFont('Courier','',10);
			$this->Cell(63,6,$this->customernumber . ',' . $this->customerfax . ',' . $this->customermobile,'R',0,'L');
			$this->SetFont('Courier','B',10);
			$this->Cell(50,6,'DESCRIPTION: ','L',0,'L');
			$this->SetFont('Courier','',10);
			$this->Cell(50,6,'','R',0,'L');
			$this->Ln();
			
			$this->SetFont('Courier','B',10);
			$this->Cell(20,6,'Remarks: ','LT',0,'T');
			$this->SetFont('Courier','',10);
			$this->Cell(170,6,$this->remarks,'TR',0,'T');
			$this->Ln();
			$this->Cell(20,6,'','L',0,'T');
			$this->Cell(170,6,'','R',0,'T');
			$this->Ln();
			$this->Cell(20,6,'','L',0,'T');
			$this->Cell(170,6,'','R',0,'T');
			$this->Ln();
			$this->Cell(190,1,'','LBR',0,'T');
			$this->Ln();
			
			$this->TableHeader();
		}
		
		private function AccessoryData($w){
			$data = $this->accessorydata;
			$cntdata = 1;
			$total_accessory = 0;
			
			// Data
			$this->SetFont('Courier','',10);
			foreach($data as $row){
				//$this->Cell($w[0],6,'1','LR',0,'C');
				$this->Cell($w[0],6,'PC','LR',0,'C');
				$this->Cell($w[1],6,$row['description'],'LR',0,'L');
				$this->Cell($w[2],6,number_format($row['amount'],2),'LR',0,'R');
				
				$total_accessory = $row['amount'];
				$this->Cell($w[3],6,number_format($total_accessory,2),'LR',0,'R');
				$this->Ln();
			}
			
			$this->accessorytotal = $total_accessory;
		}
		private function JobData($w){
			$data = $this->jobdata;
			$cntdata = 1;
			$total_job = 0;
			
			// Data
			$this->SetFont('Courier','',10);
			foreach($data as $row){
				//$this->Cell($w[0],6,'1','LR',0,'C');
				$this->Cell($w[0],6,'LABOR','LR',0,'C');
				$this->Cell($w[1],6,$row['description'],'LR',0,'L');
				$this->Cell($w[2],6,number_format($row['amount'],2),'LR',0,'R');
				
				$total_job += $row['amount'];
				$this->Cell($w[3],6,number_format($total_job,2),'LR',0,'R');
				$this->Ln();
			}
			
			$this->jobtotal = $total_job;
		}
		private function MakeData($w){
			$data = $this->makedata;
			$cntdata = 1;
			$total_make = 0;
			
			// Data
			$this->SetFont('Courier','',10);
			foreach($data as $row){
				//$this->Cell($w[0],6,'1','LR',0,'C');
				$this->Cell($w[0],6,'LABOR','LR',0,'C');
				$this->Cell($w[1],6,$row['description'],'LR',0,'L');
				$this->Cell($w[2],6,number_format($row['amount'],2),'LR',0,'R');
				
				$total_make += $row['amount'];
				$this->Cell($w[3],6,number_format($total_make,2),'LR',0,'R');
				$this->Ln();
			}
			
			$this->maketotal = $total_make;
		}
		private function MaterialData($w){
			$data = $this->materialdata;
			$cntdata = 1;
			$total_material = 0;
			
			// Data
			$this->SetFont('Courier','',10);
			foreach($data as $row){
				//$this->Cell($w[0],6,'1','LR',0,'C');
				$this->Cell($w[0],6,'PC','LR',0,'C');
				$this->Cell($w[1],6,$row['description'],'LR',0,'L');
				$this->Cell($w[2],6,number_format($row['amount'],2),'LR',0,'R');
				
				$total_material += $row['amount'];
				$this->Cell($w[3],6,number_format($total_material,2),'LR',0,'R');
				$this->Ln();
			}
			
			$this->materialtotal = $total_material;
		}
		private function PartsData($w){
			$data = $this->partsdata;
			$cntdata = 1;
			$total_parts = 0;
			
			// Data
			$this->SetFont('Courier','',10);
			foreach($data as $row){
				//$this->Cell($w[0],6,'1','LR',0,'C');
				$this->Cell($w[0],6,'PC','LR',0,'C');
				$this->Cell($w[1],6,$row['description'],'LR',0,'L');
				$this->Cell($w[2],6,number_format($row['amount'],2),'LR',0,'R');
				
				$total_parts += $row['amount'];
				$this->Cell($w[3],6,number_format($total_parts,2),'LR',0,'R');
				$this->Ln();
			}
			
			$this->partstotal = $total_parts;
		}
		private function TotalAmount(){
			$this->grandtotal = 0;
			$this->totalamount = 0;
			$this->totalamount += $this->accessorytotal;
			$this->totalamount += $this->jobtotal;
			$this->totalamount += $this->maketotal;
			$this->totalamount += $this->materialtotal;
			$this->totalamount += $this->partstotal;
			
			$discount = $this->discount;
			if(!empty($discount)){
				$this->subtotal = $this->totalamount;
				$this->totalamount -= $discount;
			}
			
			$this->vat = $this->totalamount * .12;
			
			$this->grandtotal = $this->totalamount + $this->vat;
			
			//$this->Cell(20,1,'','LR',0,'C');
			$this->Cell(20,1,'','LR',0,'C');
			$this->Cell(110,1,'','LR',0,'L');
			$this->Cell(30,1,'','LRB',0,'L');
			$this->Cell(30,1,'','LRB',0,'R');
			$this->Ln();
			//$this->Cell(20,1,'','LR',0,'C');
			$this->Cell(20,1,'','LR',0,'C');
			$this->Cell(110,1,'','LR',0,'L');
			$this->Cell(30,1,'','LRB',0,'L');
			$this->Cell(30,1,'','LRB',0,'R');
			$this->Ln();
			
			if(!empty($discount)){
				//$this->Cell(20,6,'','LR',0,'C');
				$this->Cell(20,6,'','LR',0,'C');
				$this->Cell(110,6,'','LR',0,'L');
				$this->SetFont('Courier','',10);
				$this->Cell(30,6,'Sub-total','LR',0,'L');
				$this->SetFont('Courier','B',10);
				$this->Cell(30,6,number_format($this->subtotal,2),'LR',0,'R');
				$this->Ln();
				
				$this->Cell(20,6,'','LR',0,'C');
				$this->Cell(110,6,'','LR',0,'L');
				$this->SetFont('Courier','',10);
				$this->Cell(30,6,'Discount','LR',0,'L');
				$this->SetFont('Courier','B',10);
				$this->Cell(30,6,number_format($this->discount,2),'LR',0,'R');
				$this->Ln();
			}
			
			//$this->Cell(20,6,'','LR',0,'C');
			$this->Cell(20,6,'','LR',0,'C');
			$this->Cell(110,6,'','LR',0,'L');
			$this->SetFont('Courier','',10);
			$this->Cell(30,6,'Total','LR',0,'L');
			$this->SetFont('Courier','B',10);
			$this->Cell(30,6,number_format($this->totalamount,2),'LR',0,'R');
			$this->Ln();
			
			$this->SetFont('Courier','',10);
			//$this->Cell(20,6,'','LR',0,'C');
			$this->Cell(20,6,'','LR',0,'C');
			$this->Cell(110,6,'','LR',0,'L');
			$this->SetFont('Courier','',10);
			$this->Cell(30,6,'Vat 12%','LR',0,'L');
			$this->SetFont('Courier','B',10);
			$this->Cell(30,6,number_format($this->vat,2),'LR',0,'R');
			$this->Ln();
			
			$this->SetFont('Courier','',10);
			//$this->Cell(20,6,'','LRB',0,'C');
			$this->Cell(20,6,'','LRB',0,'C');
			$this->Cell(110,6,'','LRB',0,'L');
			$this->SetFont('Courier','',10);
			$this->Cell(30,6,'Grand Total','LRB',0,'L');
			$this->SetFont('Courier','B',10);
			$this->Cell(30,6,number_format($this->grandtotal,2),'LRB',0,'R');
			$this->Ln();
		}
		public function ImprovedTable(){
			$header = $this->header;
			// Column widths
			$w = array(20, 110, 30, 30);
			
			// Header
			for($i=0;$i<count($header);$i++){
				$this->Cell($w[$i],6,$header[$i],1,0,'C');
			}
			$this->Ln();
			
			$this->AccessoryData($w);
			$this->JobData($w);
			$this->MakeData($w);
			$this->MaterialData($w);
			$this->PartsData($w);
			$this->TotalAmount();
			
		}
		public function Footer(){
			$this->SetY(-30);
			
			$this->SetFont('Courier','B',10);
			$this->Cell(60,6,'Prepared By: ',0,0,'L');
			$this->Cell(5,6,'',0,0,'L');
			$this->Cell(25,6,'Date: ',0,0,'L');
			$this->Cell(5,6,'',0,0,'L');
			$this->Cell(65,6,'Approved By: ',0,0,'L');
			$this->Cell(5,6,'',0,0,'L');
			$this->Cell(25,6,'Date: ',0,0,'L');
			$this->Cell(5,6,'',0,0,'L');
			$this->Ln(8);
			
			$this->Cell(60,6,'','B',0,'L');
			$this->Cell(5,6,'',0,0,'L');
			$this->Cell(25,6,'','B',0,'L');
			$this->Cell(5,6,'',0,0,'L');
			$this->Cell(60,6,'','B',0,'L');
			$this->Cell(5,6,'',0,0,'L');
			$this->Cell(25,6,'','B',0,'L');
			$this->Cell(5,6,'',0,0,'L');
			$this->Ln(6);
			
			$this->SetFont('Courier','',10);
			$this->Cell(60,6,'Signature over printed name',0,0,'L');
			$this->Cell(5,6,'',0,0,'L');
			$this->Cell(25,6,'',0,0,'L');
			$this->Cell(5,6,'',0,0,'L');
			$this->Cell(60,6,'Signature over printed name',0,0,'L');
			$this->Cell(5,6,'',0,0,'L');
			$this->Cell(25,6,'',0,0,'L');
			$this->Cell(5,6,'',0,0,'L');
			$this->Ln(4);
		}
	}
?>