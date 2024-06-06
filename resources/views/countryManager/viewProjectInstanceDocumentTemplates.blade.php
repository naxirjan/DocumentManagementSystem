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
<li class="active">Assign Document Templates To Project Instance</li>
@endsection
@include('countryManager.includes.breadCrumb')
@endsection

@section('settingbox-section')
@include('countryManager.includes.settingBox')
@endsection

@section('pageheader-section')
<h1>
    Assign Document Templates To Project Instance
</h1>
@endsection


@section('page-content')
<!--Project Instance Details Data-->
<div class="row">
    <div class="col-md-12">
        <?php


            extract($project_instance_detail[0]);
            //dd($project_id);
        ?>
        <div class="form-horizontal">

            <!--Project-->
            <div class="form-group">
                <label class="col-sm-3"><b>Project:</b></label>
                <div class="col-sm-6">
                    <label class="">
                        <?php
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
                        {{ $project_instance_description }}
                    </label>
                </div>
            </div>

            <!--Start Date-->
            <div class="form-group">
                <label class="col-sm-3 "><b>Start From Month:</b> </label>
                <div class="col-sm-6">
                    <label class="">
                        {{ date('F, Y',strtotime($project_instance_start_date)) }}
                    </label>
                </div>
            </div>

            <!--End Date-->
            <div class="form-group">
                <label class="col-sm-3 "><b>End To Month:</b> </label>
                <div class="col-sm-6">
                    <label class="">
                        {{ date('F, Y',strtotime($project_instance_end_date)) }}
                    </label>
                </div>
            </div>

            <!--Project Instance Title Will Be Here Dynamically-->
            <div class="form-group">
                <label class="col-sm-3 "><b>Project Instance Title:</b> </label>
                <div class="col-sm-9">
                    <label class="">
                        {{ $project_instance_title }}
                    </label>
                </div>
            </div>
            <!--Project Instance Added By-->
            <div class="form-group">
                <label class="col-sm-3 "><b>Added By:</b> </label>
                <div class="col-sm-9">
                    <label class="">
                        <?php
                            $result=getUserAndRoleByRoleUserId($added_by);
                        ?>
                        {{$result[0]->first_name}} {{$result[0]->last_name}} <span class="label label-sm label-info arrowed-in arrowed-in-right">{{$result[0]->role}}</span>
                    </label>    
                </div>
            </div>

            <!--Status Label-->
            <div class="form-group">
                <label class="col-sm-3 "><b>Status:</b></label>
                <div class="col-sm-6">
                    <label class="">
                        <span class="label label-sm label-{{($status=='Active'?'success':'danger')}} arrowed-in-right arrowed-in"> {{$status}} </span>
                    </label>
                </div>
            </div>
            <!--Status-->
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
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
 @if(count($common_document_templates)>0)
<!--Document Templates And Project Instance Countries-->
{!!Form::open(array("url"=>"/countryManager/assignDocumentTemplatesToProjectInstance","method"=>"post","role"=>"form", "name"=>"assignDocumentTemplates", "id"=>"assignDocumentTemplates", "enctype"=>"multipart/form-data"))!!}

<div class="row">
    <div class="col-md-5">
        <h3><b>Assign Templates</b></h3>
        <br />
        <div class="widget-box">
            <div class="widget-header" style="padding-left:0px">
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-search"></i>
                    </span>
                    <input class="form-control" type="text" placeholder="Search Documnet Template" id="search_template">
                </div>
            </div>
            <div class="widget-body">
                <div class="widget-main document_templates document_templates_searchable"> 
                    @foreach($common_document_templates as $key => $document_template)
                    <div class="widget-header widget-header-small add_document_template_component ui-draggable ui-draggable-handle"   document_template_id="{{$document_template['document_template_id']}}" style="border:1px solid #f1eded;margin-top:5px" document_template="{{$document_template['document_template_title']}}">   
                        <h6 class="widget-title">
                             <i class="menu-icon fa fa-arrows"></i>
                            &nbsp;&nbsp;
                            <span class="widget-data" style="font-size:15px;">
                                {{$document_template['document_template_title']}}
                            </span>
                        </h6>
                        <div class="widget-toolbar">
                            <a target="_blank" href="/countryManager/viewTemplateDetail/{{$document_template['document_template_id']}}" title="View Details">
                                <i class="ace-icon fa fa-eye"></i>
                                View
                            </a>
                        </div>
                    </div>
                    @endforeach
                    <div id="localSearchMark"></div>
                       
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <h3></h3><br />
        <div class="tabbable">
            <?php
                $i=0;
            ?>
            <ul class="nav nav-tabs padding-12 tab-color-blue background-blue" id="myTab4">
                @foreach ($project_instance_countries as $key => $country)
                <li class="country_tab <?php if($i==0){echo 'active';}?>">
                    <a data-toggle="tab" href="#country_{{$country['project_instance_assigned_id']}}" aria-expanded="<?php if($i==0){echo 'true';}else{echo 'false';}?>">
                       <?php 
                            $district_operation = getDistrcitOperationNameByDistrcitOperationId($country['district_operation_id']);
                            $opearion = strtolower($district_operation[0]->district_operation_short_name);
                        ?>
                           <b> {{ $district_operation[0]->district_operation_short_name }}</b> 
                               
                    </a>
                </li>
                <?php 
                    $i++;
                ?>
                @endforeach

            </ul>

            <div class="tab-content form-horizontal">
                <?php $j=0;?>
                @foreach ($project_instance_countries as $key => $country)
                <div project_instance_assigned_id="{{$country['project_instance_assigned_id']}}" id="country_{{$country['project_instance_assigned_id']}}" class="country_tab_section tab-pane <?php if($j==0){echo 'active';}?>">
                    <?php    
                    foreach($project_instance_document_templates as $key => $document_template)
                    {
                    if($document_template->project_instance_assigned_id == $country['project_instance_assigned_id'])
                    {
                    ?>
                    <div style="padding:10px;border-radius:10px;border:1px solid #c7c3c3;" class="document_template_already_exists" document_template_id="{{$document_template->document_template_id}}" template_type_id="{{$document_template->template_type_id}}">
                        <!--Project-->
                        <div class="form-group">
                            <label class="col-sm-3 "><b>Template:</b></label>
                            <div class="col-sm-9">
                                <label>{{$document_template->document_template_title}}</label>

                            </div>
                        </div>
                        <!--Start Date-->
                        <div class="form-group">
                            <label class="col-sm-3 "><b>Submission Start:</b> </label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <label>{{date('d F, Y',strtotime($document_template->project_instance_submission_start_date))}}</label>
                                </div>
                            </div>
                        </div>
                        <!--End Date-->
                        <div class="form-group">
                            <label class="col-sm-3 "><b>Submission End:</b> </label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <label>{{date('d F, Y',strtotime($document_template->project_instance_submission_stop_date))}}</label>
                                </div>
                            </div>
                        </div>
                        <!--Status Label-->
                        <div class="form-group">
                            <label class="col-sm-3 "><b>Status:</b></label>
                            <div class="col-sm-9">
                                 <label class="">
                                   
                                    <span class="label label-sm label-{{($document_template->document_template_assigned_status=='Active'?'success':'danger')}} arrowed-in-right arrowed-in"> {{$document_template->document_template_assigned_status}} </span>
                                </label>
                                
                                
                                <p class="pull-right"><u><b><a href="">Submissions: {{countTotalProjectInstanceDocumentTemplateSubmissions($document_template->project_instance_document_template_id,$document_template->project_instance_assigned_id)}}</a></b></u>
                                    
                                </p>
                               
                            </div>
                        </div>
                    </div>
                    <br />
                    <?php
                        }
                    }
                    ?>
                </div>
                <?php $j++;?>
                @endforeach

            </div>
        </div>
    </div>
</div>
<!--Form Action Buttons-->
<div class="row">
    <div class="col-md-5"></div>
    <div class="col-md-7">
        {!! Form::submit("Save Document Template", array("class"=>"btn btn-success")) !!}
    </div>
</div>
{!!Form::close()!!}
@else
    <div class="col-md-3">
        <h3><b>Assign Templates</b></h3>
    </div>
     <div class="col-md-8">
         <h3 class="label label-danger arrowed-in arrowed-in-right">No Document Template Was Found For This Project Instance !...</h3>
        <br/><br/> 
         <a href="/countryManager/viewProjectInstances" class="btn btn-secondary"><i class="fa fa-arrow"></i> Back</a>
    </div>
@endif


<style>
    .tab-content>.active,
    .document_templates{
        overflow-y: scroll;
        overflow-x: hidden;
        height: 550px;
    }

    .ui-state-hover {
        background-color: #e4e6e9;
        border: 3px solid #ababab;
        border-style: dashed;
        padding: 15px;
    }

    .widget-header.widget-header-small.add_document_template_component:hover
    {
        cursor: move;    
    }
    

</style>

<div id="modal-document-template-exists" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header no-padding">
                <div class="table-header" style="background-color:red;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <span>&times;</span>
                    </button>
                    <span class="blink_me"><i class="menu-icon fa fa-warning bigger-130"></i> <b>Warning !...</b></span>
                </div>
            </div>

            <div class="modal-body">
                <h4 class="red text-center" id="document-template-exists-msg"></h4>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
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
@include('countryManager.includes.footer')
@endsection

@section('page_related_scripts')


<script>
jQuery(function()
{  
    
    var index = 0;

/*Drag Document Template To Drop*/        
    jQuery(".add_document_template_component").draggable({
        helper: function()
        {
            return jQuery(this).clone().appendTo('body').css({ 'zIndex': 5});
        },
        cursor: 'move',
        containment: "document",
    });
/*Drag Document Template To Drop*/ 
    

/*Drop Document Template After Dragging*/        
    jQuery('.country_tab_section.tab-pane').droppable({
        activeClass: 'ui-state-hover',
        accept: '.add_document_template_component',
        drop: function(event, ui)
        {
            var height = ($('.tab-content > .active').height() +250);
            var document_template_id = ui.draggable.attr('document_template_id');
            var project_instance_assigned_id = $(".country_tab_section.tab-pane.active").attr('project_instance_assigned_id');
            var country_section_id = $(".country_tab_section.tab-pane.active").attr('id');

            var current_active_country_name=$(".country_tab.active").text().trim();
            var current_dragged_document_template = ui.draggable.attr('document_template');

            if (!ui.draggable.hasClass("dropped"))
            {
                var flag=false;
                //jQuery(this).append(jQuery(ui.draggable).clone().addClass("dropped").draggable());

                $(".country_tab_section.tab-pane.active > .document_template_already_exists").each(function(index)
                {
                    /*Check If Document Template Is Already Assigned*/
                    if($(this).attr('document_template_id')==document_template_id && $(this).attr('template_type_id') !=4)
                    {
                        $("#modal-document-template-exists").modal('show');
                        $("#document-template-exists-msg").html("<h5 class='red'><b>"+current_dragged_document_template+"</b></h5><h5 class='green'><b> Already Exists In</b></h5><h4 class='red'><b> "+current_active_country_name+"</b></h4>");
                        flag=true;
                    }                 
                });
                
                if(flag==false)
                {    
                    var content=null;
                    $.get("/countryManager/loadProjectInstanceDocumentTemplateContent/" + document_template_id + "_" + project_instance_assigned_id, function(data)
                    {
                        content = data;
                        $("#" + country_section_id).append(content);

                        /*Month Picker Start Month & End Month With DateChange/DateSubmit Event*/
                        $('.date-picker').datepicker({
                            autoclose: true,
                            todayHighlight: true
                            })
                        .next().on(ace.click_event, function(){
                            $(this).prev().focus();
                        });
                    }); 
                    $("div.tab-content > .active" ).animate({scrollTop: height});
                }
            }
        }
    });
/*Drop Document Template After Dragging*/        

    

/*Change Status To Active/In Active On onChange Status Radio Button*/
    $(document).on('change', '.template_status', function()
    {
        if ($(this).prop("checked") == true)
        {
            $(this).prev("input[type=hidden]").val('Active');
        }
        else if ($(this).prop("checked") == false)
        {
            $(this).prev("input[type=hidden]").val('InActive');
        }
    });
/*Change Status To Active/In Active On onChange Status Radio Button*/
    

/*Search Document Template*/
    $(document).on("keyup",'#search_template', function() 
    {
        var text = $(this).val().toLowerCase();

        $(".add_document_template_component").filter(function() 
        {
            var match = ($(this).text().toLowerCase().indexOf(text) > -1);
            $(this).toggle(match);
        });
    });
/*Change Status To Active/In Active On onChange Status Radio Button*/
        
        
/*Remove Document Template*/
    $(document).on('click',".remove_document_template",function(){
       $(this).parent().parent().parent().parent().remove(); 
    });
/*Remove Document Template*/
    
   
});

</script>
@endsection
