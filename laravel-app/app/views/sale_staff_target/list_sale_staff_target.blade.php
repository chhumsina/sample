@section('title', 'Dragon Warrior Quota')
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
		     text: "Sale's ID is <span class='replaceField'></span>. <br /> Are you sure you want delete?",
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
<h4>Dragon Warrior Quota</h4>
<div class="row">
	<div class="col-md-12">
		<!-- Default panel contents -->
		<div class="panel panel-default">
		  <div class="panel-heading">
		  	<div class="row">
				<div class="col-md-12">
					<ul class="nav nav-pills" role="tablist">
					  <!-- <li role="presentation" class="active"><a href="#">Home</a></li> -->
					  <li role="presentation">
					  	<?php
				      		if (Entrust::can('add_dragon_warrior_target'))
			    				echo link_to('sales/create-target', 'Add Dragon Warrior Quota');
				      	?>
					  </li>
					  <li role="presentation">
					  	<?php
				      		if (Entrust::can('upload_dragon_warrior_target'))
			    				echo link_to('sales/upload-sale-target', 'Upload Dragon Warrior Quota');
				      	?>
					  </li>
						<li role="presentation">
							<?php
							if (Entrust::can('upload_dragon_warrior_target'))
								echo link_to('upload-sale-target', 'List Upload Dragon Warrior Quota');
							?>
						</li>
					</ul>
				</div>
			</div>
			{{ Form::open(array('url' => 'sales/target')) }}
				<div class="row">
	  	 			<div class="col-md-2">
  	 					<label>Sale ID:</label>
  	 				</div>	  	 				
  	 				<div class="col-md-2">
  	 					<?php $saleStaffId = ''; if (Input::has('sale_staff_id')){$saleStaffId = Input::get('sale_staff_id');} ?>
  	 					{{Form::text('sale_staff_id',$saleStaffId)}}
  	 				</div>
  	 				<div class="col-md-2">
  	 					<label>Sale Name:</label>
  	 				</div>
  	 				<div class="col-md-2">
			  	 	    <?php $name = ''; if (Input::has('name')){$name = Input::get('name');}?>
			  	 	    {{Form::text('name',$name)}}
  	 				</div>
  	 			</div>
				<div class="row">
  	 				<div class="col-md-2">
  	 					<label>Year:</label>
  	 				</div>
  	 				<div class="col-md-2">
			  	 	    <?php $year = ''; if (Input::has('year')){$year = Input::get('year');}
			  	 	    	  $listYears = array('2015'=>'2015','2016'=>'2016','2017'=>'2017','2018'=>'2018','2019'=>'2019','2020'=>'2020');
			  	 	   	?>
			  	 	    {{ Form::select('year', array('' => 'Please Select')+$listYears, $year, ['id' => 'status']) }}
  	 				</div>
  	 				<div class="col-md-1">
  	 					<input type="submit" name="search_terminal" value="Search" />
  	 				</div>
  	 			</div>
  	 		{{ Form::close() }}	
		  </div>	
		  <!-- Table -->
		@if ($saleStaffTargets->count())
		    <table class="table table-striped table-bordered">
		        <thead>
		            <tr>
		                <th>No</th>
				        <th>Sale Id</th>
				        <th>Sale Name</th>
				        <th>Year</th>
				        <th>Month</th>
				        <th>Week</th>
				        <th>Sale Game</th>
				        <th>Top Up</th>
				        <th>Num New Recruit</th>
				        <th>Num Sale Visit</th>
				        <th>Created At</th>
				        <th>Created By</th>
				        <th>Action</th>
		            </tr>
		        </thead>
		        <tbody>
		        	<?php $i = ($saleStaffTargets->getCurrentPage() - 1)* $saleStaffTargets->getPerPage(); ?>
		            @foreach ($saleStaffTargets as $saleStaffTarget)
		            	<?php $i += 1; ?>
		                <tr>
		                    <td>{{ $i }}</td>
		                    <td>{{ $saleStaffTarget->sale_staff__id }}</td>
		          			<td>{{ $saleStaffTarget->saleStaffName }}</td>
		          			<td>{{ $saleStaffTarget->target_year }}</td>
		          			<td>{{ $saleStaffTarget->target_month }}</td>
		          			<td>{{ $saleStaffTarget->target_week }}</td>
		          			<td>{{ number_format($saleStaffTarget->target_sale_game,2) }} KHR</td>
		          			<td>{{ number_format($saleStaffTarget->target_topup_game,2) }} KHR</td>
		          			<td>{{ $saleStaffTarget->target_num_new_recruit }}</td>
		          			<td>{{ $saleStaffTarget->target_num_sale_visit }}</td>
		          			<td>{{ $saleStaffTarget->created_at }}</td>
		          			<td>{{ $saleStaffTarget->createdBy }}</td>
		          			<td>
						      	<?php
							  	if (Entrust::can('edit_dragon_warrior_target')) {
									echo link_to('sales/'.$saleStaffTarget->sale_staff_target_id.'/edit-target', 'Edit');
								}?>
								
		          			</td>
		          		</tr>
		          	@endforeach
		        </tbody>
		    </table>
			<?php echo $saleStaffTargets->links(); ?>
		@else
		    There are no record!
		@endif
		</div>
		@stop
	</div>
</div>