<div> 
    
        @if(is_numeric($project_instance_submission_status) && $project_instance_submission_status==0)
            <label class="hidden get_project_instance_document_status">
                <span class="label label-lg label-warning arrowed-in arrowed-in-right">Saved</span>
            </label>
        @elseif($project_instance_submission_status==1)
            <label class="hidden get_project_instance_document_status">
                <span class="label label-lg label-info arrowed-in arrowed-in-right">Submitted</span>
            </label>
        @elseif($project_instance_submission_status==4)
           <label class="hidden get_project_instance_document_status">
                <span class="label label-lg label-success arrowed-in arrowed-in-right">Approved By Country Manager</span>
            </label>
        @elseif($project_instance_submission_status==5)
        <label class="hidden get_project_instance_document_status">
                <span class="label label-lg label-danger arrowed-in arrowed-in-right">UnApproved By Country Manager</span>
        </label>
        @endif

        @if($project_instance_submission_status==0 || $project_instance_submission_status==5)
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
                                            <ul class="list-unstyled spaced2 item-list" id="all-assigned-sections">
                                                <li id="add_all_question">
                                                    <span class="pull-right">
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
            <br />
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
{!! Form::hidden('project_instance_submission_id',$project_instance_submission_id)!!}                
{!! Form::saveButton() !!}    
{!! Form::submitButton() !!}
{!! Form::reset("Cancel",  array("class"=>"btn")) !!}    
</p>
        @endif
</div>    

