@section('title', 'Edit Dragon Warrior Quota')
@section('content')
<?php
$baseUrl = URL::to('/');
?>
<script type="text/javascript">
	$(document).ready(function(){
		$baseUrl = '{{$baseUrl}}';
	});
</script>
<h4>Edit Dragon Warrior Quota</h4>
@include('layouts.partial.render-message')
<div class="row">
	<div class="col-md-12">
		{{ Form::open(array('url' => 'sales/edit-target')) }}
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('sale_staff__id', 'Sale Staff*:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::hidden('sale_staff_target_id', $target->sale_staff_target_id)}}
			  		{{ Form::select('sale_staff__id', array('' => 'Please Select')+$saleStaffs, $target->sale_staff__id, ['id' => 'sale_staff__id', 'class' => 'form-control','required'=>'required']) }}
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
			  		{{ Form::select('target_year', $listYears, $target->target_year, ['id' => 'target_year', 'class' => 'form-control','required'=>'required']) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('target_month', 'Month:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::select('target_month', $months, $target->target_month, ['id' => 'target_month', 'class' => 'form-control','required'=>'required']) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('target_week', 'Week:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		<?php
			  		$weeks = array('Week 1'=>'Week 1','Week 2'=>'Week 2','Week 3'=>'Week 3','Week 4'=>'Week 4');
			  		?>
			  		{{ Form::select('target_week', $weeks, $target->target_week, ['id' => 'target_week', 'class' => 'form-control','required'=>'required']) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('target_sale_game', 'Target Amount Dealer Sale Game (KHR):') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('target_sale_game', $target->target_sale_game, array('class' => 'form-control')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('target_topup_game', 'Target Amount Dealer Top Up Game (KHR):') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('target_topup_game', $target->target_topup_game, array('class' => 'form-control')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('target_num_new_recruit', 'Target Numer Recruit:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('target_num_new_recruit', $target->target_num_new_recruit, array('class' => 'form-control')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('target_num_sale_visit', 'Target Numer Visit:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('target_num_sale_visit', $target->target_num_sale_visit, array('class' => 'form-control')) }}
			  	</div>
			</div>
			
			<div class="row">
				<div class="col-md-4 text-right"></div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::submit('Update',array('class' => 'btn btn-primary')) }}
			  	</div>
			</div>
			@include('layouts.partial.render-message-form')
		{{ Form::close() }}
	</div>
</div>
@stop