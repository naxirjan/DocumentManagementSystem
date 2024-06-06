@extends( 'layouts.master' )

@section('page_sepecific_plugin')
@endsection


@section('navbar-section')
	@include('partnerManager.includes.navBar')
@endsection

@section('sidebar-section')
	@include('partnerManager.includes.sideBar')
@endsection

@section('breadcrumb-section')
	@section('breadcrumb-content')
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="#">Home</a>
	</li>
	<li class="active">Partner Dashboard</li>
	@endsection
	@include('partnerManager.includes.breadCrumb')
@endsection

@section('settingbox-section')
	@include('partnerManager.includes.settingBox')
@endsection

@section('pageheader-section')
<h1>
	Partner Manager Dashboard <span class="label label-lg label-primary arrowed-in arrowed-in-right">
		<i class="ace-icon fa fa-circle light-green bigger-10"></i> {{Session::get('current_cont_name')." (".Session::get('current_dop_name').")"}}</span>
	<!-- <small>
		<i class="ace-icon fa fa-angle-double-right"></i>
			overview &amp; stats
	</small> -->
</h1>
@endsection


@section('page-content')
	@foreach($project_instances['project_instance'] as $project_instance)
		@php
	        $project_instance_id = $project_instance['project_instance_id'];
	        $status = overallProjectInstanceDocumentTemplatesSubmissionStatus($project_instance_id);
	        $instance_title = explode('(F',$project_instance['project_instance_title']);
	        $document_templates = $project_instances['assigned_document_template_count'][$project_instance_id];
	    @endphp
		@if($status != 'Completed')
		<div class="col-xs-6 col-sm-4 col-md-4 pricing-box">
			<div class="widget-box widget-color-blue">
				<div class="widget-header">
					<h5 class="widget-title bigger lighter">{{$instance_title[0]??""}}</h5>
					<div class="widget-toolbar">
						<span class="badge badge-danger">{{count($document_templates)}}</span>
					</div>
				</div>

				<div class="widget-body">
					<div class="widget-main" >
						<p class="text-center blue"><b>(F{{$instance_title[1]??""}}</b></p>
						<div class="text-center blue"><b>Assigned By :
							<?php
							$user = getUserAndRoleByRoleUserId($project_instance['added_by']);
							?>
							<small class="label label-sm arrowed-in-right arrowed-in label-grey">
								{{$user[0]->first_name." ".$user[0]->last_name}} ({{$user[0]->role}})</small>
						</b>
						</div>
						<h6 class="text-center blue"><b>Document Templates</b></h6>
						<hr/>
						<div style="overflow-y:auto;height:300px;">
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
							<!--Assigned Document Templates -->
							<div class="col-xs-12 col-sm-12 col-md-12">
								<p class="text-center">
								<i class="ace-icon fa fa-check {{$bg_class}}"></i>
								{{$template['document_template_title']}}
								<br/>
								<p class="text-center">
								<small class="label label-sm arrowed-in-right arrowed-in label-{{$lb_class}}">{{(isset($staus[0]->status_type))?$staus[0]->status_type:'Assigned'}}</small></p>
								</p><div class="hr hr-24"></div>
							</div><!--end-->
							@endforeach
						</div>
						
					</div>

					<div>
						<a href="{{url('partnerManager/submitDocumentTemplate').'/'.$project_instance_id}}" class="btn btn-block btn-primary">
							<i class="ace-icon fa fa-save bigger-110"></i>
							<span>Submit</span>
						</a>
					</div>
				</div>
			</div>
		</div>
		@endif
	@endforeach
@endsection


@section('footer-section')
	@include('partnerManager.includes.footer')
@endsection

@section('page_related_scripts')
@endsection