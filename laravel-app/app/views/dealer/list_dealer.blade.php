@section('title', 'Dealers List')
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
		$khanCode = '{{$khanCode}}';
		$communeCode = '{{$communeCode}}';
		$villageCode = '{{$villageCode}}';
		var seleteType = $("#dealer_type__id");
		var seleteProvince = $("#province");
		var seleteKhan = $("#khan__code");
		var seleteCommune = $("#commune__code");
		var seleteVillage = $("#village__code");
		var plsSelect = 'Please Select';
		
		seleteType.on("change",function(){
			setType($(this));
			
			if ($(this).val() == '4') {
				$("#parent_dealer_id").show();
			} else {
				$("#parent_dealer_id").hide();
			}
		});
		seleteProvince.on("change",function(){
			setProvince($(this));
		});
		seleteKhan.on("change",function(){
			setDistrict($(this));
		});
		seleteCommune.on("change",function(){
			setCommune($(this));
		});
		setType(seleteType);
		setProvince(seleteProvince);
		setDistrict(seleteKhan);
		function setType(ob) {
			$val = ob.val();
			if ($val == '') {
				$val = 'x';
			}
			$("#idType").html($val);
		}
		function setProvince(ob) {
			$val = ob.val();
			if ($val == '') {
				$val = 'xx';
			} else {
				loadDistrictByProvinceCode($val);
			}
			$("#idProvince").html($val);
		}
		function setDistrict(ob) {
			$val = ob.val();
			if ($val == '') {
				$val = 'xxxx';
			}
			$("#idDistrict").html($val.substring(2));
			loadCommuneByKhanCode($val);
		}
		function setCommune(ob) {
			$val = ob.val();
			loadVillageByCommuneCode($val);
		}
		function loadDistrictByProvinceCode($code) {
			seleteKhan.html('');
			seleteKhan.append('<option value="">'+plsSelect+'</option>');
			seleteCommune.html('');
			seleteCommune.append('<option value="">'+plsSelect+'</option>');
			seleteVillage.html('');
			seleteVillage.append('<option value="">'+plsSelect+'</option>');
						
			var url = $baseUrl+'/locations/khan-by-province-code/'+$code;
			console.log(url);
	    	$.ajax({ 
		        type: 'GET', 
		        url: url, 
		        data: { code: $code }, 
		        success: function (data) {
		        	console.log(data);
		        	Object.keys(data).forEach(function (key) { 
		        	    var value = data[key];
		        	    console.log("key ="+key);
		        	    console.log("$khanCode = "+$khanCode);
		        	    if ($khanCode != '') {
		        	    	
		        	    	if ($khanCode == key) {
		        	    		seleteKhan.append('<option value="'+key+'" selected="selected">('+key+') '+data[key]+'</option>');
							} else {
								seleteKhan.append('<option value="'+key+'">('+key+') '+data[key]+'</option>');
							}
		        	    } else {
		        	    	seleteKhan.append('<option value="'+key+'">('+key+') '+data[key]+'</option>');
		        	    }
		        	    
		        	    // iteration code
		        	});
		        }
		    });
		}
		
		function loadCommuneByKhanCode($code) {
			seleteCommune.html('');
			seleteCommune.append('<option value="">'+plsSelect+'</option>');
			seleteVillage.html('');
			seleteVillage.append('<option value="">'+plsSelect+'</option>');
						
			var url = $baseUrl+'/locations/commune-by-khan-code/'+$code;
			console.log(url);
	    	$.ajax({ 
		        type: 'GET', 
		        url: url, 
		        data: { code: $code }, 
		        success: function (data) {
		        	console.log(data);
		        	Object.keys(data).forEach(function (key) { 
		        	    var value = data[key];
		        	    console.log("key ="+key);
		        	    console.log("$communeCode = "+$communeCode);
		        	    if ($communeCode != '') {
		        	    	
		        	    	if ($communeCode == key) {
		        	    		seleteCommune.append('<option value="'+key+'" selected="selected">('+key+') '+data[key]+'</option>');
							} else {
								seleteCommune.append('<option value="'+key+'">('+key+') '+data[key]+'</option>');
							}
		        	    } else {
		        	    	seleteCommune.append('<option value="'+key+'">('+key+') '+data[key]+'</option>');
		        	    }
		        	    
		        	    // iteration code
		        	});
		        }
		    });
		}
		
		function loadVillageByCommuneCode($code) {
			seleteVillage.html('');
			seleteVillage.append('<option value="">'+plsSelect+'</option>');
						
			var url = $baseUrl+'/locations/village-by-commune-code/'+$code;
			console.log(url);
	    	$.ajax({ 
		        type: 'GET', 
		        url: url, 
		        data: { code: $code }, 
		        success: function (data) {
		        	console.log(data);
		        	Object.keys(data).forEach(function (key) { 
		        	    var value = data[key];
		        	    console.log("key ="+key);
		        	    console.log("$villageCode = "+$villageCode);
		        	    if ($villageCode != '') {
		        	    	
		        	    	if ($villageCode == key) {
		        	    		seleteVillage.append('<option value="'+key+'" selected="selected">('+key+') '+data[key]+'</option>');
							} else {
								seleteVillage.append('<option value="'+key+'">('+key+') '+data[key]+'</option>');
							}
		        	    } else {
		        	    	seleteVillage.append('<option value="'+key+'">('+key+') '+data[key]+'</option>');
		        	    }
		        	    
		        	    // iteration code
		        	});
		        }
		    });
		}
		
		if ($("#dealer_type__id").val() == '4') {
			$("#parent_dealer_id").show();
		} else {
			$("#parent_dealer_id").hide();
		}
		if ($khanCode != '') {
			
		}
		
		
		/*----------------------------------------*/
		var link;
		var replaceField = '';
		
		 $(".confirm").click(function(e) {
		 	link = this;
		 }); 
		 $(".confirm").confirm({
		     text: "Dealer's ID is <span class='replaceField'></span>. <br /> Are you sure you want delete?",
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
<h4>Dealers List</h4>
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
					  	if (Entrust::can('add_dealer'))
    						echo link_to('dealers/create', 'Add Dealer');
					  	?>
					  </li>
					</ul>
				</div>
			</div>
			{{ Form::open(array('url' => 'dealers/search')) }}
				<div class="row">
	  	 			<div class="col-md-2">
  	 					<label>DID:</label>
  	 				</div>	  	 				
  	 				<div class="col-md-2">
  	 					<?php $did = ''; if (Input::has('id')){$did = Input::get('id');} ?>
  	 					{{Form::text('id',$did,array('pattern' => '\d*'))}}
  	 				</div>
  	 				<div class="col-md-2">
  	 					<label>Name:</label>
  	 				</div>
  	 				<div class="col-md-2">
			  	 	    <?php $name = ''; if (Input::has('name')){$name = Input::get('name');}?>
			  	 	    {{Form::text('name',$name)}}
  	 				</div>
  	 			</div>
  	 			<div class="row">
	  	 			<div class="col-md-2">
  	 					<label>Type:</label>
  	 				</div>	  	 				
  	 				<div class="col-md-2">
  	 					<?php $type_id = ''; if (Input::has('dealer_type__id')){$type_id = Input::get('dealer_type__id');} ?>
  	 					<?php
				  		foreach ($listDealerTypes as $key => $item) {
							$listDealerTypes[$key] = '('.$key.') ' . $item;
						}
				  		?>
  	 					{{ Form::select('dealer_type__id', array('' => 'Please Select')+$listDealerTypes, $type_id, ['id' => 'dealer_type__id']) }}
  	 				</div>
  	 				<div class="col-md-2">
  	 					<label>Status:</label>
  	 				</div>
  	 				<div class="col-md-2">
			  	 	    <?php $status = ''; if (Input::has('status')){$status = Input::get('status');}
			  	 	    	  $listStatus = array('active'=>'active','inactive'=>'inactive','used'=>'used', 'reject'=>'reject');
			  	 	   	?>
			  	 	    {{ Form::select('status', array('' => 'Please Select')+$listStatus, $status, ['id' => 'status']) }}
  	 				</div>
  	 			</div>
  	 			<div class="row">
		  	 			<div class="col-md-2">
	  	 					<label>Province/City:</label>
	  	 				</div>	  	 				
	  	 				<div class="col-md-2">
	  	 					<?php $provinceCode = ''; if (Input::has('province__code')){$provinceCode = Input::get('province__code');} ?>
	  	 					<?php
					  		foreach ($listProvinces as $key => $item) {
								$listProvinces[$key] = '('.$key.') ' . $item;
							}
					  		?>
					  		{{ Form::select('province__code', array('' => 'Please Select')+$listProvinces, $provinceCode, ['id' => 'province']) }}
	  	 				
	  	 				</div>
	  	 				<div class="col-md-2">
	  	 					<label>District/Khan:</label>
	  	 				</div>
	  	 				<div class="col-md-2">
				  	 	    <?php $dealerName = ''; if (Input::has('dealerName')){$dealerName = Input::get('dealerName');}?>
				  	 	    {{ Form::select('khan__code', array('' => 'Please Select'),null, array('id' => 'khan__code')) }}
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
		@if ($dealers->count())
		    <table class="table table-striped table-bordered">
		        <thead>
		            <tr>
		                <th>No</th>
				        <th>DID</th>
				        <th>Name</th>
				        <th>Type</th>
				        <!--<th>Wallet</th>-->
				        <th>Phone</th>
				        <th>Khan Code</th>
				        <th>Created At</th>
				        <th>Created By</th>
				        <th>Status</th>
				        <th>Action</th>
		            </tr>
		        </thead>
		        <tbody>
		        	<?php $i = ($dealers->getCurrentPage() - 1)* $dealers->getPerPage(); ?>
		            @foreach ($dealers as $dealer)
		            	<?php $i += 1; ?>
		                <tr>
		                    <td>{{ $i }}</td>
		          			<td>{{ $dealer->id }}</td>
		          			<td>{{ $dealer->name }}</td>
		          			<td>{{ $dealer->tname }}</td>
		          			<!--<td>{{ $dealer->wallet }}</td>-->
		          			<td>{{ $dealer->phone }}</td>
		          			<td>{{ $dealer->khan__code }}</td>
		          			<td>{{ $dealer->created_at }}</td>
		          			<td>{{ $dealer->sname }}</td>
		          			<td>{{ $dealer->status }}
		          			</td>
		          			<td>
		          				<?php
							  	if (Entrust::can('edit_dealer')) {
									?>
									<a href="{{ URL::route('dealers.edit', array($dealer->id)) }}">
									     <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
									</a>
									<?php
		    						//echo link_to_route('dealers.edit', 'Edit', array($dealer->id));
								}
								if (Entrust::can('delete_dealer')) {
									if ($dealer->status != 'used') {
										?>
										<a href="{{ URL::route('dealers.destroy', array($dealer->id)) }}" replaceField="{{$dealer->id}}" class="confirm" data-method="delete">
										     <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
										</a>
										<?php
	          							//echo ' '.link_to_route('dealers.destroy', 'Delete', array($dealer->id),array('class'=>'confirm','data-method' => 'delete', 'replaceField'=>$dealer->id));
	          						}
								}
		          				?>
		          				<a href="{{ URL::route('dealers.show', array($dealer->id)) }}">
								     <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
								</a>
		          			</td>
		          		</tr>
		          	@endforeach
		        </tbody>
		    </table>
			<?php echo $dealers->links();?>
		@else
		    There are no record!
		@endif
		</div>
		@stop
	</div>
</div>