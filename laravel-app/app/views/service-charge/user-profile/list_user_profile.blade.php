@section('title', 'User Profile Service Charge')
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
<h4>User Profile Service Charge</h4>
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
    						echo link_to('service-charges/create_user_profile_service_charge', 'Add User Profile Service Charge');
					  	?>
					  </li>
					</ul>
				</div>
			</div>
			{{ Form::open(array('url' => 'dealers/search')) }}
				
  	 		{{ Form::close() }}
		  </div>	
		  <!-- Table -->
		@if ($dealerProfiles->count())
		    <table class="table table-striped table-bordered">
		        <thead>
		            <tr>
		                <th>No</th>
		                <th>DID</th>
				        <th>Dealer Name</th>
				        <th>Class Of Service Charge</th>
				        <th>Status</th>
				        <th>Action</th>
		            </tr>
		        </thead>
		        <tbody>
		        	<?php $i = ($dealerProfiles->getCurrentPage() - 1)* $dealerProfiles->getPerPage(); ?>
		            @foreach ($dealerProfiles as $dealerProfile)
		            	<?php $i += 1; ?>
		                <tr>
		                    <td>{{ $i }}</td>
		          			<td>{{ $dealerProfile->dealer__id }}</td>
		          			<td>{{ $dealerProfile->name }}</td>
		          			<td>{{ $dealerProfile->profile_name }}</td>
		          			<td>{{ $dealerProfile->status }}</td>
		          			</td>
		          			<td>
		          				<?php
							  	if (Entrust::can('edit_class_of_service_charge')) {
									echo link_to('transactions/'.$dealerProfile->user_profile_id.'/detail','Edit');
								}
		          				?>
		          			</td>
		          		</tr>
		          	@endforeach
		        </tbody>
		    </table>
			<?php echo $dealerProfiles->links();?>
		@else
		    There are no record!
		@endif
		</div>
		@stop
	</div>
</div>