@section('title', 'Update Dealer-Link-Terminal')
@section('content')
<?php
$baseUrl = URL::to('/');
?>
<script type="text/javascript">
	$(document).ready(function(){
	});
</script>
<h4>Update Dealer-Link-Terminal</h4>
@include('layouts.partial.render-message')
<div class="row">
	<div class="col-md-12">
		{{ Form::model($dealerTerminal, array('method' => 'PATCH', 'route' =>array('dealer-terminals.update', $dealerTerminal->serial,'did'=>$dealerTerminal->dealer__id))) }}
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('serial', 'Terminal Serial*:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('serial', $dealerTerminal->serial, array('class' => 'form-control','disabled'=>'disabled')) }}
			  		{{ Form::hidden('serial', $dealerTerminal->serial) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('dealer__id', 'Dealer ID*:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('dealer__id', $dealerTerminal->dealer__id, array('class' => 'form-control','disabled'=>'disabled')) }}
			  		{{ Form::hidden('dealer__id', $dealerTerminal->dealer__id) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('status', 'Status:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		<?php 
			  			$status = $dealerTerminal->status;
						if ($status == 'active' || $status == 'suspend') {
							echo Form::select('status',array('active' => 'active', 'suspend' => 'suspend'),$status, array('id' => 'khan', 'class' => 'form-control','required'=>'required'));
						}
			  			
			  		?>
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
@stop