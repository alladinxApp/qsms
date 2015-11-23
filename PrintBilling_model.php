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
		public function setServiceAdviser($serviceadviser){
			$this->serviceadviser = $serviceadviser;
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
			$this->Ln(40);
		}
		public function ImprovedTable(){
			$this->SetFont('Courier','',9);
			$this->Cell(190,4,'',0,0,'L');
			$this->Ln();
			$this->Cell(2,4,null,0,0,'L');
			$this->Cell(88,4,$this->customer['custname'],0,0,'L');
			$this->Cell(2,4,null,0,0,'L');
			$this->Cell(98,4,$this->customer['company'],0,0,'L');
			$this->Ln();
			$this->Cell(190,4,'',0,0,'L');
			$this->Ln();
			$this->Cell(2,4,null,0,0,'L');
			$this->Cell(138,4,$this->customer['address'] . ' ' . $this->customer['city'] . ' ' . $this->customer['province'] . ' ' . $this->customer['zipcode'],0,0,'L');
			$this->Cell(2,4,null,0,0,'L');
			$this->Cell(48,4,$this->customer['tin'],0,0,'L');
			$this->Ln();
			$this->Cell(190,8,null,0,0,'L');
			$this->Ln();
			$this->Cell(2,4,null,0,0,'L');
			$this->Cell(138,4,null,0,0,'L');
			$this->Cell(2,4,null,0,0,'L');
			$this->Ln();
			$this->Cell(190,4,null,0,0,'L');
			$this->Ln();
			$this->Cell(58,4,null,0,0,'L');
			$this->Cell(2,4,null,0,0,'L');
			$this->Cell(35,4,$this->vehicle['make_desc'],0,0,'L');
			$this->Cell(2,4,null,0,0,'L');
			$this->Cell(43,4,$this->serviceadviser,0,0,'L');
			$this->Cell(48,4,$this->customer['landline'],0,0,'R');
			$this->Ln();
			$this->Cell(190,4,null,0,0,'L');
			$this->Ln();
			$this->Cell(2,4,null,0,0,'L');
			$this->Cell(56,4,$this->vehicle['plate_no'],0,0,'L');
			$this->Cell(2,4,null,0,0,'L');
			$this->Cell(35,4,$this->vehicle['model_desc'],0,0,'L');
			$this->Cell(2,4,null,0,0,'L');
			$this->Cell(43,4,dateFormat($this->servicemst['wo_trans_date'],"M d, Y h:i"),0,0,'L');
			$this->Cell(48,4,$this->user,0,0,'R');
			$this->Cell(48,4,$this->servicemst['tech_name'],0,0,'L');
			$this->Ln(8);
			$this->Cell(190,4,null,0,0,'L');
			$this->Ln();
			$this->Cell(2,4,null,0,0,'L');
			$this->Cell(56,4,$this->servicemst['odometer'],0,0,'L');
			$this->Cell(2,4,null,0,0,'L');
			$this->Cell(35,4,null,0,0,'L');
			$this->Cell(2,4,null,0,0,'L');
			$this->Cell(43,4,dateFormat($this->jobclock['job_end'],"M d, Y h:i"),0,0,'L');
			$this->Cell(2,4,null,0,0,'L');
			$this->Cell(48,4,null,0,0,'L');
			$this->Ln();
			$this->Cell(190,4,null,0,0,'L');
			$this->Ln();
			$this->Cell(2,4,null,0,0,'L');
			$this->Cell(56,4,null,0,0,'L');
			$this->Cell(2,4,null,0,0,'L');
			$this->Cell(35,4,$this->vehicle['engine_no'],0,0,'L');
			$this->Cell(2,4,null,0,0,'L');
			$this->Cell(43,4,null,0,0,'L');
			$this->Cell(2,4,null,0,0,'L');
			$this->Cell(48,4,null,0,0,'L');
			$this->Ln();
			$this->Cell(190,4,null,0,0,'L');
			$this->Ln();
			$this->Cell(2,4,null,0,0,'L');
			$this->Cell(56,4,null,0,0,'L');
			$this->Cell(2,4,null,0,0,'L');
			$this->Cell(35,4,null,0,0,'L');
			$this->Cell(2,4,null,0,0,'L');
			$this->Cell(43,4,$this->servicemst['payment_mode'],0,0,'L');
			$this->Cell(2,4,null,0,0,'L');
			$this->Cell(48,4,'',0,0,'L');
			$this->Ln(10);
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
			$this->Ln(10);
			
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
			$vat = ($grand_total * 0.12);
			$total_vat = $this->total_amount - $sub_total;
			$totalamnt = ($sub_total + $vat);

			$this->Cell(15,4,null,0,0,'L');
			$this->Cell(40,4,null,0,0,'L');
			$this->SetFont('Courier','',10);
			$this->Cell(1,4,null,0,0,'C');
			$this->Cell(29,4,null,0,0,'C');
			$this->Cell(60,4,'VAT',0,0,'R');
			$this->Cell(30,4,number_format($vat,2),0,0,'R');
			$this->Cell(15,4,null,0,0,'R');
			$this->Ln();

			$this->Cell(15,4,null,0,0,'L');
			$this->Cell(40,4,null,0,0,'L');
			$this->SetFont('Courier','B',10);
			$this->Cell(1,4,null,0,0,'C');
			$this->Cell(29,4,null,0,0,'C');
			$this->Cell(60,4,'Grand Total >>>>>>>>>>',0,0,'R');
			$this->Cell(30,4,number_format($totalamnt,2),"T",2,'R');
			$this->Cell(15,4,null,0,0,'R');
			$this->Ln();
			
		}
		public function Footer(){
			$this->SetY(-29);
			
			$this->Cell(95,24,null,0,0,'L');
			$this->Ln();
			$this->Cell(105,4,null,0,0,'L');
			$this->SetFont('Courier','B',12);
			$this->Cell(75,4,$this->customer['custname'],0,0,'C');
			$this->Cell(10,4,null,0,0,'L');
			$this->Ln();
			$this->Cell(95,16,null,0,0,'L');
			$this->Ln();
		}
	}
?>