<label class="col-sm-2 control-label no-padding-right blue"><b>Location</b></label>
<div class="col-sm-3">
    <select class="form-control" name="district_operation_id[<?php echo $country_id;?>][]">
        <?php
            if(count($district_operations)>0)
            {
                foreach($district_operations as $district_operation)
                {
                    ?>
                    <option value="{{$district_operation->district_operation_id}}">{{$district_operation->district_operation_full_name}} ({{$district_operation->district_operation_short_name}})</option>
                    <?php
                }
            }
            else
            {
                ?>
                    <option value="">No Location Found !...</option>
                <?php
            }
        ?>
    </select>        
</div>