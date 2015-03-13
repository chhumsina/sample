@section('title', 'Report')
@section('content')
	<script>

		$(document).ready(function(){});
	</script>
	<?php
	$baseUrl = URL::to('/');
	?>

	<h4>Report</h4>
@include('layouts.partial.render-message')
<div class="row form">
	<div class="col-md-3"></div>
	<div class="col-md-9">
		{{ Form::open(array('url' => url('lucky/show-report'), 'files'=>true,'class'=>'form-horizontal', 'id'=>'form', 'style'=>'border:solid gray 0px')) }}

		<div class="form-group">
			{{ Form::label('did', 'Dealer Id *', array('class'=>'col-sm-2 control-label')) }}

			<div class="col-sm-3">
				{{ Form::select('did', array('' => 'Please Select')+$dealers, null, array('class' => 'form-control','id' => 'did','required'=>'required'))}}
			</div>
		</div>
		<div class="form-group">
			{{ Form::label('datepicker_start', 'Start Date *', array('class'=>'col-sm-2 control-label')) }}
			<div class="col-sm-3">
				{{FORM::text('start_date','',array('class'=>'form-control', 'required' => 'required','id'=>'datepicker_start'))}}
			</div>
		</div>
		<div class="form-group">
			{{ Form::label('datepicker_end', 'End Date *', array('class'=>'col-sm-2 control-label')) }}
			<div class="col-sm-3">
				{{FORM::text('end_date','',array('class'=>'form-control', 'required' => 'required','id'=>'datepicker_end'))}}
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<input class="btn btn-primary" type="submit" name="report" value="Submit" />
				<a href="<?php echo $baseUrl;?>"><span class="btn btn-warning">Back</span></a>
			</div>
		</div>
		{{ Form::close() }}
	</div>
</div>
<di class="row cancel-ticket">
		<div class="col-md-12">
			@if (isset($items))
				<table class="table table-striped">
					<tr>
						<th>Date</th>
						<th>Sale</th>
						<th>Cancel</th>
						<th>Payout</th>
						<th>Total</th>
						<th>Previous Balance</th>
						<th>Post Balance</th>
					</tr>
				@foreach ($items as  $item)
					<tr>
						@foreach ($item as $i)
							<td>{{$i}}</td>
						@endforeach
					</tr>
				@endforeach

				</table>
			@endif
		</div>
</di>

@stop