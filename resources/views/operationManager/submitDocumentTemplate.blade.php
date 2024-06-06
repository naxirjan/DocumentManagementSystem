@extends( 'layouts.master' )

@section('page_sepecific_plugin')
@endsection


@section('navbar-section')
@include('operationManager.includes.navBar')
@endsection

@section('sidebar-section')
@include('operationManager.includes.sideBar')
@endsection

@section('breadcrumb-section')
@section('breadcrumb-content')
<li>
    <i class="ace-icon fa fa-home home-icon"></i>
    <a href="#">Home</a>
</li>
<li class="active">Submit Project Instance Document Templates</li>
@endsection
@include('operationManager.includes.breadCrumb')
@endsection

@section('settingbox-section')
@include('operationManager.includes.settingBox')
@endsection

@section('pageheader-section')
<h1>
    Submit Project Instance Document Templates
</h1>
@endsection


@section('page-content')
<!--Project Instance Details Data-->
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
<div class="row">
    <div class="col-md-12">
        <?php
            extract($project_instance_detail[0]);
        ?>
        <div class="form-horizontal">

            <!--Project-->
            <div class="form-group">
                <label class="col-sm-3"><b>Project:</b></label>
                <div class="col-sm-6">
                    <label class="">
                    	{{$project['proj_name']}}
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
            <!--Status Label-->
            <div class="form-group">
                <label class="col-sm-3 "><b>Document Template:</b></label>
                <div class="col-sm-6">
                    <select class="form-control" id="select_box_project_instance_document_template">
                        <option value="" project_instance_document_template_id="">--Select Temaplate--</option>
                        @foreach($project_instance_document_templates as $document_template)
                            
                              <option value="{{$document_template->project_instance_document_template_id}}" project_instance_document_template_id="{{$document_template->project_instance_document_template_id}}">
                                  {{$document_template->document_template_title}} 
                              </option>
                        @endforeach
                    </select> 
                </div>
                
            </div>
            <!--Status-->
        </div>
    </div>
</div>

 @if(count($project_instance_document_templates)>0)
<!--Document Templates And Project Instance Countries-->
{!!Form::open(array("url"=>"/operationManager/submitDocumentTemplateProcess","method"=>"post","role"=>"form", "name"=>"submitDocumentTemplateProcess", "id"=>"submitDocumentTemplateProcess", "enctype"=>"multipart/form-data"))!!}
<div class="row">
    <div class="col-sm-3"></div>
    <div class="col-sm-6 set_project_instance_document_status text-center" style="padding-top:5px"></div>
    <div class="col-sm-3"></div>
</div>
<div class="row">
    <input type="hidden" name="project_instance_document_template_id" id="project_instance_document_template_id">
    <div class="col-md-12" id="get_project_instance_document_template">
        
    </div>
</div>
{!!Form::close()!!}
@else
    <div class="col-md-3">
        <h3><b>Not Found</b></h3>
    </div>
     <div class="col-md-8">
         <h3 class="label label-danger arrowed-in arrowed-in-right">No Document Template Was Found For This Project Instance !...</h3>
        <br/><br/> 
         <a href="/operationManager/viewProjectInstances" class="btn btn-secondary"><i class="fa fa-arrow"></i> Back</a>
    </div>
@endif

<div id="modal-delete-project_instance_submission_attachment" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header no-padding">
                <div class="table-header" style="background-color:green">
                    <span class="blink_me"><i class="menu-icon fa fa-question-circle bigger-130"></i> <b>Confimation !...</b></span>
                </div>
            </div>

            <div class="modal-body no-padding">
                <h4>&nbsp;Do You Want To Delete "<span id="modal-msg" class="blue"></span>" Attachment File ?</h4>
            </div>

            <div class="modal-footer no-margin-top">
                <button class="btn btn-sm btn-success" id="btn-delete-project_instance_submission_attachment">
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
@include('operationManager.includes.footer')
@endsection

@section('page_related_scripts')
<script>
$(document).ready(function()
{
   
   /*Document Templates Dropdown onChange*/
    $("#select_box_project_instance_document_template").change(function()
    {
        $( "#get_project_instance_document_template" ).html('');
        $(".set_project_instance_document_status").html('');
        
        var project_instance_document_template_id = $(this).val();
        
        $("#project_instance_document_template_id").val(project_instance_document_template_id);
            /*jQuery Ajax*/
            $.ajax({
                url:'/operationManager/loadProjectInstanceDocumentTemplateSectionQuestions/'+project_instance_document_template_id,
                type:"POST",
                data:{
                    _token:'{{csrf_token()}}',
                    },
                success:function(data)
                {
                    $("#get_project_instance_document_template" ).html(data);
                    $(".set_project_instance_document_status").removeClass('hidden').html($(".get_project_instance_document_status").html()+"<hr /><br />"); 
                    
                }             
            });
            /*jQuery Ajax*/                                                          
    });
    /*Document Templates Dropdown onChange*/
    
    /*Set Meta Value In Meta Key Side on onChange()*/    
    $(document).on('change','.tab-content .tab-pane.fade.active.in input[type=checkbox]',function(e)
    {
        e.preventDefault();

        if ($(this).prop("checked") == true)
        {
            $(this).next().next().val($(this).val());
        }
        else if ($(this).prop("checked") == false)
        {
            $(this).next().next().val('');
        }
    });
    /*Set Meta Value In Meta Key Side on onChange()*/

    
    /*Add File Attachment Control*/
        $(document).on('click','.add-file-attachment-control',function(e)
        {
            e.preventDefault();
            
            let project_instance_submission_id = $("#hidden_project_instance_submission_id").val();
            
            loadFileAttachmentControlContent();
        });
    
    /*Add File Attachment Control*/
    
    /*Remove File Attachment Control*/
        $(document).on('click','.remove-file-attachment-control',function(e)
        {
            e.preventDefault();
            
            let total_controls_length =  $("#file_attachment_controls > div").length;
            let project_instance_submission_id = $("#hidden_project_instance_submission_id").val();
            
            $(this).parent().parent().parent().parent().parent().remove();
            
            if(total_controls_length==1)
            {
                loadFileAttachmentControlContent();
            }
            
        });
    /*Remove File Attachment Control*/
    
    /*Remove Old File Attachment Control*/
        $(document).on('click','.delete-old-file-attachment',function(e)
        {
            e.preventDefault();
            
            $("#btn-delete-project_instance_submission_attachment").attr("project_instance_submission_attachment_id",$(this).attr("project_instance_submission_attachment_id"));
            
            $("#btn-delete-project_instance_submission_attachment").attr("project_instance_submission_id",$(this).attr("project_instance_submission_id"));
            
            $("#btn-delete-project_instance_submission_attachment").attr("file_path",$(this).attr("file_path"));

            $("#btn-delete-project_instance_submission_attachment").attr("project_instance_submission_status",$(this).attr("project_instance_submission_status"));

            
            $("#modal-msg").html($(this).attr("file_path"));            
            $("#modal-delete-project_instance_submission_attachment").modal('show');
            
        });
    /*Remove Old File Attachment Control*/
    
    
     /*Dialog Box Delete Old File Attachment*/
        $("#btn-delete-project_instance_submission_attachment").click(function(e)
        {
            e.preventDefault();
            /*jQuery Ajax*/
            $.ajax({
                url:'/operationManager/deleteProjectInstanceSubmissionAttachment',
                type:"POST",
                data:{
                    _token:'{{csrf_token()}}',
                    project_instance_submission_attachment_id:$(this).attr("project_instance_submission_attachment_id"),
                    project_instance_submission_id:$(this).attr("project_instance_submission_id"),
                    file_path:$(this).attr("file_path"),
                    project_instance_submission_status:$(this).attr("project_instance_submission_status"),
                    
                    },
                success:function(data)
                {
                    $("#modal-delete-project_instance_submission_attachment").modal('hide');
                    $("#file_attachment_controls").html(data);
                }             
            });
            /*jQuery Ajax*/ 
        
        });
    
    /*Dialog Box Delete Old File Attachment Control*/

    function loadFileAttachmentControlContent()
    {
        $.get("/operationManager/loadFileAttachmentControlContent", function( content ) 
        {
            $("#file_attachment_controls").append(content);

        });
    }    
    
});
</script>
@endsection
