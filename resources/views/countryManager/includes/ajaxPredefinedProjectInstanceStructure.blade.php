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
        <div><label class="col-sm-3"><h4><b>Project Instance Assigned To</b></h4></label></div>
            <div class="col-sm-6 text-center">
                <label></label>
            </div>
    </div>
    <div id="countries_controls">           
    {{--dd(count($countryDistrict))--}}
    <?php $index = 0; ?>
    @if(count($countryDistrict) > 0) 
        @foreach($countryDistrict as $data)
        {{--dd($data->district_operation_id)--}}
        <?php $selected = '';?>
        <div class="form-group countries" id="countries_{{$index}}" index="{{$index}}">
            <label class="col-sm-1 control-label no-padding-right"><b>Location</b></label>
            <div class="col-sm-6">
                <select class="form-control districts" name="district_operation_id[]">
                    <option value="">-- Select Locations --</option>
                    <?php
                    foreach($dop as $district)
                    {
                        if($data->district_operation_id == $district['district_operation_id']){
                            $selected = 'selected';
                           ?>
                           <option {{$selected}} value="{{$district['district_operation_id']}}">{{$district['district_operation_full_name']}}</option>
                           <?php 
                        }else{
                            ?>
                            <option  value="{{$district['district_operation_id']}}">{{$district['district_operation_full_name']}}</option>
                        <?php
                            }
                        ?>
                            
                        <?php
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
                        {!! Form::checkbox("district_status[]", null, $status, array("class"=>"ace ace-switch ace-switch-6")) !!}
                        <span class="lbl"></span>
                    </label>
                </div>
            </div>
            <div class="col-sm-2">
                @if ($loop->last)
                <button class="btn btn-white btn-xs add_country_control" type="button"  index="{{$index}}">
                    <i class="ace-icon fa fa-plus bigger-200 green"></i>
                </button>
                @else
                <!-- <button  class="btn btn-white btn-xs remove_country_control" type="button" index="0"><i class="ace-icon fa fa-remove bigger-200 red"></i></button> -->
                @endif
            </div>
        </div>
        @php $index++; @endphp
        @endforeach
    @else
        <div class="form-group countries" id="countries_{{$index}}" index="{{$index}}">
            <label class="col-sm-1 control-label no-padding-right"><b>Location</b></label>
            <div class="col-sm-6">
                <select class="form-control districts" name="district_operation_id[]">
                    <option value="">-- Select Locations --</option>
                    <?php
                    foreach($dop as $district)
                    {
                    ?>  
                        <option  value="{{$district['district_operation_id']}}">{{$district['district_operation_full_name']}}</option>
                    <?php
                    }
                    ?>
                </select>
                
            </div>
            <label class="col-sm-1 control-label no-padding-right"><b>Status</b></label>
            <div class="col-sm-1">
                <div class="checkbox">  
                    <label style="padding-left:10px">
                        {!! Form::checkbox("district_status[]", null, true, array("class"=>"ace ace-switch ace-switch-6")) !!}
                        <span class="lbl"></span>
                    </label>
                </div>
            </div>
            <div class="col-sm-2">
                <button class="btn btn-white btn-xs add_country_control" type="button"  index="{{$index}}">
                    <i class="ace-icon fa fa-plus bigger-200 green"></i>
                </button>
            </div>
        </div>
        @php $index++; @endphp
    @endif
    </div>