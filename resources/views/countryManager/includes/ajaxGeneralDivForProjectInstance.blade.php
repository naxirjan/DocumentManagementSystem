@if($flag == 'Yes')
	<!--Project-->
    <div class="form-group">
            <label class="col-sm-3 "><b>Project <span class="red">(*)</span> :</b></label>
            <div class="col-sm-6">
                
                <select name="project_id" class="form-control chosen-select" id="project">
                    <option value="">--Select Project--</option>
                    @foreach($projects as $project)
                        <option  value="{{$project['proj_id']}}">{{$project['proj_name']}}</option>
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
                {!! Form::textarea("project_instance_description", null, array("placeholder"=>"Enter Question Description", 'id'=>'address', "class"=>"form-control", "rows"=>"3")) !!}
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
                <div class="space-2"></div>
                <div id="project-end-date-error" style="display:none">
                <span class="col-md-6 col-sm-6 label label-xs label-danger arrowed arrowed-right"> The Project End Date Field Is Required</span>
                </div>
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
            <div class="col-sm-6 text-center">
                <label></label>
            </div>
    </div>
    <div id="countries_controls">           
    </div>
@else
<!--Predefined Project Instance-->
        <div class="form-group">
            <label class="col-sm-3 "><b>Predefined Project Instances<span class="red">(*)</span> :</b></label>
            <div class="col-sm-6">
                <select name="predefined_project_intance" class="form-control chosen-select" id="predefined_project_intance_id">
                    <option value="">--Select Predefined Project Instances--</option>
                    @foreach($predefinedProjectInstances['project_intance'] as $project)
                    <option value="{{$project['project_instance_assigned_id']}}">
                        {{$project['project_instance_title']}}
                    </option>
                    @endforeach
                </select>
                @if($errors->has("project_id"))
                <span class="badge badge-danger">
                    {{$errors->first("project_id")}}
                </span>
                @endif
            </div>
        </div>
        <!-- Ajax Response -->
        <div id="predefined_project_intance">
            <!-- Ajax Response -->
        </div>
@endif