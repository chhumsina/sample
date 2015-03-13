@section('title', 'Transaction Cancel Game')
@section('content')
<h4>Transaction Cancel Game</h4>
@include('layouts.partial.render-message')
<div class="row">
	<div class="col-md-12">
		{{ Form::open(array('url' => url('transactions/cancel-game'), 'files'=>true,'class'=>'form', 'id'=>'form', 'style'=>'border:solid gray 0px')) }}
		<!-- {{ Form::open(array('route' => 'announcements.store','files'=>true)) }}-->
			
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('file_name', 'Excel File List of TSN Game:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::file('file_name') }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('remark', 'Remark :') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::textarea('remark', '', array('class' => 'form-control','placeholder'=>'Remark','size' => '30x5')) }}
			  	</div>
			</div>
			<div class="row">
				<div class="col-md-4 text-right">
				</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::submit('Confirm Upload To Cancel',array('class' => 'btn btn-primary')) }}
			  	</div>
			</div>
			@include('layouts.partial.render-message-form')
		{{ Form::close() }}
	</div>
</div>
@stop