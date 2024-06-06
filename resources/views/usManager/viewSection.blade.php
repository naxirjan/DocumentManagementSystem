{{--dd($allSections)--}}
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
	<li class="active">View Section</li>
	@endsection
	@include('usManager.includes.breadCrumb')
@endsection

@section('settingbox-section')
	@include('usManager.includes.settingBox')
@endsection

@section('pageheader-section')
<h1>
	View Section 
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
			Results for "Sections"
		</div>

		<!-- div.table-responsive -->

		<!-- div.dataTables_borderWrap -->
		<div>
			<table id="sections" class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<th class="hidden-480 text-center">ID</th>
						<th>Section Title</th>
						<th class="hidden-480">Section Description</th>
						<th class="hidden-480 text-center">
							Total Question
						</th>
						<th class="hidden-480 text-center">Added By</th>
						<th class="hidden-480">Created At</th>
						<th class="text-center">Status</th>
						<th class="text-center">Actions</th>
					</tr>
				</thead>

				<tbody>
					@php $i = 1; @endphp
					@forelse($allSections as $section)
					<tr>
						<td class="hidden-480 text-center">{{$i++}}</td>
						<td title="{{$section->section_title}}">{{$section->section_title}}</td>
						<td class="hidden-480" title="{{$section->section_description}}">{{$section->section_description}}</td>
						<td class="hidden-480 text-center">{{$section->total_question}}</td>
						<td class="hidden-480 text-center">{{ $section->fullname}} <br/><small class="label label-sm label-primary arrowed-in-right arrowed-in">{{$section->role}}</small></td>
						<td class="hidden-480" title="{{$section->created_at}}">
						{{date('d M, Y',strtotime($section->created_at))}}
						</td>
						<td class="text-center">
							@if($section->status == 'Active')
							<span class="label label-sm label-success arrowed-in-right arrowed-in" title="{{$section->status}}">
								{{$section->status}}
							</span>
							@else
							<span class="label label-sm label-danger arrowed-in-right arrowed-in" title="{{$section->status}}">
								{{$section->status}}
							</span>
							@endif
						</td>

						<td class="text-center">
							<div class="hidden-sm hidden-xs action-buttons">
								<a title="View Section Detail" target="_blank" class="green" href="{{url('usManager/viewSectionDetail').'/'.$section->section_id}}">
									<i class="ace-icon fa fa-search-plus bigger-150"></i>
								</a>

								<a title="Edit Section" target="_blank" class="red" href="{{url('usManager/editSection').'/'.$section->section_id}}">
									<i class="ace-icon fa fa-pencil bigger-150"></i>
								</a>
							</div>

							<div class="hidden-md hidden-lg">
								<div class="inline pos-rel">
									<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
										<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
									</button>

									<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
										<li>
											<a target="_blank" href="{{url('usManager/viewSectionDetail').'/'.$section->section_id}}" class="tooltip-info" data-rel="tooltip" title="View Section Detail">
												<span class="green">
													<i class="ace-icon fa fa-search-plus bigger-120"></i>
												</span>
											</a>
										</li>

										<li>
											<a target="_blank" href="{{url('usManager/editSection').'/'.$section->section_id}}" class="tooltip-success" data-rel="tooltip" title="Edit Section">
												<span class="red">
													<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
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
		$('#sections')
			.dataTable({
                    "aaSorting": [],
        });
				
	});
</script>
@endsection
