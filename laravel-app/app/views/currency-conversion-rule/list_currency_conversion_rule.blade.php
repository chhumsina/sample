@section('title', 'Currency Conversion Rule Exchange Rate')
@section('content')

<?php
$baseUrl = URL::to('/');
?>
<script type="text/javascript">
	
	$(document).ready(function(){
		$baseUrl = '{{$baseUrl}}';
		
	});
	
</script>
@include('layouts.partial.render-message')
<h4>Currency Conversion Rule Exchange Rates List</h4>
<div class="row">
	<div class="col-md-12">
		<!-- Default panel contents -->
		<div class="panel panel-default">	
		  <!-- Table -->
		@if ($currencyConversionRules->count())
		    <table class="table table-striped table-bordered">
		        <thead>
		            <tr>
		                <th>No</th>
				        <th>From Currency</th>
				        <th>To Currency</th>
				        <th>Buy Rate</th>
				        <th>Sale Rate</th>
				        <th>Mid Rate</th>
				        <th>Status</th>
				        <th>Updated At</th>
				        <th>Updated By</th>
				        <th>Action</th>
		            </tr>
		        </thead>
		        <tbody>
		        	<?php $i = ($currencyConversionRules->getCurrentPage() - 1)* $currencyConversionRules->getPerPage(); ?>
		            @foreach ($currencyConversionRules as $currencyConversionRule)
		            	<?php $i += 1; ?>
		                <tr>
		                    <td>{{ $i }}</td>
		          			<td>{{ $currencyConversionRule->from_currency__id }}</td>
		          			<td>{{ $currencyConversionRule->to_currency__id }}</td>
		          			<td>{{ $currencyConversionRule->buy_rate }}</td>
		          			<td>{{ $currencyConversionRule->sell_rate }}</td>
		          			<td>{{ $currencyConversionRule->mid_rate }}</td>
		          			<td>{{ $currencyConversionRule->status }}
		          			<td>{{ $currencyConversionRule->updated_at }}</td>
		          			<td>{{ $currencyConversionRule->staffName }}</td>
		          			
		          			</td>
		          			<td>
		          				<?php
							  	if (Entrust::can('edit_currency_conversion_rule')) {
									?>
									<a href="{{ URL::route('currency-conversion-rules.edit', array($currencyConversionRule->c_conversion_rule_id)) }}">
									     <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
									</a>
									<?php
		    						//echo link_to_route('dealers.edit', 'Edit', array($dealer->id));
								}
		          				?>
		          			</td>
		          		</tr>
		          	@endforeach
		        </tbody>
		    </table>
			<?php echo $currencyConversionRules->links();?>
		@else
		    There are no record!
		@endif
		</div>
		@stop
	</div>
</div>