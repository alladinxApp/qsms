<?
	require_once('fpdf.php');
	class PrintBilling extends FPDF{
		public function setCompanyInfo($companyinfo){
			$this->companyname = $companyinfo['companyname'];
			$this->companyaddress = $companyinfo['companyaddress'];
			$this->companyno = $companyinfo['companyno'];
		}
		public function setCustomerInfo($customer){
			$this->customer = $customer;
		}
		public function setVehicleInfo($vehicle){
			$this->vehicle = $vehicle;
		}
		public function setUser($user){
			$this->user = $user;
		}
		public function setServiceMaster($servicemst){
			$this->servicemst = $servicemst;
		}
		public function setJobclock($jobclock){
			$this->jobclock = $jobclock;
		}
		public function setServiceDetail($servicedtl){
			$this->servicedtl = $servicedtl;
		}
		public function setBillingMaster($billingmst,$num_billingmst){
			$this->billingmst = $billingmst;
			$this->numbillingmst = $num_billingmst;
		}
		public function setPayments($amount,$discount){
			$this->total_amount = $amount;
			$this->total_discount = $discount;
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
			
			$this->SetFont('Courier','',8);
			$this->Cell(190,4,'Tel No. ' . $this->companyno,0,0,'C');
			$this->Ln();
			
			$this->SetFont('Courier','B',12);
			$this->Cell(190,4,'BILLING STATEMENT',0,0,'C');
			$this->Ln();
		}
		public function ImprovedTable(){
			$this->SetFont('Courier','B',9);
			$this->Cell(60,4,'Name: ',"LT",0,'L');
			$this->Cell(80,4,'Business Name/Style: ',"T",0,'L');
			$this->Cell(21,4,'TIN/SC-TIN: ',"TL",0,'L');
			$this->SetFont('Courier','',8);
			$this->Cell(29,4,$this->customer['tin'],"TR",0,'L');
			$this->Ln();
			$this->SetFont('Courier','',8);
			$this->Cell(2,4,null,"L",0,'L');
			$this->Cell(58,4,$this->customer['custname'],0,0,'L');
			$this->Cell(2,4,null,0,0,'L');
			$this->Cell(78,4,$this->customer['company'],0,0,'L');
			$this->SetFont('Courier','B',8);
			$this->Cell(26,4,'OSCA/PWD-ID No: ',"TL",0,'L');
			$this->SetFont('Courier','',8);
			$this->Cell(24,4,null,"TR",0,'L');
			$this->Ln();
			$this->SetFont('Courier','B',8);
			$this->Cell(140,4,'Address:',"TL",0,'L');
			$this->Cell(50,4,'Tel No: ',"TRL",0,'L');
			$this->Ln();
			$this->SetFont('Courier','',8);
			$this->Cell(2,4,null,"L",0,'L');
			$this->Cell(138,4,$this->customer['address'] . ' ' . $this->customer['city'] . ' ' . $this->customer['province'] . ' ' . $this->customer['zipcode'],"R",0,'L');
			$this->Cell(2,4,null,0,0,'L');
			$this->Cell(48,4,$this->customer['landline'],"R",0,'L');
			$this->Ln();
			$this->SetFont('Courier','B',8);
			$this->Cell(58,4,'S.O No:',"LTR",0,'L');
			$this->Cell(37,4,'Make:',"LTR",0,'L');
			$this->Cell(45,4,'Service Advisor:',"LTR",0,'L');
			$this->Cell(50,4,'Prepared By:',"LTR",0,'L');
			$this->Ln();
			$this->SetFont('Courier','',8);
			$this->Cell(58,4,null,"LR",0,'L');
			$this->Cell(37,4,$this->vehicle['make_desc'],"LR",0,'L');
			$this->Cell(45,4,$this->user,"LR",0,'L');
			$this->Cell(50,4,$this->user,"LR",0,'L');
			$this->Ln();
			$this->SetFont('Courier','B',7);
			$this->Cell(58,4,'Lic. Plate No/Conduction Sticker No:',"LTR",0,'L');
			$this->Cell(37,4,'Model:',"LTR",0,'L');
			$this->Cell(45,4,'Date Received:',"LTR",0,'L');
			$this->Cell(50,4,'Checked By:',"LTR",0,'L');
			$this->Ln();
			$this->SetFont('Courier','',8);
			$this->Cell(58,4,$this->vehicle['plate_no'],"LR",0,'L');
			$this->Cell(37,4,$this->vehicle['model_desc'],"LR",0,'L');
			$this->Cell(45,4,dateFormat($this->servicemst['wo_trans_date'],"M d, Y h:i"),"LR",0,'L');
			$this->Cell(50,4,$this->servicemst['tech_name'],"LR",0,'L');
			$this->Ln();
			$this->SetFont('Courier','B',8);
			$this->Cell(58,4,'Km. Reading	:',"LTR",0,'L');
			$this->Cell(37,4,'Product No:',"LTR",0,'L');
			$this->Cell(45,4,'Date Completed:',"LTR",0,'L');
			$this->Cell(50,4,'Approved By:',"LTR",0,'L');
			$this->Ln();
			$this->SetFont('Courier','',8);
			$this->Cell(58,4,$this->servicemst['odometer'],"LR",0,'L');
			$this->Cell(37,4,null,"LR",0,'L');
			$this->Cell(45,4,dateFormat($this->jobclock['job_end'],"M d, Y h:i"),"LR",0,'L');
			$this->Cell(50,4,null,"LR",0,'L');
			$this->Ln();
			$this->SetFont('Courier','B',8);
			$this->Cell(58,4,'Date Sold:',"LTR",0,'L');
			$this->Cell(37,4,'Engine No:',"LTR",0,'L');
			$this->Cell(45,4,'Date Released:',"LTR",0,'L');
			$this->Cell(50,4,'Credit & Coll:',"LTR",0,'L');
			$this->Ln();
			$this->SetFont('Courier','',8);
			$this->Cell(58,4,null,"LR",0,'L');
			$this->Cell(37,4,$this->vehicle['engine_no'],"LR",0,'L');
			$this->Cell(45,4,null,"LR",0,'L');
			$this->Cell(50,4,null,"LR",0,'L');
			$this->Ln();
			$this->SetFont('Courier','B',8);
			$this->Cell(58,4,'Service Type:',"LTR",0,'L');
			$this->Cell(37,4,'VIN:',"LTR",0,'L');
			$this->Cell(45,4,'Payment Term:',"LTR",0,'L');
			$this->Cell(50,4,'Cashier:',"LTR",0,'L');
			$this->Ln();
			$this->SetFont('Courier','',8);
			$this->Cell(58,4,null,"LRB",0,'L');
			$this->Cell(37,4,null,"LRB",0,'L');
			$this->Cell(45,4,$this->servicemst['payment_mode'],"LRB",0,'L');
			$this->Cell(50,4,$this->user,"LRB",0,'L');
			$this->Ln(10);
			
			$this->Cell(15,4,null,0,0,'R');
			$this->Cell(39,4,'Type',"B",0,'C');
			$this->Cell(1,4,null,0,0,'C');
			$this->Cell(59,4,'Description',"B",0,'C');
			$this->Cell(1,4,null,0,0,'C');
			$this->Cell(29,4,'Quantity/Hour',"B",0,'C');
			$this->Cell(1,4,null,0,0,'C');
			$this->Cell(30,4,'Amount',"B",0,'R');
			$this->Cell(15,4,null,0,0,'R');
			$this->Ln();
			
			$grand_total = 0;
			$cnt = 0;
			for($i=0;$i<count($this->servicedtl);$i++){
				switch($this->servicedtl[$i]['type']){
					case "accessory":
							$desc = $this->servicedtl[$i]['accessory_name'];
						break;
					case "job":
							$desc = $this->servicedtl[$i]['job_name'];
						break;
					case "material":
							$desc = $this->servicedtl[$i]['material_name'];
						break;
					case "parts":
							$desc = $this->servicedtl[$i]['parts_name'];
						break;
					default:
						break;
				}

				$type = null;
				if($this->servicedtl[$i]['type'] != $this->servicedtl[$i - 1]['type']){
					switch($this->servicedtl[$i]['type']){
						case "accessory":
								$type = "Lubricants";
							break;
						case "job":
								$type = "Labor";
							break;
						case "material":
								$type = "Materials";
							break;
						case "parts":
								$type = "Parts";
							break;
						default:
							break;
					}
				}

				$this->Cell(15,4,null,0,0,'R');
				$this->Cell(40,4,$type,0,0,'L');
				$this->Cell(60,4,$desc,0,0,'L');
				$this->Cell(30,4,$this->servicedtl[$i]['qty'],0,0,'C');
				$this->Cell(30,4,number_format($this->servicedtl[$i]['amount'],2),0,0,'R');
				$this->Cell(15,4,null,0,0,'R');
				$this->Ln();
				$grand_total += $this->servicedtl[$i]['amount'];
			}

			// for($i=0;$i<count($this->numbillingmst);$i++){
			// 	$this->Cell(30,4,null,0,0,'L');
			// 	$this->Cell(40,4,dateFormat($this->billingmst['wo_trans_date'],"M d, Y h:i"),0,0,'C');
			// 	$this->Cell(50,4,$this->billingmst['wo_refno'],0,0,'C');
			// 	$this->Cell(40,4,number_format($this->billingmst['total_amount'],2),0,0,'R');
			// 	$this->Cell(30,4,null,0,0,'R');
			// 	$this->Ln();
			// 	$grand_total += $this->billingmst['total_amount'];
			// }
			
			$this->Cell(15,4,null,0,0,'L');
			$this->Cell(40,4,null,0,0,'L');
			$this->SetFont('Courier','',10);
			$this->Cell(1,4,null,0,0,'C');
			$this->Cell(29,4,null,0,0,'C');
			$this->Cell(60,4,'Gross Total',0,0,'R');
			$this->Cell(30,4,number_format($grand_total,2),"T",0,'R');
			$this->Cell(15,4,null,0,0,'R');
			$this->Ln();

			$this->Cell(15,4,null,0,0,'L');
			$this->Cell(40,4,null,0,0,'L');
			$this->SetFont('Courier','',10);
			$this->Cell(1,4,null,0,0,'C');
			$this->Cell(29,4,null,0,0,'C');
			$this->Cell(60,4,'Discount',0,0,'R');
			$this->Cell(30,4,number_format($this->total_discount,2),0,0,'R');
			$this->Cell(15,4,null,0,0,'R');
			$this->Ln();

			$sub_total = $grand_total - $this->total_discount;
			$total_vat = $this->total_amount - $sub_total;

			$this->Cell(15,4,null,0,0,'L');
			$this->Cell(40,4,null,0,0,'L');
			$this->SetFont('Courier','',10);
			$this->Cell(1,4,null,0,0,'C');
			$this->Cell(29,4,null,0,0,'C');
			$this->Cell(60,4,'VAT',0,0,'R');
			$this->Cell(30,4,number_format($total_vat,2),0,0,'R');
			$this->Cell(15,4,null,0,0,'R');
			$this->Ln();

			$this->Cell(15,4,null,0,0,'L');
			$this->Cell(40,4,null,0,0,'L');
			$this->SetFont('Courier','B',10);
			$this->Cell(1,4,null,0,0,'C');
			$this->Cell(29,4,null,0,0,'C');
			$this->Cell(60,4,'Grand Total >>>>>>>>>>',0,0,'R');
			$this->Cell(30,4,number_format($this->total_amount,2),"T",2,'R');
			$this->Cell(15,4,null,0,0,'R');
			$this->Ln();
			
		}
		public function Footer(){
			$this->SetY(-53);
			
			$this->SetFont('Courier','',7);
			$this->Cell(95,4,null,"TRL",0,'L');
			$this->Cell(95,4,null,"TRL",0,'L');
			$this->Ln();
			$this->Cell(95,4,'     I/We hereby bind myself/ourselves to pay interest at 3% ',"LR",0,'L');
			$this->Cell(95,4,'RECEIVED AFORESAID VEHICLE IN GOOD ORDER AND CONDTION AND',"LR",0,'L');
			$this->Ln();
			$this->Cell(95,4,'per month on all overdue accounts and 33- 1/3% of the total ',"LR",0,'L');
			$this->Cell(95,4,'HEREBY CERTIFY THAT THE REPAIR HAVE BEEN MADE TO MY ENTIRE',"LR",0,'L');
			$this->Ln();
			$this->Cell(95,4,"amount due, but in no case less tha P5,000 as attorney's fee","LR",0,'L');
			$this->Cell(95,4,'SATISFACTION.',"LR",0,'L');
			$this->Ln();
			$this->Cell(95,4,'in case in any legal action arises from the transactions.',"LR",0,'L');
			$this->Cell(95,4,null,"LR",0,'L');
			$this->Ln();
			$this->Cell(95,4,'     I/We further authorized Aniec Car Repair Services to sell',"LR",0,'L');
			$this->Cell(95,4,null,"LR",0,'L');
			$this->Ln();
			$this->Cell(95,4,'at public auction the above described motor vehicle to settle',"LR",0,'L');
			$this->Cell(95,4,null,"LR",0,'L');
			$this->Ln();
			$this->Cell(95,4,'the foregoing obligaton if I/We fail to get the said vehicle',"LR",0,'L');
			$this->Cell(95,4,null,"LR",0,'L');
			$this->Ln();
			$this->Cell(95,4,'after the lapse or 30 days following receipt of notice',"LR",0,'L');
			$this->Cell(10,4,null,"L",0,'L');
			$this->SetFont('Courier','B',12);
			$this->Cell(75,4,$this->customer['custname'],"B",0,'C');
			$this->Cell(10,4,null,"R",0,'L');
			$this->Ln();
			$this->SetFont('Courier','',7);
			$this->Cell(95,4,'of completion of repair work.',"LR",0,'L');
			$this->Cell(95,4,"Customer's Signature Over Printed Name","LR",0,'C');
			$this->Ln();
			$this->Cell(95,4,null,"LRB",0,'L');
			$this->Cell(95,4,null,"LRB",0,'L');
			$this->Ln();
		}
	}
?>