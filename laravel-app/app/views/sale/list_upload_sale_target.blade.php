@section('title', 'Actual Channel Sale')
@section('content')
<?php
$baseUrl = URL::to('/');
?>
<script type="text/javascript">
	$(document).ready(function(){
		/*----------------------------------------*/
		var link;
		var replaceField = '';
		
		 $(".confirm").click(function(e) {
		 	link = this;
		 }); 
		 $(".confirm").confirm({
		     text: "Are you sure you want delete?",
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
<h4>List Upload Dragon Warrior Quota</h4>
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
						  if (Entrust::can('upload_dragon_warrior_target')){
							  echo link_to('sales/upload-sale-target', 'Upload Dragon Warrior Quota');
					  	}	
					  	?>
					  </li>
					</ul>
				</div>
			</div>
			{{ Form::open(array('url' => 'upload-sale-target/index')) }}
				<div class="row">
	  	 			<div class="col-md-2">
  	 					<label>Date From:</label>
  	 				</div>	  	 				
  	 				<div class="col-md-2">
  	 					<input required="required" type="text" id="datepicker_start" name="start_date" value="<?php if(Input::has('start_date')) {echo  Input::get('start_date');}else{ echo date("Y-m-j");} ?>" />
  	 				</div>
  	 				<div class="col-md-2">
  	 					<label>To:</label>
  	 				</div>
  	 				<div class="col-md-2">
  	 					<input required="required" type="text" id="datepicker_end" name="end_date" value="<?php if(Input::has('end_date')) {echo  Input::get('end_date');}else{echo date("Y-m-j");} ?>" />
  	 				</div>
  	 				<input type="submit" name="search_terminal" value="Search" />
  	 			</div>
  	 		{{ Form::close() }}	
		  </div>	
		  <!-- Table -->
		@if ($uploadSaleTargets->count())
		    <table class="table table-striped table-bordered">
		        <thead>
		            <tr>
		                <th>No</th>
				        <th>File Name</th>
				        <th>Remark</th>
				        <th>Uploaded At</th>
				        <th>Uploaded By</th>
				        <th>Action</th>
		            </tr>
		        </thead>
		        <tbody>
		        	<?php $i = ($uploadSaleTargets->getCurrentPage() - 1)* $uploadSaleTargets->getPerPage(); ?>
		            @foreach ($uploadSaleTargets as $uploadSaleTarget)
		            	<?php $i += 1; ?>
		                <tr>
		                    <td>{{ $i }}</td>
		                    <td><a href="{{ $baseUrl.'/images/upload_add_actual_channel_sale/'.$uploadSaleTarget->file_name }}">{{ $uploadSaleTarget->file_name}}</a></td>
		          			<th>{{ $uploadSaleTarget->remark }}</th>
		          			<td>{{ $uploadSaleTarget->created_at }}</td>
		          			<td>{{ $uploadSaleTarget->createdBy }}</td>
		          			<td>
						      	<?php
								if (Entrust::can('delete_upload_actual_sale')) {
									?>
									<a href="{{ URL::route('upload-sale-target.destroy', array($uploadSaleTarget->upload_id)) }}" replaceField="{{$uploadSaleTarget->upload_id}}" class="confirm" data-method="delete">
									     <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
									</a>
									<?php
								}
								?>
								
		          			</td>
		          		</tr>
		          	@endforeach
		        </tbody>
		    </table>
			<?php echo $uploadSaleTargets->links(); ?>
		@else
		    There are no record!
		@endif
		</div>
		@stop
	</div>
</div>