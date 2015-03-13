@section('title', 'Upload Pin')
@section('content')
<h4>Upload Pin</h4>
@include('layouts.partial.render-message')
<div class="row">
	<div class="col-md-12">
		{{ Form::open(array('url' => url('pin-stocks/upload-pin'), 'files'=>true,'class'=>'form', 'id'=>'form', 'style'=>'border:solid gray 0px')) }}
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('title', 'Title *:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('title', '', array('class' => 'form-control','placeholder'=>'Title','required'=>'required')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('operator', 'Operator *:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		<?php $operator = ''; if (Input::has('operator__id')){$operator = Input::get('operator__id');}
					//$operators = array('1'=>'Smart','2'=>'Metfone');
		  	 	   	?>
		  	 	    {{ Form::select('operator__id', array('' => 'Please Select')+$operators, $operator, array('class' => 'form-control','id' => 'operator__id','required'=>'required')) }}
		  	 	    
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('avatar', 'File:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::file('avatar') }}
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
			  		{{ Form::submit('Upload Pin',array('class' => 'btn btn-primary')) }}
			  	</div>
			</div>
			@include('layouts.partial.render-message-form')
		{{ Form::close() }}
	</div>
</div>
@stop