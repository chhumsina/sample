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
<h4>Actual Channel Sale</h4>
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
					  	if (Entrust::can('add_actual_sale')) {
					  		echo link_to('sale-actual/create-sale-actual', 'Add Actual Channel Sale');
					  	}	
					  	?>
					  </li>
					  <li role="presentation">
					  	<?php
						if (Entrust::can('upload_actual_sale')) {
					  		echo link_to('sale-actual/upload-sale-actual', 'Upload Actual Channel Sale');
					  	}	
					  	?>
					  </li>
					  <li role="presentation">
					  	<?php
						if (Entrust::can('upload_actual_sale')) {
					  		echo link_to('upload-sale-actuals', 'List Upload Actual Channel Sale');
					  	}	
					  	?>
					  </li>
					  
					</ul>
				</div>
			</div>
			{{ Form::open(array('url' => 'sale-actual/index')) }}
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
  	 			</div>
  	 			<div class="row">
  	 				<div class="col-md-2">
  	 					<label>Channel Owner:</label>
  	 				</div>
  	 				<div class="col-md-2">
			  	 	    <?php $channelOwner = ''; if (Input::has('channel_owner__id')){$channelOwner = Input::get('channel_owner__id');}
			  	 	   	?>
			  	 	    {{ Form::select('channel_owner__id', array('' => 'Please Select')+$channelOwners, $channelOwner, ['id' => 'channel_owner__id']) }}
  	 				</div>
  	 				<div class="col-md-1">
  	 					<input type="submit" name="search_terminal" value="Search" />
  	 				</div>
  	 			</div>
  	 		{{ Form::close() }}	
		  </div>	
		  <!-- Table -->
		@if ($actualSales->count())
		    <table class="table table-striped table-bordered">
		        <thead>
		            <tr>
		                <th>No</th>
				        <th>Channel Owner</th>
				        <th>Sale Date</th>
				        <!--<th>Currency</th>-->
				        <th>Sale Game</th>
				        <th>Draw 639 N#</th>
				        <th>Date Draw 639</th>
				        <th>Draw Pick 5 N#</th>
				        <th>Date Draw Pick 5</th>
				        <th>#Subscriber 639</th>
				        <th>#Subscriber Pick5</th>
				        <th>#Ticket 639</th>
				        <th>#Ticket Pick 5</th>
				        <th>Active POS 639</th>
				        <th>Active POS Pick5</th>
				        <th>Created At</th>
				        <th>Created By</th>
				        <th>Action</th>
		            </tr>
		        </thead>
		        <tbody>
		        	<?php $i = ($actualSales->getCurrentPage() - 1)* $actualSales->getPerPage(); ?>
		            @foreach ($actualSales as $actualSale)
		            	<?php $i += 1; ?>
		                <tr>
		                    <td>{{ $i }}</td>
		                    <td>{{ $actualSale->channel_owner_name }}</td>
		          			<td>{{ $actualSale->datetime }}</td>
		          			<td>
		          				<ul>
		          				<?php
		          					/*$listGameSales = DB::table('rep_channel_sale_service_type as csst')
													->select('csst.*')
													->join('sys_service_type as st','st.service_type_id','=','csst.service_type__id')
													->orderBy('st.sequence_number')
													->where('csst.channel_sale__id',$actualSale->channel_sale_id)
													->get();
												
									foreach ($listGameSales as $key => $gameSale) {
										echo '<li style="list-style:none;">'.$gameSale->service_type__id.' = '.$gameSale->amount.'</li>';
									}*/
		          				?>
		          				</ul>
		          			</td>
		          			<td>{{ $actualSale->draw_639_n }}</td>
		          			<td>{{ $actualSale->draw_639_date }}</td>
		          			<td>{{ $actualSale->draw_pick_5_n }}</td>
		          			<td>{{ $actualSale->draw_pick5_date }}</td>
		          			<td>{{ $actualSale->subscriber_639_n }}</td>
		          			<td>{{ $actualSale->subscriber_pick_5 }}</td>
		          			<td>{{ $actualSale->ticket_639 }}</td>
		          			<td>{{ $actualSale->ticket_pick_5 }}</td>
		          			<td>{{ $actualSale->active_pos_639 }}</td>
		          			<td>{{ $actualSale->active_pos_pick5 }}</td>
		          			<td>{{ $actualSale->created_at }}</td>
		          			<td>{{ $actualSale->createdBy }}</td>
		          			<td>
						      	<?php
								if (Entrust::can('edit_actual_sale')) {
									?>
									<a href="{{ 'sale-actual/'.$actualSale->channel_sale_id.'/edit' }}">
									     <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
									</a>
									<?php
								}
								if (Entrust::can('delete_actual_sale')) {
									?>
									<a href="{{ URL::route('sale-actual.destroy', array($actualSale->channel_sale_id)) }}" replaceField="{{$actualSale->channel_sale_id}}" class="confirm" data-method="delete">
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
			<?php echo $actualSales->links(); ?>
		@else
		    There are no record!
		@endif
		</div>
		@stop
	</div>
</div>