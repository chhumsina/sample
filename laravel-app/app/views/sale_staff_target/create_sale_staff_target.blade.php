@section('title', 'Add Dragon Warrior Quota')
@section('content')
<?php
$baseUrl = URL::to('/');
?>
<script type="text/javascript">
	$(document).ready(function(){
		$baseUrl = '{{$baseUrl}}';
	});
</script>
<h4>Add Dragon Warrior Quota</h4>
@include('layouts.partial.render-message')
<div class="row">
	<div class="col-md-12">
		{{ Form::open(array('url' => 'sales/create-target')) }}
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('sale_staff__id', 'Sale Staff*:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::select('sale_staff__id', array('' => 'Please Select')+$saleStaffs, null, ['id' => 'sale_staff__id', 'class' => 'form-control','required'=>'required']) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('target_year', 'Year:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		<?php 
			  	 		$listYears = array('2015'=>'2015','2016'=>'2016','2017'=>'2017','2018'=>'2018','2019'=>'2019','2020'=>'2020');
			  	 	?>
			  		{{ Form::select('target_year', $listYears, null, ['id' => 'target_year', 'class' => 'form-control','required'=>'required']) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('target_month', 'Month:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::select('target_month', $months, null, ['id' => 'target_month', 'class' => 'form-control','required'=>'required']) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('target_week', 'Week:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		<!-- <?php
			  		$weeks = array('Week 1'=>'Week 1','Week 2'=>'Week 2','Week 3'=>'Week 3','Week 4'=>'Week 4');
			  		?>
			  		{{ Form::select('target_week', $weeks, null, ['id' => 'target_week', 'class' => 'form-control','required'=>'required']) }} -->
			  		
			  		<select name="target_week" id="target_week" class="form-control">
						<?php for ($i = 1; $i <= 52; $i++) { ?>
						<option value="<?php echo "Week ".$i; ?>" <?php if ($i == date('W')) { echo 'selected="selected"';} ?>><?php echo "Week ".$i; ?></option>
						<?php } ?>
					</select>
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('target_sale_game', 'Target Amount Dealer Sale Game (KHR):') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('target_sale_game', '', array('class' => 'form-control')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('target_topup_game', 'Target Amount Dealer Top Up Game (KHR):') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('target_topup_game', '', array('class' => 'form-control')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('target_num_new_recruit', 'Target Numer Recruit:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('target_num_new_recruit', '', array('class' => 'form-control')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('target_num_sale_visit', 'Target Numer Visit:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('target_num_sale_visit', '', array('class' => 'form-control')) }}
			  	</div>
			</div>
			
			<div class="row">
				<div class="col-md-4 text-right"></div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::submit('Save',array('class' => 'btn btn-primary')) }}
			  	</div>
			</div>
			@include('layouts.partial.render-message-form')
		{{ Form::close() }}
	</div>
</div>
<div class="row">
	<?php if ($saleStaffTargets != null) {?>
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
		@endif
		<?php } ?>
</div>
@stop