<?php
    extract($document_templates[0]);
?>
<div style="padding:10px;border-radius:10px;border:1px solid #c7c3c3;" class="document_template_already_exists" document_template_id="{{$document_template_id}}">
    <!--Project-->
    <div class="form-group">
        <label class="col-sm-3 "><b>Template:</b></label>
        <div class="col-sm-9">
            <span class="pull-right">
                <button type="button" class="btn btn-xs btn-danger remove_document_template" data-toggle="button">X</button>
            </span>
            <label>{{$document_template_title}}</label>

            {{ Form::hidden("templates[$project_instance_assigned_id][$document_template_id][document_template_id]", $document_template_id) }}


            {{ Form::hidden("templates[$project_instance_assigned_id][$document_template_id][project_instance_assigned_id]", $project_instance_assigned_id) }}

        </div>
    </div>
    <!--Start Date-->
    <div class="form-group">
        <label class="col-sm-3 "><b>Submission Start:</b> </label>
        <div class="col-sm-9">
            <div class="input-group">
                <!--            <input type="text" id="project_instance_submission_start_date" name="" class="form-control monthpicker" placeholder="Select Start From Month" data-date-format="MM, yyyy" />-->

                {!! Form::text("templates[$project_instance_assigned_id][$document_template_id][project_instance_submission_start_date]", NULL, array("placeholder"=>"Select Submission Start", "class"=>"form-control date-picker","data-date-format"=>"dd MM, yyyy")) !!}


                <span class="input-group-addon">
                    <i class="fa fa-calendar bigger-110"></i>
                </span>
            </div>
        </div>
    </div>
    <!--End Date-->
    <div class="form-group">
        <label class="col-sm-3 "><b>Submission End:</b> </label>
        <div class="col-sm-9">
            <div class="input-group">
                {!! Form::text("templates[$project_instance_assigned_id][$document_template_id][project_instance_submission_stop_date]", NULL, array("placeholder"=>"Select Submission End", "class"=>"form-control date-picker","data-date-format"=>"dd MM, yyyy")) !!}
                <span class="input-group-addon">
                    <i class="fa fa-calendar bigger-110"></i>
                </span>
            </div>
        </div>
    </div>
    <!--Status Label-->
    <div class="form-group">
        <label class="col-sm-3 "><b>Status:</b></label>
        <div class="col-sm-9">
            <div class="checkbox">
                <label style="padding-left:10px">
                    <input type="hidden" name="templates[<?php echo $project_instance_assigned_id;?>][<?php echo $document_template_id;?>][status]" value="Active">
                    {!! Form::checkbox("status", null, true, array("class"=>"ace ace-switch ace-switch-6 template_status")) !!}

                    <span class="lbl"></span>
                </label>
            </div>
            <p class="pull-right"><u><b><a href="">Submissions: 0</a></b></u></p>
        </div>
    </div>
    
</div>
<br />
