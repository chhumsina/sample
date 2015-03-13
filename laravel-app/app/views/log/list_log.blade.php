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
		
		$(".detial-log").click(function(ob){
			
			$id = $(this).attr('atr-id');
			//alert($id);
			$selector = "#"+$id;
			$($selector).slideToggle();
		});
		
	});
	
</script>
@include('layouts.partial.render-message')
<h4>System AuditTrail List</h4>
<div class="row">
	<div class="col-md-12">
		<!-- Default panel contents -->
		<div class="panel panel-default">
		  <div class="panel-heading">
			{{ Form::open(array('url' => 'sys-audits/search')) }}
				
  	 			<div class="row">
	  	 			<div class="col-md-2">
  	 					<label>Staff Name:</label>
  	 				</div>
  	 				<div class="col-md-2">
			  	 	    <?php $staffName = ''; if (Input::has('name')){$staffName = Input::get('name');}?>
			  	 	    {{Form::text('name',$staffName)}}
  	 				</div>
  	 				<div class="col-md-2">
  	 					<label>Action:</label>
  	 				</div>
  	 				<div class="col-md-2">
			  	 	    <?php $action = ''; if (Input::has('action')){$action = Input::get('action');}
			  	 	    	  $listAction= array('add'=>'add','update'=>'update','upload'=>'upload','approve'=>'approve','reject'=>'reject','cancel'=>'cancel','delete'=>'delete','promotion'=>'promotion','deposit'=>'deposit','auth_fail'=>'auth_fail','change_password'=>'change_password');
			  	 	   	?>
			  	 	    {{ Form::select('action', array('' => 'Please Select')+$listAction, $action, ['id' => 'action']) }}
  	 				</div>
  	 			</div>
  	 			<div class="row">
  	 				<div class="col-md-2">
  	 					<label>Object Type:</label>
  	 				</div>
  	 				<div class="col-md-2">
			  	 	    <?php $objectType = ''; if (Input::has('object_type')){$objectType = Input::get('object_type');}
			  	 	    	  $listObjectType= array('dealer'=>'dealer','terminals'=>'terminals','terminal'=>'dealer-link-terminal','announcement'=>'announcement','sale'=>'sale',
							  'actual_sale'=>'actual_sale',
							  'dragon_warrior_target'=>'dragon_warrior_target',
							  'upload_actual_channel_sale'=>'upload_actual_channel_sale',
							  'upload_cancel_game'=>'upload_cancel_game',
							  'deposit_game'=>'deposit_game',
							  'deposit'=>'deposit',
							  'promotion_game'=>'promotion_game',
							  'promotion'=>'promotion',
							  'stock'=>'stock',
							  'stock_approval'=>'stock_approval',
							  'c_currency_conversion_rule'=>'currency_conversion_rule'
							  );
			  	 	   	?>
			  	 	    {{ Form::select('object_type', array('' => 'Please Select')+$listObjectType, $objectType, ['id' => 'object_type']) }}
  	 				</div>
	  	 			<div class="col-md-2">
  	 					<label>Object Id:</label>
  	 				</div>
  	 				<div class="col-md-2">
			  	 	    <?php $objectId = ''; if (Input::has('object__id')){$objectId = Input::get('object__id');}?>
			  	 	    {{Form::text('object__id',$objectId)}}
  	 				</div>
  	 			</div>
  	 			<div class="row">
	  	 			<div class="col-md-2">
  	 					<label>Date From:</label>
  	 				</div>	  	 				
  	 				<div class="col-md-2">
  	 					<input type="text" id="datepicker_start_datetime" name="start_date" value="<?php if(Input::has('start_date')) {echo  Input::get('start_date');}//else{echo date("Y-m-j").' 00:00';} ?>" />
  	 				</div>
  	 				<div class="col-md-2">
  	 					<label>To:</label>
  	 				</div>
  	 				<div class="col-md-2">
  	 					<input type="text" id="datepicker_end_datetime" name="end_date" value="<?php if(Input::has('end_date')) {echo  Input::get('end_date');}//else{echo date("Y-m-j").' 23:59';} ?>" />
  	 				</div>
  	 				<div class="col-md-1">
  	 					<input type="submit" name="search_terminal" value="Search" />
  	 				</div>
  	 			</div>
  	 		{{ Form::close() }}	
		  </div>	
		  <!-- Table -->
		@if ($logs->count())
		    <table class="table table-striped table-bordered">
		        <thead>
		            <tr>
		                <th>No</th>
				        <th>Who</th>
				        <th>Action</th>
				        <th>Object Type</th>
				        <th>Object ID</th>
				        <th>Action Date</th>
				        <th style="max-width: 180px;">Reason</th>
				        <th style="max-width: 37px;">Detial</th>
		            </tr>
		        </thead>
		        <tbody>
		        	<?php $i = ($logs->getCurrentPage() - 1)* $logs->getPerPage(); ?>
		            @foreach ($logs as $log)
		            	<?php $i += 1; ?>
		                <tr>
		                    <td>{{ $i }}</td>
		          			<td>
		          				<?php
		          				if ($log->staff__id == '0') {
									echo '<span class="red">'.'(System Noted)'.'</span> '.$log->atr_value;
		          				} else {
		          					echo $log->staffName;
		          				}
		          				?>	
		          			</td>
		          			<td>{{ $log->action }}</td>
		          			<td>
		          			<?php
		          			if ($log->object_type == 'terminal') {
		          				echo 'dealer-link-terminal';
		          			} else {
		          				echo $log->object_type;
		          			}
		          			?>	
		          			</td>
		          			<td>{{ $log->object__id }}</td>
		          			<td>
		          				<?php 
		          				try {
		          					//$ob = $log->created_at->toFormattedDateString();
									
		          					//$ob = $log->created_at->format('Y-M-D h:i:s');
									// strpos($log->created_at, '.');
									//echo $log->created_at->format('h:i:s');
									//Carbon::createFromFormat('Y-m-d H:i:s.u', $log->created_at)->format('d/m/Y H:i:s');
		          					//echo $log->created_at->toFormattedDateString();
		          					echo $log->created_at;
		          				} catch (Exception $e) {
		          					echo $e;
		          				}
		          				
		          				?></td>
		          			<td style="max-width: 180px;">{{ $log->reason }}</td>
		          			<td><div class="detial-log" atr-id="<?php echo $log->id?>">
		          				<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
		          				</div>
		          			</td>
		          		</tr>
		          		<tr style="display: none;" id="<?php echo $log->id?>">
		          			<td colspan="7" style="max-width: 900px;">
		          				<?php
		          				//$log->old_data
		          				//$log->new_data
		          				$oldOb = json_decode($log->old_data);
								$newOb = json_decode($log->new_data);
								if ($oldOb != null && $newOb != null) {
									echo '<h5>Update Field</h5>';
									echo '<ul>';
									$oldArray = get_object_vars($oldOb);
									$newArray = get_object_vars($newOb);
									foreach($newArray as $key => $value)
									{
									  	if ($key != '_token' && $key != 'staff__id' && $key != '_method' && $key != 'updated_by_staff__id'
										&& $key != 'updated_at') {
									  		
											if (array_key_exists($key,$oldArray) && $value != $oldArray[$key]) {
												
												if ($log->object_type == 'terminal') {
													if ($key == 'serial' || $key == 'dealer__id' || $key == 'status') {
														echo '<li><span class="field">'.$key.'</span> : change from <span class="value-old">'.$oldArray[$key].'</span> to <span class="value-new">'.$value.'</span></li>';
													}
												} else {
													echo '<li><span class="field">'.$key.'</span> : change from <span class="value-old">'.$oldArray[$key].'</span> to <span class="value-new">'.$value.'</span></li>';
												}
												
											}
									 	}
									}
									echo '</ul>';
									
									//echo 'old object';
									//$oldArray = get_object_vars($oldOb);
									//var_dump($oldArray);
									
									//foreach($oldArray as $key => $value)
									//{
									  //$mykey = key($array);
									  //echo $key.':';
									  //echo $value.'</br>';
									//}
									//echo $oldArray['name'];
		          					//var_dump(json_decode($log->old_data));
								} else if ($newOb != null) {
									echo '<h5>Input Field</h5>';
									echo '<ul>';
									$newArray = get_object_vars($newOb);
									foreach($newArray as $key => $value)
									{
									  	if ($key != '_token' && $key != 'staff__id' && $key != '_method' && $key != 'updated_by_staff__id'
										&& $key != 'updated_at') {
									  		if ($log->object_type == 'terminal') {
												if ($key == 'serial' || $key == 'dealer__id' || $key == 'status') {
													echo '<li><span class="field">'.$key.'</span> : <span class="value-new">'.$value.'</span></li>';
												}
											} else if ($log->object_type == 'upload_cancel_game') {
												if ($key == 'file_name') {
													echo '<li><span class="field">'.$key.'</span> : <span class="value-new"><a href="'.$baseUrl.'/images/upload_cancel_game/'.$value.'">'.$value.'</a></span></li>';
												} else {
													echo '<li><span class="field">'.$key.'</span> : <span class="value-new">'.$value.'</span></li>';
												}
											} else {
												echo '<li><span class="field">'.$key.'</span> : <span class="value-new">'.$value.'</span></li>';
											}
									 	}
									}
									echo '</ul>';
								}
		          				
		          				?>
		          			</td>
		          		</tr>
		          		</div>
		          	@endforeach
		        </tbody>
		    </table>
			<?php echo $logs->links(); ?>
		@else
		    There are no record!
		@endif
		</div>
		@stop
	</div>
</div>