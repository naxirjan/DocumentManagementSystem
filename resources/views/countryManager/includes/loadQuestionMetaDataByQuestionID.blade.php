<div class="form-group">
    <div><label class="col-sm-3" for="district_operation"><b>Items <span class="red">(*)</span> :</b></label></div>
    <div class="col-sm-6 text-center">
        <label>Value/Label</label>
    </div>
</div>
<div id="questions_meta_data">
 <?php
    if(count($question_meta)>0)
    {    
        foreach($question_meta as $key => $meta)
        {
            ?>
                <div class="form-group meta_controls" id="meta_controls_old_{{$meta['question_meta_id']}}">
                    <label class="col-sm-3 control-label no-padding-right"></label>
                    <div class="col-sm-7">
                        <input type="text" class=" form-control" name="question_meta_old_value[]"  value="{{$meta['value']}}" id="question_meta_old_value_{{$meta['question_meta_id']}}" index="{{$meta['question_meta_id']}}" old_meta='yes'/>

                        <input type="hidden" class="form-control" name="question_meta_old_key[]"  value="{{$meta['key']}}" id="question_meta_old_key_{{$meta['question_meta_id']}}" index="{{$meta['question_meta_id']}}" old_meta='yes'/>
                        
                        <input type="hidden" name="question_meta_old_id[]"  value="{{$meta['question_meta_id']}}" />
                        
                    </div>
                    <div class="col-sm-2">
                        <button class="btn btn-white btn-xs remove_meta_control_old" type="button"  index="{{$meta['question_meta_id']}}">
                            <i class="ace-icon fa fa-remove bigger-200 red"></i>
                        </button>
                    </div>
                </div>
            <?php
        }
        ?>
            <div class="form-group meta_controls" id="meta_controls_new_0">
            <label class="col-sm-3"></label>
            <div class="col-sm-7">
                <input type="text" class="form-control" name="question_meta_new_value[]" id="question_meta_old_value_0" index="0" old_meta='no'/>
                <input type="hidden" class="form-control" name="question_meta_new_key[]" id="question_meta_old_key_0" index="0" value="0" old_meta='no'/>                
            </div>

            <div class="col-sm-2">
                <button class="btn btn-white btn-xs add_meta_control_new" type="button"  index="0">
                    <i class="ace-icon fa fa-plus bigger-200 green"></i>
                </button>
            </div>
        </div>
        <?php
    }
    else
    {
    ?>
    <div class="form-group meta_controls" id="meta_controls_new_0">
        <label class="col-sm-3 control-label no-padding-right"></label>
        <div class="col-sm-7">
            <input type="text" class=" form-control" name="question_meta_new_value[]" id="question_meta_new_value_0" index="0" old_meta='no'/>
            <input type="hidden" class="form-control" name="question_meta_new_key[]" id="question_meta_new_key_0" index="0" value="0" old_meta='no'/>
        </div>

        <div class="col-sm-2">
            <button class="btn btn-white btn-xs add_meta_control_new" type="button" index="0">
                <i class="ace-icon fa fa-plus bigger-200 green"></i>
            </button>
        </div>
    </div>
    <?php
    }
?>
</div>    