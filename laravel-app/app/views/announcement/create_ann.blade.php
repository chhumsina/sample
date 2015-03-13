@section('title', 'Create New Announcement')
@section('content')
<h4>New Announcement</h4>
<div class="row">
	<div class="col-md-12">
		<!--{{ Form::open(array('url' => url('announcements/create'), 'class'=>'form', 'id'=>'form', 'style'=>'border:solid gray 0px')) }}-->
		{{ Form::open(array('route' => 'announcements.store','files'=>true)) }}
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('title', 'Title:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('title', '', array('class' => 'form-control','placeholder'=>'Title')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('message_en', 'Message En:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::textarea('message_en', '', array('class' => 'form-control','placeholder'=>'Mesasge En','size' => '30x5')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('message_kh', 'Message Kh:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::file('message_kh') }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('start_date', 'Start Date:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('start_date', '', array('class' => 'form-control','id'=>'datepicker_start_datetime','placeholder'=>'Start Date')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('end_date', 'End Date:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('end_date', '', array('class' => 'form-control','id'=>'datepicker_end_datetime','placeholder'=>'End Date')) }}
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