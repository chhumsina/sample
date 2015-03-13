@section('title', 'Dealer-Link-Termianl List')
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
<h4>Dealer-Link-Termianl List</h4>
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
				      		if (Entrust::can('add_dealer_link_terminal'))
			    				echo link_to('dealer-terminals/create', 'Add Dealer-Link-Terminal');
				      	?>
					  </li>
					</ul>
				</div>
			</div>
			{{ Form::open(array('url' => 'dealer-terminals/search')) }}
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
  	 			<div class="row">
  	 				<div class="col-md-2">
  	 					<label>Terminal Serial:</label>
  	 				</div>	  	 				
  	 				<div class="col-md-2">
  	 					<?php $serial = ''; if (Input::has('serial')){$serial = Input::get('serial');} ?>
  	 					{{Form::text('serial',$serial)}}
  	 				</div>
  	 				<div class="col-md-2">
  	 					<label>Status:</label>
  	 				</div>
  	 				<div class="col-md-2">
			  	 	    <?php $status = ''; if (Input::has('status')){$status = Input::get('status');}
			  	 	    	  $listStatus = array('active'=>'active','suspend'=>'suspend');
							  
			  	 	   	?>
			  	 	    {{ Form::select('status', array('' => 'Please Select')+$listStatus, $status, ['id' => 'status']) }}
  	 				</div>
  	 			</div>
  	 			<div class="row">
	  	 			<div class="col-md-2">
  	 					<label>N-Record:</label>
  	 				</div>
  	 				<div class="col-md-2">
			  	 	    <?php $nRecord = ''; if (Input::has('n_record')){$nRecord = Input::get('n_record');}
			  	 	    	  $nRecords = array('10'=>'10','30'=>'30','50'=>'50','100'=>'100','200'=>'200','500'=>'500','1000'=>'1000');
			  	 	   	?>
			  	 	    {{ Form::select('n_record', $nRecords, $nRecord, ['id' => 'n_record']) }}
  	 				</div>
  	 				<div class="col-md-2">
  	 					<input class="btn btn-primary" type="submit" name="search_terminal" value="Search" />
  	 				</div>
	  	 		</div>
  	 		{{ Form::close() }}
		  </div>	
		  <!-- Table -->
		@if ($dealerTerminals->count())
		    <table class="table table-striped table-bordered">
		        <thead>
		            <tr>
		                <th>No</th>
				        <th>Dealer ID</th>
				        <th>Dealer Name</th>
				        <th>Termianl Serial</th>
				        <th>Created At</th>
				        <th>Created By</th>
				        <th>Updated At</th>
				        <th>Updated By</th>
				        <th>Status</th>
				        <th>Action</th>
		            </tr>
		        </thead>
		        <tbody>
		        	<?php $i = ($dealerTerminals->getCurrentPage() - 1)* $dealerTerminals->getPerPage(); ?>
		            @foreach ($dealerTerminals as $dealerTerminal)
		            	<?php $i += 1; ?>
		                <tr>
		                    <td>{{ $i }}</td>
		          			<td>{{ $dealerTerminal->dealer__id }}</td>
		          			<td>{{ $dealerTerminal->dName }}</td>
		          			<td>{{ $dealerTerminal->serial }}</td>
		          			<td>{{ $dealerTerminal->datetime }}</td>
		          			<td>{{ $dealerTerminal->crbName }}</td>
		          			<td>{{ $dealerTerminal->updated_at }}</td>
		          			<td>{{ $dealerTerminal->upbName }}</td>
		          			<td>{{ $dealerTerminal->status }}
		          			</td>
		          			<td>
		          				<?php
						      		if (Entrust::can('edit_dealer_link_terminal'))
					    				echo link_to_route('dealer-terminals.edit', 'Edit', array($dealerTerminal->serial,'did'=>$dealerTerminal->dealer__id));
						      	?>
		          			</td>
		          		</tr>
		          	@endforeach
		        </tbody>
		    </table>
			<?php echo $dealerTerminals->links();?>
		@else
		    There are no record!
		@endif
		</div>
		@stop
	</div>
</div>