@section('title', 'View Pin Stock')
@section('content')

<?php
$baseUrl = URL::to('/');
?>
<script type="text/javascript">

	$(document).ready(function(){
		var link;
		var replaceField = '';

		 $(".confirm").click(function(e) {
		 	link = this;
		 });
		 $(".confirm").confirm({
		     text: "Terminal's serial is <span class='replaceField'></span>. <br /> Are you sure you want delete?",
		     title: "Confirmation!",
		     confirm: function(button) {
		         // do something
		     	$(link).find("form").submit();
		     },
		     cancel: function(button) {
		         // do something
		     },
		     confirmButton: "Yes",
		     cancelButton: "No",
		     post: true
		 });
		 $(".confirm").click(function(e) {
		 	link = this;
		  	replaceField = $(this).attr("replaceField");
		  	$(".replaceField").html(replaceField);
		 });



		 $(function(){
		    $('[data-method]').append(function(){
		        return "\n"+
		        "<form action='"+$(this).attr('href')+"' method='POST' style='display:none'>\n"+
		        "   <input type='hidden' name='_method' value='"+$(this).attr('data-method')+"'>\n"+
		        "</form>\n"
		    })
		    .removeAttr('href')
		    .attr('style','cursor:pointer;');
		});


	});

</script>
@include('layouts.partial.render-message')
<h4>Summary Pin Code Stock Report</h4>
<div class="row">
	<div class="col-md-12">
		<!-- Default panel contents -->
		<div class="panel panel-default">
		  <div class="panel-heading">
			{{ Form::open(array('url' => 'pin-stocks/summary-pin-code-stock')) }}
  	 			<div class="row">
	  	 			<div class="col-md-2">
  	 					<label>Date From:</label>
  	 				</div>
  	 				<div class="col-md-2">
  	 					<input type="text" id="datepicker_start" name="start_date" value="<?php if(Input::has('start_date')) {echo  Input::get('start_date');} else{echo date("Y-m-j");} ?>" />
  	 				</div>
  	 				<div class="col-md-2">
  	 					<label>Date To:</label>
  	 				</div>
  	 				<div class="col-md-2">
  	 					<input type="text" id="datepicker_end" name="end_date" value="<?php if(Input::has('end_date')) {echo  Input::get('end_date');} else{echo date("Y-m-j");} ?>" />
  	 				</div>

  	 			</div>
			  <div class="row">
				  <div class="col-md-2">
					  <label>Operator:</label>
				  </div>
				  <div class="col-md-2">
					  <?php $operator = ''; if (Input::has('operator_id')){$operator = Input::get('operator_id');}
					  //$operators = array('1'=>'Smart','2'=>'Metfone');
					  ?>
					  {{ Form::select('operator_id',array(''=>'All')+$operators, $operator, array('id' => 'operator__id')) }}
				  </div>
				  <div class="col-md-2">
					  <input class="btn btn-primary" type="submit" name="search_terminal" value="Search" />
					  <input class="btn btn-primary export" type="submit" name="export_excel" value="Export To Excel" />
				  </div>
			  </div>
  	 		{{ Form::close() }}
		  </div>
		 <div>
			 
			 @foreach($pinsArray as $key => $pins)
			 
					 <?php
					 	$instock = 0;
					 	$beginningTotal = 0;
					 	$salesTotal = 0;
					 	$endingTotal = 0;
					 ?>
			 
					 <table class="table table-bordered">
						 <thead>
						 	 <tr>
								 <h4>{{$key}}</h4>
								 </br>
							 </tr>
							 <tr>
								 <th>Value</th>
								 <th>In Stock</th>
								 <th>Beginning Stock</th>
								 <th>Sales</th>
								 <th>Stock Available</th>
							 </tr>
						 </thead>
					 <tbody>
				 @foreach($pins as $pin)
					 <?php
					 $color = "";
					 if(		$pin['face']==1 && $pin['ending_stock']<=200
							 || $pin['face']==2 && $pin['ending_stock']<=100
							 || $pin['face']==5 && $pin['ending_stock']<=50
							 || $pin['face']==20 && $pin['ending_stock']<=10
							 || $pin['face']==50 && $pin['ending_stock']<=2
							 || $pin['face']==10 && $pin['ending_stock']<=0
					 ) {
						 $color =  "#FF0000";
					 } else {
						 $color =  "#FFFFFF";
					 }

							// print_r($pin);
							// die();
					 ?>

					 <tr bgcolor="{{$color}}">
						 <td>{{$pin['face']}}</td>
						 <td>{{$pin['instock']}}</td>
						 <td>{{$pin['beginning_stock']}}</td>
						 <td>{{$pin['sales_stock']}}</td>
						 <td>{{$pin['ending_stock']}}</td>
						 <?php
						 $instock = $instock + $pin['instock'];
						 $beginningTotal = $beginningTotal + $pin['beginning_stock'];
						 $salesTotal = $salesTotal + $pin['sales_stock'];
						 $endingTotal = $endingTotal + $pin['ending_stock'];
						 ?>
					 </tr>
				 @endforeach
                 <tr>
                     <th>Total</td>
                     <th>{{$instock}}</th>
                     <th>{{$beginningTotal}}</th>
                     <th>{{$salesTotal}}</th>
                     <th>{{$endingTotal}}</th>
                 </tr>
			 @endforeach
				 </tbody>
			 </table>
		 </div>
		</div>
		@stop
	</div>
</div>