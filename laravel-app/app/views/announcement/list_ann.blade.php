@section('title', 'List Announcement')
@section('content')

<?php
$baseUrl = URL::to('/');
?>
<script type="text/javascript">
	
	$(document).ready(function(){
		var link;
		var title = '';
		
		 $(".confirm").click(function(e) {
		 	link = this;
		 }); 
		 $(".confirm").confirm({
		     text: "Announcement's title is <span class='title'></span>. <br /> Are you sure you want delete?",
		     title: "Confirmation!",
		     confirm: function(button) {
		         // do something         
		     	//window.location = link.href;
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
		  	title = $(this).attr("title");
		  	$(".title").html(title);
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
		    //.attr('onclick','$(this).find("form").submit();');
		});
		
		
	});
	
</script>
@include('layouts.partial.render-message')
<h4>Announcement List</h4>
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
				      		if (Entrust::can('add_announcement'))
			    				echo link_to('announcements/create', 'Add Announcement');
				      	?>
					  </li>
					</ul>
				</div>
			</div>
			{{ Form::open(array('url' => 'announcements/search')) }}
				
  	 			<div class="row">
	  	 			<div class="col-md-2">
  	 					<label>Title:</label>
  	 				</div>
  	 				<div class="col-md-2">
			  	 	    <?php $title = ''; if (Input::has('title')){$title = Input::get('title');}?>
			  	 	    {{Form::text('title',$title)}}
  	 				</div>
  	 				<div class="col-md-2">
  	 					<label>Status:</label>
  	 				</div>
  	 				<div class="col-md-2">
			  	 	    <?php $status = ''; if (Input::has('status')){$status = Input::get('status');$status = $status == 1 ?1:0;}
			  	 	    	  $listStatus = array('1'=>'active','0'=>'inactive');
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
		@if ($anns->count())
		    <table class="table table-striped table-bordered">
		        <thead>
		            <tr>
		                <th>No</th>
				        <th>Title</th>
				        <th>Message EN</th>
				        <th>Message KH</th>
				        <th>Start Date</th>
				        <th>End Date</th>
				        <th>Status</th>
				        <th>Action</th>
		            </tr>
		        </thead>
		        <tbody>
		        	<?php $i = ($anns->getCurrentPage() - 1)* $anns->getPerPage(); ?>
		            @foreach ($anns as $ann)
		            	<?php $i += 1; ?>
		                <tr>
		                    <td>{{ $i }}</td>
		          			<td>{{ $ann->title }}</td>
		          			<td>{{ $ann->message_en }}</td>
		          			<td>{{ $ann->message_kh }}</td>
		          			<td>{{ $ann->start_date }}</td>
		          			<td>{{ $ann->end_date }}</td>
		          			<td>
		          				<?php
		          				if ($ann->status == 1) {
		          					echo 'active';
		          				} else {
		          					echo 'inactive';
		          				}
		          				?>
		          			</td>
		          			<td>
		          				<?php
						      		if (Entrust::can('edit_announcement'))
					    				echo link_to_route('announcements.edit', 'Edit', array($ann->id));
									//if (Entrust::can('delete_announcement'))
					    				//echo ' '.link_to_route('announcements.destroy', 'Delete', array($ann->id),array('class'=>'confirm','data-method' => 'delete', 'title'=>$ann->title));
						      	?>
		          			</td>
		          		</tr>
		          	@endforeach
		        </tbody>
		    </table>
			<?php echo $anns->links(); ?>
		@else
		    There are no Announcements!
		@endif
		</div>
		@stop
	</div>
</div>