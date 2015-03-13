@section('title', 'Dealer-Link-Banks List Account')
@section('content')

<?php
$baseUrl = URL::to('/');
?>
<script type="text/javascript">
	
	$(document).ready(function(){
		var link;
		var replaceField = '';
		
		 $(".confirm").click(function(e) {
		 	link = this;
		 }); 
		 $(".confirm").confirm({
		     text: "Sale's ID is <span class='replaceField'></span>. <br /> Are you sure you want delete?",
		     title: "Confirmation!",
		     confirm: function(button) {
		         // do something         
		     	$(link).find("form").submit();
		     },
		     cancel: function(button) {
		         // do something
		     },
		     confirmButton: "Yes",
		     cancelButton: "No",
		     post: true
		 });
		 $(".confirm").click(function(e) {
		 	link = this;
		  	replaceField = $(this).attr("replaceField");
		  	$(".replaceField").html(replaceField);
		 }); 
		 
		 
		 
		 $(function(){
		    $('[data-method]').append(function(){
		        return "\n"+
		        "<form action='"+$(this).attr('href')+"' method='POST' style='display:none'>\n"+
		        "   <input type='hidden' name='_method' value='"+$(this).attr('data-method')+"'>\n"+
		        "</form>\n"
		    })
		    .removeAttr('href')
		    .attr('style','cursor:pointer;');
		});
		
		
	});
	
</script>
@include('layouts.partial.render-message')
<h4>Dealer-Link-Banks Account List</h4>
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
				      		if (Entrust::can('add_dealer_link_bank'))
			    				echo link_to('dealer-banks/create', 'Add Dealer-Link-Banks');
				      	?>
					  </li>
					</ul>
				</div>
			</div>
			{{ Form::open(array('url' => 'dealer-banks/search')) }}
				<div class="row">
  	 				<div class="col-md-2">
  	 					<label>Dealer ID:</label>
  	 				</div>
  	 				<div class="col-md-2">
			  	 	    <?php $did = ''; if (Input::has('did')){$did = Input::get('did');}?>
			  	 	    {{Form::text('did',$did,array('pattern' => '\d*'))}}
  	 				</div>
  	 				<div class="col-md-2">
  	 					<label>Dealer Name:</label>
  	 				</div>	  	 				
  	 				<div class="col-md-2">
  	 					<?php $name = ''; if (Input::has('name')){$name = Input::get('name');} ?>
  	 					{{Form::text('name',$name)}}
  	 				</div>
  	 			</div>
  	 			<!--<div class="row">
  	 				<div class="col-md-2">
  	 					<label>Bank:</label>
  	 				</div>	  	 				
  	 				<div class="col-md-2">
			  	 	    <?php //$bankId = ''; if (Input::has('bank__id')){$status = Input::get('bank__id');}
							//echo Form::select('bank__id', array('' => 'Please Select')+$banks, $bankId, ['id' => 'bank__id']) ;
			  	 	   	?>
  	 				</div>
  	 			</div>-->
  	 			<div class="row">
  	 				<div class="col-md-2">
  	 					<label>Status:</label>
  	 				</div>
  	 				<div class="col-md-2">
			  	 	    <?php $status = ''; if (Input::has('status')){$status = Input::get('status');}
			  	 	    	  $listStatus = array('active'=>'active','inactive'=>'inactive');
							  
			  	 	   	?>
			  	 	    {{ Form::select('status', array('' => 'Please Select')+$listStatus, $status, ['id' => 'status']) }}
  	 				</div>
  	 				<div class="col-md-1">
  	 					<input type="submit" name="search_terminal" value="Search" />
  	 				</div>
  	 			</div>
  	 		{{ Form::close() }}
		  </div>	
		  <!-- Table -->
		@if ($dealerBanks->count())
		    <table class="table table-striped table-bordered">
		        <thead>
		            <tr>
		                <th>No</th>
				        <th>Dealer ID</th>
				        <th>Dealer Name</th>
				        <th>Bank</th>
				        <th>Bank Account Name</th>
				        <th>Bank Account</th>
				        <th>Updated At</th>
				        <th>Updated By</th>
				        <th>Status</th>
				        <th>Action</th>
		            </tr>
		        </thead>
		        <tbody>
		        	<?php $i = ($dealerBanks->getCurrentPage() - 1)* $dealerBanks->getPerPage(); ?>
		            @foreach ($dealerBanks as $dealerBank)
		            	<?php $i += 1; ?>
		                <tr>
		                    <td>{{ $i }}</td>
		          			<td>{{ $dealerBank->did }}</td>
		          			<td>{{ $dealerBank->dName }}</td>
		          			<td>{{ $dealerBank->bank_name }}</td>
		          			<td>{{ $dealerBank->account_name }}</td>
		          			<td>{{ $dealerBank->account }}</td>
		          			<td>{{ $dealerBank->updated_at }}</td>
		          			<td>{{ $dealerBank->upbStaffName }}</td>
		          			<td>{{ $dealerBank->dealer_bank_status }}
		          			</td>
		          			<td>
		          				<?php
						      		if (Entrust::can('edit_dealer_link_bank'))
					    				echo link_to_route('dealer-banks.edit', 'Edit', $dealerBank->dealer_bank_id);
						      	?>
		          			</td>
		          		</tr>
		          	@endforeach
		        </tbody>
		    </table>
			<?php echo $dealerBanks->links();?>
		@else
		    There are no record!
		@endif
		</div>
		@stop
	</div>
</div>