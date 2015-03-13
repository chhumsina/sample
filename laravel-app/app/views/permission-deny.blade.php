@section('title', 'Permission Deny')
@section('content')
<div class="welcome">
    <p>
        <?php  if(Session::has('flash_notice_error')): ?>
            <div id="flash_notice" class='red'><?php echo Session::get('flash_notice_error') ?></div>
        <?php endif; ?>
        <?php  if(Session::has('flash_notice')): ?>
            <div id="flash_notice" class='info'><?php echo Session::get('flash_notice') ?></div>
        <?php endif; ?>
    </p>
</div>
@stop