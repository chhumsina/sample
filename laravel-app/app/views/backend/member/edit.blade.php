@section('title', 'List Member')
@section('content')
	<?php echo Form::open(array('url' => 'backend/member', 'role' => 'form', 'class'=>'form-inline')) ?>
		<div class="form-group">
			<input type="text" class="form-control" id="exampleInputEmail3" placeholder="username" name="username">
		</div>
		<div class="form-group">
			<input type="email" class="form-control" id="" placeholder="email" name="email">
		</div>
		<div class="form-group">
			<select name="status" class="form-control">
				<option value="1">Active</option>
				<option value="0">Inactive</option>
			</select>
		</div>
		<button type="submit" class="btn btn-default">Search</button>
	<?php echo Form::close() ?>

	@if ($members->count())

	<div class="row">
		<div class="col-md-12">
			<div class="table-responsive">


				<table id="mytable" class="table table-bordred table-striped">

					<thead>
					<th>No</th>
					<th>Username</th>
					<th>Email</th>
					<th>Phone</th>
					<th>Location</th>
					<th>Photo</th>
					<th>Created Date</th>
					<th>Action</th>
					</thead>
					<tbody>


					@foreach($members as $key => $member)
						<tr>
							<td>{{$key+1}}</td>
							<td>{{$member->username}}</td>
							<td>{{$member->email}}</td>
							<td>{{$member->phone}}</td>
							<td>{{$member->location}}</td>
							<td>{{$member->photo}}</td>
							<td>{{$member->created_date}}</td>
							<td>{{$member->created_date}}</td>
						</tr>
					@endforeach

					</tbody>

				</table>

				<div class="clearfix"></div>
				<span class="pagination pull-right">
					<?php echo $members->links();?>
				</span>

			</div>

		</div>
	</div>
	</div>
	@else
			There are no record!
	@endif
@stop