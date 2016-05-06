<?
	require_once('fpdf.php');
	class PrintPayment extends FPDF{
		public function setImageLogo($img){
			$this->logo = $img;
		}
		public function setCompanyInfo($companyinfo){
			$this->companyname = $companyinfo['companyname'];
			$this->companyaddr = $companyinfo['companyaddr'];
			$this->companytelno = $companyinfo['companytelno'];
		}
		public function setDetails($dtl){
			$this->dtl = $dtl;
		}
		public function setMaster($mst){
			$this->mst = $mst;
		}
		public function Header(){
			$this->Image($this->logo, 10, 0, 55);
			
			$this->SetFont('Courier','B',30);
			$this->Cell(190,4,'CHECK VOUCHER',0,0,'R');
			$this->Ln(10);
			
			$this->SetFont('Courier','B',8);
			$this->Cell(20,4,'Address',0,0,'L');
			$this->Cell(5,4,':',0,0,'C');
			$this->SetFont('Courier','',8);
			$this->Cell(115,4,$this->companyaddr,0,0,'L');
			$this->SetFont('Courier','B',8);
			$this->Cell(20,4,'CV Reference',0,0,'L');
			$this->Cell(5,4,':',0,0,'C');
			$this->SetFont('Courier','',8);
			$this->Cell(25,4,$this->mst['cv_reference_no'],0,0,'L');
			$this->Ln();

			$this->SetFont('Courier','B',8);
			$this->Cell(20,4,'Telephone No',0,0,'L');
			$this->Cell(5,4,':',0,0,'C');
			$this->SetFont('Courier','',8);
			$this->Cell(115,4,$this->companytelno,0,0,'L');
			$this->SetFont('Courier','B',8);
			$this->Cell(20,4,'Date',0,0,'L');
			$this->Cell(5,4,':',0,0,'C');
			$this->SetFont('Courier','',8);
			$this->Cell(25,4,$this->mst['payment_date'],0,0,'L');
			$this->Ln();

			$this->SetFont('Courier','B',8);
			$this->Cell(20,4,'Email',0,0,'L');
			$this->Cell(5,4,':',0,0,'C');
			$this->SetFont('Courier','',8);
			$this->Cell(95,4,"",0,0,'L');
			$this->Ln(6);

			$this->SetFont('Courier','B',10);
			$this->Cell(95,4,'SUPPLIER',"LTR",0,'L');
			$this->Cell(95,4,'DELIVER TO',"LTR",0,'L');
			$this->Ln();
			
			$this->SetFont('Courier','B',10);
			$this->Cell(20,4,'Name',"L",0,'L');
			$this->Cell(5,4,':',0,0,'C');
			$this->SetFont('Courier','',10);
			$this->Cell(70,4,$this->mst['supplier_name'],"R",0,'L');
			$this->SetFont('Courier','B',10);
			$this->Cell(20,4,'Name',"L",0,'L');
			$this->Cell(5,4,':',0,0,'C');
			$this->SetFont('Courier','',10);
			$this->Cell(70,4,$this->mst['deliver_to'],"R",0,'L');
			$this->Ln();

			$this->SetFont('Courier','B',10);
			$this->Cell(20,4,'Address',"L",0,'L');
			$this->Cell(5,4,':',0,0,'C');
			$this->SetFont('Courier','',10);
			$this->Cell(70,4,$this->mst['supplier_addr'],"R",0,'L');
			$this->SetFont('Courier','B',10);
			$this->Cell(20,4,'Address',"L",0,'L');
			$this->Cell(5,4,':',0,0,'C');
			$this->SetFont('Courier','',10);
			$this->Cell(70,4,$this->mst['delivery_addr'],"R",0,'L');
			$this->Ln();

			$this->SetFont('Courier','B',10);
			$this->Cell(20,4,'Tel No',"LB",0,'L');
			$this->Cell(5,4,':','B',0,'C');
			$this->SetFont('Courier','',10);
			$this->Cell(70,4,$this->mst['supplier_phone'],"RB",0,'L');
			$this->SetFont('Courier','B',10);
			$this->Cell(20,4,'Tel No',"LB",0,'L');
			$this->Cell(5,4,':','B',0,'C');
			$this->SetFont('Courier','',10);
			$this->Cell(70,4,'',"RB",0,'L');
			$this->Ln(5);

			$this->SetFont('Courier','B',8);
			$this->Cell(190,4,'Special Instruction',0,0,'L');
			$this->Ln();

			$this->SetFont('Courier','',8);
			$this->Cell(10,4,'',0,0,'L');
			$this->Cell(180,4,$this->mst['special'],0,0,'L');
			$this->Ln(8);
		}
		
		public function ImprovedTable(){
			$this->SetFont('Courier','B',10);
			$this->Cell(30,4,'ITEM CODE',"LTRB",0,'C');
			$this->Cell(60,4,'DESCRIPTION',"LTRB",0,'C');
			$this->Cell(20,4,'PRICE',"LTRB",0,'C');
			$this->Cell(20,4,'POed QTY',"LTRB",0,'C');
			$this->Cell(20,4,'RCVD QTY',"LTRB",0,'C');
			$this->Cell(20,4,'VARIANCE',"LTRB",0,'C');
			$this->Cell(20,4,'TOTAL',"LTRB",0,'C');
			$this->Ln();

			$this->totalqty = 0;
			$this->totalrrqty = 0;
			$this->totalvar = 0;
			$this->total = 0;
			for($i=0;$i<count($this->dtl);$i++){
				$this->SetFont('Courier','',8);
				$this->Cell(30,4,$this->dtl[$i]['item_code'],"LR",0,'C');
				$this->Cell(60,4,$this->dtl[$i]['item_desc'],"LR",0,'L');
				$this->Cell(20,4,number_format($this->dtl[$i]['price'],2),"LR",0,'R');
				$this->Cell(20,4,$this->dtl[$i]['qty'],"LR",0,'C');
				$this->Cell(20,4,$this->dtl[$i]['rr_qty'],"LR",0,'C');
				$this->Cell(20,4,$this->dtl[$i]['variance'],"LR",0,'C');
				$this->Cell(20,4,number_format($this->dtl[$i]['total'],2),"LR",0,'R');
				$this->Ln();

				$this->totalqty += $this->dtl[$i]['qty'];
				$this->totalrrqty += $this->dtl[$i]['rr_qty'];
				$this->totalvar += $this->dtl[$i]['variance'];
				$this->total += $this->dtl[$i]['total'];
			}

			$this->SetFont('Courier','B',8);
			$this->Cell(110,8,'TOTAL >>>>>>>>>>',"LTRB",0,'R');
			$this->Cell(20,8,$this->totalqty,"LTRB",0,'C');
			$this->Cell(20,8,$this->totalrrqty,"LTRB",0,'C');
			$this->Cell(20,8,$this->totalvar,"LTRB",0,'C');
			$this->Cell(20,8,number_format($this->total,2),"LTRB",0,'R');
			$this->Ln();

			$this->Cell(110,0,'',"LRB",0,'C');
			$this->Cell(20,0,'',"LRB",0,'C');
			$this->Cell(20,0,'',"LRB",0,'C');
			$this->Cell(20,0,'',"LRB",0,'C');
			$this->Cell(20,0,'',"LRB",0,'C');
			$this->Ln(4);

			$this->SetFont('Courier','B',8);
			$this->Cell(135,4,'',0,0,'C');
			$this->Cell(25,4,'Sub-Total',0,0,'L');
			$this->SetFont('Courier','',8);
			$this->Cell(30,4,number_format($this->mst['sub_total'],2),0,0,'R');
			$this->Ln();

			$this->SetFont('Courier','B',8);
			$this->Cell(135,4,'',0,0,'C');
			$this->Cell(25,4,'Discount',0,0,'L');
			$this->SetFont('Courier','',8);
			$this->Cell(30,4,number_format($this->mst['discount'],2),0,0,'R');
			$this->Ln();

			$this->SetFont('Courier','B',8);
			$this->Cell(135,4,'',0,0,'C');
			$this->Cell(25,4,'VAT 12%',0,0,'L');
			$this->SetFont('Courier','',8);
			$this->Cell(30,4,number_format($this->mst['vat'],2),0,0,'R');
			$this->Ln();

			$this->SetFont('Courier','B',8);
			$this->Cell(135,6,'',0,0,'C');
			$this->Cell(25,6,'Total Amount',"T",0,'L');
			$this->SetFont('Courier','',8);
			$this->Cell(30,6,number_format($this->mst['total_amount'],2),"T",0,'R');
			$this->Ln();
		}
		
		public function Footer(){
			$this->SetY(-30);
			$this->SetFont('Courier','B',9);
			$this->Cell(50,4,'Prepared by:',0,0,'L');
			$this->Cell(20,4,'',0,0,'L');
			$this->Cell(50,4,'Recommended by:',0,0,'L');
			$this->Cell(20,4,'',0,0,'L');
			$this->Cell(50,4,'Approved by:',0,0,'L');
			$this->Ln(8);

			$this->Cell(50,4,'',"B",0,'L');
			$this->Cell(20,4,'',0,0,'L');
			$this->Cell(50,4,'',"B",0,'L');
			$this->Cell(20,4,'',0,0,'L');
			$this->Cell(50,4,'',"B",0,'L');
			$this->Ln();
		}
	}
?>