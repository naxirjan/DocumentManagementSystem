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
	<li class="active">View Project Instance Details</li>
	@endsection
	@include('usManager.includes.breadCrumb')
@endsection

@section('settingbox-section')
	@include('usManager.includes.settingBox')
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
                        $project = getProjectNameByProjectId($project_instance_detail[0]['project_id']);
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
                        {{ $project_instance_detail[0]['project_instance_description'] }}
                    </label>    
    			</div>
            </div>
            
             <!--Start Date-->    
            <div class="form-group">
                <label class="col-sm-3 "><b>Start From Month:</b> </label>
                <div class="col-sm-6">
                    <label class="">
                            {{ date('F, Y',strtotime($project_instance_detail[0]['project_instance_start_date'])) }}
                    </label>    
            </div>
            </div>    
            
            <!--End Date-->    
            <div class="form-group">
                <label class="col-sm-3 "><b>End To Month:</b> </label>
                <div class="col-sm-6">
                    <label class="">
                        {{ date('F, Y',strtotime($project_instance_detail[0]['project_instance_end_date'])) }}
                    </label>    
                </div>
            </div>    
            
            <!--Project Instance Title Will Be Here Dynamically-->
            <div class="form-group">
                <label class="col-sm-3 "><b>Project Instance Title:</b> </label>
                <div class="col-sm-9">
                    <label class="">
                        {{ $project_instance_detail[0]['project_instance_title'] }}
                    </label>    
                </div>
            </div>
            
            <!--Project Instance Added By-->
            <div class="form-group">
                <label class="col-sm-3 "><b>Added By:</b> </label>
                <div class="col-sm-9">
                    <label class="">
                        <?php
                            $added_by=getUserAndRoleByRoleUserId($project_instance_detail[0]['added_by']);
                            //print_r($added_by);
                        ?>
                        {{$added_by[0]->first_name}} {{$added_by[0]->last_name}} <span class="label label-sm label-info arrowed-in arrowed-in-right">{{$added_by[0]->role}}</span>
                    </label>    
                </div>
            </div>

            <!--Status Label-->
            <div class="form-group">
                <label class="col-sm-3 "><b>Status:</b></label>
                <div class="col-sm-6">
                        <label class="">
                        <span class="label label-sm label-{{($project_instance_detail[0]['status']=='Active'?'success':'danger')}} arrowed-in-right arrowed-in"> {{$project_instance_detail[0]['status']}} </span>
                    </label>
                    </div>
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
<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-4">
        <h3><b>Project Instance Assigned To</b></h3>
        <table class="table table-responsive">											
        <tbody>
             <?php
                foreach($project_instance_detail[0]['project_instance_countries'] as $country )
                {
                    $country_data = getCountryNameByCountryId($country['country_id']);
                    ?>
                        <tr>
                            <td style="border-top:none">
                                <b>Country: &nbsp;&nbsp;</b>
                                {{$country_data[0]->cont_name}}
                            </td>
                            <td style="border-top:none">
                                <b>Status: &nbsp;&nbsp;</b>
                                <?php
                                if($country['status']=='Active')
                                {
                                    echo '<span class="green">Active</span>';
                                }
                                else
                                {
                                    echo '<span class="red">In Active</span>';
                                }
                                ?>
                            </td>
                        </tr>
                   <?php
                }
            ?>
        </tbody>
        </table> 
                
    </div>
    <div class="col-md-6">
    </div>
</div>


@endsection


@section('footer-section')
	@include('usManager.includes.footer')
@endsection

@section('page_related_scripts')
@endsection
