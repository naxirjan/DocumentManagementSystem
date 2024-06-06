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
	<li class="active">Add Project Instance</li>
	@endsection
	@include('usManager.includes.breadCrumb')
@endsection

@section('settingbox-section')
	@include('usManager.includes.settingBox')
@endsection

@section('pageheader-section')
<h1> 
	Add Project Instance
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
    <div class="col-md-1"></div>
    <div class="col-md-10">
        <!--HTML Form-->
        {!!Form::open(array("url"=>"/usManager/addOrUpdateProjectInstanceProcess","method"=>"post","class"=>"form-horizontal","role"=>"form", "name"=>"addQuestionForm", "id"=>"addQuestionForm", "enctype"=>"multipart/form-data"))!!}

        <!--Project-->
        <div class="form-group">
            <label class="col-sm-3"><b>Project <span class="red">(*)</span> :</b></label>
            <div class="col-sm-9">
                {!! Form::select('project_id',$projects,old('project_id'),['id'=>"project","class"=>"form-control"]) !!}
                @if($errors->has("project_id"))
                <span class="badge badge-danger">
                    {{$errors->first("project_id")}}
                </span>
                @endif
            </div>
        </div>
        
         <!--Project Instance Description-->    
        <div class="form-group">
            <label class="col-sm-3 "><b>Project Instance Description <span class="red">(*)</span> :</b> </label>
            <div class="col-sm-9">
                {!! Form::textarea("project_instance_description", null, array("placeholder"=>"Enter Question Description", 'id'=>'address', "class"=>"form-control", "rows"=>"3")) !!}
                  @if($errors->has("project_instance_description"))
                <span class="badge badge-danger">
                    {{$errors->first("project_instance_description")}}
                </span>
                @endif
			</div>
        </div>
        
         <!--Start Date-->    
        <div class="form-group">
            <label class="col-sm-3 "><b>Start From Month <span class="red">(*)</span> :</b> </label>
            <div class="col-sm-9">
                <div class="input-group">
                {!! Form::text("project_instance_start_date", NULL, array('id'=>'start_month',"placeholder"=>"Select Start From Month", "class"=>"form-control monthpicker","data-date-format"=>"MM, yyyy")) !!}
                <span class="input-group-addon">
                    <i class="fa fa-calendar bigger-110"></i>
                </span>    
                </div>
                  @if($errors->has("project_instance_start_date"))
                <span class="badge badge-danger">
                    {{$errors->first("project_instance_start_date")}}
                </span>
                @endif
			</div>
        </div>
        
        <!--End Date-->    
        <div class="form-group">
            <label class="col-sm-3 "><b>End To Month <span class="red">(*)</span> :</b> </label>
            <div class="col-sm-9">
                <div class="input-group">
                {!! Form::text("project_instance_end_date", NULL, array('id'=>'end_month',"placeholder"=>"Select End To Month", "class"=>"form-control monthpicker","data-date-format"=>"MM, yyyy")) !!}
                <span class="input-group-addon">
                    <i class="fa fa-calendar bigger-110"></i>
                </span>    
                </div>
                  @if($errors->has("project_instance_end_date"))
                <span class="badge badge-danger">
                    {{$errors->first("project_instance_end_date")}}
                </span>
                @endif
			</div>
        </div>
        
        <!--Project Instance Title Will Be Here Dynamically-->
        <div class="form-group" id="project_instance_title">
        </div>

        <!--Status Label-->
        <div class="form-group">
            <label class="col-sm-3 "><b>Status <span class="red">(*)</span> :</b></label>
            <div class="col-sm-9">
                <div class="checkbox">	
                    <label style="padding-left:10px">
                        {!! Form::checkbox("status", null, true, array("class"=>"ace ace-switch ace-switch-6")) !!}
                        <span class="lbl"></span>
                    </label>
                </div>
            </div>
        </div>
        <!--Status-->
    
        <br />
        <!--Load Countries Control-->
        <div class="form-group">
            <div><label class="col-sm-3"><h4><b>Project Instance Assigned To</b></h4></label></div>
            <div class="col-sm-9 text-center">
                <label></label>
            </div>
        </div>
        <div id="countries_controls"></div> 
      


        
        <div class="form-group">
            <label class="col-sm-5 "></label>
            <div class="col-sm-7">
                <!--Hidden Field-->
                {{ Form::hidden('action', 'add') }}
                {{ Form::hidden('project_instance_title',null,array("class"=>"project_instance_title")) }}
        
                
                {!! Form::submit("Save", array("class"=>"btn btn-success btn-save-project-instance")) !!}
                {!! Form::reset("Cancel", array("class"=>"btn btn-secondary")) !!}
                
                <span id="countries" class="hidden">{{json_encode($countries)}}</span>
            </div>
        </div>
        
        {!!Form::close()!!} 
    </div>
        <div class="col-md-1"></div>

</div>


<!--Dialog Project Instance Country Already Exists-->
<div id="modal-project-instance-country-already-exists" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header no-padding">
                <div class="table-header" style="background-color:red;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <span class="white">&times;</span>
                    </button>
                    <span class="blink_me"><i class="menu-icon fa fa-warning"></i> <b>Warning !...</b></span>
                </div>
            </div>

            <div class="modal-body">
                <div class="text-center" id="modal-msg">
                </div>
            </div>

        </div>
    </div>
</div>
<!--Dialog Project Instance Country Already Exists-->

<style>
.blink_me {
  animation: blinker 1s linear infinite;
    }
@keyframes blinker {  
  50% { opacity: 0.0; }
}
</style>


@endsection


@section('footer-section')
	@include('usManager.includes.footer')
@endsection

@section('page_related_scripts')
<script type="text/javascript">
$(document).ready(function()
{
    var index=0;
    
    /*Load Project Instance Assigned To Countries*/
    $( "#countries_controls" ).load( "/usManager/loadProjectInstanceAssignedToCountriesContent");
    
    /*Project Dropdown onChange Event*/
    $("#project").change(function(){        
        $("#end_month,#start_month").val('');
        $("#project_instance_title").html('');
    });
    
    /*Month Picker Start Month & End Month With DateChange/DateSubmit Event*/    
    $('#end_month,#start_month').datepicker({
        dateFormat : "mm-yyyy",
        autoclose  :true,
        viewMode   : "months", 
        minViewMode: "months"
    }).on('changeDate', function(selected)
    {
        var project     = $("#project option:selected").html();
        var start_month = $("#start_month").val();
        var end_month   = $("#end_month").val();
        
        if(project !='' && start_month!='' && end_month!='' )
        {
            $("#project_instance_title").html('<label class="col-sm-3 "><b>Project Instance Title :</b></label><div class="col-sm-6"><label class="">'+project+' (From: '+start_month+' To: '+end_month+')</label></div>');
            
            $(".project_instance_title").val(project+' (From: '+start_month+' To: '+end_month+')');    
        }
               
    });
    
    /*Month Picker Start Month & End Month With onChangeEvent*/    
    $('#end_month,#start_month').change(function(){
       
        if($(this).val()=='')
        {
            $("#project_instance_title").html('')
        }
    });
    
    
    /*Add Countires Controls*/    
    $(document).on("click",".add_country_control",function()
    {   
        index = (parseInt($(this).attr('index')) + 1);

        var countries =  $("#countries").html();            
        var selectbox ='<select class="form-control country_control" index="'+index+'" name="country_id[]"><option value="">-- Select Country --</option>';
        $.each(JSON.parse(countries),function(index,value){

            selectbox +='<option value="'+value.cont_id+'">'+value.cont_name+'</option>';
        });

        selectbox +='</select>';

        $("#countries_controls").append('<div class="form-group countries" id="countries_'+index+'" index="'+index+'"><label class="col-sm-1 control-label no-padding-right"><b>Country</b></label><div class="col-sm-3">'+selectbox+'</div><div class="country_district_operations_'+index+'"><label class="col-sm-5 control-label no-padding-right"></label></div><label class="col-sm-1 control-label no-padding-right"><b>Status</b></label><div class="col-sm-1"><div class="checkbox"><label style="padding-left:10px"><input type="checkbox" class="ace ace-switch ace-switch-6" name="country_status[]" checked="checked"><span class="lbl"></span></label></div></div><div class="col-sm-1"><button class="btn btn-white btn-xs remove_country_control" type="button" index="'+(index)+'"><i class="ace-icon fa fa-remove bigger-200 red"></i></button><button class="btn btn-white btn-xs add_country_control" type="button"  index="'+(index)+'"><i class="ace-icon fa fa-plus bigger-200 green"></i></button></div></div>');            

        if(index==1)
        {
            $(this).before('<button class="btn btn-white btn-xs remove_country_control" type="button" index="'+(index-1)+'"><i class="ace-icon fa fa-remove bigger-200 red"></i></button>');
        }

        $(this).remove();
    });
    
    
    /*Remove Country Control*/    
    $(document).on("click",".remove_country_control",function()
    {   
        $("#countries_"+$(this).attr('index')).remove();

        length = $(".countries").length;
        if(length==0)
        {
            $( "#countries_controls" ).load( "/usManager/loadProjectInstanceAssignedToCountriesContent");
        }
        else
        {
                $(".countries .add_country_control").last().remove();
                $(".countries .remove_country_control").last().after('<button class="btn btn-white btn-xs add_country_control" type="button"  index="'+($(this).attr('index')-1)+'"><i class="ace-icon fa fa-plus bigger-200 green"></i></button>');
        }

      });
    
    /*Countries DropDown onChange Event*/
    $(document).on("change",".country_control",function(e){
        e.preventDefault();
        
        let country_id    = $(this).val();
        let country_index = $(this).attr('index');
        let total_length  = $("select.country_control" ).length;
        let count         = 0; 
        let country_name  = null;
         
        if(country_id=='' || country_id==null)
        {
            $(".country_district_operations_"+country_index).html('<label class="col-sm-5 control-label no-padding-right"></label>');
        }
        else
        {
            
            $("select.country_control" ).each(function( index )
            {
                if( ( $(this).val()!='') && $(this).val()==country_id)
                {
                   count++;
                    country_name = $(this).children("option:selected").text();
                }
            });
            
            
            if(count > 1)
            {
                $(".btn-save-project-instance").addClass("hidden");
                $("#modal-project-instance-country-already-exists").modal('show');
                $("#modal-msg").html("<h4 class='red'><b>"+country_name+" Country</b></h4><h5 class='green'><b> Already Exists In</b></h5><h5 class='red'><b> "+$(".project_instance_title").val()+"</b></h5>");
            }
            else if(country_id==1)
            {
                $(".country_district_operations_"+$(this).attr('index')).html('<label class="col-sm-5 control-label no-padding-right"></label>');
                $(".btn-save-project-instance").removeClass("hidden");

            }
            else if(country_id!=1)
            {
                $(".country_district_operations_"+$(this).attr('index')).load( "/usManager/loadCountryDistrictOperationsContent/"+$(this).val());
                $(".btn-save-project-instance").removeClass("hidden");
            }
    
        }

    });
    
});
</script>
@endsection
