{{--dd($project_instances)--}}
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
	<li class="active">View Assigned Project Instances</li>
	@endsection
	@include('projectManager.includes.breadCrumb')
@endsection

@section('settingbox-section')
	@include('projectManager.includes.settingBox')
@endsection

@section('pageheader-section')
<h1> 
	View Assigned Project Instances
</h1>
@endsection


@section('page-content')
<div class="row">
    <div class="col-md-12">
        <div class="table-header">
            View All Assigned Project Instances
        </div>

        <table id="questions-table" class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th class="">Project Instance Title</th>
                    <th class="text-center hidden-480">Start Month</th>
                    <th class="text-center hidden-480">End Month</th>
                    <th class="text-center">Status</th>
                    <th class="text-center hidden-480">Added By</th>
                    <th class="text-center hidden-480">Total Document Templates</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                    @forelse($project_instances['project_instance'] as $project_instance)
                        <tr class="">
                            <td class="">
                            	{{$project_instance['project_instance_title']}}
                            </td>
                            <td class="text-center hidden-480">{{date('F, Y',strtotime($project_instance['start_date']))}}</td>
                            <td class=" text-center hidden-480">{{date('F, Y',strtotime($project_instance['end_date']))}}</td>
                            <td class=" text-center">
                            @php
                                $project_instance_id = $project_instance['project_instance_id'];
                                
                                $status = overallProjectInstanceDocumentTemplatesSubmissionStatus($project_instance_id);
                            @endphp
                                @if($status == 'Completed')
                                <small class="label label-sm label-success arrowed-in-right arrowed-in">
                                {{$status}}
                                </small>
                                @elseif($status == 'Assigned')
                                <small class="label label-sm label-info arrowed-in-right arrowed-in">
                                {{$status}}
                                </small>
                                @else
                                <small class="label label-sm label-danger arrowed-in-right arrowed-in">
                                {{$status}}
                                </small>
                                @endif
                            
                            </td>
                            <td class="text-center hidden-480">
                                <?php $role = getUserAndRoleByRoleUserId($project_instance['added_by']); ?>
                                {{ $role[0]->first_name." ".$role[0]->last_name}} <br/>
                                <small class="label label-sm label-success arrowed-in-right arrowed-in">{{$role[0]->role}}</small>
                            </td>
                            <td class="text-center hidden-480">
                                {{count($project_instances['assigned_document_template_count'][$project_instance_id])}}   
                            </td>
                            
                            <td class="text-center">
                        <div class="hidden-sm hidden-xs action-buttons">
                            <a title="View Detail" class="green" href="{{url('projectManager/viewAssignedProjectInstanceDetail').'/'.$project_instance['project_instance_id']}}">
                                <i class="ace-icon fa fa-search-plus bigger-150"></i>
                            </a>
                            <a title="Submit Document Templates" class="red" href="{{url('projectManager/submitDocumentTemplate').'/'.$project_instance['project_instance_id']}}">
                                <i class="ace-icon fa fa-save bigger-150"></i>
                            </a>
                        </div>

                        <div class="hidden-md hidden-lg">
                            <div class="inline pos-rel">
                                <button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
                                    <i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
                                </button>

                                <ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
                                    <li>
                                        <a href="{{url('projectManager/viewAssignedProjectInstanceDetail').'/'.$project_instance['project_instance_id']}}" class="tooltip-info" data-rel="tooltip" title="View Detail">
                                            <span class="green">
                                                <i class="ace-icon fa fa-search-plus bigger-120"></i>
                                            </span>
                                        </a>
                                        <a href="{{url('projectManager/submitDocumentTemplate').'/'.$project_instance['project_instance_id']}}" class="tooltip-info" data-rel="tooltip" title="Submit Document Templates">
                                            <span class="red">
                                                <i class="ace-icon fa fa-save bigger-120"></i>
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
@endsection


@section('footer-section')
	@include('projectManager.includes.footer')
@endsection

@section('page_related_scripts')
<script src="{{asset('../assets/js/dataTables/jquery.dataTables.js')}}"></script>
<script src="{{asset('../assets/js/dataTables/jquery.dataTables.bootstrap.js')}}"></script>
<script src="{{asset('../assets/js/dataTables/extensions/TableTools/js/dataTables.tableTools.js')}}"></script>
<script src="{{asset('../assets/js/dataTables/extensions/ColVis/js/dataTables.colVis.js')}}"></script>
        

<script type="text/javascript">
		jQuery(function($) {
				$('#questions-table')
				.dataTable({
                    "aaSorting": [],
                });
        } );    
			
</script>
@endsection
