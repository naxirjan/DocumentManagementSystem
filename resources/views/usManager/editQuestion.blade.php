@extends( 'layouts.master' )

@section('page_sepecific_plugin')
@endsection


@section('navbar-section')
	@include('usManager.includes.navBar')
@endsection

@section('sidebar-section')
	@include('usManager.includes.sideBar')
@endsection

@section('breadcrumb-section')
	@section('breadcrumb-content')
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="#">Home</a>
	</li>
	<li class="active">Edit Question</li>
	@endsection
	@include('usManager.includes.breadCrumb')
@endsection

@section('settingbox-section')
	@include('usManager.includes.settingBox')
@endsection

@section('pageheader-section')
<h1> 
	Edit Question
	<small><i class="ace-icon fa fa-angle-double-right"></i> Required (*) Fields Must Be Filled</small>
</h1>
@endsection


@section('page-content')
<div class="row">
    <div class="col-md-12">
         <!--Success Message-->
        @if(session('msg_success'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert">
                    <i class="ace-icon fa fa-times"></i>
                </button>
                <strong>
                     {{session('msg_success')}}
                </strong>
            </div>
        @endif
        <!--Fail Message-->
        @if(session('msg_fail'))
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert">
                    <i class="ace-icon fa fa-times"></i>
                </button>
                <strong>
                     {{session('msg_fail')}}
                </strong>
            </div>
        @endif 
    </div>
</div>

<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
               
        
         {!!Form::open(array("url"=>"/usManager/addOrUpdateQuestionProcess","method"=>"post","class"=>"form-horizontal","role"=>"form", "name"=>"addQuestionForm", "id"=>"addQuestionForm", "enctype"=>"multipart/form-data"))!!}

        <!--Question Description-->    
        <div class="form-group">
            <label class="col-sm-3 " for="question"><b>Question Description <span class="red">(*)</span> :</b> </label>
            <div class="col-sm-9">
                {!! Form::textarea("question",$question[0]->question , array("placeholder"=>"Enter Question Description", 'id'=>'address', "class"=>"form-control","rows"=>5)) !!}
                  @if($errors->has("question"))
                <span class="badge badge-danger">
                    {{$errors->first("question")}}
                </span>
                @endif
			</div>
        </div>    
        <!--Question Type-->
        <div class="form-group">
            <label class="col-sm-3 "><b>Question Type <span class="red">(*)</span> :</b></label>
            <div class="col-sm-9">
                {!! Form::select('question_type_id',$question_types,$question[0]->question_type_id,['id'=>"question_type_id","class"=>"form-control"]) !!}
                @if($errors->has("question_type_id"))
                <span class="badge badge-danger">
                    {{$errors->first("question_type_id")}}
                </span>
                @endif
            </div>
        </div>
        <!--Status-->
         <div class="form-group">
            <label class="col-sm-3 "><b>Status <span class="red">(*)</span> :</b></label>
            <div class="col-sm-9">
                <div class="checkbox">	
                    <label style="padding-left:10px">
                        <?php $status = ($question[0]->status=='Active'?true:false)?>
                        {!! Form::checkbox("status",null, $status, array("class"=>"ace ace-switch ace-switch-6")) !!}
                        <span class="lbl"></span>
                    </label>
                </div>
            </div>
        </div>     
        <!--Status-->
        
        <!--Is Sum & Is Average & Active/InActive-->             
        <div class="form-group">
        	<label class="col-sm-3 "></label>
            <div class="col-sm-2 is_sum {{($question[0]->question_type_id==1?'':'hidden')}}"> 
            	<div class="checkbox">
					<label>
                        <?php $is_sum = ($question[0]->is_sum=='Yes'?true:false)?>
						{!! Form::checkbox("is_sum", null, $is_sum, array("class"=>"ace")) !!}
						<span class="lbl"> Is Sum </span>
					</label>
				</div>
	       </div>
            <div class="col-sm-2 is_average {{($question[0]->question_type_id==1?'':'hidden')}}"> 
            	<div class="checkbox">
					<label>
                        <?php $is_average = ($question[0]->is_average=='Yes'?true:false)?>
						{!! Form::checkbox("is_average", null, $is_average, array("class"=>"ace")) !!}
						<span class="lbl"> Is Average</span>
					</label>
				</div>
	       </div>
           
        </div>
        
        <br />
        <div id="load_question_meta_section" style="display:block;">
            <?php
            if($question[0]->question_type_id != 1)
            {?> 
                <div class="form-group">
                    <div><label class="col-sm-3" for="district_operation"><b>Items <span class="red">(*)</span> :</b></label></div>
                    <div class="col-sm-6 text-center">
                        <label>Value / Label</label>
                    </div>
                </div>
                <div id="questions_meta_data">
                 <?php
                
                    if(count($question_meta)>0)
                    {    
                        foreach($question_meta as $key => $meta)
                        {
                            ?>
                                <div class="form-group meta_controls_old" id="meta_controls_old_{{$meta->question_meta_id}}">
                                    <label class="col-sm-3 "></label>
                                    <div class="col-sm-7">
                                        <input type="text" class=" form-control" name="question_meta_old_value[]"  value="{{$meta->value}}" id="question_meta_old_value_{{$meta->question_meta_id}}" index="{{$meta->question_meta_id}}" old_meta='yes'/>

                                        <input type="hidden" class="form-control" name="question_meta_old_key[]"  value="{{$meta->key}}" id="question_meta_old_key_{{$meta->question_meta_id}}" index="{{$meta->question_meta_id}}" old_meta='yes'/>
                                        
                                        <input type="hidden" name="question_meta_old_id[]"  value="{{$meta->question_meta_id}}" />
                                        
                                    </div>
                                    <div class="col-sm-2">
                                        <button class="btn btn-white btn-xs remove_meta_control_old" type="button"  index="{{$meta->question_meta_id}}">
                                            <i class="ace-icon fa fa-remove bigger-200 red"></i>
                                        </button>
                                    </div>
                                </div>
                            <?php
                        }
                    }
                ?>
                    <div class="form-group meta_controls_new" id="meta_controls_new_0">
                        <label class="col-sm-3 "></label>
                        <div class="col-sm-7">
                            <input type="text" class=" form-control" name="question_meta_new_value[]" id="question_meta_new_value_0" index="0" old_meta='no'/>
                            <input type="hidden" class="form-control" name="question_meta_new_key[]" id="question_meta_new_key_0" index="0" value="0" old_meta='no'/>
                        </div>

                        <div class="col-sm-2">
                            <button class="btn btn-white btn-xs add_meta_control_new" type="button"  index="0">
                                <i class="ace-icon fa fa-plus bigger-200 green"></i>
                            </button>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>   
        </div>
        
        <div class="form-group">
            <label class="col-sm-5 " for="district_operation"></label>
            <div class="col-sm-7">
                <span class="hidden" id="meta_total_length">{{count($question_meta)}}</span>
                <span class='hidden' id="get_question_id">{{$question[0]->question_id}}</span>
                <!--Hidden Field-->
                {{ Form::hidden('action', 'update') }}
                {{ Form::hidden('question_id', $question[0]->question_id) }}
                {{ Form::hidden('old_question_type_id', $question[0]->question_type_id) }}
                                
                {!! Form::submit("Save", array("class"=>"btn btn-success")) !!}
                <a href="/usManager/viewQuestions" class="btn">Cancel</a>
                </div>
            </div>
            </div>
            <div class="form-group">
            <div class="col-sm-2"></div>    
            <div class="col-sm-8">
                 <div id="msg_remove_question_meta"></div>
                <div class="col-sm-2"></div>    
            </div>    
        </div>
        
      {!!Form::close()!!} 
    </div>
    <div class="col-md-2"></div>
    <div id="modal-delete-old-question-meta" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header no-padding green">
                    <div class="table-header">
                        Confirmation
                    </div>
                </div>

                <div class="modal-body no-padding">
                    <h4>&nbsp;Do You Want To Delete "<span id="modal-msg" class="blue"></span>" Question Item ?</h4>
                </div>

                <div class="modal-footer no-margin-top">
                    <button class="btn btn-sm btn-success" id="btn-delete-old-question-meta">
                        <i class="ace-icon fa fa-check"></i>
                        Yes
                    </button>
                    <button class="btn btn-sm btn-danger" data-dismiss="modal">
                        <i class="ace-icon fa fa-times"></i>
                        No
                    </button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
</div>

@endsection


@section('footer-section')
	@include('usManager.includes.footer')
@endsection

@section('page_related_scripts')
<script type="text/javascript">
$(document).ready(function()
{           
        var index=0;
        var length=0;
        var question_id = $("#get_question_id").html();

        /*Question Type onChange()*/
        $("#question_type_id").change(function(){

            if($(this).val()=='' || $(this).val()==null)
            {
                alert("Please Select Question Type !...");
                $( "#load_question_meta_section" ).html('');
            }
            else if($(this).val()==1)
            {
                $( "#load_question_meta_section" ).html('');
                $(".is_sum").removeClass('hidden');
                $(".is_average").removeClass('hidden');
            }
            else
            {            
               $( "#load_question_meta_section" ).load( "/usManager/loadQuestionMetaDataByQuestionID/"+question_id);
                $(".is_sum").addClass('hidden');
                $(".is_average").addClass('hidden');
            }

        });  

        /*Add Question Meta Value In Question Meta Key Control On onChange()*/    
        $(document).on('change','input[type=text]',function(e)
        {
            e.preventDefault();

            var is_old_meta_control = $(this).attr('old_meta');

            var textLowerCase = $(this).val().toLowerCase();
            var textReplaced  = textLowerCase.replace(" ", "_");

            if(is_old_meta_control=="yes")
            {
                $("#question_meta_old_key_"+$(this).attr('index')).val(textReplaced);
            }
            else
            {
                $("#question_meta_new_key_"+$(this).attr('index')).val(textReplaced);
            }

});
    
        /*Add More Question Meta Controls*/    
        $(document).on("click",".add_meta_control_new",function()
        {                
            index = (parseInt($(this).attr('index')) + 1);
            
            $("#questions_meta_data").append('<div class="form-group meta_controls_new" id="meta_controls_new_'+index+'"><label class="col-sm-3 "></label><div class="col-sm-7"><input type="text" class="form-control" name="question_meta_new_value[]" id="question_meta_new_value_'+index+'" index="'+index+'" old_meta="no"/><input type="hidden" class="form-control" name="question_meta_new_key[]" id="question_meta_new_key_'+index+'" index="'+index+'" old_meta="no"/></div><div class="col-sm-2"><button class="btn btn-white btn-xs remove_meta_control_new" type="button"  index="'+index+'"><i class="ace-icon fa fa-remove bigger-200 red"></i></button><button class="btn btn-white btn-xs add_meta_control_new" type="button"  index="'+index+'"><i class="ace-icon fa fa-plus bigger-200 green"></i></button></div></div>');

            if(index==1)
            {
                $(this).before('<button class="btn btn-white btn-xs remove_meta_control_new" type="button" index="'+(index-1)+'"><i class="ace-icon fa fa-remove bigger-200 red"></i></button>');
            }
              $(this).remove(); 

        });

        
        /*Remove New Meta Control*/    
        $(document).on("click",".remove_meta_control_new",function()
        {   
            $("#meta_controls_new_"+$(this).attr('index')).remove();
            
             length = $(".meta_controls_new").length;
            
            if(length==0)
            {
                $( "#load_question_meta_section" ).load( "/usManager/loadQuestionMetaDataByQuestionID/"+question_id);
            }
            else
            {
                $(".meta_controls_new .add_meta_control_new").last().remove();
                $(".meta_controls_new .remove_meta_control_new").last().after('<button class="btn btn-white btn-xs add_meta_control_new" type="button"  index="'+($(this).attr('index')-1)+'"><i class="ace-icon fa fa-plus bigger-200 green"></i></button>');
            }
                
        });
    
        /*Show Confirmation Dialog For Removing Old Question Meta Control*/    
        $(document).on("click",".remove_meta_control_old",function()
        {   
            var modal_msg= $("#question_meta_old_value_"+$(this).attr('index')).val();
            $("#btn-delete-old-question-meta").attr('question_meta_id',$(this).attr('index'));
            
            $("#modal-delete-old-question-meta").modal('show');
            $("#modal-msg").html(modal_msg);

        });
    
        /*Remove Old Question Meta Control*/    
        $("#btn-delete-old-question-meta").click(function()
        {   
            /*jQuery Ajax*/
                $.ajax({
                url:'/usManager/deleteQuestionMeta',
                type:"POST",
                data:{
                    _token:'{{csrf_token()}}',
                    question_meta_id:$(this).attr('question_meta_id'),
                    },
                success:function(message)
                {
                    if(message=='success')
                    {
                        $( "#load_question_meta_section" ).load( "/usManager/loadQuestionMetaDataByQuestionID/"+$("#get_question_id").html());
                        
                        $("#msg_remove_question_meta").html('<div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button></i>Question Item Deleted Successfully !...</div>');
                    }
                    else
                    {
                        $("#msg_remove_question_meta").html('<div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>>Some Error Occured, Please Try Again Later !...</div>');   
                    }
                    $("#modal-delete-old-question-meta").modal('hide');
                }             
                });
        });
        
});
    
</script>
@endsection
