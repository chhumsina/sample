@section('title', 'Show Sale Staff')
@section('content')
<?php
$baseUrl = URL::to('/');
?>
<script type="text/javascript">
	
</script>
<h4>Show Sale Staff</h4>
<div class="row">
	<div class="col-md-12">
		{{ Form::open(array('route' => 'sales.store')) }}
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('id', 'Sale Id:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ $sale->id }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('name', 'Sale Name:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ $sale->name }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('position', 'Position:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ $sale->position }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('phone', 'Phone:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ $sale->phone }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('email', 'Email:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ $sale->email }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('parent_id', 'Report To Sale Id:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ $sale->parent_id }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('dealers', 'Hold Dealers:') }}
			  	</div>
			  	<div class="col-md-6 text-left">
			  		<div style="overflow: scroll;max-height: 400px;border:1px solid gray">
			  			<ul style="list-style: none;">
			  				<?php
			  				if ($holdDealers != null) {
								foreach ($holdDealers as $key => $dealer) {
									 $stp = '<li>';
									 $stp .= '<span>('.$dealer->id.') '.$dealer->name.'</span>';
									 $stp .= '</li>';
									 echo $stp;
								}
			  				}
			  				?>
			  			</ul>
			  			
			  		</div>
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