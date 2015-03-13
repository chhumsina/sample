@section('title', 'Detail Dealer')
@section('content')
<?php
$baseUrl = URL::to('/');
?>
<script type="text/javascript">
	$(document).ready(function(){
		$baseUrl = '{{$baseUrl}}';
		
	});
</script>
<h4>Detail Dealer</h4>
@include('layouts.partial.render-message')
<div class="row">
	<div class="col-md-12">
		{{ Form::model($dealer, array('method' => 'PATCH', 'route' =>array('dealers.update', $dealer->id))) }}
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('did', 'DID:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{$dealer->id}}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('name', 'Name*:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ $dealer->name }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('national_card_id', 'National ID:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ $dealer->national_card_id}}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('phone', 'Phone:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ $dealer->phone}}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('email', 'Email:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ $dealer->email }}
			  	</div>
			</div>
			
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('dealer_type__id', 'Dealer Type*:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ $dealerTypes->name }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('street', 'Address:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ $dealer->street }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('province', 'Province/City *:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ $khan->pname }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('khan__code', 'District/Khan *:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ $khan->name_en }}
			  	</div>
			</div>
			
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('commune__code', 'Commune *:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ $dealer->commune__code }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('village__code', 'Village *:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ $dealer->village__code}}
			  	</div>
			</div>
			<?php
			if ($dealer->dealer_type__id == 4) { ?>
				<div class="row" >
				  	<div class="col-md-4 text-right">
				  		{{ Form::label('parent_id', 'Parent Dealer Id:') }}
				  	</div>
				  	<div class="col-md-4 text-left">
				  		{{ $dealer->parent_id }}
				  	</div>
				</div>
			<?php } ?>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('reference_dealer_id', 'Reference Dealer ID:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ $dealer->reference_dealer_id }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('status', 'Status:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ $dealer->status}}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('status', 'Status Game:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ $dealer->status_game}}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('status', 'Status Other Service:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ $dealer->status_os}}
			  	</div>
			</div>
			
			<?php
				$i = 0;
				//print_r($dealerBanks);
				//die();
				foreach ($dealerBanks as $dealerBank) {
					$i++;
			?>
				<div class="row">
				  	<div class="col-md-4 text-right">
				  		<?php echo Form::label('bank__id', "Bank $i:")?>
				  	</div>
				  	<div class="col-md-4 text-left">
				  		{{$dealerBank->bank_name}}
				  	</div>
				</div>
				<div class="row">
				  	<div class="col-md-4 text-right">
				  		<?php echo Form::label('account_name', "Bank Account Name $i:")?>
				  	</div>
				  	<div class="col-md-4 text-left">
				  		{{$dealerBank->account_name}}
				  	</div>
				</div>
				<div class="row">
				  	<div class="col-md-4 text-right">
				  		<?php echo Form::label('account', "Bank Account $i:")?>
				  	</div>
				  	<div class="col-md-4 text-left">
				  		{{$dealerBank->account}}
				  	</div>
				</div>
			<?php
				}
			?>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('created_at', 'Created At:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ $dealer->created_at }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('staff__id', 'Created By:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		<?php
			  		if ($createdBy != null) {
			  			echo $createdBy->name;
			  		}
			  		?>
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('updated_at', 'Updated At:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ $dealer->updated_at }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('updated_by_staff__id', 'Updated By:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		<?php
			  		if ($updatedBy != null) {
			  			echo $updatedBy->name;
			  		}
			  		?>
			  	</div>
			</div>
			@include('layouts.partial.render-message-form')
		{{ Form::close() }}
	</div>
</div>
@stop