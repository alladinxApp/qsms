<?
	require_once('fpdf.php');
	class PrintWorkOrder extends FPDF{
		public function setServiceAdvisor($serviceadvisor){
			$this->serviceadvisor = $serviceadvisor;
		}
		public function setCompanyInfo($companyname,$companyaddress,$companyno){
			$this->companyname = $companyname;
			$this->companyaddress = $companyaddress;
			$this->companyno = $companyno;
		}
		public function setCustomerInfo($customer){
			$this->customerid = $customer['customer_id'];
			$this->customername = $customer['customer_name'];
			$this->address = $customer['address'] . 'asdfasdfasdfasdfasdfasdfasdfasdfasdf';
			$this->business = $customer['business'];
			$this->fax = $customer['fax'];
			$this->business = $customer['business'];
		}
		public function setWOMST($mst){
			$this->mst = $mst;
		}
		public function setVehicleInfo($vehicle){
			$this->vehicle = $vehicle;
		}
		public function setJCMST($jobclock){
			$this->jc = $jobclock;
		}
		public function setServiceDetail($detail){
			$this->detail = $detail;
		}
		public function setHistoryMST($historymst){
			$this->historymst = $historymst;
		}
		public function setJobHistory($jobhistory){
			$this->jobhistory = $jobhistory;
		}
		public function Header(){
			// $this->SetFont('Courier','B',16);
			// $this->SetTextColor(0,0,0);
			// $this->Cell(190,4,$this->companyname,0,0,'C');
			// $this->Ln();
			$this->Image('images/logo1.png', 90, 0, 30);
			
			$this->SetFont('Courier','',8);
			$this->Cell(190,4,$this->companyaddress,0,0,'C');
			$this->Ln();
			// $this->Cell(190,4,'Tel No. ' . $this->companyno,0,0,'C');
			$this->Cell(45,4,'',0,0,'C');
			$this->Cell(100,4,'Tel No. ' . $this->companyno,0,0,'C');
			$this->SetFont('Courier','B',20);
			$this->Cell(45,6,$this->mst['wo_refno'],0,0,'R');
			$this->Ln();
		}
		public function ImprovedTable(){
			$this->SetFont('Courier','B',8);
			$this->Cell(23,4,'Customer',"TL",0,'L');
			$this->Cell(2,4,':',"T",0,'C');
			$this->SetFont('Courier','',8);
			$this->Cell(70,4,$this->customername,"TR",0,'L');
			$this->SetFont('Courier','B',8);
			$this->Cell(33,4,'Customer Code/Type',"TLB",0,'L');
			$this->Cell(2,4,':',"TB",0,'C');
			$this->SetFont('Courier','',8);
			$this->Cell(60,4,$this->customerid,"TRB",0,'L');
			$this->Ln();
			$this->Cell(95,4,null,"RL",0,'C');
			$this->SetFont('Courier','B',8);
			$this->Cell(33,4,'Payment Method',"TLB",0,'L');
			$this->Cell(2,4,':',"TB",0,'C');
			$this->SetFont('Courier','',8);
			$this->Cell(60,4,$this->mst['payment_mode'],"TRB",0,'L');
			$this->Ln();
			$this->Cell(95,4,null,"RL",0,'C');
			$this->SetFont('Courier','B',8);
			$this->Cell(33,4,'Insurance Code',"TLB",0,'L');
			$this->Cell(2,4,':',"TB",0,'C');
			$this->SetFont('Courier','',8);
			$this->Cell(60,4,null,"TRB",0,'L');
			$this->Ln();
			$this->Cell(95,4,null,"RL",0,'C');
			$this->SetFont('Courier','B',8);
			$this->Cell(33,4,'Contact Person',"TLB",0,'L');
			$this->Cell(2,4,':',"TB",0,'C');
			$this->SetFont('Courier','',8);
			$this->Cell(60,4,$this->customername,"TRB",0,'L');
			$this->Ln();
			$this->Cell(95,4,null,"RL",0,'C');
			$this->SetFont('Courier','B',8);
			$this->Cell(23,4,'  Home',"TL",0,'L');
			$this->Cell(2,4,':',"T",0,'C');
			$this->SetFont('Courier','',8);
			$this->Cell(70,4,substr($this->address,0,40),"TR",0,'L');
			$this->Ln();
			$this->Cell(95,4,null,"RL",0,'C');
			$this->SetFont('Courier','B',8);
			$this->Cell(23,4,'',"LB",0,'L');
			$this->Cell(2,4,'',"B",0,'C');
			$this->SetFont('Courier','',8);
			$this->Cell(70,4,substr($this->address,40,80),"RB",0,'L');
			$this->Ln();
			$this->Cell(95,4,null,"RL",0,'C');
			$this->SetFont('Courier','B',8);
			$this->Cell(23,4,'  Business',"TLB",0,'L');
			$this->Cell(2,4,':',"TB",0,'C');
			$this->SetFont('Courier','',8);
			$this->Cell(70,4,$this->business,"TRB",0,'L');
			$this->Ln();
			$this->Cell(95,4,null,"RLB",0,'C');
			$this->SetFont('Courier','B',8);
			$this->Cell(23,4,'  Facsimile',"TLB",0,'L');
			$this->Cell(2,4,':',"TB",0,'C');
			$this->SetFont('Courier','',8);
			$this->Cell(70,4,$this->fax,"TRB",0,'L');
			$this->Ln();
			$this->SetFont('Courier','B',8);
			$this->Cell(38,4,'Appointment/Arrival',"TRL",0,'L');
			$this->Cell(32,4,'Time Attended',"TRL",0,'L');
			$this->Cell(25,4,'Promised Time',"TRL",0,'L');
			$this->Cell(45,4,'Service Advisor',"TRL",0,'L');
			$this->Cell(25,4,'Code',"TRL",0,'L');
			$this->Cell(25,4,'Number Block',"TRL",0,'L');
			$this->Ln();
			$this->SetFont('Courier','',8);
			$this->Cell(38,4,'',"BRL",0,'L');
			$this->Cell(32,4,dateFormat($this->jc['job_start'],"m/d/y h:i"),"BRL",0,'L');
			$this->Cell(25,4,$this->mst['promise_time'],"BRL",0,'L');
			$this->Cell(45,4,$this->serviceadvisor,"BRL",0,'L');
			$this->Cell(25,4,'',"BRL",0,'L');
			$this->Cell(25,4,'',"BRL",0,'L');
			$this->Ln();
			$this->SetFont('Courier','B',8);
			$this->Cell(38,4,'Plate No',"TRL",0,'L');
			$this->Cell(57,4,'Make/Model',"TRL",0,'L');
			$this->Cell(45,4,'Model Code',"TRL",0,'L');
			$this->Cell(50,4,'Frame No/ VIN',"TRL",0,'L');
			$this->Ln();
			$this->SetFont('Courier','',8);
			$this->Cell(38,4,$this->vehicle['plate_no'],"BRL",0,'L');
			$this->Cell(57,4,$this->vehicle['make_desc'] . ' - ' . $this->vehicle['model_desc'],"BRL",0,'L');
			$this->Cell(45,4,$this->vehicle['model'],"BRL",0,'L');
			$this->Cell(50,4,'',"BRL",0,'L');
			$this->Ln();
			$this->SetFont('Courier','B',8);
			$this->Cell(38,4,'Engine No',"TRL",0,'L');
			$this->Cell(57,4,'Color / Color Code',"TRL",0,'L');
			$this->Cell(45,4,'Trim / Trim Code',"TRL",0,'L');
			$this->Cell(50,4,'Odometer',"TRL",0,'L');
			$this->Ln();
			$this->SetFont('Courier','',8);
			$this->Cell(38,4,$this->vehicle['engine_no'],"BRL",0,'L');
			$this->Cell(57,4,$this->vehicle['color_desc'] . ' - ' . $this->vehicle['color'],"BRL",0,'L');
			$this->Cell(45,4,'',"BRL",0,'L');
			$this->Cell(50,4,$this->mst['odometer'],"BRL",0,'L');
			$this->Ln();
			$this->SetFont('Courier','B',8);
			$this->Cell(38,4,'Production No.',"TRL",0,'L');
			$this->Cell(38,4,'Sealing Dealer',"TRL",0,'L');
			$this->Cell(38,4,'Reg Date',"TRL",0,'L');
			$this->Cell(38,4,'Delivery Date',"TRL",0,'L');
			$this->Cell(38,4,'Customer Type',"TRL",0,'L');
			$this->Ln();
			$this->SetFont('Courier','',8);
			$this->Cell(38,4,'',"BRL",0,'L');
			$this->Cell(38,4,'',"BRL",0,'L');
			$this->Cell(38,4,'',"BRL",0,'L');
			$this->Cell(38,4,'',"BRL",0,'L');
			$this->Cell(38,4,'',"BRL",0,'L');
			$this->Ln();
			$this->SetFont('Courier','B',8);
			$this->Cell(22,4,'Repair Hist',"TRLB",0,'L');
			$this->Cell(21,4,'R.O. No.',"TRLB",0,'L');
			$this->Cell(21,4,'Repair Date',"TRLB",0,'L');
			$this->Cell(21,4,'Odometer',"TRLB",0,'L');
			$this->Cell(21,4,'Job1',"TRLB",0,'L');
			$this->Cell(21,4,'Job2',"TRLB",0,'L');
			$this->Cell(21,4,'Job3',"TRLB",0,'L');
			$this->Cell(21,4,'Job4',"TRLB",0,'L');
			$this->Cell(21,4,'Job5',"TRLB",0,'L');
			$this->Ln();
			$this->SetFont('Courier','B',8);
			$this->Cell(22,4,'Latest',"RL",0,'L');
			$this->SetFont('Courier','',7);
			$this->Cell(21,4,$this->historymst[0]['rono'],"RL",0,'L');
			$this->Cell(21,4,dateFormat($this->historymst[0]['rodate'],"m/d/y"),"RL",0,'L');
			$this->Cell(21,4,$this->historymst[0]['odometer'],"RL",0,'L');
			$this->Cell(21,4,$this->jobhistory[0][0],"RL",0,'L');
			$this->Cell(21,4,$this->jobhistory[0][1],"RL",0,'L');
			$this->Cell(21,4,$this->jobhistory[0][2],"RL",0,'L');
			$this->Cell(21,4,$this->jobhistory[0][3],"RL",0,'L');
			$this->Cell(21,4,$this->jobhistory[0][4],"RL",0,'L');
			$this->Ln();
			$this->SetFont('Courier','B',8);
			$this->Cell(22,4,'Last',"RL",0,'L');
			$this->SetFont('Courier','',7);
			$this->Cell(21,4,$this->historymst[1]['rono'],"RL",0,'L');
			$this->Cell(21,4,dateFormat($this->historymst[1]['rodate'],"m/d/y"),"RL",0,'L');
			$this->Cell(21,4,$this->historymst[1]['odometer'],"RL",0,'L');
			$this->Cell(21,4,$this->jobhistory[1][0],"RL",0,'L');
			$this->Cell(21,4,$this->jobhistory[1][1],"RL",0,'L');
			$this->Cell(21,4,$this->jobhistory[1][2],"RL",0,'L');
			$this->Cell(21,4,$this->jobhistory[1][3],"RL",0,'L');
			$this->Cell(21,4,$this->jobhistory[1][4],"RL",0,'L');
			$this->Ln();
			$this->SetFont('Courier','B',8);
			$this->Cell(22,4,'Before Last',"RL",0,'L');
			$this->SetFont('Courier','',7);
			$this->Cell(21,4,$this->historymst[2]['rono'],"RL",0,'L');
			$this->Cell(21,4,dateFormat($this->historymst[2]['rodate'],"m/d/y"),"RL",0,'L');
			$this->Cell(21,4,$this->historymst[2]['odometer'],"RL",0,'L');
			$this->Cell(21,4,$this->jobhistory[2][0],"RL",0,'L');
			$this->Cell(21,4,$this->jobhistory[2][1],"RL",0,'L');
			$this->Cell(21,4,$this->jobhistory[2][2],"RL",0,'L');
			$this->Cell(21,4,$this->jobhistory[2][3],"RL",0,'L');
			$this->Cell(21,4,$this->jobhistory[2][4],"RL",0,'L');
			$this->Ln();
			$this->SetFont('Courier','B',8);
			$this->Cell(95,4,'Customer Request',"RLBT",0,'C');
			$this->Cell(95,4,'Service Advisor Instruction',"RLBT",0,'C');
			$this->Ln();
			$this->SetFont('Courier','',8);
			$this->Cell(95,2,null,"TRL",0,'L');
			$this->Cell(95,2,null,"TRL",0,'L');
			$this->Ln();
			$this->Cell(95,4,$this->mst['remarks'],"RL",0,'L');
			$this->Cell(95,4,$this->mst['recommendation'],"RL",0,'L');
			$this->Ln();
			$this->Cell(95,2,null,"BRL",0,'L');
			$this->Cell(95,2,null,"BRL",0,'L');
			$this->Ln();
			$this->SetFont('Courier','B',8);
			$this->Cell(190,4,'Charge To: ' . $this->customername,"RLBT",0,'L');
			$this->Ln();
			$this->SetFont('Courier','B',8);
			// $this->Cell(45,4,'Charge To',"RLBT",0,'C');
			$this->Cell(60,4,'Work Description',"RLBT",0,'C');
			$this->Cell(35,4,'STK',"RLBT",0,'C');
			$this->Cell(35,4,'Part No',"RLBT",0,'C');
			$this->Cell(25,4,'Hours/QTY',"RLBT",0,'C');
			$this->Cell(35,4,'Amount',"RLBT",0,'C');
			$this->Ln();
			$this->Cell(60,2,null,"RLT",0,'C');
			// $this->Cell(45,2,null,"RLT",0,'L');
			$this->Cell(35,2,'',"RLT",0,'C');
			$this->Cell(35,2,null,"RLT",0,'C');
			$this->Cell(25,2,null,"RLT",0,'C');
			$this->Cell(35,2,null,"RLT",0,'R');
			$this->Ln();
			$this->SetFont('Courier','',8);

			$total = 0;
			for($i=0;$i<count($this->detail);$i++){
				// $this->Cell(45,4,$this->customername,"RL",0,'L');
				$this->Cell(60,4,$this->detail[$i]['desc'],"RL",0,'L');
				$this->Cell(35,4,'',"RL",0,'C');
				$this->Cell(35,4,$this->detail[$i]['id'],"RL",0,'C');
				$this->Cell(25,4,$this->detail[$i]['qty'],"RL",0,'C');
				$this->Cell(35,4,number_format($this->detail[$i]['amount'],2),"RL",0,'R');
				$this->Ln();
				$total += $this->detail[$i]['amount'];

				switch($this->detail[$i]['type']){
					case "job":
							$this->labor = $this->detail[$i]['amount'];
						break;
					case "parts":
							$this->parts = $this->detail[$i]['amount'];
						break;
					case "material":
							$this->sublet = $this->detail[$i]['amount'];
						break;
					case "accessory":
							$this->lubricants = $this->detail[$i]['amount'];
						break;
					default: break;
				}

				$this->totallabor += $this->labor;
				$this->totallubricants += $this->lubricants;
				$this->totalsublet += $this->sublet;
				$this->totalparts += $this->parts;
			}

			$this->Cell(60,2,null,"RLB",0,'C');
			$this->Cell(35,2,'',"RLB",0,'C');
			$this->Cell(35,2,null,"RLB",0,'C');
			$this->Cell(25,2,null,"RLB",0,'C');
			$this->Cell(35,2,null,"RLB",0,'R');
			$this->Ln();

			// $this->Cell(190,4,'',0,0,'R');
			// $this->Ln();
			// $this->SetFont('Courier','B',10);
			// $this->Cell(190,4,"Grand Total >>>>>>>>>> " . number_format($total,2),0,0,'R');
			// $this->Ln();
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
			$this->totalamount = ($this->grandtotal - $this->mst['discount']) + $this->vat;
			
			$this->Cell(160,4,"Discount",0,0,'R');
			$this->Cell(30,4,number_format($this->mst['discount'],2),'TRBL',0,'R');
			$this->Ln();
			$this->Cell(160,4,"VAT 12%",0,0,'R');
			$this->Cell(30,4,number_format($this->vat,2),'TRBL',0,'R');
			$this->Ln();
			$this->Cell(160,4,"Grand Total",0,0,'R');
			$this->Cell(30,4,number_format($this->totalamount,2),'TRBL',0,'R');
			$this->Ln();
		}
		public function Footer(){
			$this->SetY(-30);
			
			$this->SetFont('Courier','B',8);
			$this->Cell(115,4,null,"TLR",0,'L');
			$this->Cell(65,4,'Customer Signature',"T",0,'L');
			$this->Cell(10,4,null,"TR",0,'C');
			$this->Ln();
			$this->SetFont('Courier','B',15);
			$this->Cell(115,15,null,"LR",0,'C');
			$this->Cell(10,15,null,0,0,'L');
			$this->Cell(55,15,$this->customername,0,0,'C');
			$this->Cell(10,15,null,"R",0,'L');
			$this->Ln();
			$this->SetFont('Courier','B',8);
			$this->Cell(115,4,'The above repair charge/s is serves as an ESTIMATE only',"LRB",0,'C');
			$this->Cell(10,4,null,"LB",0,'L');
			$this->Cell(55,4,'Signature Over Printed Name',"TB",0,'C');
			$this->Cell(10,4,null,"RB",0,'L');
			$this->Ln();
		}
	}
?>