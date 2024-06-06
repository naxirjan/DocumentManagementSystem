<div class="form-group countries" id="countries_0" index="0">
    <label class="col-sm-1 control-label no-padding-right"><b>Country</b></label>
    <div class="col-sm-3">
        <select  class="form-control country_control" index="0" name="country_id[]">
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
                {!! Form::checkbox("country_status[]", null, true, array("class"=>"ace ace-switch ace-switch-6")) !!}
                <span class="lbl"></span>
            </label>
        </div>
    </div>
    <div class="col-sm-1">
       <button class="btn btn-white btn-xs add_country_control" type="button"  index="0">
            <i class="ace-icon fa fa-plus bigger-200 green"></i>
        </button>
    </div>
</div>

