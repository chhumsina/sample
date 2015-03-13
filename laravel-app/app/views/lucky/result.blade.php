@section('title', 'Result')
@section('content')
	<script>

		$(document).ready(function(){});
	</script>
	<?php
	$baseUrl = URL::to('/');
	?>

	<h4>Result</h4>
@include('layouts.partial.render-message')
<div class="row form">
	<div class="col-md-3"></div>
	<div class="col-md-9">
		{{ Form::open(array('url' => url('lucky/cancel-ticket'), 'files'=>true,'class'=>'form-horizontal', 'id'=>'form', 'style'=>'border:solid gray 0px')) }}

		<div class="form-group">
			{{ Form::label('did', 'Dealer Id *', array('class'=>'col-sm-2 control-label')) }}

			<div class="col-sm-3">
				{{ Form::select('did', array('' => 'Please Select')+$dealers, null, array('class' => 'form-control','id' => 'did','required'=>'required'))}}
			</div>
		</div>
		<div class="form-group">
			{{ Form::label('tsn', 'TSN *', array('class'=>'col-sm-2 control-label')) }}
			<div class="col-sm-3">
				{{FORM::text('tsn','',array('class'=>'form-control', 'required' => 'required','id'=>'datepicker_start'))}}
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<input class="btn btn-primary" type="submit" name="cancelTicket" value="Submit" />
				<a href="<?php echo $baseUrl;?>"><span class="btn btn-warning">Back</span></a>
			</div>
		</div>
		{{ Form::close() }}
	</div>
</div>
<di class="row cancel-ticket">
	<?php
	$item = Session::get('items');
	?>
	@if (Session::has('items'))
		<div class="col-md-3"></div>
		<div class="col-md-9">
			{{ Form::open(array('url' => url(''), 'files'=>true,'class'=>'form-horizontal', 'id'=>'form', 'style'=>'border:solid gray 0px')) }}

			<div class="form-group">
				{{ Form::label('icon', 'Dealer Id: ', array('class'=>'col-sm-2 control-label')) }}
				<div class="col-sm-3">
					{{Session::get('did')}}
				</div>
			</div>
			<div class="form-group">
				{{ Form::label('icon', 'Previous Balance', array('class'=>'col-sm-2 control-label')) }}
				<div class="col-sm-3">
					{{$item->prev_balance}}
				</div>
			</div>
			<div class="form-group">
				{{ Form::label('icon', 'Post Balance', array('class'=>'col-sm-2 control-label')) }}
				<div class="col-sm-3">
					{{$item->post_balance}}
				</div>
			</div>
			{{ Form::close() }}
		</div>
	@endif
</di>

@stop