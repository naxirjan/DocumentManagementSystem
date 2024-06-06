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
	<li class="active">View Project Instances</li>
	@endsection
	@include('usManager.includes.breadCrumb')
@endsection

@section('settingbox-section')
	@include('usManager.includes.settingBox')
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
                    <th class="text-center">Project Instance Title</th>
                    <th class="text-center">Start Month</th>
                    <th class="text-center">End Month</th>
                    <th class="text-center">Status</th>
                    <th class="hidden-480">Assigned To <br /><small>(Countries)</small></th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                    @forelse($project_instances as $project_instance)
                        <tr>
                            <td class="">
                            	{{$project_instance['project_instance_title']}}
                            </td>
                            <td class="text-center">{{date('F, Y',strtotime($project_instance['project_instance_start_date']))}}</td>
                            <td class=" text-center">{{date('F, Y',strtotime($project_instance['project_instance_end_date']))}}</td>
                            <td class=" text-center"><span class="label label-sm arrowed-in-right arrowed-in label-{{($project_instance['status']=='Active'?'success':'danger')}}">{{$project_instance['status']}}</span></td>
                            <td class=" hidden-480">
                                    <?php
                                        if(count($project_instance['project_instance_countries'])>0)
                                        {
                                            foreach($project_instance['project_instance_countries'] as $country)
                                            {
                                               
                                                $country_data=getCountryNameByCountryId($country['country_id']);
                                                $country= strtolower($country_data[0]->cont_name);
                                                ?>  <p class="badge badge-info badge-sm">
                                                    <img alt="" src='{{asset("storage/countires_icons/$country.png")}}'>
                                                    {{ $country_data[0]->cont_name }}
                                                    </p>
                                                <?php
                                            }
                                        }
                                    ?>    
                                
                            </td>
                            <td class="text-center">
                                <div class="hidden-sm hidden-xs action-buttons">
                                    <a title="View Project Instance Detail" target="_blank" class="green" href="/usManager/viewProjectInstanceDetail/{{$project_instance['project_instance_id']}}">
                                        <i class="ace-icon fa fa-search-plus bigger-150"></i>
                                    </a>

                                    <a title="Edit Project Instance" target="_blank" class="red" href="/usManager/editProjectInstance/{{$project_instance['project_instance_id']}}">
                                        <i class="ace-icon fa fa-pencil bigger-150"></i>
                                    </a>

                                    <a title="View Document Templates" target="_blank" class="blue" href="/usManager/viewProjectInstanceDocumentTemplates/{{$project_instance['project_instance_id']}}">
                                        <i class="ace-icon fa fa-file-text bigger-150"></i>
                                    </a>    
                                 </div>
                                <div class="hidden-md hidden-lg">
                                    <div class="inline pos-rel">
                                        <button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
                                            <i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
                                        </button>

                                        <ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
                                            <li>
                                                <a target="_blank" href="/usManager/viewProjectInstanceDetail/{{$project_instance['project_instance_id']}}" class="tooltip-info" data-rel="tooltip" title="View Project Instance Detail">
                                                    <span class="green">
                                                        <i class="ace-icon fa fa-search-plus bigger-120"></i>
                                                    </span>
                                                </a>
                                            </li>

                                            <li>
                                                <a target="_blank" href="/usManager/editProjectInstance/{{$project_instance['project_instance_id']}}" class="tooltip-success" data-rel="tooltip" title="Edit Project Instance">
                                                    <span class="red">
                                                        <i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
                                                    </span>
                                                </a>
                                            </li>

                                            <li>
                                                <a target="_blank" href="/usManager/viewProjectInstanceDocumentTemplates/{{$project_instance['project_instance_id']}}" class="tooltip-success" data-rel="tooltip" title="View Document Templates">
                                                    <span class="blue">
                                                        <i class="ace-icon fa fa-file-text bigger-120"></i>
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
	@include('usManager.includes.footer')
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
