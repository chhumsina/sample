@section('title', 'Dealer Summary Balance Report')
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
	});
</script>
@include('layouts.partial.render-message')
<h4>Dealer Summary Balance Report</h4>
<div class="row">
	<div class="col-md-12">
		<!-- Default panel contents -->
		<div class="panel panel-default">
		  <div class="panel-heading">
		  	<div class="row">
				{{ Form::open(array('url' => 'reports/dealer-summary-balance-report')) }}
		  	 		<div class="row">
		  	 			<div class="col-md-2">
	  	 					<label>Specific Date:</label>
	  	 				</div>	  	 				
	  	 				<div class="col-md-2">
	  	 					<input required="required" type="text" id="datepicker_start_datetime" name="specific_date" value="<?php if(Input::has('specific_date')) {echo  Input::get('specific_date');}//else{echo date("Y-m-j").' 00:00';} ?>" />
	  	 				</div>
	  	 				<div class="col-md-2">
	  	 					<label>Dealer Id:</label>
	  	 				</div>	  	 				
	  	 				<div class="col-md-2">
	  	 					<?php $dealerId = ''; if (Input::has('dealer__id')){$dealerId = Input::get('dealer__id');} ?>
	  	 					{{Form::text('dealer__id',$dealerId,array('pattern' => '\d*'))}}
	  	 				</div>
	  	 			</div>
	  	 			<div class="row">
		  	 			<div class="col-md-2">
	  	 					<label>Dealer Province:</label>
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
	  	 					<label>Dealer District:</label>
	  	 				</div>
	  	 				<div class="col-md-2">
				  	 	    <?php $dealerName = ''; if (Input::has('dealerName')){$dealerName = Input::get('dealerName');}?>
				  	 	    {{ Form::select('khan__code', array('' => 'Please Select'),null, array('id' => 'khan__code')) }}
	  	 				</div>
	  	 			</div>
	  	 			<div class="row">
		  	 			<div class="col-md-2">
	  	 					<label>Dealer Condition Balance:</label>
	  	 				</div>	  	 				
	  	 				<div class="col-md-2">
	  	 					<?php $condition = ''; if (Input::has('condition')){$condition = Input::get('condition');} ?>
	  	 					<?php
					  		$conditions = array('1'=>'Dealer Balance < 100,000 KHR','2'=>'Dealer Balance > 800,000 KHR');
					  		?>
					  		{{ Form::select('condition', array('' => 'Please Select')+$conditions, $condition, ['id' => 'province']) }}
	  	 				
	  	 				</div>
	  	 				<div class="col-md-1">
	  	 					<input type="submit" name="search_terminal" value="Search" />
	  	 				</div>
	  	 			</div>
	  	 		{{ Form::close() }}
			</div>	
		  </div>	
		  <!-- Table -->
		@if ($dealersSummaryBalance !=null && $dealersSummaryBalance->count())
		    <table class="table table-striped table-bordered">
		        <thead>
		            <tr>
		                <th>No</th>
				        <th>Dealer ID</th>
				        <th>Dealer Name</th>
				        <th>Province</th>
				        <th>District</th>
				        <th>Balance</th>
				        <th>Date Time</th>
		            </tr>
		        </thead>
		        <tbody>
		        	<?php $i = ($dealersSummaryBalance->getCurrentPage() - 1)* $dealersSummaryBalance->getPerPage(); ?>
		            @foreach ($dealersSummaryBalance as $ob)
		            	<?php $i += 1; ?>
		                <tr>
		                    <td>{{ $i }}
		                    <input type="hidden" value="{{$ob->tbl}}" name="take_from_tbl"/>
		                    <input type="hidden" value="{{$ob->txn_id}}" name="take_from_tbl_id"/>	
		                    </td>
		                    <td>{{ $ob->id }}</td>
		          			<td>{{ $ob->name }}</td>
		          			<td>{{ $ob->province }}</td>
		          			<td>{{ $ob->district }}</td>
		          			<td>{{ $ob->post_balance }}</td>
		          			<td>{{ $ob->datetime }}</td>
		          		</tr>
		          	@endforeach
		        </tbody>
		    </table>
			<?php echo $dealersSummaryBalance->links(); ?>
			<div>
				<table>
					<tr>
						<td><h3>Total Dealers:</h3></td>
	          			<td><h3>{{ number_format(count($dealersSummaryBalanceSumAll)) }}</h3></td>
	          			<td style="width: 264px;"></td>
	          			<td><h3>Total Dealers's Balance:</h3></td>
	          			<td><h3>{{ number_format($sumbalance) }} KHR</h3></td>
	          			<td></td>
	          		</tr>
				</table>
			</div>
			
		@else
		    There are no record!
		@endif
		</div>
		@stop
	</div>
</div>