@section('title', 'Class of Service Charge')
@section('content')

<?php
$baseUrl = URL::to('/');
$khanCode = '';
$communeCode = '';
$villageCode = '';
if (Input::has('khan__code')) {
	$khanCode = Input::get('khan__code');
}
?>
<script type="text/javascript">
	
	$(document).ready(function(){
		$baseUrl = '{{$baseUrl}}';
		
	});
	
</script>
@include('layouts.partial.render-message')
<h4>Class of Service Charge</h4>
<div class="row">
	<div class="col-md-12">
		<!-- Default panel contents -->
		<div class="panel panel-default">
		  <div class="panel-heading">
		  	<div class="row">
				<div class="col-md-12">
					<ul class="nav nav-pills" role="tablist">
					  <li role="presentation">
					  	<?php
					  	if (Entrust::can('add_class_of_service_charge'))
    						echo link_to('service-charges/create-service-charge', 'Add Class Of Service Charge');
					  	?>
					  </li>
					</ul>
				</div>
			</div>
			{{ Form::open(array('url' => 'dealers/search')) }}
				
  	 		{{ Form::close() }}
		  </div>	
		  <!-- Table -->
		@if ($classOfServices->count())
		    <table class="table table-striped table-bordered">
		        <thead>
		            <tr>
		                <th>No</th>
		                <th>ID</th>
				        <th>Class Of Service Charge Name</th>
				        <th>Status</th>
				        <th>Action</th>
		            </tr>
		        </thead>
		        <tbody>
		        	<?php $i = ($classOfServices->getCurrentPage() - 1)* $classOfServices->getPerPage(); ?>
		            @foreach ($classOfServices as $classOfService)
		            	<?php $i += 1; ?>
		                <tr>
		                    <td>{{ $i }}</td>
		          			<td>{{ $classOfService->id }}</td>
		          			<td>{{ $classOfService->name }}</td>
		          			<td>{{ $classOfService->status }}</td>
		          			</td>
		          			<td>
		          				<?php
							  	if (Entrust::can('edit_class_of_service_charge')) {
									?>
									<a href="{{ URL::route('dealers.edit', array($dealer->id)) }}">
									     <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
									</a>
									<?php
								}
		          				?>
		          			</td>
		          		</tr>
		          	@endforeach
		        </tbody>
		    </table>
			<?php echo $classOfServices->links();?>
		@else
		    There are no record!
		@endif
		</div>
		@stop
	</div>
</div>