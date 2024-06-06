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
	<li class="active">View Project Instance Details</li>
	@endsection
	@include('projectManager.includes.breadCrumb')
@endsection

@section('settingbox-section')
	@include('projectManager.includes.settingBox')
@endsection

@section('pageheader-section')
<h1> 
	View Project Instance Details
</h1>
@endsection


@section('page-content')
<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <div class="form-horizontal">
            
            <!--Project-->
            <div class="form-group">
                <label class="col-sm-3"><b>Project:</b></label>
                <div class="col-sm-6">
                    <label class="">
                    <?php
                        $project_instance_id = Request::segment(3);
                        $project_id = $project_instance_detail['project_intance'][$project_instance_id]['project_id'];
                        $project = getProjectNameByProjectId($project_id);
                        echo $project[0]->proj_name;
                    ?>
                    </label>
                </div>
            </div>
            
             <!--Project Instance Description-->    
            <div class="form-group">
                <label class="col-sm-3 "><b>Project Instance Description:</b> </label>
                <div class="col-sm-6">
                    <label class="">
                        {{ $project_instance_detail['project_intance'][$project_instance_id]['project_instance_description'] }}
                    </label>    
    			</div>
            </div>
            
             <!--Start Date-->    
            <div class="form-group">
                <label class="col-sm-3 "><b>Start From Month:</b> </label>
                <div class="col-sm-6">
                    <label class="">
                            {{ date('F, Y',strtotime($project_instance_detail['project_intance'][$project_instance_id]['start_date'])) }}
                    </label>    
            </div>
            </div>    
            
            <!--End Date-->    
            <div class="form-group">
                <label class="col-sm-3 "><b>End To Month:</b> </label>
                <div class="col-sm-6">
                    <label class="">
                        {{ date('F, Y',strtotime($project_instance_detail['project_intance'][$project_instance_id]['end_date'])) }}
                    </label>    
                </div>
            </div>    
            
            <!--Project Instance Title Will Be Here Dynamically-->
            <div class="form-group">
                <label class="col-sm-3 "><b>Project Instance Title:</b> </label>
                <div class="col-sm-9">
                    <label class="">
                        {{$project_instance_detail['project_intance'][$project_instance_id]['project_instance_title'] }}
                    </label>    
                </div>
            </div>

            <!--Project Instance Added By-->
            <div class="form-group">
                <label class="col-sm-3 "><b>Added By:</b> </label>
                <div class="col-sm-9">
                    <label class="">
                       @php 
                        $role_user_id =    $project_instance_detail['project_intance'][$project_instance_id]['added_by'];
                        $user =  getUserAndRoleByRoleUserId($role_user_id); 
                       @endphp 
                        {{$user[0]->first_name." ".$user[0]->last_name}}
                        <span class="label label-sm label-primary arrowed-in-right arrowed-in">{{$user[0]->role}}</span>

                    </label>    
                </div>
            </div>

            <!--Status Label-->
            <div class="form-group">
                <label class="col-sm-3 "><b>Status:</b></label>
                <?php 
                /*Status*/ 
                if($project_instance_detail['project_intance'][$project_instance_id]['status']=='Active')
                {
                    ?>
                    <div class="col-sm-6">
                        <label class="">
                            <i class="green fa fa-check-square"></i>
                            <span class="lbl green"> Active </span>
                        </label>
                    </div>    
                    <?php
                }
                else
                {
                    ?>
                    <div class="col-sm-6">
                        <label class="">
                            <i class="red fa fa-check-square"></i>
                            <span class="lbl red"> In Active </span>
                        </label>
                    </div>    
                    <?php
                }
            ?>
            </div>
            <!--Status-->
        
            <div class="form-group">
                <label class="col-sm-5 "></label>
                <div class="col-sm-7">
                    <!--Hidden Field-->
                    <span id="countries" class="hidden"></span>
                </div>
            </div>
        
        </div>
    </div>
     <div class="col-md-2"></div>
</div>
<!--Project Instance Countries-->
{{--dd($project_instance_detail['country'][$project_instance_id])--}}
<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-4">
        <h3><b>Project Instance Assigned To</b></h3>
        <table class="table table-responsive">											
        <tbody>
             <?php
                foreach($project_instance_detail['country'][$project_instance_id] as $country)
                {
                    
            ?>
                        <tr>
                            <?php if($country['dop_short']){ ?>
                            <td style="border-top:none">
                                <b>Location: &nbsp;&nbsp;</b>
                                {{$country['dop_short']}}
                            </td>
                            <td style="border-top:none">
                                <b>Status: &nbsp;&nbsp;</b>
                                <?php
                                if($country['country_status']=='Active')
                                {
                                    echo '<span class="green">Active</span>';
                                }
                                else
                                {
                                    echo '<span class="red">InActive</span>';
                                }
                                ?>
                            </td>
                            <?php }else{?>
                            <td>No Location Assigned</td>
                        <?php } ?>
                        </tr>
                   <?php
                    
                }
            ?>
        </tbody>
        </table> 
           
        <br />
        <a href="/projectManager/viewAssignedProjectInstances" class="btn">Back</a>
                
    </div>
    <div class="col-md-6">
    </div>
</div>


@endsection


@section('footer-section')
	@include('projectManager.includes.footer')
@endsection

@section('page_related_scripts')
@endsection
