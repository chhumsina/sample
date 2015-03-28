@section('title', 'List Member')
@section('content')
  <?php
  $baseUrl = URL::to('/');
  ?>

  <h2>Detail</h2>
  <div class="panel">
    <div class="panel-heading">
    </div>
    <div class="panel-body">
      <div class="row">
        <div class=" col-md-12 col-lg-12 ">
          <?php echo Form::open(array('route' => 'backend.category.store','role' => 'form', 'class'=>'form-inline')) ?>
          <table class="table table-user-information">
            <tbody>
           
            <tr>
              <td>Name:</td>
              <td>{{ Form::text('name') }}</td>
            </tr>
            <tr>
              <td>Description:</td>
              <td>{{ Form::textarea('description') }}</td>
            </tr>
            <tr>
              <td>Disable:</td>
              <td>{{ Form::checkbox('disable') }}</td>
            </tr>
            </tbody>
          </table>
            <a href="{{$baseUrl}}/backend/member" class="btn btn-default"><span class="fa fa-chevron-circle-left"></span> Back </a>
            <button type="submit" class="btn btn-primary" name="submit" value="submit"><span class="fa fa-edit"></span> Submit </button>
          <?php echo Form::close() ?>
        </div>
      </div>
    </div>
  </div>
@stop