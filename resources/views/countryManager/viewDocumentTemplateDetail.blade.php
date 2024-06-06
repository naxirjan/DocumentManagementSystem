{{--dd($singleDocumentTemplates)--}}
@extends( 'layouts.master' )

@section('page_sepecific_plugin')
@endsection


@section('navbar-section')
	@include('countryManager.includes.navBar')
@endsection

@section('sidebar-section')
	@include('countryManager.includes.sideBar')
@endsection

@section('breadcrumb-section')
	@section('breadcrumb-content')
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="#">Home</a>
	</li>
	<li class="active">Manage Template</li>
	<li class="active">View Template Detail</li>
	@endsection
	@include('countryManager.includes.breadCrumb')
@endsection

@section('settingbox-section')
	@include('countryManager.includes.settingBox')
@endsection

@section('pageheader-section')
<h1>
	View Document Template Detail 
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
				<div class="profile-info-name"> Template Type </div>
				<div class="profile-info-value">
					<span>{{$singleDocumentTemplates['singleTemplate'][0]->template_type}}</span>
				</div>
			</div>
			<div class="profile-info-row">
				<div class="profile-info-name" style="width:135px"> Project </div>
				<div class="profile-info-value">
					<span>{{$singleDocumentTemplates['singleTemplate'][0]->proj_name}}</span>
				</div>
			</div>
			<div class="profile-info-row">
				<div class="profile-info-name" style="width:135px"> Template Title </div>
				<div class="profile-info-value">
					<span>{{$singleDocumentTemplates['singleTemplate'][0]->document_template_title}}</span>
				</div>
			</div>
			<div class="profile-info-row">
				<div class="profile-info-name"> Added By </div>
				<div class="profile-info-value">
					{{$singleDocumentTemplates['singleTemplate'][0]->fullname}}&nbsp;&nbsp;<span class="label label-sm label-primary arrowed-in-right arrowed-in">{{$singleDocumentTemplates['singleTemplate'][0]->role}}</span>
				</div>
			</div>
			<div class="profile-info-row">
				<div class="profile-info-name"> Created At </div>
				<div class="profile-info-value">
					<span>{{date('d F, Y',strtotime($singleDocumentTemplates['singleTemplate'][0]->created_at))}}</span>
				</div>
			</div>
			<div class="profile-info-row">
				<div class="profile-info-name"> Template Status </div>
				<div class="profile-info-value">
					@if($singleDocumentTemplates['singleTemplate'][0]->status == 'Active')
					<span class="label label-success arrowed-in-right arrowed-in">{{$singleDocumentTemplates['singleTemplate'][0]->status}}</span>
					@else
					<span class="label label-danger arrowed-in-right arrowed-in">{{$singleDocumentTemplates['singleTemplate'][0]->status}}</span>
					@endif
				</div>
			</div>
		</div>
		<div class="space-10"></div>

		<div class="col-sm-12">
			<div id="document-template-structure">
				<div class="page-header">
					<h1>Template Design Structure</h1>
				</div>
				<div class="tabbable">
					<ul class="nav nav-tabs" id="myTab">
						@php $sectionCount = 0; @endphp
						@foreach($singleDocumentTemplates['templateSection'] as $key => $singleSection)
						<li class="{{($sectionCount == 0)?'active':''}}">
							<a data-toggle="tab" href="#section{{$singleSection->section_id}}" aria-expanded="{{($sectionCount == 0)?'true':'false'}}">
								{{$singleSection->section_title}}
								<span class="badge badge-success">
									@if(isset($totalTemplateSectionQuestion[$singleSection->section_id]))
									
									{{count($totalTemplateSectionQuestion[$singleSection->section_id])}}

									@else
										{{'0'}}
									@endif
									
								</span>
							</a>
						</li>
						@php $sectionCount++; @endphp
						@endforeach
					</ul>
						
					<div class="tab-content">
						@php $indexCount = 0; @endphp
						@foreach($singleDocumentTemplates['templateSection'] as $key => $singleSection)
						<div id="section{{$singleSection->section_id}}" class="tab-pane fade {{($indexCount == 0)?'active in':''}}">
							<div class="widget-body">
								
								<div class="widget-main padding-6" id="scroll_bar" style="overflow-y:auto;height:410px">
									
									<ul class="list-unstyled spaced2 item-list" id="all-assigned-sections">
										
										<li id="add_all_question">
											
											<span class="pull-right">
											<label class="text-danger"><b>Priority: <span style="font-size:15px">{{$singleSection->section_priority}}</span></b></label>
											</span>
										</li><br/>
										@php $questionCount = 1; @endphp
										@forelse($singleDocumentTemplates['templateSectionQuestions'] as $key => $sectionQuestion)
											@if($singleSection->section_id == $sectionQuestion->section_id)
											<li class="item-green clearfix">
						                        <label style="cursor:pointer">
						                        	<span class='lbl'>&nbsp;<b>Question {{$questionCount++}}</b></span>
						                        </label>
						                        <p>{{$sectionQuestion->question}}</p>
						                    </li>
					                    	@endif
					                    @empty
					                    <li class="item-orange clearfix">
					                        <label>
					                        <span class='lbl'>&nbsp;<b>Sorry No Sections Available ...!</b></span></label>
					                    </li>
					                    @endforelse
									</ul>
								</div>
							</div>
						</div>
						@php $indexCount++; @endphp
						@endforeach
					</div>
				</div>
			</div>
			<br/>
			<div class="text-center">
				<a class="bt btn-lg btn-primary" href="{{url('/countryManager/viewTemplate')}}">Back</a>
			</div>
		</div>
	</div>
</div>	
@endsection


@section('footer-section')
	@include('countryManager.includes.footer')
@endsection

@section('page_related_scripts')

@endsection
