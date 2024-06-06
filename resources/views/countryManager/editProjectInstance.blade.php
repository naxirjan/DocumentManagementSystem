@extends( 'layouts.master' )

@section('page_sepecific_plugin')
<link rel="stylesheet" href="{{ asset( '/' ) }}assets/css/chosen.css" />
<link rel="stylesheet" href="{{asset('../assets/css/datepicker.css')}}" />
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
	<li class="active">Edit Project Instance</li>
	@endsection
	@include('countryManager.includes.breadCrumb')
@endsection

@section('settingbox-section')
	@include('countryManager.includes.settingBox')
@endsection

@section('pageheader-section')
<h1> 
	Edit Project Instance
	<small><i class="ace-icon fa fa-angle-double-right"></i> Required (*) Fields Must Be Filled</small>
</h1>
@endsection


@section('page-content')

<div class="row">
    <div class="col-md-12">
         <!--Success Message-->
        @if(session('msg_success'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert">
                    <i class="ace-icon fa fa-times"></i>
                </button>
                <strong>
                     {{session('msg_success')}}
                </strong>
            </div>
        @endif
        <!--Fail Message-->
        @if(session('msg_fail'))
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert">
                    <i class="ace-icon fa fa-times"></i>
                </button>
                <strong>
                     {{session('msg_fail')}}
                </strong>
            </div>
        @endif
    </div>
</div>
<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-10">
        <!--HTML Form-->
        {!!Form::open(array("url"=>"/countryManager/addOrUpdateProjectInstanceProcess","method"=>"post","class"=>"form-horizontal","role"=>"form", "name"=>"editProjectInstanceForm", "id"=>"editProjectInstanceForm", "enctype"=>"multipart/form-data"))!!}

        
       <!--Project-->
        <div class="form-group">
                <label class="col-sm-3 "><b>Project <span class="red">(*)</span> :</b></label>
                <div class="col-sm-6">
                    
                    <select name="project_id" class="form-control chosen-select" id="project">
                        <option value="">--Select Project--</option>
                        @foreach($projects as $project)
                            @php 
                            $select = ($project['proj_id'] == $oldProjectInstance['project_id'])?'selected':''; 
                            @endphp
                            <option {{$select}} value="{{$project['proj_id']}}">{{$project['proj_name']}}</option>
                        @endforeach
                    </select>

                    @if($errors->has("project_id"))
                    <span class="badge badge-danger">
                        {{$errors->first("project_id")}}
                    </span>
                    @endif
                    <div class="space-2"></div>
                    <div id="project-id-error" style="display:none">
                    <span class="col-md-6 col-sm-4 label label-xs label-danger arrowed arrowed-right"> The Project Field Is Required</span>
                    </div>
                </div>
        </div>

        <!--Project Instance Description-->    
        <div class="form-group">
                <label class="col-sm-3 "><b>Project Instance Description <span class="red">(*)</span> :</b> </label>
                <div class="col-sm-6">
                    
                    {!! Form::textarea("project_instance_description", $oldProjectInstance['project_instance_description'], array("placeholder"=>"Enter Project Instance Description", 'id'=>'address', "class"=>"form-control", "rows"=>"3")) !!}

                      @if($errors->has("project_instance_description"))
                    <span class="badge badge-danger">
                        {{$errors->first("project_instance_description")}}
                    </span>
                    @endif
                    <div class="space-2"></div>
                    <div id="project-description-error" style="display:none">
                    <span class="col-md-6 col-sm-6 label label-xs label-danger arrowed arrowed-right"> The Project Description Field Is Required</span>
                    </div>
                </div>
        </div>
        
        
        <!--Start Date-->    
        <div class="form-group">
                <label class="col-sm-3 "><b>Start From Month <span class="red">(*)</span> :</b> </label>
                <div class="col-sm-6">
                    <div class="input-group">
                    <?php
                        $start_month = date("F, Y",strtotime($oldProjectInstance['project_instance_start_date']));
                    ?>
                    {!! Form::text("project_instance_start_date",$start_month, array('id'=>'start_month',"placeholder"=>"Select Start From Month", "class"=>"form-control monthpicker","data-date-format"=>"MM, yyyy")) !!}

                    <span class="input-group-addon">
                        <i class="fa fa-calendar bigger-110"></i>
                    </span>    
                    </div>
                      @if($errors->has("project_instance_start_date"))
                    <span class="badge badge-danger">
                        {{$errors->first("project_instance_start_date")}}
                    </span>
                    @endif
                    <div class="space-2"></div>
                    <div id="project-start-date-error" style="display:none">
                    <span class="col-md-6 col-sm-6 label label-xs label-danger arrowed arrowed-right"> The Project Start Date Field Is Required</span>
                    </div>
                </div>
        </div>


        <!--End Date-->    
        <div class="form-group">
                <label class="col-sm-3 "><b>End To Month <span class="red">(*)</span> :</b> </label>
                <div class="col-sm-6">
                    <div class="input-group">
                    <?php
                        $end_month = date("F, Y",strtotime($oldProjectInstance['project_instance_end_date']));
                    ?>
                    {!! Form::text("project_instance_end_date",$end_month, array('id'=>'end_month',"placeholder"=>"Select End To Month", "class"=>"form-control monthpicker","data-date-format"=>"MM, yyyy")) !!}
                    <span class="input-group-addon">
                        <i class="fa fa-calendar bigger-110"></i>
                    </span>    
                    </div>
                      @if($errors->has("project_instance_end_date"))
                    <span class="badge badge-danger">
                        {{$errors->first("project_instance_end_date")}}
                    </span>
                    @endif 
                    <div class="space-2"></div>
                    <div id="project-end-date-error" style="display:none">
                    <span class="col-md-6 col-sm-6 label label-xs label-danger arrowed arrowed-right"> The Project End Date Field Is Required</span>
                    </div>
                </div>
        </div>
        
        
        <!--Project Instance Title Will Be Here Dynamically-->
        <div class="form-group" id="project_instance_title">
            <label class="col-sm-3 ">
                    <b>Project Instance Title :</b></label>
                <div class="col-sm-6">
                    <label class="old_project_intance_title">{{$oldProjectInstance['project_instance_title']}}</label>
                </div>
        </div>

        
        <!--Status Label-->
        <div class="form-group">
                <label class="col-sm-3 "><b>Status <span class="red">(*)</span> :</b></label>
                <div class="col-sm-9">
                    <div class="checkbox">  
                        <label style="padding-left:10px">
                            <?php 
                                $status = ($oldProjectInstance['status']=='Active'?true:false);
                            ?>
                            
                            {!! Form::checkbox("status", null, $status, array("class"=>"ace ace-switch ace-switch-6")) !!}
                            <span class="lbl"></span>
                        </label>
                    </div>
                </div>
        </div>
        <!--Status-->
        
        <br />
        <!--Load Countries Control-->
        <div class="form-group">
            <div><label class="col-sm-3 "><h4><b>Project Instance Assigned To</b></h4></label></div>
            <div class="col-sm-6 text-center">
                <label></label>
            </div>
        </div>

        <div id="countries_controls">
            <?php
                foreach($countryDistrict as $data)
                {    
                    ?>
                        <div class="form-group old_countries" id="old_project_instance_country_control_{{$data->project_instance_assigned_id}}">
                            <label class="col-sm-1 control-label no-padding-right"><b>Location</b></label>
                            <div class="col-sm-6">
                                <select class="form-control districts" name="district_operation_id[]" id="old_project_instance_country_{{$data->project_instance_assigned_id}}">
                                    <?php
                                        foreach($dop as $district)
                                        {
                                           if($data->district_operation_id == $district['district_operation_id'])
                                           {
                                            ?>
                                                <option value="{{$district['district_operation_id']}}" selected>{{$district['district_operation_full_name']}}</option>
                                            <?php
                                           }
                                            else
                                            {
                                                
                                             ?>
                                                <option value="{{$district['district_operation_id']}}">{{$district['district_operation_full_name']}}</option>
                                            <?php
                                            }
                                        }
                                        ?>
                                </select>
                            </div>
                            
                            <label class="col-sm-1 control-label no-padding-right"><b>Status</b></label>
                             <div class="col-sm-1">
                                <div class="checkbox">	
                                    <label style="padding-left:10px">
                                        <?php 
                                            $status = ($data->assigned_country_status=='Active'?true:false);
                                        ?>
                                        
                                        <input type="hidden" name="project_instance_district_status[]" value="{{$data->assigned_country_status}}"/>
                                        {!! Form::checkbox("district_status[]", $data->assigned_country_status, $status, array("class"=>"ace ace-switch ace-switch-6 project_instance_country_status")) !!}
                                        <span class="lbl"></span>
                                    </label>
                                </div>
                            </div>
                           
                        </div>
                    <?php
                }
               
                                
            ?>
                <div class="form-group countries" id="countries_0" index="0">
                <label class="col-sm-1 control-label no-padding-right"><b>Location</b></label>
                <div class="col-sm-6">
                    <select class="form-control districts" name="district_operation_id[]">
                        <option value="">--Select Location --</option>
                        <?php
                        foreach($dop as $district)
                        {
                            ?>
                                <option value="{{$district['district_operation_id']}}">{{$district['district_operation_full_name']}}</option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <label class="col-sm-1 control-label no-padding-right"><b>Status</b></label>
                <div class="col-sm-1">
                    <div class="checkbox">	
                        <label style="padding-left:10px">
                            <input type="hidden" name="project_instance_district_status[]" value="Active"/>
                            {!! Form::checkbox("district_status[]", null, true, array("class"=>"ace ace-switch ace-switch-6 project_instance_country_status")) !!}
                            <span class="lbl"></span>
                        </label>
                    </div>
                </div>    
                <div class="col-sm-2">
                   <button class="btn btn-white btn-xs add_project_instance_country_control" type="button"  index="0">
                        <i class="ace-icon fa fa-plus bigger-200 green"></i>
                    </button>
                </div>
            </div>
            
        </div> 

        <div class="form-group" id="div-btn">
            <label class="col-sm-5 "></label>
            <div class="col-sm-7">
               
                <!--Hidden Field-->
                {{ Form::hidden('action', 'edit') }}
                {{ Form::hidden('project_instance_title',$oldProjectInstance['project_instance_title'],array("id"=>"hidden_project_instance_title")) }}
                {{ Form::hidden('project_instance_id',$oldProjectInstance['project_instance_id'],array("class"=>"project_instance_title")) }}
        
                
                {!! Form::submit("Save", array("class"=>"btn btn-success" ,"id"=>"btn_edit_project_instance")) !!}
               <a href="/countryManager/viewProjectInstances" class="btn">Cancel</a>
                <br />
                    <span id="countries" class="hidden">{{json_encode($dop)}}</span>
                </div>
            </div>
        
        <div class="form-group">
            <div class="col-sm-2"></div>    
            <div class="col-sm-8">
                 <div id="msg_delete_project_instance_country"></div>
                <div class="col-sm-2"></div>    
            </div>    
        </div>
          
        {!!Form::close()!!} 
    </div>
</div>
<!--Dialog Delete Old Control-->
<div id="modal-delete-old-project-instance-country-control" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header no-padding green">
                    <div class="table-header">
                        Confirmation
                    </div>
                </div>

                <div class="modal-body no-padding">
                    <h4>&nbsp;Do You Want To Delete "<span id="modal-msg" class="blue"></span>" Location ?</h4>
                </div>

                <div class="modal-footer no-margin-top">
                    <button class="btn btn-sm btn-success" id="btn-delete-old-project-instance-country-control">
                        <i class="ace-icon fa fa-check"></i>
                        Yes
                    </button>
                    <button class="btn btn-sm btn-danger" data-dismiss="modal">
                        <i class="ace-icon fa fa-times"></i>
                        No
                    </button>
                </div>
            </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!--Dialog Delete Old Control-->

<!--Modal Appears When Not Selected District Operation-->
<div id="modal-check-district-operation-1" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="bootbox-close-button close" data-dismiss="modal" aria-hidden="true" style="margin-top: -10px;">×</button>
                <div class="bootbox-body">
                    <h3 class="text-danger text-center"><i class="ace-icon fa fa-exclamation-triangle bigger-120"></i> Please Select District Operation
                </h3>
            </div>
            </div>
            <div class="modal-footer background-blue"></div>
        </div>
    </div>
</div><!-- -->

<!--Modal Appears When District Operation Is Not Unique-->
<div id="modal-check-district-operation-unique-1" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="bootbox-close-button close" data-dismiss="modal" aria-hidden="true" style="margin-top: -10px;">×</button>
                <div class="bootbox-body">
                    <h3 class="text-danger text-center"><i class="ace-icon fa fa-exclamation-triangle bigger-120"></i> Selected District Operation Is Not Unique
                </h3>
            </div>
            </div>
            <div class="modal-footer background-blue"></div>
        </div>
    </div>
</div><!-- -->
@endsection


@section('footer-section')
	@include('countryManager.includes.footer')
@endsection

@section('page_related_scripts')
<script src="{{ asset( '/' ) }}assets/js/chosen.jquery.js"></script>
<script src="{{asset('../assets/js/date-time/bootstrap-datepicker.js')}}"></script>
<script type="text/javascript">
$(document).ready(function()
{
    var index=0;
    
    /*Make Dynamic Project Instance Title*/
    function makeProjectInstanceTitle()
    {
        
        if($("#project").val()!='' && $("#start_month").val()!='' && $("#end_month").val()!='')
        {
            project  = $("#project option:selected").html();
            start_month = $("#start_month").val();
            end_month = $("#end_month").val();
            
            $("#project_instance_title").html('<label class="col-sm-3 "><b>Project Instance Title :</b></label><div class="col-sm-6"><label class="">'+project+' (From: '+start_month+' To: '+end_month+')</label></div>');
            
            $("#hidden_project_instance_title").val(project+' (From: '+start_month+' To: '+end_month+')');   

        }        
    }
    
    /*Project Dropdown onChange Event*/
    $("#project").change(function(){        
        makeProjectInstanceTitle();
    });
    
    /*Month Picker Start Month & End Month With DateChange/DateSubmit Event*/    
        
    $('#start_month').datepicker({
        dateFormat : "mm-yyyy",
        //autoclose  :true,
        //endDate    :'+0d', 
        viewMode   : "months", 
        minViewMode: "months",
    }).on('changeDate', function(selected)
    {
        makeProjectInstanceTitle();
    }).datepicker("setDate", $("#start_month").val());
    
    
    $('#end_month').datepicker({
        dateFormat : "mm-yyyy",
        //autoclose  :true,
        //endDate    :'+0d', 
        viewMode   : "months", 
        minViewMode: "months"
    }).on('changeDate', function(selected)
    {
        makeProjectInstanceTitle();
    }).datepicker("setDate", $("#end_month").val());
    
    
    /*Add Countires Controls*/    
        $(document).on("click",".add_project_instance_country_control",function()
        {   
            index = (parseInt($(this).attr('index')) + 1);
            
            var countries =  $("#countries").html();            
            var selectbox ='<select class="form-control" name="district_operation_id[]"><option>-- Select Location --</option>';
            $.each(JSON.parse(countries),function(index,value){
                
                selectbox +='<option value="'+value.district_operation_id+'">'+value.district_operation_full_name+'</option>';
            });
            
            selectbox +='</select>';
            
            $("#countries_controls").append('<div class="form-group countries" id="countries_'+index+'" index="'+index+'"><label class="col-sm-1 control-label no-padding-right"><b>Location</b></label><div class="col-sm-6">'+selectbox+'</div><label class="col-sm-1 control-label no-padding-right"><b>Status</b></label><div class="col-sm-1"><div class="checkbox"><label style="padding-left:10px"><input type="hidden" name="project_instance_district_status[]"  value="Active"/><input type="checkbox" name="district_status[]" class="ace ace-switch ace-switch-6 project_instance_country_status" checked><span class="lbl"></span></label></div></div><div class="col-sm-2"><button class="btn btn-white btn-xs remove_project_instance_country_control" type="button"  index="'+index+'"><i class="ace-icon fa fa-remove bigger-200 red"></i></button><button class="btn btn-white btn-xs add_project_instance_country_control" type="button"  index="'+index+'"><i class="ace-icon fa fa-plus bigger-200 green"></i></button></div></div>');
            
            
            if(index==1)
            {
                $(this).before('<button class="btn btn-white btn-xs remove_project_instance_country_control" type="button" index="'+(index-1)+'"><i class="ace-icon fa fa-remove bigger-200 red"></i></button>');
            }
            
            $(this).remove();
            

        });
    
    
        /*Remove Country Control*/    
        $(document).on("click",".remove_project_instance_country_control",function()
        {   
            
            let $length = $(".countries").length;
            if($length > 1){
                $("#countries_"+$(this).attr('index')).remove();

                $(".countries .add_project_instance_country_control").last().remove();
                $(".countries .remove_project_instance_country_control").last().after('<button class="btn btn-white btn-xs add_project_instance_country_control" type="button"  index="'+($(this).attr('index')-1)+'"><i class="ace-icon fa fa-plus bigger-200 green"></i></button>');    
            }else{
                $('.remove_project_instance_country_control[index='+$(this).attr('index')+']').remove();
            }
            
        });
    
    
    $(document).on('change','.project_instance_country_status',function(){
       
            if($(this).prop("checked") == true)
            {
                $(this).prev("input[type=hidden]").val('Active');
                //$("#project_instance_country_status_"+$(this).attr('id')).val('Active');
            }
            else if($(this).prop("checked") == false)
            {
                //$("#project_instance_country_status_"+$(this).attr('id')).val('In Active');
                $(this).prev("input[type=hidden]").val('InActive');
            }
        
    });
});

    /*Submit Project Instance Form*/
    $(document).on('click' ,'#btn_edit_project_instance' ,function(evt){
        evt.preventDefault();

        let $districtId = [];
        /*Insert Selected District Operaion In Array*/
        $.each($('.districts') ,function(){
                     
            if($(this).val() != ''){
                $districtId.push($(this).val());   
            }   
        });

                /*Check All District Operation Row  Value Is Selected*/
                if(($districtId.length != 0)){
                    
                    if(checkIfArrayIsUnique($districtId)){
                        $('#div-btn').hide();
                        $('#editProjectInstanceForm').submit();
                    }else{
                        $('#modal-check-district-operation-unique-1').modal('show');    
                    }
                    
                }else{
                    $('#modal-check-district-operation-1').modal('show');
                }
    });
    
    /*Check Priority Array Unique Or Not*/
        function checkIfArrayIsUnique(myArray) {
         return myArray.length === new Set(myArray).size;
        }
        //-->
</script>
@endsection
