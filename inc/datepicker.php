<link rel="stylesheet" type="text/css" media="screen" href="style/datePicker.css">
<link rel="stylesheet" type="text/css" media="screen" href="style/demo.css">

<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery-1.4.1.min.js"></script>
<script type="text/javascript" src="js/date.js"></script>
<script type="text/javascript" src="js/jquery.datePicker.js"></script>

<script type="text/javascript">
$(function(){
Date.firstDayOfWeek = 0;
Date.format = 'mm/dd/yyyy';
$('.date-pick').datePicker({startDate:'01/01/1950'})
$('#txtdatefrom').bind(
	'dpClosed',
	function(e, selectedDates)
	{
		var d = selectedDates[0];
		if (d) {
			d = new Date(d);
			$('#txtdateto').dpSetStartDate(d.addDays(0).asString());
		}
	}
);
$('#txtdateto').bind(
	'dpClosed',
	function(e, selectedDates)
	{
		var d = selectedDates[0];
		if (d) {
			d = new Date(d);
			$('#txtdatefrom').dpSetEndDate(d.addDays().asString());
		}
	}
);
$('#birthday').bind(
	'dpClosed',
	function(e, selectedDates)
	{
		var d = selectedDates[0];
		if (d) {
			d = new Date(d);
			//$('#txtdatefrom').dpSetEndDate(d.addDays().asString());
		}
	}
);
});
</script>
