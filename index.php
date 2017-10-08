<head>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


<link rel="stylesheet" href="/css/bootstrap.min.css">

<!-- -->
<link rel="stylesheet" type="text/css" href="/css/dataTables.bootstrap.min.css">

<link rel="stylesheet" type="text/css" href="/css/custom.css">
</head>

<body>

<div class="container" >
	<div class="chart-container" style="width:40%">
		<canvas id="myChart" width="400" height="400"></canvas>
	</div>

	<button class="btn btn-primary btn-chart" data-type='horizontalBar'>Barre</button>
	<button class="btn btn-primary btn-chart" data-type='pie'>Torta</button>

<table class="table" id="results">
	<thead>
	<tr><td>Domain</td><td>Base Domain</td><td>Live</td><td>Redirect</td><td>Valid HTTPS</td><td>Domain Enforces HTTPS</td></tr>
	</thead>
	<tbody>
	<?php

		$file = fopen('result.csv', 'r');

		$i=1;
		$count_valid_https = 0;
		while ( $row = fgetcsv($file, 2048, ",")) {

			echo 	"<tr>";
			foreach ($row as $key => $field ) {
				switch ($field) {
					case 'True':
						$valore = 'Si';
						if ($key == 4) $count_valid_https++;	
						break;
					case 'False':
						$valore = 'No';	
						break;
					default:
						$valore = $field;
						break;
				}
			 ?>
				<td><?php echo $valore ?></td>

			<?php } ?>

			</tr>

	<?php }?>
	</tbody>
	</table>







<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script type="text/javascript" src="http://getbootstrap.com/assets/js/vendor/popper.min.js"></script>

<!--
<script type="text/javascript" src="http://getbootstrap.com/dist/js/bootstrap.min.js"></script>
-->


<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>


<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.min.js"></script>

<script type="text/javascript">
	$(document).ready(function() {

		// console.log($('#results'));
	    var oTable = $('#results').DataTable();

	oTable.on('search.dt', function() {
    	//filtered rows data as arrays
    	//console.log(oTable.rows( { filter : 'applied'} ).data());   

    	
		updateChart( oTable);

	});


	$('.btn-chart').on('click', function() {

			var new_type = $(this).attr('data-type');
			$('.btn-chart').prop('disabled', false);
			$(this).prop('disabled', true);
			//var new_type = (window.myChart.type == 'pie') ? 'bar' : 'pie';

			console.log( 'new type = ' + new_type)
		updateChart( oTable, new_type);
	});

	});
</script>



</div>
<script>



function updateChart( oTable, type_chart = 0) {

	console.log( 'chiamata aggiorna_chart');

	var filter_results = oTable.rows( { filter : 'applied'} ).data();

    	var filter_results_num = oTable.rows( { filter : 'applied'} ).data().length;
		var valid_https= '';
		var count_valid_https = 0;
	
		filter_results.each( function(riga) {
			valid_https =  riga[4];
			if (valid_https == 'Si') count_valid_https++;

		});


	
	var new_data = [count_valid_https, parseInt(filter_results_num - count_valid_https)];

	if (type_chart) {

		$('#myChart').remove();

		$('.chart-container').append('<canvas id="myChart" width="400" height="400"></canvas>');
		
		createChart(new_data, type_chart);

	}
	else {
		window.myChart.data.datasets[0].data = new_data;
		window.myChart.update();
	}


}

function changeChartType() {

	var current_type = window.myChart.type;

	
	window.myChart.type = new_type;
	window.myChart.update();
}



var count_valid_https = <?php echo $count_valid_https; ?>;

var count_not_valid_https = 99 -parseInt(count_valid_https);

var arr_data = [count_valid_https, count_not_valid_https];

function createChart( arr_data, type_chart) {
	var ctx = document.getElementById("myChart");

	var param_options, stepSize;

var count_valid_https = arr_data[0];
var count_not_valid_https = arr_data[1];

var tot_values = parseInt( count_valid_https + count_not_valid_https);

stepSize = (tot_values> 30) ? 10 : 1;

if ( type_chart == 'horizontalBar') {

	param_options ={
			title: {
	            display: true,
	            text: 'HTTPS valido'
	        	},
		scales: {
        xAxes: [{
            ticks: {
                max: tot_values,
                min: 0,
                stepSize: stepSize
            }
        }]
    	}
    }
} else {
		param_options =	{
			title: {
	            display: true,
	            text: 'HTTPS valido'
	        	}
	        }
	}

	window.myChart = new Chart(ctx, {
	    type: type_chart,
	    data: {
	        labels: [ "Si", "No", ],
	        datasets: [{
	            label: '# di domini',
	            data: arr_data,
	            backgroundColor: [
	                'rgb(67, 224, 88)',
	                'rgba(54, 162, 235, 0.2)',
	            ],
	            borderColor: [
	                'rgb(53, 186, 71)',
	                'rgba(54, 162, 235, 1)',
	            ],
	            borderWidth: 1
	        }]
	        
	    },
	    options: param_options
	});

}

createChart( arr_data, 'pie');

</script>


</body>

