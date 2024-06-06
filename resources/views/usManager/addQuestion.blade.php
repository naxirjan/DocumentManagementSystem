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
	<li class="active">Add Question</li>
	@endsection
	@include('usManager.includes.breadCrumb')
@endsection

@section('settingbox-section')
	@include('usManager.includes.settingBox')
@endsection

@section('pageheader-section')
<h1> 
	Add Question
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
        
        <!--HTML Form-->
        {!!Form::open(array("url"=>"/usManager/addOrUpdateQuestionProcess","method"=>"post","class"=>"form-horizontal","role"=>"form", "name"=>"addQuestionForm", "id"=>"addQuestionForm", "enctype"=>"multipart/form-data"))!!}

        
        <!--Question Description-->    
        <div class="form-group">
            <label class="col-sm-3" for="question"><b>Question Description <span class="red">(*)</span> :</b> </label>
            <div class="col-sm-9">
                {!! Form::textarea("question", null, array("placeholder"=>"Enter Question Description", 'id'=>'address', "class"=>"form-control", "rows"=>"3")) !!}
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
                {!! Form::select('question_type_id',$question_types,old('question_type_id'),['id'=>"question_type_id","class"=>"form-control"]) !!}
                @if($errors->has("question_type_id"))
                <span class="badge badge-danger">
                    {{$errors->first("question_type_id")}}
                </span>
                @endif
            </div>
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
        
        <!--Is Sum & Is Average-->             
        <div class="form-group">
        	<label class="col-sm-3 "></label>
            <!--Is Sum-->
            <div class="col-sm-2 is_sum hidden"> 
            	<div class="checkbox">
					<label>
						{!! Form::checkbox("is_sum", null, false, array("class"=>"ace")) !!}
						<span class="lbl"> Is Sum </span>
					</label>
				</div>
	       </div>
            <!--Is Average-->
            <div class="col-sm-2 is_average hidden"> 
            	<div class="checkbox">
					<label>
						{!! Form::checkbox("is_average", null, false, array("class"=>"ace")) !!}
						<span class="lbl"> Is Average</span>
					</label>
				</div>
	       </div>
        </div>
        
        <br />
        <!--Load Question Meta Control-->
        <div id="load_question_meta_section"></div>
        
        <div class="form-group">
            <label class="col-sm-4"></label>
            <div class="col-sm-8">
                <!--Hidden Field-->
                {{ Form::hidden('action', 'add') }}
        
                
                {!! Form::submit("Save", array("class"=>"btn btn-success")) !!}
                {!! Form::reset("Cancel", array("class"=>"btn btn-secondary")) !!}

            </div>
        </div>
        
        {!!Form::close()!!} 
         
    </div>
    <div class="col-md-2"></div>

</div>
@endsection


@section('footer-section')
	@include('usManager.includes.footer')
@endsection

@section('page_related_scripts')
<script type="text/javascript">
    
    
	$(document).ready(function(){
    var index=0;
    var length=0;    

    /*Add More Meta Control*/    
    $(document).on("click",".add_meta_control",function(){

        index = (parseInt($(this).attr('index')) + 1);

            $("#questions_meta_data").append('<div class="form-group meta_controls" id="meta_controls_'+index+'"><label class="col-sm-3 "></label><div class="col-sm-7"><input type="text" class="form-control" name="question_meta_value[]" id="question_meta_value_'+index+'" index="'+index+'"/><input type="hidden" class="form-control" name="question_meta_key[]" id="question_meta_key_'+index+'" index="'+index+'" /></div><div class="col-sm-2"><button class="btn btn-white btn-xs remove_meta_control" type="button"  index="'+index+'"><i class="ace-icon fa fa-remove bigger-200 red"></i></button><button class="btn btn-white btn-xs add_meta_control" type="button"  index="'+index+'"><i class="ace-icon fa fa-plus bigger-200 green"></i></button></div></div>');

            if(index==1)
            {
                $(this).before('<button class="btn btn-white btn-xs remove_meta_control" type="button" index="'+(index-1)+'"><i class="ace-icon fa fa-remove bigger-200 red"></i></button>');
            }
              
            $(this).remove();             
      });
    
        
    /*Remove Meta Control*/    
    $(document).on("click",".remove_meta_control",function()
    {   
        $("#meta_controls_"+$(this).attr('index')).remove();
        
        length = $(".meta_controls").length;
        if(length==0)
        {
            $( "#load_question_meta_section" ).load( "/usManager/loadQuestionMetaControlContent" );
        }
        else
        {
                $(".meta_controls .add_meta_control").last().remove();
                $(".meta_controls .remove_meta_control").last().after('<button class="btn btn-white btn-xs add_meta_control" type="button"  index="'+($(this).attr('index')-1)+'"><i class="ace-icon fa fa-plus bigger-200 green"></i></button>');
        }

      });
        
    /*Question Type onChange()*/
    $("#question_type_id").change(function(){
       
        if($(this).val()==1)
        {
            $( "#load_question_meta_section" ).html('');
            $(".is_sum").removeClass('hidden');
            $(".is_average").removeClass('hidden');
        }
        else
        {            
            $( "#load_question_meta_section" ).load( "/usManager/loadQuestionMetaControlContent" );
            $(".is_sum").addClass('hidden');
            $(".is_average").addClass('hidden');
        }
        
    });  
        
    /*Set Meta Value In Meta Key Side on onChange()*/    
    $(document).on('change','input[type=text]',function(e){
        e.preventDefault();

        var textLowerCase = $(this).val().toLowerCase();
        var textReplaced  = textLowerCase.replace(" ", "_");
        $("#question_meta_key_"+$(this).attr('index')).val(textReplaced);
        $("#question_meta_key_display_"+$(this).attr('index')).html(textReplaced);
    });

        
});
    
</script>
@endsection
