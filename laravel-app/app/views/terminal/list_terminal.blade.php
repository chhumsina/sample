@section('title', 'Terminal List')
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
		     text: "Terminal's serial is <span class='replaceField'></span>. <br /> Are you sure you want delete?",
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
<h4>Terminals List</h4>
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
				      		if (Entrust::can('add_terminal'))
			    				echo link_to('terminals/create', 'Add Terminal');
				      	?>
					  </li>
					</ul>
				</div>
			</div>
			{{ Form::open(array('url' => 'terminals/search')) }}
				<div class="row">
	  	 			<div class="col-md-2">
  	 					<label>Serial:</label>
  	 				</div>	  	 				
  	 				<div class="col-md-2">
  	 					<?php $serial = ''; if (Input::has('serial')){$serial = Input::get('serial');} ?>
  	 					{{Form::text('serial',$serial)}}
  	 				</div>
  	 				<div class="col-md-2">
  	 					<label>IMSI:</label>
  	 				</div>
  	 				<div class="col-md-2">
			  	 	    <?php $imsi = ''; if (Input::has('imsi')){$imsi = Input::get('imsi');}?>
			  	 	    {{Form::text('imsi',$imsi)}}
  	 				</div>
  	 			</div>
  	 			<div class="row">
  	 				<div class="col-md-2">
  	 					<label>Ecard:</label>
  	 				</div>
  	 				<div class="col-md-2">
			  	 	    <?php $ecard = ''; if (Input::has('ecard')){$ecard = Input::get('ecard');}?>
			  	 	    {{Form::text('ecard',$ecard)}}
  	 				</div>
  	 				<div class="col-md-2">
  	 					<label>Status:</label>
  	 				</div>
  	 				<div class="col-md-2">
			  	 	    <?php $status = ''; if (Input::has('status')){$status = Input::get('status');}
			  	 	    	  $listStatus = array('active'=>'active','inactive'=>'inactive','used'=>'used');
			  	 	   	?>
			  	 	    {{ Form::select('status', array('' => 'Please Select')+$listStatus, $status, ['id' => 'status']) }}
  	 				</div>
  	 			</div>
  	 			<div class="row">
  	 				<div class="col-md-2">
  	 					<label>Stock Locations:</label>
  	 				</div>
  	 				<div class="col-md-2">
			  	 	    <?php $ecard = ''; if (Input::has('ecard')){$ecard = Input::get('ecard');}?>
			  	 	     <?php $stock__location = ''; if (Input::has('stock__location')){$stock__location = Input::get('stock__location');}?>
			  	 	    {{ Form::select('stock__location', array('' => 'Please Select', 'no' => 'No location assign')+$stockLocations, $stock__location, ['id' => 'stockLocations']) }}
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
		@if ($terminals->count())
		    <table class="table table-striped table-bordered">
		        <thead>
		            <tr>
		                <th>No</th>
				        <th>Serial</th>
				        <th>IMSI</th>
				        <th>Ecard</th>
				        <th>Stoct Location</th>
				        <th>Created At</th>
				        <th>Created By</th>
				        <th>Updated At</th>
				        <th>Updated By</th>
				        <th>Status</th>
				        <th>Action</th>
		            </tr>
		        </thead>
		        <tbody>
		        	<?php $i = ($terminals->getCurrentPage() - 1)* $terminals->getPerPage(); ?>
		            @foreach ($terminals as $terminal)
		            	<?php $i += 1; ?>
		                <tr>
		                    <td>{{ $i }}</td>
		          			<td>{{ $terminal->serial }}</td>
		          			<td>{{ $terminal->imsi }}</td>
		          			<td>{{ $terminal->ecard_id }}</td>
		          			<td>{{ $terminal->stock__location }}</td>
		          			<td>{{ $terminal->created_at }}</td>
		          			<td>{{ $terminal->crbName }}</td>
		          			<td>{{ $terminal->updated_at }}</td>
		          			<td>{{ $terminal->upbName }}</td>
		          			<td>{{ $terminal->status }}
		          			</td>
		          			<td>
		          				<?php
						      		if (Entrust::can('edit_terminal'))
					    				echo link_to_route('terminals.edit', 'Edit', array($terminal->serial));
									if (Entrust::can('delete_terminal')) {
										if($terminal->status != 'used') echo ' '.link_to_route('terminals.destroy', 'Delete', array($terminal->serial),array('class'=>'confirm','data-method' => 'delete', 'replaceField'=>$terminal->serial));
									} 
						      	?>
		          			</td>
		          		</tr>
		          	@endforeach
		        </tbody>
		    </table>
			<?php echo $terminals->links();?>
		@else
		    There are no record!
		@endif
		</div>
		@stop
	</div>
</div>