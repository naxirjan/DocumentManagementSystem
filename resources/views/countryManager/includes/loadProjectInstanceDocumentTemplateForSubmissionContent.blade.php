<div> 
        @if(is_numeric($project_instance_submission_status) && $project_instance_submission_status==0)
            <label class="hidden get_project_instance_document_status">
                <span class="label label-lg label-warning arrowed-in arrowed-in-right">Saved</span>
            </label>
        @elseif($project_instance_submission_status==1)
            <label class="hidden get_project_instance_document_status">
                <span class="label label-lg label-info arrowed-in arrowed-in-right">Submitted By</span>
            </label>
        @elseif($project_instance_submission_status==2)
           <label class="hidden get_project_instance_document_status">
                <span class="label label-lg label-success arrowed-in arrowed-in-right">Approved By US Manager</span>
            </label>
        @elseif($project_instance_submission_status==3)
        <label class="hidden get_project_instance_document_status">
                <span class="label label-lg label-danger arrowed-in arrowed-in-right">UnApproved By US Manager</span>
        </label>
        @endif

        @if($project_instance_submission_status==0 || $project_instance_submission_status==3)
    
        <div class="tabbable">
            <ul class="nav nav-tabs padding-16">
                <li class="active">
                    <a data-toggle="tab" href="#template-fieldset">
                        <i class="blue ace-icon fa fa-file-text bigger-125"></i>
                        Template
                    </a>
                </li>

            </ul>
            <div class="tab-content profile-edit-tab-content">
                <div id="template-fieldset" class="tab-pane in active">
                    <br />
                    <div class="tabbable">
                        <ul class="nav nav-tabs" id="myTab">
                            @php $active_section_tab = 0; @endphp

                            @foreach($sections_with_questions as $key1 => $sections)
                                @foreach($sections as $section => $questions)
                                  @php  $section_priority_and_title = explode("_",$section) @endphp

                                         <li class="{{($active_section_tab == 0)?'active':''}}">
                                    <a data-toggle="tab" href="#section{{$key1}}" aria-expanded="{{($active_section_tab == 0)?'true':'false'}}">
                                        {{$section_priority_and_title[1]}}
                                        <span class="badge badge-success">
                                            {{count($questions)}} 
                                        </span>
                                    </a>
                                </li>
                                @endforeach
                                @php $active_section_tab++; @endphp
                            @endforeach
                        </ul>  
                        <div class="tab-content" style="overflow-y:auto;height:500px">
                            @php $active_section_tab_pane = 0; @endphp

                            @foreach($sections_with_questions as $key1 => $sections)  
                                @php $total_questions = 1; @endphp
                                    @foreach($sections as $section => $questions)
                                        @php  $section_priority_and_title = explode("_",$section) @endphp
                                        <div id="section{{$key1}}" class="tab-pane fade {{($active_section_tab_pane == 0)?'active in':''}}">
                                            <div class="widget-body">
                                                <div class="widget-main padding-6" id="scroll_bar" style="overflow-y:auto;height:500px">
                                                    <ul class="list-unstyled spaced2 item-list">
                                                        <li id="add_all_question">
                                                            <span class="pull-right text-danger">
                                                            <label class="text-danger"><b>Priority: <span style="font-size:15px">{{$section_priority_and_title[0]}}</span></b></label>
                                                            </span>
                                                        </li>
                                                        <br/>
                                                        @foreach($questions as $question)

                                                           <li class="item-green clearfix">
                                                                <label style="cursor:pointer">
                                                                    <span class='lbl'><b>Question {{$total_questions++}}</b></span>
                                                                </label>
                                                                <p>{{($question['question'])}}</p>
                                                                <p>
                                                                    <?php 
                                                                        generateQuestionMetaControl($question);
                                                                    ?>
                                                               </p>
                                                            </li>

                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    @php $active_section_tab_pane++;@endphp
                            @endforeach
                        </div>
</div>
                </div>
            </div>
        </div>
        <div class="space"></div>
        <div class="tabbable">
            <ul class="nav nav-tabs padding-16">
                <li class="active">
                    <a data-toggle="tab" href="#file-attachment-fieldset">
                        <i class="blue ace-icon fa fa-file-image-o bigger-125"></i>
                        <i class="blue ace-icon fa fa-image bigger-125"></i>
                        File Attachments
                    </a>
                </li>

            </ul>
            <div class="tab-content profile-edit-tab-content">
                <div id="file-attachment-fieldset" class="tab-pane in active">
                    <div id="file_attachment_controls">
                        @if(!empty($attachments))
                            @foreach($attachments as $attachment)
                            <div class="row">
                            <br />
                            <div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="col-sm-3" style="padding:5px;" for="form-field-1"> File Type: </label>
                                        <div class="col-sm-9">
                                            <input type="hidden"  name="attachments[attachment_id][]" value="{{$attachment->project_instance_submission_attachment_id}}">
                                            <input type="hidden"  name="attachments[old_file_path][]" value="{{$attachment->file_path}}">
                                            @php
                                                $file_types = getAllFileTypes();
                                            @endphp
                                            <select class="form-control" name="attachments[file_type_id][]">
                                                <option value="" >-- Select File Type --</option>
                                                @foreach($file_types as $file_type)
                                                    <option value="{{$file_type->file_type_id}}" <?php if($attachment->file_type_id==$file_type->file_type_id){echo "selected";}?> >{{$file_type->file_type}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-sm-3" style="padding:5px;" for="form-field-1"> Decription: </label>
                                        <div class="col-sm-9">
                                            <input type="text"  name="attachments[file_desctiption][]" placeholder="Description" class="form-control" value="{{$attachment->file_description}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 attachments-section">
                                    <div class="form-group">
                                        <label class="col-sm-2" style="padding:5px;" for="form-field-1"> File: </label>
                                        <div class="col-sm-8">
                                            <input type="file" class="form-control attachment-file" name="attachments[file_path][]">
                                            <small><a target="_blank" href="{{asset('storage/FileAttachments/'.$attachment->project_instance_submission_id.'/'.$attachment->file_path)}}">{{$attachment->file_path}}</a></small>
                                        </div>
                                        <label class="col-sm-1" style="padding:5px;">
                                        <i class="dark ace-icon fa fa-upload bigger-125"></i>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label class="col-sm-12" style="" for="form-field-1">
                                        <button project_instance_submission_attachment_id="{{$attachment->project_instance_submission_attachment_id}}" project_instance_submission_id="{{$attachment->project_instance_submission_id}}" file_path="{{$attachment->file_path}}" project_instance_submission_status="{{$project_instance_submission_status}}" class="btn btn-xs btn-danger <?php if($project_instance_submission_status==3){echo 'remove-file-attachment-control';}else{echo 'delete-old-file-attachment';}?>"><b>X</b></button>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                            @endforeach
                        @endif
                        <div class="row">
                            <br />
                            <div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="col-sm-3" style="padding:5px;" for="form-field-1"> File Type: </label>
                                        <div class="col-sm-9">
                                            <input type="hidden"  name="attachments[attachment_id][]" value="">
                                            <input type="hidden"  name="attachments[old_file_path][]" value="">
                                            @php
                                                $file_types = getAllFileTypes();
                                            @endphp
                                            <select class="form-control" name="attachments[file_type_id][]">
                                                <option value="" >-- Select File Type --</option>
                                                @foreach($file_types as $file_type)
                                                    <option value="{{$file_type->file_type_id}}">{{$file_type->file_type}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-sm-3" style="padding:5px;" for="form-field-1"> Decription: </label>
                                        <div class="col-sm-9">
                                            <input type="text"  name="attachments[file_desctiption][]" placeholder="Description" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-sm-2" style="padding:5px;" for="form-field-1"> File: </label>
                                        <div class="col-sm-8">
                                            <input type="file" class="form-control" name="attachments[file_path][]">
                                        </div>
                                        <label class="col-sm-1" style="padding:5px;">
                                        <i class="dark ace-icon fa fa-upload bigger-125"></i>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label class="col-sm-12" style="" for="form-field-1">
                                        <button class="btn btn-xs btn-danger remove-file-attachment-control"><b>X</b></button>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>    
                    <div class="space"></div>
                    <div class="text-center">
                        <button type="button" class="btn btn-info btn-sm add-file-attachment-control">Add More</button>
                    </div>
                </div>
            </div>
        </div>    
       <div class="space"></div>
        <p class="text-center">
            @php
                Form::macro('submitButton', function()
                {
                    return '<input type="submit" name="submit" value="Submit" class="btn btn-success">';
                });

                Form::macro('saveButton', function()
                {
                    return '<input type="submit" name="save" value="Save" class="btn btn-info">';
                });

            @endphp    
            {!! Form::hidden('project_instance_submission_status',$project_instance_submission_status)!!}
            {!! Form::hidden('project_instance_submission_id',$project_instance_submission_id,['id'=>'hidden_project_instance_submission_id'])!!}                
            {!! Form::saveButton() !!}    
            {!! Form::submitButton() !!}
            {!! Form::reset("Cancel",  array("class"=>"btn")) !!}    
        </p>
        @endif   
</div>    
