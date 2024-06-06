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
	<li class="active">Add Project Instance</li>
	@endsection
	@include('countryManager.includes.breadCrumb')
@endsection

@section('settingbox-section')
	@include('countryManager.includes.settingBox')
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
    <div class="col-md-2"></div>
    <div class="col-md-10">
        <!--HTML Form-->
        {!!Form::open(array("url"=>"/countryManager/addOrUpdateProjectInstanceProcess","method"=>"post","class"=>"form-horizontal","role"=>"form", "name"=>"addProjectInstanceForm", "id"=>"addProjectInstanceForm", "enctype"=>"multipart/form-data"))!!}

        <!--Build project-->
        <div class="form-group">
            <label class="col-sm-3 "><b>Build Project Instance  <span class="red">(*)</span> :</b></label>
            <div class="col-sm-6">
                <label>
                    <input name="checkedProjectInstance" type="radio" class="ace form-control" value="Yes" />
                    <span class="lbl"> Want To Create New Project </span>
                </label>
                &nbsp;&nbsp;
                <label>
                    <input name="checkedProjectInstance" type="radio" class="ace form-control" value="No" />
                    <span class="lbl"> Use Predefined Project Instance </span>
                </label>
               
                @if($errors->has("project_id"))
                <span class="badge badge-danger">
                    {{$errors->first("project_id")}}
                </span>
                @endif
            </div>
        </div>

        <!-- Ajax Div -->
        <div id="general-div" style="display:block">
            
        </div>
        <!-- End -->

      <div class="form-group" id="div-btn" style="display:none">
            <label class="col-sm-5 "></label>
            <div class="col-sm-7">
                <!--Hidden Field-->
                {{ Form::hidden('action', 'add') }}
                {{ Form::hidden('project_instance_title',null,array("class"=>"project_instance_title")) }}
        
                
                {!! Form::submit("Save", array("class"=>"btn btn-success" ,"id"=>"btn_save_project_instance")) !!}
                {{-- Form::reset("Cancel", array("class"=>"btn btn-secondary")) --}}
                <a href="/countryManager/viewProjectInstances" class="btn">Cancel</a>
                <span id="countries" class="hidden">{{json_encode($dop)}}</span>
            </div>
        </div>
        
        {!!Form::close()!!} 
    </div>
</div>

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
    /*For Select Box With Search*/
        $('.chosen-select').chosen({allow_single_deselect:true});
    
    /*Get General Div For Project Instance*/
    $(document).on('change' ,'input[name=checkedProjectInstance]:checked' ,function(){

        let $flagID = $(this).val();
        
        $.ajax({
                    url:"{{ url('countryManager/getGeneralDivForProjectInstance') }}",
                    type:"GET",
                    //dataType: 'json',
                    beforeSend:function(){
                        $('#processing_div').show();
                    },
                    data:{
                        _token:'{{csrf_token()}}',
                        flag:$flagID,
                        },
                        success:function(data){
                            $('#general-div').html(data);
                            $( "#countries_controls" ).load( "/countryManager/loadProjectInstanceAssignedToCountriesContent");
                            
                            /*For Select Box With Search*/
                             $('.chosen-select').chosen({allow_single_deselect:true});
                            myDatePicker();
                            if($flagID == 'Yes'){
                                $('#div-btn').show();    
                            }else{
                                $('#div-btn').hide();
                            }
                        }             
        });

        
    });

    var index=0;
    
    /*Project Dropdown onChange Event*/
    $(document).on('change' ,'#project',function(){        
        $("#end_month,#start_month").val('');
        $("#project_instance_title").html('');
    });


    function myDatePicker(){
        /*Month Picker Start Month & End Month With DateChange/DateSubmit Event*/    
        $('#start_month').datepicker({
            dateFormat : "mm-yyyy",
            autoclose  :true,
            //endDate    :'+0d', 
            viewMode   : "months", 
            minViewMode: "months"
        }).on('changeDate', function(selected)
        {
            makeProjectInstanceTitle();
            
            startDate = new Date(selected.date.valueOf());
            startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
            startDate.setMonth(startDate.getMonth()+1);
            $('#end_month').datepicker('setStartDate', startDate);
            /*var project     = $("#project option:selected").html();
            var start_month = $("#start_month").val();
            var end_month   = $("#end_month").val();
            
            if(project !='' && start_month!='' && end_month!='' )
            {
                $("#project_instance_title").html('<label class="col-sm-3 "><b>Project Instance Title :</b></label><div class="col-sm-6"><label class="">'+project+' (From: '+start_month+' To: '+end_month+')</label></div>');
                
                $(".project_instance_title").val(project+' (From: '+start_month+' To: '+end_month+')');    
            }

            */

        });

        $('#end_month').datepicker({
            dateFormat : "mm-yyyy",
            autoclose  :true,
            //endDate    :'+0d', 
            viewMode   : "months", 
            minViewMode: "months"
        }).on('changeDate', function(selected)
        {
            /*var project     = $("#project option:selected").html();
            var start_month = $("#start_month").val();
            var end_month   = $("#end_month").val();
            
            if(project !='' && start_month!='' && end_month!='' )
            {
                $("#project_instance_title").html('<label class="col-sm-3 "><b>Project Instance Title :</b></label><div class="col-sm-6"><label class="">'+project+' (From: '+start_month+' To: '+end_month+')</label></div>');
                
                $(".project_instance_title").val(project+' (From: '+start_month+' To: '+end_month+')');    
            }*/
             makeProjectInstanceTitle();   
        });    
    }

    /*Make Dynamic Project Instance Title*/
    function makeProjectInstanceTitle()
    {
        
        if($("#project").val()!='' && $("#start_month").val()!='' && $("#end_month").val()!='')
        {
           var  project  = $("#project option:selected").html();
            var start_month = $("#start_month").val();
            var end_month = $("#end_month").val();
            
            $("#project_instance_title").html('<label class="col-sm-3 "><b>Project Instance Title :</b></label><div class="col-sm-6"><label class="">'+project+' (From: '+start_month+' To: '+end_month+')</label></div>');
            
            $(".project_instance_title").val(project+' (From: '+start_month+' To: '+end_month+')');   

        }  

    }

    /*Month Picker Start Month & End Month With onChangeEvent*/    
    $(document).on('change' ,'#end_month,#start_month' ,function(){
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
            var selectbox ='<select class="form-control districts" name="district_operation_id[]"><option value="">-- Select Location --</option>';
            $.each(JSON.parse(countries),function(index,value){
                
                selectbox +='<option value="'+value.district_operation_id+'">'+value.district_operation_full_name+'</option>';
            });
            
            selectbox +='</select>';
            
            $("#countries_controls").append('<div class="form-group countries" id="countries_'+index+'" index="'+index+'"><label class="col-sm-1 control-label no-padding-right"><b>Location</b></label><div class="col-sm-6">'+selectbox+'</div><label class="col-sm-1 control-label no-padding-right"><b>Status</b></label><div class="col-sm-1"><div class="checkbox"><label style="padding-left:10px"><input type="checkbox" class="ace ace-switch ace-switch-6" name="district_status[]" checked="checked"><span class="lbl"></span></label></div></div><div class="col-sm-2"><button class="btn btn-white btn-xs remove_country_control" type="button" index="'+(index)+'"><i class="ace-icon fa fa-remove bigger-200 red"></i></button><button class="btn btn-white btn-xs add_country_control" type="button"  index="'+(index)+'"><i class="ace-icon fa fa-plus bigger-200 green"></i></button></div></div>');            
            
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
                $( "#countries_controls" ).load( "/countryManager/loadProjectInstanceAssignedToCountriesContent");
            }
            else
            {
                    $(".countries .add_country_control").last().remove();
                    $(".countries .remove_country_control").last().after('<button class="btn btn-white btn-xs add_country_control" type="button"  index="'+($(this).attr('index')-1)+'"><i class="ace-icon fa fa-plus bigger-200 green"></i></button>');
            }

          });
    
    
    /*Get Predefined Project Instance Structure*/
    $(document).on('change' ,'#predefined_project_intance_id' ,function(){
        let $predefined_project_instance_id = $(this).val();
        if($predefined_project_instance_id){
            
            $.ajax({
                url:"{{ url('countryManager/getPredefinedProjectInstance') }}",
                type:"GET",
                //dataType: 'json',
                beforeSend:function(){
                    $('#processing_div').show();
                },
                data:{
                    _token:'{{csrf_token()}}',
                    project_instance_id:$predefined_project_instance_id,
                },
                success:function(data){
                            console.log(data);
                            $('#predefined_project_intance').html(data);
                            /*For Select Box With Search*/
                            $('.chosen-select').chosen({allow_single_deselect:true});
                            
                            $('#div-btn').show();
                            $('.project_instance_title').val($(".old_project_intance_title").html());
                            
                            //$( "#countries_controls" ).load( "/countryManager/loadProjectInstanceAssignedToCountriesContent");

                            $('#start_month').datepicker({
                                dateFormat : "mm-yyyy",
                                autoclose  :true,
                                //endDate    :'+0d', 
                                viewMode   : "months", 
                                minViewMode: "months",
                            }).on('changeDate', function(selected)
                            {
                                //myDatePicker();

                                makeProjectInstanceTitle();   

                            }).datepicker("setDate", $("#start_month").val());
    
    
                            $('#end_month').datepicker({
                                dateFormat : "mm-yyyy",
                                autoclose  :true,
                                //endDate    :'+0d', 
                                viewMode   : "months", 
                                minViewMode: "months"
                            }).on('changeDate', function(selected)
                            {
                                //myDatePicker();
                                makeProjectInstanceTitle();
                            }).datepicker("setDate", $("#end_month").val());

                }             
            });
        }
    });
    //-->


    /*Submit Project Instance Form*/
    $(document).on('click' ,'#btn_save_project_instance' ,function(evt){
            evt.preventDefault();
            $('#modal-check-district-operation-1').modal('hide');
            let $projectId = $('#project').val();
            let $projectDesc = $('#address').val();
            let $startDate = $('#start_month').val();
            let $endDate = $('#end_month').val();
            let $districtId = [];
            let $validate = formValidation($projectId,$projectDesc,$startDate,$endDate);
            
            if($validate){
                
                /*Insert Selected District Operaion In Array*/
                $.each($('.districts') ,function(){
                     
                     if($(this).val() != ''){
                        $districtId.push($(this).val());   
                    }   
                });

                
                /*Check All District Operation Row  Value Is Selected*/
                if( ($districtId.length != 0)  && ($('.districts').length == $districtId.length)){
                    
                    if(checkIfArrayIsUnique($districtId)){
                        $('#div-btn').hide();
                        $('#addProjectInstanceForm').submit();
                    }else{
                        $('#modal-check-district-operation-unique-1').modal('show');    
                    }
                    
                }else{
                    $('#modal-check-district-operation-1').modal('show');
                }

            }else{
                evt.preventDefault();
            }

    });
    //-->

    /*Check Priority Array Unique Or Not*/
        function checkIfArrayIsUnique(myArray) {
         return myArray.length === new Set(myArray).size;
        }
        //-->
    
    /*Form Validation*/   
    function formValidation($projectId,$projectDesc,$startDate,$endDate){
        var $return = 0;
            if($projectId == ''){
                $('#project-id-error').show();
                $return++;
            }else{
                $('#project-id-error').hide();
            }

            if($projectDesc == ''){
                $('#project-description-error').show();
                $return++;
            }else{
                $('#project-description-error').hide();
            }        

            if($startDate == ''){
                $('#project-start-date-error').show();
                $return++;
            }else{
                $('#project-start-date-error').hide();
            }               

            if($endDate == ''){
                $('#project-end-date-error').show();
                $return++;
            }else{
                $('#project-end-date-error').hide();
            }

            if($return === 0){
                return true;    
            }else{
                //window.scrollTo({top:0,left:0,behaviour:'smooth'});
                return false;
            } 
    }
    //-->
});
    
</script>
@endsection
