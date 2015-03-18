<h2 class="title text-center"><?php echo str_replace('_', ' ', Request::segment(2));?></h2>
@include('layouts.partial.render-message')
<div class="tabbable-panel">
	<div class="tabbable-line">
		<ul class="nav nav-tabs ">
			<li class="@if(Request::segment(2)=='manage_ads')active @endif">
				<a href="{{$baseUrl}}/member/manage_ads">
					Manage Ads
				</a>
			</li>
			<li class="@if(Request::segment(2)=='my_profile')active @endif">
				<a href="{{$baseUrl}}/member/my_profile">
					My Profile
				</a>
			</li>
			<li class="@if(Request::segment(2)=='page')active @endif">
				<a href="{{$baseUrl}}/member/page">
					Page
				</a>
			</li>
			<li class="@if(Request::segment(2)=='my_map')active @endif">
				<a href="{{$baseUrl}}/member/my_map">
					My Map
				</a>
			</li>
		</ul>
		<div class="tab-content">