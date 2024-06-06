{{--dd($activeQuestions)--}}
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
		<a href="{{url('/usManager')}}">Home</a>
	</li>
	<li class="active">Manage Section</li>
	<li class="active">Add Section</li>
	@endsection
	@include('usManager.includes.breadCrumb')
@endsection

@section('settingbox-section')
	@include('usManager.includes.settingBox')
@endsection

@section('pageheader-section')
<h1>
	Add Section 
	<small>
		<i class="ace-icon fa fa-angle-double-right"></i>
			Required (*) Fields Must Be Filled
	</small>
</h1>
@endsection


@section('page-content')

<div class="row">
	<div class="col-sm-12">	
	{!! Form::open(array('url'=>'#', 'method'=>'POST' , 'class'=>'form-horizontal' , 'id'=>'section-form')) !!}
		<div>
			<label for="form-field-8"><b>Section Title <span class="text-danger">(*)</span></b></label>
			<input type="text" name="section_title" class="form-control" id="form-field-8" placeholder="Enter Section Title" />
			<div id="section-title-error" style="display: none">
				<div class="space-4"></div>
				<span  class="col-md-3 col-sm-3 col-xs-12 label label-xs label-danger arrowed arrowed-right"> The Section Field Is Required</span>
				<div class="space-10"></div>
			</div>	
		</div>
		<div class="space-10"></div>
		<div>
			<label for="form-field-8"><b>Section Description <span class="text-danger">(*)</span></b></label>
			<textarea name="section_description" class="form-control" id="form-field-8" placeholder="Enter Section Description"></textarea>
			<div id="section-description-error" style="display: none">
				<div class="space-4"></div>
				<span  class="col-md-3 col-sm-3 col-xs-12 label label-xs label-danger arrowed arrowed-right"> The Section Description Field Is Required</span>
				<div class="space-20"></div>
			</div>
		</div>
		<div class="space-16"></div>
		<div>
			<label for="form-field-8"><b>Section Status: </b></label>
			<input name='section_status' class='ace ace-switch ace-switch-6' type='checkbox' checked='checked' value='Active'>
			<span class='lbl middle'></span>
		</div>
		<div class="hr hr-24 dotted hr-double"></div>
		<div class="page-header">
			<h1>Assign Question In Section</h1>
		</div>
		<!-- Question Pool Div Start-->
		<div class="col-md-4 col-sm-4 col-xs-12">
			<div class="widget-box widget-color-blue light-border">
				<div class="widget-header">
					<h5 class="widget-title smaller">Questions Pool</h5>

					<div class="widget-toolbar">
						<h5 class="badge badge-danger" id="question_pool_count"><?php echo count($activeQuestions); ?></h5>
					</div>
				</div>

				<div class="widget-body">
					@if($activeQuestions)
					<input type="text" id="search_questions" class="form-control search-query" placeholder="Search Questions">
					@endif
					<div class="widget-main padding-6" id="scroll_bar" style="overflow-y:auto;height:410px">
						<ul class="list-unstyled spaced2 item-list" id="all-questions">
							@if($activeQuestions)
							<li id="add_all">
								<label><input class='ace ace-checkbox-2' question_id='0' type="checkbox" name='checkbox-add-all'  id="checkbox-add-all" />
		                        <span class='lbl'>&nbsp;<b>Select All</b></span></label>
							</li>
							@endif
							
							@forelse($activeQuestions as $question)
							<li class="item-orange clearfix" id='questions-row<?php echo $question['question_id'];?>' data-order='<?php echo $question['question_id'];?>'>
		                        <label style="cursor: pointer" class="checkbox-add-individual" question_id='<?php echo $question['question_id'];?>' question_name='<?php echo $question['question'];?>'><input class='ace ace-checkbox-2 checkbox-add-individual' question_id='<?php echo $question['question_id'];?>' question_name='<?php echo $question['question'];?>' type="hidden" name='questions' value="<?php echo $question['question_id'];?>" />
		                        <span class='lbl'>&nbsp;<b><?php echo $question['question'];?></b></span></label>
		                    </li>
		                    @empty
		                    <li class="item-orange clearfix" id='questions-row0' data-order='0'>
		                        <label>
		                        <span class='lbl'>&nbsp;<b>Sorry No Question Available ...!</b></span></label>
		                    </li>
		                    @endforelse
						</ul>
					</div>
				</div>
			</div>
		</div>
		<!-- Question Pool Div End-->

		<!-- Section Question Div Start-->
		<div class="col-md-8 col-sm-8 col-xs-12">
			<div class="widget-box widget-color-blue light-border">
				<div class="widget-header">
					<h5 class="widget-title smaller">Section Questions</h5>
					<div class="widget-toolbar">
						<h5 class="badge badge-danger" id="section_question_count">0</h5>
					</div>
				</div>

				<div class="widget-body">
					<div class="widget-main padding-6">
						<div class="widget-main padding-6" id="scroll_bar" style="overflow-y:auto;height:410px">
						<li id="div_remove_all" style="display:none;list-style:none">
							<h5 class="text-center">
								<span id="remove-all">
								<i class="ace-icon fa fa-remove bigger-115 red"></i>
								<span class="" style="cursor:pointer;"><b> Remove All</b></span>
								</span>
							</h5>
						</li>	
						<ul class="list-unstyled spaced2 item-list ui-sortable-handle" id="section-questions">
		                	
		                </ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Section Question Div End-->
		<div class="hr hr-24 dotted hr-double"></div>
		<div class="space-8"></div>
		<div class="col-md-offset-6 col-md-6 col-sm-offset-6 col-sm-6 col-xs-offset-2 col-xs-10" id="div-btn" style="display:none">
			<button class="btn btn-success" type="button" id="btn-assign-section-question">
				<i class="ace-icon fa fa-check bigger-110"></i>
				Save
			</button>
			&nbsp; &nbsp; &nbsp;
			<button class="btn" type="reset">
				<i class="ace-icon fa fa-remove bigger-110"></i>
				Cancel
			</button>
		</div>
		{!! Form::close() !!}
		<div class="col-sm-offset-4 col-sm-8" id="div-message" style="display:none">
			<div class="alert alert-success" id="error-message-success" style="display:none">
		  	<strong >Section Added Successfully ...!</strong>
		  	<br>
			</div>
			<div class="alert alert-danger" id="error-message-not" style="display:none">
		  	<strong >Some Error Occured, Please Try Again Later !...</strong>
		  	<br>
			</div>
			<div class="alert alert-info" id="loading" style="display:none">
		  	<i class="ace-icon fa fa-spinner fa-spin blue bigger-150"></i>
		  	<strong>Processing ...!</strong>
		  	<br>
			</div>
		</div>

	</div>
</div>	
@endsection


@section('footer-section')
	@include('usManager.includes.footer')
@endsection

@section('page_related_scripts')
<script>
	$(document).ready(function(){
		
		/*Dragable Question Div*/
        $('#section-questions').sortable({
			opacity:0.8,
			revert:true,
			forceHelperSize:true,
			placeholder: 'draggable-placeholder',
			forcePlaceholderSize:true,
			tolerance:'pointer',
			stop: function( event, ui ) {
				//just for Chrome!!!! so that dropdowns on items don't appear below other items after being moved
				$(ui.item).css('z-index', 'auto');
				/*Sort Section Questions*/
	        	sortSectionQuestion()
	        	}
		});
        //-->

        /*Search Question To Assign*/
        $(document).on("keyup",'#search_questions', function() 
        {
            var value = $(this).val().toLowerCase();
            $("#all-questions li").filter(function() 
            {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            }); 

        });
        //-->

        
		/*To Assign All Question To Section Panel*/    
        $(document).on('change','#checkbox-add-all',function() {
         	
         	/*checkbox is checked true*/
         	if($(this).prop('checked')){

         		/*To Hide SearchBar*/
            	$("#search_questions").hide();
            	/*To Show Remove All*/
            	$("#div_remove_all").show();
            	/*To Show Button Div*/
            	$('#div-btn').show();
				
            	var questions = [];
         		$.each($("#all-questions input[name='questions']"), function(){
                	
                questions.push("<li class='item-green clearfix ui-sortable-handle' data-order='"+$(this).attr('question_id')+"' id='questions-row"+$(this).attr('question_id')+"' style='cursor:pointer;'><div><h5><span><b class='question_serial'></b></span><span class='pull-right' style='cursor:pointer;'><i class='ace-icon fa fa-remove bigger-150 red remove-individual' question_id='"+$(this).attr('question_id')+"' question_name='"+$(this).attr('question_name')+"'></i></span></h5><div class='col-md-12 col-sm-12 col-xs-12'><p>"+$(this).attr('question_name')+"</p></div><br/><div class='hr hr-24 dotted hr-double'></div><label class='pull-right'><span><b>Status: </b></span><input name='question_status' class='ace ace-switch ace-switch-6' type='checkbox' checked='checked' value='1'><span class='lbl middle'></span></label><input type='hidden' question_id='"+$(this).attr('question_id')+"' question_name='"+$(this).attr('question_name')+"' name='section_questions[]' value='"+$(this).attr('question_id')+"'/></div></li>");

                	$('ul#all-questions>#questions-row'+$(this).attr('question_id')).remove();
            	});
         		$('#section-questions').append(questions);
         		
         		$("#question_pool_count").html($('#all-questions>li.item-orange').length);
         		$('#section_question_count').html($('#section-questions>li').length);	
         		
         		/*Sort Section Questions*/
	        	sortSectionQuestion()
	        	/*Sort Section Questions*/
         		
         	}
         	/*To Hide Select All Box*/
         	$('#checkbox-add-all').prop('checked' ,false).parent().parent().hide();
         	
        });
		//end addAll-->

		/*To Assign Individual Question To Section Panel*/
		$(document).on('click','.checkbox-add-individual',function() {
			
			/*To Show Remove All*/
            $("#div_remove_all").show();
            /*To Show Button Div*/
            $('#div-btn').show();
			
			var questions = [];
         	questions.push("<li class='item-green clearfix ui-sortable-handle' data-order='"+$(this).attr('question_id')+"' id='questions-row"+$(this).attr('question_id')+"' style='cursor:pointer;'><div><h5><span><b class='question_serial'></b></span><span class='pull-right' style='cursor:pointer;'><i class='ace-icon fa fa-remove bigger-150 red remove-individual' question_id='"+$(this).attr('question_id')+"' question_name='"+$(this).attr('question_name')+"'></i></span></h5><div class='col-md-12 col-sm-12 col-xs-12'><p>"+$(this).attr('question_name')+"</p></div><br/><div class='hr hr-24 dotted hr-double'></div><label class='pull-right'><span><b>Status: </b></span><input name='question_status' class='ace ace-switch ace-switch-6' type='checkbox' checked='checked' value='1'><span class='lbl middle'></span></label><input type='hidden' question_id='"+$(this).attr('question_id')+"' question_name='"+$(this).attr('question_name')+"' name='section_questions[]' value='"+$(this).attr('question_id')+"'/></div></li>");

            $('ul#all-questions>#questions-row'+$(this).attr('question_id')).remove();
       		$('#section-questions').append(questions);

       		if($("#all-questions input[name='questions']").length == 0){
       			//$('#checkbox-add-all').parent().parent().hide();
       			$('#add_all').css('visibility','hidden');
       			//$('#add_all').hide();
       			$("#search_questions").hide();
       		}

       		$("#question_pool_count").html($('#all-questions>li.item-orange').length);
         	$('#section_question_count').html($('#section-questions>li').length);

         	//$("#search_class").show();
			var value = $("#search_questions").val('');
			
			$("#all-questions li").filter(function() 
            {
                $(this).show($(this).text().toLowerCase().indexOf(value) > -1)
            });

       		/*Sort Section Questions*/
	        sortSectionQuestion()
	        /*Sort Section Questions*/
	    });//end addIndividual-->
		

		/*To Remove All Question From Section Panel*/
		$(document).on('click','#remove-all',function() {
				removeAll();
        });
		//-->

		/*To Remove Individual Question From Section Panel*/
		$(document).on('click','.remove-individual',function() {
			
			/*To Show SearchBar*/
            $("#search_questions").show();
         	/*To Show Select All Box*/
         	$('#checkbox-add-all').parent().parent().show();
         	$('#add_all').css('visibility','visible');
			
			var questions = [];
			questions.push("<li class='item-orange clearfix' data-order='"+$(this).attr('question_id')+"' id='questions-row"+$(this).attr('question_id')+"'><label style='cursor:pointer' class='checkbox-add-individual' question_id='"+$(this).attr('question_id')+"' question_name='"+$(this).attr('question_name')+"'><input class='ace ace-checkbox-2 checkbox-add-individual' question_id='"+$(this).attr('question_id')+"' question_name='"+$(this).attr('question_name')+"' type='hidden' name='questions' value='"+$(this).attr('question_id')+"'/><span class='lbl'>&nbsp<b>"+$(this).attr('question_name')+"</b></span></label></li>");
			$('ul#section-questions>#questions-row'+$(this).attr('question_id')).remove();
            $('#all-questions').append(questions);
            
            if($("#section-questions input[name='section_questions[]']").length == 0){
       				/*To Hide Remove All*/
       				$("#div_remove_all").hide();
       				/*To Hide Button Div*/
            		$('#div-btn').hide();
       		}

            /*Sort ul-all-questions list items*/
	        var items = $('ul#all-questions>li');
	        items.sort(function(a, b){
	            return +$(a).data('order') - +$(b).data('order');
	        });
	        items.appendTo('ul#all-questions');
	        /*Sort ul-all-questions list items*/

	        $("#question_pool_count").html($('#all-questions>li.item-orange').length);
         	$('#section_question_count').html($('#section-questions>li').length);


	        /*Sort Section Questions*/
	        sortSectionQuestion()
	        /*Sort Section Questions*/

		});//end removeIndividual-->

		/*Sort Section Question Title Count*/
		function sortSectionQuestion(){
			var count = 1;
		    $.each($("#section-questions .question_serial"), function(){
		        $(this).html('Question '+ count++);
		    });
	    }
		/*Sort Section Question Title Count*/


		/*Assign Question To Section*/        
        $(document).on('click','#btn-assign-section-question',function() {
        	
        	var section_title = $('input[name=section_title]').val();
        	var section_description = $('textarea[name=section_description]').val();
        	var section_status = $('input[name="section_status"]');
        	
        	if(section_status[0].checked){
        		section_status = 'Active';
        	}else{
        		section_status = 'InActive';
        	}
        	
        	$validationFlag = formValidation(section_title,section_description);
        	
        	if($validationFlag){
	        	
	        	if(section_title && section_description && $('ul#section-questions li').length>=1)
	          	{
	          		
	          		var section_question_ids = []; 
	          		var section_question_status = [];
	          		var question_priority = [];

	          		var priority = 1
	          		$.each($("#section-questions input[name='section_questions[]']"), function(){
	                	section_question_ids.push($(this).val());
	             		question_priority.push(priority++);
	             	});

	          		$.each($("#section-questions input[name='question_status']"), function(){
	                	if($(this)[0].checked){
	             			section_question_status.push('Active');	
	             		}else{
	             			section_question_status.push('InActive');
	             		}
	             	});

	          		/*jQuery Ajax*/
	                $.ajax({
		                url:"{{ url('usManager/saveSection') }}",
		                type:"POST",
		                dataType: 'json',
		                beforeSend:function(){
		                	$('#div-btn').hide();
		                	$('#div-message').show();
		                	$('#div-message>#loading').show();
		                },
		                data:{
		                    _token:'{{csrf_token()}}',
		                    section_title:section_title,
		                    section_description:section_description,
		                    section_status:section_status,
		                    section_question_ids:section_question_ids,
		                    question_priority:question_priority,
		                    section_question_status:section_question_status,
		                    },
			                success:function(data){
			             		
			                	if(data.message == 'Yes'){
			              			$('#div-message>#loading').hide();
			              			$('#div-message>#error-message-not').hide();
			              			$('#div-message>#error-message-success').show();  		
			                		$("#section-form").trigger("reset");
			                		removeAll();
			                		setTimeout(function(){
			                			$('#div-message>#error-message-success').hide();
			                			$('#div-btn').show();
			                		},3000);
			                	}else{
			                		$('#div-message>#loading').hide();
			              			$('#div-message>#error-message-not').show();
			              			$('#div-message>#error-message-success').hide();
			                		$("#section-form").trigger("reset");
			                		removeAll();
			                		setTimeout(function(){
			                			$('#div-message>#error-message-not').show();
			                		},3000);
			                	}
			                }             
	                });

	          	}
	        }  	

        });//end-->

        /*To Remove All Question From Section Panel*/
        function removeAll(){
        	/*To Show SearchBar*/
            $("#search_questions").show();
         	/*To Show Select All Box*/
         	$('#checkbox-add-all').parent().parent().show();	
         	$('#add_all').css('visibility','visible');
         	/*To Hide Remove All*/
            $('#div_remove_all').hide();
            /*To Hide Button Div*/
            $('#div-btn').hide();
            
            var questions = [];
         	$.each($("#section-questions input[name='section_questions[]']"), function(){
                	
                questions.push("<li class='item-orange clearfix' data-order='"+$(this).attr('question_id')+"' id='questions-row"+$(this).attr('question_id')+"'><label style='cursor:pointer' class='checkbox-add-individual' question_id='"+$(this).attr('question_id')+"' question_name='"+$(this).attr('question_name')+"'><input class='ace ace-checkbox-2 checkbox-add-individual' question_id='"+$(this).attr('question_id')+"' question_name='"+$(this).attr('question_name')+"' type='hidden' name='questions' value='"+$(this).attr('question_id')+"'/><span class='lbl'>&nbsp<b>"+$(this).attr('question_name')+"</b></span></label></li>");
                	$('ul#section-questions>#questions-row'+$(this).attr('question_id')).remove();
            });

         	$('#all-questions').append(questions);	
         	
         	$("#question_pool_count").html($('#all-questions>li.item-orange').length);
         	$('#section_question_count').html($('#section-questions>li').length);

	        /*Sort ul-all-questions list items*/
	        var items = $('ul#all-questions>li');
	        items.sort(function(a, b){
	            return +$(a).data('order') - +$(b).data('order');
	        });
	        items.appendTo('ul#all-questions');
	        /*Sort ul-all-questions list items*/
        }//end removeAll-->


        /*Form Validation*/
        function formValidation(secTitle,secDesc){
        	var $return = 0;
        	if(secTitle == ''){
        		$('#section-title-error').show();
        		$return++;
        	}else{
        		$('#section-title-error').hide();
        	}

        	if(secDesc == ''){
        		$('#section-description-error').show();
        		$return++;
        	}else{
        		$('#section-description-error').hide();
        	}

        	/*if(secStatus == undefined){
        		alert('Please Check Section Status Atcive/Inactive');
        		$return++;
        	}*/

        	if($return === 0){
        		return true;	
        	}else{
        		window.scrollTo({top:0,left:0,behaviour:'smooth'});
        		return false;
        	}
        	
        }//end formValidation-->

	});
</script>
@endsection
