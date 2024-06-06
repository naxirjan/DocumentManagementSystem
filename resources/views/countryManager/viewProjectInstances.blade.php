{{--dd($project_instances)--}}
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
    <li class="active">Project Instance</li>
	<li class="active">View Project Instances</li>
	@endsection
	@include('countryManager.includes.breadCrumb')
@endsection

@section('settingbox-section')
	@include('countryManager.includes.settingBox')
@endsection

@section('pageheader-section')
<h1> 
	View Project Instances
</h1>
@endsection


@section('page-content')
<div class="row">
    <div class="col-md-12">
        <div class="table-header">
            View All Project Instances
        </div>

        <table id="questions-table" class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th class="">Project Instance Title</th>
                    <th class="text-center hidden-480">Start Month</th>
                    <th class="text-center hidden-480">End Month</th>
                    <th class="text-center">Status</th>
                    <th class="text-center hidden-480">Added By</th>
                    <th class="hidden-480 hidden-480">Location</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if(count($project_instances['project_instance']) > 0)
                    @forelse($project_instances['project_instance'] as $project_instance)
                        <tr class="">
                            <td class="">
                            	{{$project_instance['project_instance_title']}}
                            </td>
                            <td class="text-center hidden-480">{{date('F, Y',strtotime($project_instance['start_date']))}}</td>
                            <td class=" text-center hidden-480">{{date('F, Y',strtotime($project_instance['end_date']))}}</td>
                            <td class=" text-center"><span class="label label-sm arrowed-in-right arrowed-in label-{{($project_instance['status']=='Active'?'success':'danger')}}">{{$project_instance['status']}}</span></td>
                            <td class="text-center hidden-480">
                                <?php $role = getUserAndRoleByRoleUserId($project_instance['added_by']); ?>
                                {{ $role[0]->first_name." ".$role[0]->last_name}} <br/>
                                @if($role[0]->role_id == 1)
                                    @php $class = 'success'; @endphp
                                @elseif($role[0]->role_id == 2 && $project_instance['added_by'] == session()->get('current_role_user_id'))
                                    @php $class = 'warning'; @endphp
                                @else
                                    @php $class = 'primary'; @endphp
                                @endif
                                <small class="label label-sm label-{{$class}} arrowed-in-right arrowed-in">{{$role[0]->role}}</small>
                            </td>
                            <td class=" hidden-480">
                                <?php
                                    if($project_instances['country'][$project_instance['project_instance_id']]){
                                        $countries = $project_instances['country'][$project_instance['project_instance_id']];
                                        

                                        echo "<ul>";
                                        foreach ($countries as $key => $country) {
                                            if($country['dop_short']){
                                                echo "<li>".$country['dop_short']."</li>";    
                                            }
                                            
                                        }
                                        echo "</ul>";

                                    }
                                ?>    
                            </td>
                            
                            <td class="text-center">
                        <div class="hidden-sm hidden-xs action-buttons">
                            <a class="green" href="{{url('countryManager/viewProjectInstanceDetail').'/'.$project_instance['project_instance_id']}}">
                                <i class="ace-icon fa fa-search-plus bigger-150"></i>
                            </a>
                            @if($role[0]->role_id == 2 && $project_instance['added_by'] == session()->get('current_role_user_id'))
                            <a class="red" href="{{url('countryManager/editProjectInstance').'/'.$project_instance['project_instance_id']}}">
                                <i class="ace-icon fa fa-pencil bigger-150"></i>
                            </a>

                            <a class="blue" href="{{url('countryManager/viewProjectInstanceDocumentTemplates').'/'.$project_instance['project_instance_id']}}">
                                <i class="ace-icon fa fa-file-text bigger-150"></i>
                            </a>
                            @endif
                        </div>

                        <div class="hidden-md hidden-lg">
                            <div class="inline pos-rel">
                                <button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
                                    <i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
                                </button>

                                <ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
                                    <li>
                                        <a href="{{url('countryManager/viewProjectInstanceDetail').'/'.$project_instance['project_instance_id']}}" class="tooltip-info" data-rel="tooltip" title="View">
                                            <span class="green">
                                                <i class="ace-icon fa fa-search-plus bigger-120"></i>
                                            </span>
                                        </a>
                                        @if($role[0]->role_id == 2 && $project_instance['added_by'] == session()->get('current_role_user_id'))
                                        <a href="{{url('countryManager/editProjectInstance').'/'.$project_instance['project_instance_id']}}" class="tooltip-info" data-rel="tooltip" title="Edit">
                                            <span class="red">
                                                <i class="ace-icon fa fa-pencil bigger-120"></i>
                                            </span>
                                        </a>
                                        <a href="{{url('countryManager/viewProjectInstanceDocumentTemplates').'/'.$project_instance['project_instance_id']}}" class="tooltip-info" data-rel="tooltip" title="View">
                                            <span class="blue">
                                                <i class="ace-icon fa fa-file bigger-120"></i>
                                            </span>
                                        </a>
                                        @endif
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </td>
                            
                        </tr>
                    @empty
                    @endforelse
                @endif    
        </tbody>
        </table> 
    </div>
</div>
@endsection


@section('footer-section')
	@include('countryManager.includes.footer')
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
