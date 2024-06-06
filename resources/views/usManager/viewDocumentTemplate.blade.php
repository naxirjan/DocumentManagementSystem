{{--dd($allDocumentTemplates)--}}
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
	<li class="active">Manage Template</li>
	<li class="active">View Templates</li>
	@endsection
	@include('usManager.includes.breadCrumb')
@endsection

@section('settingbox-section')
	@include('usManager.includes.settingBox')
@endsection

@section('pageheader-section')
<h1>
	View Document Template 
	<!-- <small>
		<i class="ace-icon fa fa-angle-double-right"></i>
			overview &amp; stats
	</small> -->
</h1>
@endsection


@section('page-content')
<div class="row">
	<div class="col-xs-12">
	<div class="clearfix">
		<div class="pull-right tableTools-container"></div>
	</div>
	<div class="table-header">
		Results for "Document Templates"
	</div>

	<!-- div.table-responsive -->

	<!-- div.dataTables_borderWrap -->
	<div>
		<table id="document-templates" class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th class="text-center hidden-480">ID</th>
					<th>Template Title</th>
					<th class="hidden-480">Project</th>
					<th class="hidden-480">Type</th>
					<th class="hidden-480 text-center">
						Total Section
					</th>
					<th class="text-center hidden-480">Added By</th>
					<th class="hidden-480">Created At</th>
					<th class="text-center">Status</th>
					<th class="text-center">Actions</th>
				</tr>
			</thead>

			<tbody>
				@php $i = 1; @endphp
				@forelse($allDocumentTemplates as $template)
				<tr>
					<td class="text-center hidden-480">{{$i++}}</td>
					<td title="{{$template->document_template_title}}">{{$template->document_template_title}}</td>
					<td class="hidden-480" title="{{$template->proj_name}}">{{$template->proj_name}}</td>
					<td class="hidden-480" title="{{$template->template_type}}">{{$template->template_type}}</td>
					<td class="hidden-480 text-center">{{$template->total_section}}</td>
					<td class="text-center hidden-480">{{ $template->fullname}} <br/><small class="label label-sm label-primary arrowed-in-right arrowed-in">{{$template->role}}</small></td>
					<td class="hidden-480" title="{{$template->created_at}}">
					{{date('d M, Y',strtotime($template->created_at))}}
					</td>
					<td class="text-center">
						@if($template->status == 'Active')
						<span class="label label-sm label-success arrowed-in-right arrowed-in" title="{{$template->status}}">
							{{$template->status}}
						</span>
						@else
						<span class="label label-sm label-danger arrowed-in-right arrowed-in" title="{{$template->status}}">
							{{$template->status}}
						</span>
						@endif
					</td>

					<td class="text-center">
						<div class="hidden-sm hidden-xs action-buttons">
							<a title="View Document Template Detail" target="_blank" class="green" href="{{url('usManager/viewTemplateDetail').'/'.$template->document_template_id}}">
								<i class="ace-icon fa fa-search-plus bigger-130"></i>
							</a>
						</div>

						<div class="hidden-md hidden-lg">
							<div class="inline pos-rel">
								<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
									<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
								</button>

								<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
									<li>
										<a target="_blank" href="{{url('usManager/viewTemplateDetail').'/'.$template->document_template_id}}" class="tooltip-info" data-rel="tooltip" title="View Document Template Detail">
											<span class="green">
												<i class="ace-icon fa fa-search-plus bigger-120"></i>
											</span>
										</a>
									</li>
								</ul>
							</div>
						</div>
					</td>
				</tr>
				@empty
				@endforelse
			</tbody>
		</table>
	</div>
	</div>
</div>	

										
@endsection


@section('footer-section')
	@include('usManager.includes.footer')
@endsection

@section('page_related_scripts')
<script src="{{asset('../assets/js/dataTables/jquery.dataTables.js')}}"></script>
<script src="{{asset('../assets/js/dataTables/jquery.dataTables.bootstrap.js')}}"></script>
<script type="text/javascript">
	$(document).ready(function(){
		//initiate dataTables plugin
		$('#document-templates')
				.dataTable({
                    "aaSorting": [],
        });
				
	});
</script>
@endsection
