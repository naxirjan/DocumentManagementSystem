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
        <div class="col-md-4">
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