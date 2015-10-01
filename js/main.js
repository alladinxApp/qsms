jQuery(document).ready(function($){
	$("table.list").dataTable({
        "sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>"
    });
	
	//CHOSEN JQUERY FOR WORKORDER PARTS USED
	$("select[name='parts_used']").chosen();
	$("select[name='parts_used']").change(function(){
		var success_cb = function(json){
			$("input[name='parts_subtotal']").val(json.sum);
			$("input[name='parts_subtotal']").trigger("keyup");
		}
		$.ajax({
		type: "POST",
		url: "http.php?do=getPartsSubtotal",
		data: "ids="+$(this).val(),
		success: success_cb,
		dataType: "json"
		});
	});
	
	//WORK ORDER CREATION AND COMPLETION DATE
	if( $("input[name='creation_date']").length>0 ){
		$("input[name='creation_date']").datepicker({ dateFormat: "yy-mm-dd" });
		$("input[name='completion_date']").datepicker({ dateFormat: "yy-mm-dd" });
	}
	
	//EQUIPMENT DATES
	if( $("input[name='purchase_date']").length>0 ){
		$("input[name='purchase_date']").datepicker({ dateFormat: "yy-mm-dd" });
		$("input[name='insurance_applied_date']").datepicker({ dateFormat: "yy-mm-dd" });
		$("input[name='insurance_expiration_date']").datepicker({ dateFormat: "yy-mm-dd" });
	}
	
	if( $("input[name='labor_subtotal']").length>0 ){
		$("input[name='labor_subtotal'], input[name='misc_subtotal'], input[name='parts_subtotal'], input[name='discount']").keyup(function(){
			var inputVal = $(this).val();
			var roundToDecimal = 2;
			while( (inputVal.length>0 && inputVal!='-' && isNaN(inputVal)) || (inputVal.substring(inputVal.indexOf('.')+1).length>roundToDecimal && inputVal.indexOf('.')!=-1) ){
				$(this).val( inputVal.substring(0, inputVal.length-1) );
				inputVal = $(this).val();
			}
			
			woCompleteTotalCost();
		});
		$("input[name='labor_subtotal'], input[name='misc_subtotal'], input[name='parts_subtotal'], input[name='discount']").blur(function(){
			var inputVal = $(this).val();
			var roundToDecimal = 2;
			while( (inputVal.length>0 && inputVal!='-' && isNaN(inputVal)) || (inputVal.substring(inputVal.indexOf('.')+1).length>roundToDecimal && inputVal.indexOf('.')!=-1) ){
				$(this).val( inputVal.substring(0, inputVal.length-1) );
				inputVal = $(this).val();
			}
			
			woCompleteTotalCost();
		});
	}
	
	if( $("input.xInteger").length>0 ){
		$("input.xInteger").keyup(function(){
			var inputVal = $(this).val();
			while( inputVal.length>0 && inputVal!='-' && (parseFloat(inputVal) != parseInt(inputVal) || isNaN(inputVal)) ){
				$(this).val( inputVal.substring(0, inputVal.length-1) );
				inputVal = $(this).val();
			}
		});
		$("input.xInteger").blur(function(){
			var inputVal = $(this).val();
			while( inputVal.length>0 && inputVal!='-' && (parseFloat(inputVal) != parseInt(inputVal) || isNaN(inputVal)) ){
				$(this).val( inputVal.substring(0, inputVal.length-1) );
				inputVal = $(this).val();
			}
		});
	}
	
	if( $("input.xDouble").length>0 ){
		$("input.xDouble").keyup(function(){
			var inputVal = $(this).val();
			var roundToDecimal = 2;
			while( (inputVal.length>0 && inputVal!='-' && isNaN(inputVal)) || (inputVal.substring(inputVal.indexOf('.')+1).length>roundToDecimal && inputVal.indexOf('.')!=-1) ){
				$(this).val( inputVal.substring(0, inputVal.length-1) );
				inputVal = $(this).val();
			}
		});
		$("input.xDouble").blur(function(){
			var inputVal = $(this).val();
			var roundToDecimal = 2;
			while( (inputVal.length>0 && inputVal!='-' && isNaN(inputVal)) || (inputVal.substring(inputVal.indexOf('.')+1).length>roundToDecimal && inputVal.indexOf('.')!=-1) ){
				$(this).val( inputVal.substring(0, inputVal.length-1) );
				inputVal = $(this).val();
			}
		});
	}
});

function woCompleteTotalCost(){
	var labor_subtotal = Number($("input[name='labor_subtotal']").val());
	var mis_subtotal = Number($("input[name='misc_subtotal']").val());
	var parts_subtotal = Number($("input[name='parts_subtotal']").val());
	var discount = Number($("input[name='discount']").val());
	
	// 12% of subtotal
	//var tax_subtotal = Number($("input[name='tax_subtotal']").val());
	var tax_subtotal = Number(labor_subtotal + mis_subtotal + parts_subtotal - discount) * .12;
	$("input[name='tax_subtotal']").val(tax_subtotal);
	
	var total_cost = labor_subtotal + mis_subtotal + parts_subtotal - discount + tax_subtotal;
	$("input[name='total_cost']").val(total_cost);
}

function veh_report(){
	var category = $("#veh_report_category").val();
	var customer = $("#veh_report_customer").val();
	window.location.href = 'report_vehicle.php?ca='+category+'&cu='+customer;
}

function wo_report(){
	var wo_report_status = $("#wo_report_status").val();
	window.location.href = 'report_workorder.php?stat='+wo_report_status;
}