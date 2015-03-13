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
<h4>Summary Balance Report</h4>
<div class="row">
	<div class="col-md-12">
		<!-- Default panel contents -->
		<div class="panel panel-default">
		  <div class="panel-heading">
		  	<div class="row">
				{{ Form::open(array('url' => 'reports/dealer-summary-balance-report')) }}
		  	 		<div class="row">
		  	 			<div class="col-md-2">
	  	 					<label>Specific Date*:</label>
	  	 				</div>	  	 				
	  	 				<div class="col-md-2">
	  	 					<input required="required" type="text" id="datepicker_start_datetime" name="specific_date" value="<?php if(Input::has('specific_date')) {echo  Input::get('specific_date');}//else{echo date("Y-m-j").' 00:00';} ?>" />
	  	 				</div>
	  	 			</div>
	  	 			<div class="row">
	  	 				<div class="col-md-2">
	  	 					<label>Dealer Id:</label>
	  	 				</div>	  	 				
	  	 				<div class="col-md-2">
	  	 					<?php $dealerId = ''; if (Input::has('dealer__id')){$dealerId = Input::get('dealer__id');} ?>
	  	 					{{Form::text('dealer__id',$dealerId,array('pattern' => '\d*'))}}
	  	 				</div>
		  	 			<div class="col-md-2">
	  	 					{{ Form::label('balance_type', 'Balance Type*:') }}
	  	 				</div>	  	 				
	  	 				<div class="col-md-2">
	  	 					<?php $balanceType = ''; if (Input::has('balance_type')){$balanceType = Input::get('balance_type');} 
	  	 						$balanceTypes = array('post_balance'=>'Post Balance','post_balance_credit'=>'Post Balance Credit');	
	  	 					?>
	  	 					{{ Form::select('balance_type', $balanceTypes, $balanceType, array('id' => 'balance_type','required'=>'required')) }}
	  	 				</div>
	  	 			</div>
	  	 			<div class="row">
		  	 			<div class="col-md-2">
	  	 					{{ Form::label('wallet_type__id', 'Wallet Type*:') }}
	  	 				</div>	  	 				
	  	 				<div class="col-md-2">
	  	 					<?php $walletTypeId = ''; if (Input::has('wallet_type__id')){$walletTypeId = Input::get('wallet_type__id');} ?>
	  	 					{{ Form::select('wallet_type__id', array('' => 'Please Select')+$wallet_types, $walletTypeId, array('id' => 'wallet_type__id','required'=>'required')) }}
	  	 				</div>
	  	 				<div class="col-md-2">
	  	 					{{ Form::label('wallet_currency_id', 'Wallet Currency*:') }}
	  	 				</div>	  	 				
	  	 				<div class="col-md-2">
	  	 					<?php $walletCurrencyId = ''; if (Input::has('wallet_currency_id')){$walletCurrencyId = Input::get('wallet_currency_id');} ?>
	  	 					{{ Form::select('wallet_currency_id', array('' => 'Please Select')+$currencies, $walletCurrencyId, array('id' => 'tcy_currency_id','required'=>'required')) }}
	  	 				</div>
	  	 			</div>
	  	 			<div class="row">
		  	 			<div class="col-md-2">
	  	 					{{ Form::label('wallet_type__id', 'User Category:') }}
	  	 				</div>	  	 				
	  	 				<div class="col-md-2">
	  	 					<?php $userCategory = ''; if (Input::has('user_category')){$userCategory = Input::get('user_category');} 
	  	 						$userCategories = array('channel'=>'Channel','stock'=>'Stock');
	  	 					?>
	  	 					{{ Form::select('user_category', $userCategories, $userCategory, array('id' => 'wallet_type__id')) }}
	  	 				</div>
	  	 				<div class="col-md-2">
	  	 					{{ Form::label('dealer_type__id', 'User Type:') }}
	  	 				</div>	  	 				
	  	 				<div class="col-md-2">
	  	 					<?php $dealerTypeId = ''; if (Input::has('dealer_type__id')){$dealerTypeId = Input::get('dealer_type__id');} ?>
	  	 					{{ Form::select('dealer_type__id', array('' => 'Please Select')+$dealerTypeIds, $dealerTypeId, array('id' => 'tcy_currency_id')) }}
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
					  		$conditions = array('1'=>'Dealer Balance < 100,000','2'=>'Dealer Balance > 800,000');
					  		?>
					  		{{ Form::select('condition', array('' => 'Please Select')+$conditions, $condition, ['id' => 'province']) }}
	  	 				</div>
	  	 			</div>
	  	 			<div class="row">
		  	 			<div class="col-md-2">
	  	 					<label>Account Type:</label>
	  	 				</div>
	  	 				<div class="col-md-2">
				  	 	    <?php $type_account = ''; if (Input::has('type_account')){$type_account = Input::get('type_account');}
				  	 	    	  $type_accounts = array('real'=>'Real','test'=>'Test');
				  	 	   	?>
				  	 	    {{ Form::select('type_account',$type_accounts, $type_account, ['id' => 'type_account']) }}
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
				        <th>Wallet Type</th>
				        <th>Wallet Currency</th>
				        <th>
				        	<?php
				        		if($balanceType == 'post_balance') {
				        			echo 'Post Balance';
				        		} else {
				        			echo 'Post Balance Credit';
				        		}
				        	?>	
				        </th>
				        <th>Date Time</th>
		            </tr>
		        </thead>
		        <tbody>
		        	<?php $i = ($dealersSummaryBalance->getCurrentPage() - 1)* $dealersSummaryBalance->getPerPage(); ?>
		            @foreach ($dealersSummaryBalance as $ob)
		            	<?php $i += 1; ?>
		                <tr>
		                    <td>{{ $i }}
		                    </td>
		                    <td>{{ $ob->id }}</td>
		          			<td>{{ $ob->name }}</td>
		          			<td>{{ $ob->province }}</td>
		          			<td>{{ $ob->district }}</td>
		          			<td>{{ $ob->wallet_type__id }}</td>
		          			<td>{{ $ob->wallet_currency__id }}</td>
		          			<td align="right">
		          				<?php
		          					$amount = 0;
					        		if($balanceType == 'post_balance') {
					        			$amount =  $ob->post_balance;
					        		} else {
					        			
										//if ($ob->dealer_type__id == '9') {
											//echo $ob->post_balance;
										//} else {
											$amount = $ob->post_balance_credit;
										//}
					        		}
									
									if ($amount != null) {
										echo number_format($amount,2);
									} else {
										echo 0;
									}
					        	?>
		          				{{ $ob->wallet_currency__id }}
		          			</td>
		          			<td>{{ $ob->datetime }}</td>
		          		</tr>
		          	@endforeach
		        </tbody>
		    </table>
			<?php echo $dealersSummaryBalance->links(); ?>
			<div>
				<table>
					<tr>
						<td><h3>Total Account:</h3></td>
	          			<td><h3>{{ number_format(count($dealersSummaryBalanceSumAll)) }}</h3></td>
	          			<td style="width: 264px;"></td>
	          			<td><h3>Total Account's Balance:</h3></td>
	          			<td><h3>{{ number_format($sumbalance,2) }} {{$walletCurrencyId}}</h3></td>
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