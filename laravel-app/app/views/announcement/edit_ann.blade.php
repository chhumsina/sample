@section('title', 'Edit Announcement')
@section('content')
<?php
$baseUrl = URL::to('/');
?>
<h4>Edit Announcement</h4>
<div class="row">
	<div class="col-md-12">
		{{ Form::model($announcement, array('method' => 'PATCH', 'route' =>array('announcements.update', $announcement->id), 'files'=>true)) }}
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('title', 'Title:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('title', $announcement->title, array('class' => 'form-control','placeholder'=>'Title')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('message_en', 'Message En:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::textarea('message_en', $announcement->message_en, array('class' => 'form-control','placeholder'=>'Mesasge En','size' => '30x5')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('message_kh', 'Message Kh:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::file('message_kh_update') }}
			  		{{ Form::hidden('message_kh', $announcement->message_kh) }}
			  		<a href="<?php echo $baseUrl;?>/images/uploads/<?php echo $announcement->message_kh?>" alt="a picture"><?php echo $announcement->message_kh?></a>
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('start_date', 'Start Date:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('start_date', $announcement->start_date, array('class' => 'form-control','id'=>'datepicker_start_datetime')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('end_date', 'End Date:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('end_date', $announcement->end_date, array('class' => 'form-control','id'=>'datepicker_end_datetime')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('status', 'Status:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		<?php 
			  			$status = $announcement->status == 1 ?1:0;
			  			echo Form::select('status',array('1' => 'active', '0' => 'inactive'),$status,array('id' => 'khan', 'class' => 'form-control','required'=>'required'));
			  		?>
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