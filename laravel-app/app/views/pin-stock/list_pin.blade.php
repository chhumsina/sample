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
<h4>View Pin Stock</h4>
<div class="row">
	<div class="col-md-12">
		<!-- Default panel contents -->
		<div class="panel panel-default">
		  <div class="panel-heading">
			{{ Form::open(array('url' => 'pin-stocks/view-pin-stock')) }}
				<div class="row">
					<div class="col-md-2">
  	 					<label>Operator:</label>
  	 				</div>
  	 				<div class="col-md-2">
			  	 	    <?php $operator = ''; if (Input::has('operator_id')){$operator = Input::get('operator_id');}
						//$operators = array('1'=>'Smart','2'=>'Metfone');
			  	 	   	?>
			  	 	    {{ Form::select('operator_id', array('' => 'Please Select')+$operators, $operator, array('id' => 'operator__id')) }}
  	 				</div>
  	 				<div class="col-md-2">
  	 					<label>Face Value:</label>
  	 				</div>
  	 				<div class="col-md-2">
			  	 	    <?php $faceValue = ''; if (Input::has('face')){$faceValue = Input::get('face');}
			  	 	    	  $faces = array('1'=>'1','2'=>'2','5'=>'5','10'=>'10','20'=>'20','50'=>'50');
			  	 	   	?>
			  	 	    {{ Form::select('face', array('' => 'Please Select')+$faces, $faceValue, ['id' => 'face']) }}
  	 				</div>
  	 			</div>
  	 			<div class="row">
  	 				<div class="col-md-2">
  	 					<label>Status:</label>
  	 				</div>
  	 				<div class="col-md-2">
			  	 	    <?php $status = ''; if (Input::has('status')){$status = Input::get('status');}
			  	 	    	  $listStatus = array('0'=>'Printed','1'=>'Available');
			  	 	   	?>
			  	 	    {{ Form::select('status', array('' => 'Please Select')+$listStatus, $status, ['id' => 'status']) }}
  	 				</div>
  	 				<div class="col-md-2">
  	 					<label>Check Pin Exprie:</label>
  	 				</div>
  	 				<div class="col-md-2">
			  	 	    <?php
			  	 	    	$checkExpire = ''; if (Input::has('check_exprie')){$checkExpire = Input::get('check_exprie');}
							if ($checkExpire == 1) {
								echo Form::checkbox('check_exprie', '1', true);
							} else {
								echo Form::checkbox('check_exprie', '1');
							}
			  	 	   	?>
  	 				</div>
  	 			</div>
  	 			<div class="row">
	  	 			<div class="col-md-2">
  	 					<label>Date From:</label>
  	 				</div>	  	 				
  	 				<div class="col-md-2">
  	 					<input type="text" id="datepicker_start_datetime" name="start_date" value="<?php if(Input::has('start_date')) {echo  Input::get('start_date');}//else{echo date("Y-m-j").' 00:00';} ?>" />
  	 				</div>
  	 				<div class="col-md-2">
  	 					<label>Date To:</label>
  	 				</div>
  	 				<div class="col-md-2">
  	 					<input type="text" id="datepicker_end_datetime" name="end_date" value="<?php if(Input::has('end_date')) {echo  Input::get('end_date');}//else{echo date("Y-m-j").' 23:59';} ?>" />
  	 				</div>
  	 				<div class="col-md-1">
  	 					<input type="submit" name="search_terminal" value="Search" />
  	 				</div>
  	 			</div>
  	 		{{ Form::close() }}
		  </div>	
		  <!-- Table -->
		@if ($pins->count())
		    <table class="table table-striped table-bordered">
		        <thead>
		            <tr>
		                <th>No</th>
				        <th>Pin ID</th>
				        <th>Pin</th>
				        <th>Operator</th>
				        <th>Face Value</th>
				        <th>Serial Number</th>
				        <th>Expire Date</th>
				        <th>Printed Date</th>
				        <th>Status</th>
		            </tr>
		        </thead>
		        <tbody>
		        	<?php $i = ($pins->getCurrentPage() - 1)* $pins->getPerPage(); 
		        	$dateTime = date("Y-m-d H:i:s");
		        	?>
		            @foreach ($pins as $pin)
		            	<?php $i += 1; 
		            	?>
		                <tr>
		                    <td>{{ $i }}</td>
		          			<td>{{ $pin['pin_id'] }}</td>
		          			<td>--</td>
		          			<td>{{ $pin['operator_name'] }}</td>
		          			<td>{{ $pin['face'] }}</td>
		          			<td>--</td>
		          			<td>
		          			<?php
		          				
								if ($pin['status']) {
									if ($pin['expired_date'] <= $dateTime) {
			          					echo '<span class="red">'.$pin['expired_date'].'</span>';
			          				} else {
			          					echo $pin['expired_date'];
			          				}
	          					}
		          			?>	
		          			</td>
		          			<td>{{ $pin['printed_date'] }}</td>
		          			<td>
		          				<?php
		          					if ($pin['status']) {
										if ($pin['expired_date'] <= $dateTime) {
				          					echo '<span class="red">Available</span>';
				          				} else {
				          					echo 'Available';
				          				}
		          					} else {
		          						echo 'Printed';
		          					}
		          				?>
		          			</td>
		          		</tr>
		          	@endforeach
		        </tbody>
		    </table>
			<?php echo $pins->links();?>
		@else
		    There are no record!
		@endif
		</div>
		@stop
	</div>
</div>