<div class="form-group countries" id="countries_0" index="0">
    <label class="col-sm-1 control-label no-padding-right"><b>Location</b></label>
    <div class="col-sm-6">
        <select class="form-control districts" name="district_operation_id[]">
            <option value="">-- Select Locations --</option>
            <?php
            foreach($dop as $district)
            {
                ?>
                    <option value="{{$district['district_operation_id']}}">{{$district['district_operation_full_name']}}</option>
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
       <button class="btn btn-white btn-xs add_country_control" type="button"  index="0">
            <i class="ace-icon fa fa-plus bigger-200 green"></i>
        </button>
    </div>
</div>

