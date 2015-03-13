@section('title', 'Edit Terminal')
@section('content')
<h4>Edit Terminal</h4>
<div class="row">
	<div class="col-md-12">
		{{ Form::model($terminal, array('method' => 'PATCH', 'route' =>array('terminals.update', $terminal->serial))) }}
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('serial', 'Terminal Serial*:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ $terminal->serial }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('imsi', 'IMSI*:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('imsi', $terminal->imsi, array('class' => 'form-control','placeholder'=>'IMSI','required'=>'required')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('ecard_id', 'ECard*:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('ecard_id', $terminal->ecard_id, array('class' => 'form-control','placeholder'=>'ECard','required'=>'required')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('status', 'Status:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		<?php 
			  			$status = $terminal->status;
			  			if ($status == 'used') {
			  				echo Form::select('status',array('active' => 'active', 'inactive' => 'inactive','used'=>'used'),$status,array('class' => 'form-control','required'=>'required', 'disabled' => 'disabled'));
			  			} else {
			  				echo Form::select('status',array('active' => 'active', 'inactive' => 'inactive'),$status,array( 'class' => 'form-control','required'=>'required'));
			  			}
			  		?>
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('stock__location', 'Stock Locations:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
		  		{{ Form::select('stock__location', array('' => 'Please Select')+$stockLocations, $terminal->stock__location, ['id' => 'stockLocations', 'class' => 'form-control']) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('reason', 'Reason:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('reason', $terminal->reason, array('class' => 'form-control','placeholder'=>'Reason')) }}
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