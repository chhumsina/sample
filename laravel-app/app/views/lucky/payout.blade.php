@section('title', 'Payout')
@section('content')
	<script>

		$(document).ready(function(){});
	</script>
	<?php
		$baseUrl = URL::to('/');
		$item = Session::get('items');
		$payout = Session::get('payouts');
	?>

	<h4>Payout</h4>
	@include('layouts.partial.render-message')
	<div class="row form">
		<div class="col-md-3"></div>
		<div class="col-md-9">
			{{ Form::open(array('url' => url('lucky/payout'), 'files'=>true,'class'=>'form-horizontal', 'id'=>'form', 'style'=>'border:solid gray 0px')) }}

			<div class="form-group">
				{{ Form::label('did', 'Dealer Id *', array('class'=>'col-sm-2 control-label')) }}

				<div class="col-sm-3">
					{{ Form::select('did', array('' => 'Please Select')+$dealers, null, array('class' => 'form-control','id' => 'did','required'=>'required'))}}
				</div>
			</div>
			<div class="form-group">
				{{ Form::label('tsn', 'TSN *', array('class'=>'col-sm-2 control-label')) }}
				<div class="col-sm-3">
					{{FORM::text('tsn','',array('class'=>'form-control', 'required' => 'required'))}}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					@if (Session::has('items'))
						<input class="btn btn-primary" type="submit" name="payout" value="Submit" />
						<a href="{{$baseUrl}}/lucky/payout"><span class="btn btn-warning">Back</span></a>
					@else
						<input class="btn btn-primary" type="submit" name="check" value="Check" />
						<a href="<?php echo $baseUrl;?>"><span class="btn btn-warning">Back</span></a>
					@endif
				</div>
			</div>
			{{ Form::close() }}
		</div>
	</div>
	<di class="row cancel-ticket">

		@if (Session::has('items'))
			<div class="col-md-2"></div>
			<div class="col-md-10">
				{{ Form::open(array('url' => url(''), 'files'=>true,'class'=>'form-horizontal', 'id'=>'form', 'style'=>'border:solid gray 0px')) }}

				<div class="form-group">
					{{ Form::label('icon', 'Dealer Id: ', array('class'=>'col-sm-3 control-label')) }}
					<div class="col-sm-4">
						{{Session::get('did')}}
					</div>
				</div>
				<div class="form-group">
					{{ Form::label('icon', 'Previous Balance: ', array('class'=>'col-sm-3 control-label')) }}
					<div class="col-sm-4">
						{{$item->pre_balance}}
					</div>
				</div>
				<div class="form-group">
					{{ Form::label('icon', 'Post Balance: ', array('class'=>'col-sm-3 control-label')) }}
					<div class="col-sm-4">
						{{$item->post_balance}}
					</div>
				</div>
				<div class="form-group">
					{{ Form::label('icon', 'Commission: ', array('class'=>'col-sm-3 control-label')) }}
					<div class="col-sm-4">
						{{$item->commission}}
					</div>
				</div>
				<div class="form-group">
					{{ Form::label('icon', 'Amount: ', array('class'=>'col-sm-3 control-label')) }}
					<div class="col-sm-4">
						{{$item->amount}}
					</div>
				</div>
				{{ Form::close() }}
			</div>
		@elseif(Session::has('payouts'))
			<div class="col-md-2"></div>
			<div class="col-md-10">
				{{ Form::open(array('url' => url(''), 'files'=>true,'class'=>'form-horizontal', 'id'=>'form', 'style'=>'border:solid gray 0px')) }}

				<div class="form-group">
					{{ Form::label('icon', 'Dealer Id: ', array('class'=>'col-sm-3 control-label')) }}
					<div class="col-sm-4">
						{{Session::get('did')}}
					</div>
				</div>
				<div class="form-group">
					{{ Form::label('icon', 'Previous Balance: ', array('class'=>'col-sm-3 control-label')) }}
					<div class="col-sm-4">
						{{$payout->supre_balance}}
					</div>
				</div>
				<div class="form-group">
					{{ Form::label('icon', 'Post Balance: ', array('class'=>'col-sm-3 control-label')) }}
					<div class="col-sm-4">
						{{$payout->supost_balance}}
					</div>
				</div>
				<div class="form-group">
					{{ Form::label('icon', 'Commission: ', array('class'=>'col-sm-3 control-label')) }}
					<div class="col-sm-4">
						{{$payout->commission}}
					</div>
				</div>
				<div class="form-group">
					{{ Form::label('icon', 'Amount: ', array('class'=>'col-sm-3 control-label')) }}
					<div class="col-sm-4">
						{{$payout->amount}}
					</div>
				</div>
				{{ Form::close() }}
			</div>
		@endif
	</di>

@stop