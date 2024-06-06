{{--dd($singleSection)--}}
@extends( 'layouts.master' )

@section('page_sepecific_plugin')
@endsection


@section('navbar-section')
	@include('usManager.includes.navBar')
@endsection

@section('sidebar-section')
	@include('usManager.includes.sideBar')
@endsection

@section('breadcrumb-section')
	@section('breadcrumb-content')
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="#">Home</a>
	</li>
	<li class="active">Manage Section</li>
	<li class="active">View Section Detail</li>
	@endsection
	@include('usManager.includes.breadCrumb')
@endsection

@section('settingbox-section')
	@include('usManager.includes.settingBox')
@endsection

@section('pageheader-section')
<h1>
	View Section Detail 
	<!-- <small>
		<i class="ace-icon fa fa-angle-double-right"></i>
			overview &amp; stats
	</small> -->
</h1>
@endsection


@section('page-content')
<div class="row">
	<div class="col-sm-12">
		<div class="profile-user-info">
			<div class="profile-info-row">
				<div class="profile-info-name"> Section Title </div>
				<div class="profile-info-value">
					<span>{{$singleSection['singleSection'][0]->section_title}}</span>
				</div>
			</div>
			<div class="profile-info-row">
				<div class="profile-info-name" style="width:135px"> Section Description </div>
				<div class="profile-info-value">
					<span>{{$singleSection['singleSection'][0]->section_description}}</span>
				</div>
			</div>
			<div class="profile-info-row">
				<div class="profile-info-name"> Added By </div>
				<div class="profile-info-value">
					{{$singleSection['singleSection'][0]->fullname}}&nbsp;&nbsp;<span class="label label-sm label-primary arrowed-in-right arrowed-in">{{$singleSection['singleSection'][0]->role}}</span>
				</div>
			</div>
			<div class="profile-info-row">
				<div class="profile-info-name"> Created At </div>
				<div class="profile-info-value">
					<span>{{date('d F, Y',strtotime($singleSection['singleSection'][0]->created_at))}}</span>
				</div>
			</div>
			<div class="profile-info-row">
				<div class="profile-info-name"> Section Status </div>
				<div class="profile-info-value">
					@if($singleSection['singleSection'][0]->status == 'Active')
					<span class="label label-success arrowed-in-right arrowed-in">{{$singleSection['singleSection'][0]->status}}</span>
					@else
					<span class="label label-danger arrowed-in-right arrowed-in">{{$singleSection['singleSection'][0]->status}}</span>
					@endif
				</div>
			</div>
		</div>
		<div class="space-10"></div>
		<div class="page-header">
			<h1>Section Questions</h1>
		</div>
		<div class="widget-body">
			<div class="widget-main padding-6">
				<ul class="list-unstyled spaced2 item-list ui-sortable-handle ui-sortable" id="section-questions">
				    @php $i = 1; @endphp
				    @forelse($singleSection['sectionQuestions'] as $question)
				    <li class="item-@php echo ($question->question_status == 'Active')?'green':'red' @endphp clearfix ui-sortable-handle" data-order="1" id="questions-row1" style="cursor:pointer;">
				    <div>
				    <h5>
				    <span><b class="question_serial">Question {{$i++}}</b></span>
				    </h5>
				    <div class="col-md-12 col-sm-12 col-xs-12">
				    <p>{{$question->question}}
				    </p>
				    </div>
				    <br>
				    <div class="hr hr-24 dotted "></div>
				    <label class="pull-right"><span><b>Status:</b></span>
				    @if($question->question_status == 'Active')
				    <span class="label label-success arrowed-in-right arrowed-in">Active</span>
				    @else
				    <span class="label label-danger arrowed-in-right arrowed-in">InActive</span>
				    @endif
				    <span class="lbl middle"></span>
				    </label>
				    </div>
				    </li>
				    @empty
				    @endforelse
				</ul>
			</div>
		</div>
		<div class="text-center">
		<a class="bt btn-lg btn-primary" href="{{url('/usManager/viewSection')}}">Back</a>
		</div>
	</div>
</div>
@endsection


@section('footer-section')
	@include('usManager.includes.footer')
@endsection

@section('page_related_scripts')

@endsection
