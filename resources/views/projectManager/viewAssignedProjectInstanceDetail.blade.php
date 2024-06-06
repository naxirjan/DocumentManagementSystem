{{--dd($singleSection)--}}
@extends( 'layouts.master' )

@section('page_sepecific_plugin')
@endsection


@section('navbar-section')
	@include('projectManager.includes.navBar')
@endsection

@section('sidebar-section')
	@include('projectManager.includes.sideBar')
@endsection

@section('breadcrumb-section')
	@section('breadcrumb-content')
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="#">Home</a>
	</li>
	<li class="active">Project Instance</li>
	<li class="active">View Assigned Project Instance Detail</li>
	@endsection
	@include('projectManager.includes.breadCrumb')
@endsection

@section('settingbox-section')
	@include('projectManager.includes.settingBox')
@endsection

@section('pageheader-section')
<h1>
	View Project Instance Detail 
	<!-- <small>
		<i class="ace-icon fa fa-angle-double-right"></i>
			overview &amp; stats
	</small> -->
</h1>
@endsection


@section('page-content')
	
<div class="profile-user-info">
	<div class="profile-info-row">
		<div class="profile-info-name" style="width:150px"> Project Instance Title </div>
		<div class="profile-info-value">
			<span>{{$project_instance['project_instance'][$projectInstanceId]['project_instance_title']}}</span>
			}
		</div>
	</div>
	<div class="profile-info-row">
		<div class="profile-info-name" style="width:135px"> Start Date </div>
		<div class="profile-info-value">
			<span>{{date('d F, Y',strtotime($project_instance['project_instance'][$projectInstanceId]['start_date']))}}</span>
		</div>
	</div>
	<div class="profile-info-row">
		<div class="profile-info-name" style="width:135px"> Stop Date </div>
		<div class="profile-info-value">
			<span>{{date('d F, Y',strtotime($project_instance['project_instance'][$projectInstanceId]['end_date']))}}</span>
		</div>
	</div>
	<div class="profile-info-row">
		<div class="profile-info-name"> Added By </div>
		<div class="profile-info-value">
			<?php $role = getUserAndRoleByRoleUserId($project_instance['project_instance'][$projectInstanceId]['added_by']); ?>
			{{$role[0]->first_name." ".$role[0]->last_name}}&nbsp;&nbsp;<span class="label label-sm label-primary arrowed-in-right arrowed-in">{{$role[0]->role}}</span>
		</div>
	</div>
	<div class="profile-info-row">
		<div class="profile-info-name"> Created At </div>
		<div class="profile-info-value">
			<span>{{date('d F, Y',strtotime($project_instance['project_instance'][$projectInstanceId]['created_at']))}}</span>
		</div>
	</div>
	<div class="profile-info-row">
		<div class="profile-info-name"> Status </div>
		<div class="profile-info-value">
			@if($project_instance['project_instance'][$projectInstanceId]['status'] == 'Active')
			<span class="label label-success arrowed-in-right arrowed-in">{{'Active'}}</span>
			@else
			<span class="label label-danger arrowed-in-right arrowed-in">{{'InActive'}}</span>
			@endif
		</div>
	</div>
</div>

<div class="space-10"></div>
<div class="page-header">
	<h1>Assigned Document Templates</h1>
</div>
@php $document_templates = $project_instance['assigned_document_template_count'][$projectInstanceId]; @endphp
<div id="accordion" class="accordion-style1 panel-group">
	<?php $count = 1; ?>
	@foreach($document_templates as $template)
		<?php 
			$doc_id = $template['project_instance_document_template_id'];
			$staus  = getSingleDocumentTemplatesSubmissionStatus($doc_id);
			if(!empty($staus)){
		?>
				@if($staus[0]->status_id == 1)
					<?php $bg_class = "blue"; $lb_class = "primary";?>
				@elseif($staus[0]->status_id == 3)
					<?php $bg_class = "red"; $lb_class = "danger";?>
				@else
					<?php $bg_class = "green"; $lb_class = "success";?>
				@endif
		<?php 
			}else{
				$bg_class = "orange"; $lb_class = "warning";
			}
		?>
	<div class="panel panel-default">
		<div class="panel-heading" >
			<h4 class="panel-title">
				<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$doc_id}}">
					<i class="ace-icon fa fa-angle-down bigger-110" data-icon-hide="ace-icon fa fa-angle-down" data-icon-show="ace-icon fa fa-angle-right"></i>
					&nbsp;{{$template['document_template_title']}}
					<span class="pull-right"><small class="label label-sm arrowed-in-right arrowed-in label-{{$lb_class}}">{{(isset($staus[0]->status_type))?$staus[0]->status_type:'Assigned'}}</small></span>
				</a>
			</h4>
		</div>

		<div class="panel-collapse collapse {{($count==1)?'in':''}}" id="collapse{{$doc_id}}">
			<div class="panel-body">
			<div class="profile-info-row">
				<div class="profile-info-name" style="width:200px"> Submission Start Date </div>
				<div class="profile-info-value">
					<span>{{date('d F, Y',strtotime($template['start_date']))}}</span>
				</div>
			</div>
			<div class="profile-info-row">
				<div class="profile-info-name" style="width:200px"> Submission Stop Date </div>
				<div class="profile-info-value">
					<span>{{date('d F, Y',strtotime($template['end_date']))}}</span>
				</div>
			</div>
			<div class="profile-info-row">
				<div class="profile-info-name" style="width:200px"> Status </div>
				<div class="profile-info-value">
					<span><small class="label label-sm arrowed-in-right arrowed-in label-{{$lb_class}}">{{(isset($staus[0]->status_type))?$staus[0]->status_type:'Assigned'}}</small></span>
				</div>
			</div>
			</div>
		</div>
	</div>
	<?php $count++; ?>
	@endforeach
</div>
<div class="text-center">
<a class="bt btn-lg btn-primary" href="{{url('projectManager/viewAssignedProjectInstances')}}">Back</a>
</div>
@endsection


@section('footer-section')
	@include('projectManager.includes.footer')
@endsection

@section('page_related_scripts')

@endsection
