<div class="row">
			  	<div class="col-md-8 col-md-offset-2">
			  		@if ($errors->any())
					    <div class="row">
						    <ul>
						        {{ implode('', $errors->all('<div class="alert alert-danger" role="alert">:message</div>')) }}
						    </ul>
						</div>
					@endif
			  	</div>
			</div>