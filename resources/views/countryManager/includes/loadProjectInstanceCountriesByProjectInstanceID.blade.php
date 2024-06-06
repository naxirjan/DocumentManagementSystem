<?php
    if(isset($project_instance_countries) && count($project_instance_countries) > 0)
    {
        foreach($project_instance_countries as $country)
        {
        ?>
            <div class="form-group" id="old_project_instance_country_control_{{$country['project_instance_assigned_id']}}">
                <label class="col-sm-1 control-label no-padding-right"><b>Country</b></label>
                <div class="col-sm-6">
                    <select class="form-control" name="country_id[]" id="old_project_instance_country_{{$country['project_instance_assigned_id']}}">
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
    <div class="col-sm-6">
        <select class="form-control" name="country_id[]">
            <option>-- Select Country --</option>
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
    <div class="col-sm-2">
       <button class="btn btn-white btn-xs add_project_instance_country_control" type="button"  index="0">
            <i class="ace-icon fa fa-plus bigger-200 green"></i>
        </button>
    </div>
</div>