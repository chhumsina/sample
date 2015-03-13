@section('title', 'List Sale Staff')
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
<h4>Sale Staff List (<?php if(isset($sales_count)) echo "	Total : ". $sales_count; ?>)</h4>
<div class="row">
	<div class="col-md-12">
		<!-- Default panel contents -->
		<div class="panel panel-default">
		  <div class="panel-heading">
		  	<div class="row">
				<div class="col-md-12">
					<ul class="nav nav-pills" role="tablist">
					  <!-- <li role="presentation" class="active"><a href="#">Home</a></li> -->
					  <li role="presentation">
					  	<?php
				      		if (Entrust::can('add_sale_staff'))
			    				echo link_to('sales/create', 'Add Sale Staff');
				      	?>
					  </li>
					</ul>
				</div>
			</div>
			{{ Form::open(array('url' => 'sales/search')) }}
				<div class="row">
	  	 			<div class="col-md-2">
  	 					<label>Sale ID:</label>
  	 				</div>	  	 				
  	 				<div class="col-md-2">
  	 					<?php $saleStaffId = ''; if (Input::has('sale_staff_id')){$id = Input::get('sale_staff_id');} ?>
  	 					{{Form::text('sale_staff_id',$saleStaffId,array('pattern' => '\d*'))}}
  	 				</div>
  	 			</div>
				<div class="row">
	  	 			<div class="col-md-2">
  	 					<label>Sale Ecard ID:</label>
  	 				</div>	  	 				
  	 				<div class="col-md-2">
  	 					<?php $id = ''; if (Input::has('id')){$id = Input::get('id');} ?>
  	 					{{Form::text('id',$id)}}
  	 				</div>
  	 				<div class="col-md-2">
  	 					<label>Sale Name:</label>
  	 				</div>
  	 				<div class="col-md-2">
			  	 	    <?php $name = ''; if (Input::has('name')){$name = Input::get('name');}?>
			  	 	    {{Form::text('name',$name)}}
  	 				</div>
  	 			</div>
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
		@if ($sales->count())
		    <table class="table table-striped table-bordered">
		        <thead>
		            <tr>
		                <th>No</th>
				        <th>Sale Id</th>
				        <th>Sale Ecard Id</th>
				        <th>Sale Name</th>
				        <th>Position</th>
				        <th>Phone</th>
				        <th>Email</th>
				        <th>Report To Sale Id</th>
				        <th>Status</th>
				        <th>Action</th>
		            </tr>
		        </thead>
		        <tbody>
		        	<?php $i = ($sales->getCurrentPage() - 1)* $sales->getPerPage(); ?>
		            @foreach ($sales as $sale)
		            	<?php $i += 1; ?>
		                <tr>
		                    <td>{{ $i }}</td>
		                    <td>{{ $sale->sale_staff_id }}</td>
		          			<td>{{ $sale->id }}</td>
		          			<td>{{ $sale->name }}</td>
		          			<td>{{ $sale->position }}</td>
		          			<td>{{ $sale->phone }}</td>
		          			<td>{{ $sale->email }}</td>
		          			<td>{{ $sale->parent_id }}</td>
		          			<td>{{ $sale->status }}</td>
		          			<td>
						      	<?php
							  	if (Entrust::can('edit_sale_staff')) {
									?>
									<a href="{{ URL::route('sales.edit', array($sale->sale_staff_id)) }}">
									     <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
									</a>
									<?php
								}
								if (Entrust::can('delete_sale_staff')) {
									?>
									<a href="{{ URL::route('sales.destroy', array($sale->sale_staff_id)) }}" replaceField="{{$sale->sale_staff_id}}" class="confirm" data-method="delete">
									     <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
									</a>
									<?php
								}
		          				?>
		          				<a href="{{ URL::route('sales.show', array($sale->sale_staff_id)) }}">
								     <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
								</a>
		          			</td>
		          		</tr>
		          	@endforeach
		        </tbody>
		    </table>
			<?php echo $sales->links(); ?>
		@else
		    There are no sale staff!
		@endif
		</div>
		@stop
	</div>
</div>