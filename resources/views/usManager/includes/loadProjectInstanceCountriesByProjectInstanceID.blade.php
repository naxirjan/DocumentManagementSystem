<?php
    if(isset($project_instance_countries) && count($project_instance_countries) > 0)
    {
        foreach($project_instance_countries as $country)
        {
        ?>
            <div class="form-group" id="old_project_instance_country_control_{{$country['project_instance_assigned_id']}}">
                <label class="col-sm-1 control-label no-padding-right"><b>Country</b></label>
                <div class="col-sm-3">
                    <select class="form-control country_control" index="{{$country['country_id']}}" name="country_id[]" id="old_project_instance_country_{{$country['project_instance_assigned_id']}}">
                    <?php
                        foreach($countries as $count)
                        {
                            if($count->cont_id==$country['country_id'])
                            {
                            ?>
                                <option value="{{$count->cont_id}}" selected>{{$count->cont_name}}</option>
                            <?php
                            }
                            ?>
                                <option value="{{$count->cont_id}}">{{$count->cont_name}}</option>
                            <?php
                        }
                        ?>    
                        
                        
                        </select>
                </div>
                <div class="country_district_operations_{{$country['country_id']}}">
                    @if(isset($country['district_operation_id']))
                    <label class="col-sm-2 control-label no-padding-right blue"><b>Location</b></label>
                    <div class="col-sm-3">
                        <select class="form-control" name="district_operation_id[<?php echo $country['country_id'];?>][]">
                        <?php
                            $district_operations = getAllDistrictOperationsByCountryId($country['country_id']);
                            foreach($district_operations as $district_operation)
                            {
                                ?>
                                <option <?php if($district_operation->district_operation_id == $country['district_operation_id']){echo "selected";} ?> value="{{$district_operation->district_operation_id}}">{{$district_operation->district_operation_full_name}} ({{$district_operation->district_operation_short_name}})</option>
                                <?php
                            }
                            ?>
                        </select>        
                    </div>
                    @else
                    <label class="col-sm-5 control-label no-padding-right"></label>
                  @endif
                </div>
                <label class="col-sm-1 control-label no-padding-right"><b>Status</b></label>
                 <div class="col-sm-1">
                    <div class="checkbox">	
                        <label style="padding-left:10px">
                            <?php
                                $status = ($country['status'] == "Active" ? true:false);
                            ?>
                            <input type="hidden" name="project_instance_country_status[]" value="{{$country['status']}}"/>
                            {!! Form::checkbox("country_status[]", null, $status, array("class"=>"ace ace-switch ace-switch-6 project_instance_country_status")) !!}
                            <span class="lbl"></span>
                        </label>
                    </div>
                </div>
            </div>
        <?php
        }
    }                
    ?>
    <div class="form-group countries" id="countries_0" index="0">
    <label class="col-sm-1 control-label no-padding-right"><b>Country</b></label>
    <div class="col-sm-3">
        <select class="form-control country_control" index="0" name="country_id[]">
            <option value="">-- Select Country --</option>
            <?php
            foreach($countries as $country)
            {
                ?>
                    <option value="{{$country->cont_id}}">{{$country->cont_name}}</option>
                <?php
            }
            ?>
        </select>
               
    </div>
        <div class="country_district_operations_0"><label class="col-sm-5 control-label no-padding-right"></label></div>
    <label class="col-sm-1 control-label no-padding-right"><b>Status</b></label>
     <div class="col-sm-1">
        <div class="checkbox">	
            <label style="padding-left:10px">
                <input type="hidden" name="project_instance_country_status[]" value="Active"/>
                {!! Form::checkbox("country_status[]", null, true, array("class"=>"ace ace-switch ace-switch-6 project_instance_country_status")) !!}
                <span class="lbl"></span>
            </label>
        </div>
    </div>    
    <div class="col-sm-1">
       <button class="btn btn-white btn-xs add_project_instance_country_control" type="button"  index="0">
            <i class="ace-icon fa fa-plus bigger-200 green"></i>
        </button>
    </div>
</div>