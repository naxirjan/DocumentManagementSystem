@extends( 'layouts.master' )

@section('page_sepecific_plugin')
<link rel="stylesheet" href="{{ asset( '/' ) }}assets/css/chosen.css" />
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
	Add Document Template 
	<small>
		<i class="ace-icon fa fa-angle-double-right"></i>
			Required (*) Fields Must Be Filled
	</small>
</h1>
@endsection


@section('page-content')

<div class="row">
    <div class="col-sm-12">
<!-- Loading Processing Div -->
<div id="processing_div" class="bootbox modal fade bootbox-confirm in" tabindex="-1" role="dialog" style="display:none; padding-right:17px;" aria-hidden="false">
        <div class="modal-backdrop fade in" style="height:786px;opacity:0.75"></div>
        <div class="modal-dialog" style="margin-top:15%">
            <div class="modal-content">
                <div class="modal-body">
                    <h3 style="text-align:center;color:#900;font-variant:petite-caps"><b>Waiting For Server Response</b></h3>
                </div>
                <div class="modal-footer" style="background-color:#1a3f54">
                    <center>
                        <h3 class="smaller" style="color:#FFF;font-variant:petite-caps">
                            <i class="ace-icon fa fa-spinner fa-spin orange bigger-130"></i>
                            Processing...
                        </h3> 
                    </center> 
                </div>
            </div>
        </div>
</div>
<!-- Loading Processung Div -->

@if(Session::has('Yes'))
	<div class="col-sm-12">
			<div class="alert alert-success">
				<button type="button" class="close" data-dismiss="alert">
					<i class="ace-icon fa fa-times"></i>
				</button>
				{{Session::get('Yes')}}
				<br>
			</div>
	</div>
@endif
@if(Session::has('No'))
	<div class="col-sm-12">
			<div class="alert alert-danger">
				<button type="button" class="close" data-dismiss="alert">
					<i class="ace-icon fa fa-times"></i>
				</button>
				{{Session::get('No')}}
				<br>
			</div>
	</div>
@endif

{!! Form::open(array('url'=>'usManager/saveDocumentTemplate', 'method'=>'POST' , 'class'=>'form-horizontal' , 'id'=>'template-form')) !!}
<div class="form-group">
	<label class="col-sm-3 control-label no-padding-right" for="form-field-1"><b>Do You Want To Use Predefined Templates <span class="text-danger">(*)</span></b></label>
	<div class="col-sm-9">
		<div class="radio">
		<label>
		<input name="checkedTemplate" type="radio" class="ace form-control" value="Yes" />
		<span class="lbl bigger-120"> Yes </span>
		</label>
		<label>
		<input name="checkedTemplate" type="radio" class="ace form-control" value="No" />
		<span class="lbl bigger-120"> No </span>
		</label>
	</div>
	</div>
</div>

<!-- Ajax Div -->
<div id="general-div" style="display:block">
	<!-- Ajax Response -->
</div>
<!-- End -->

<div class="col-md-offset-4 col-md-8 col-sm-offset-4 col-sm-8 col-xs-offset-2 col-xs-10" id="div-btn" style="display:none;margin-top:10px">
	<button class="btn btn-success" type="button" id="btn-assign-section-to-template">
		<i class="ace-icon fa fa-check bigger-110"></i>
		Save
	</button>
	&nbsp; &nbsp; &nbsp;
	<button class="btn" onclick="window.history.back()">
		<i class="ace-icon fa fa-remove bigger-110"></i>
		Cancel
	</button>
</div>
{!!Form::close()!!}

<!--Modal Appears When Any Section Priority Is Empty-->
<div id="modal-check-template-structure-1" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
      	<div class="modal-content">
      		<div class="modal-body">
      			<button type="button" class="bootbox-close-button close" data-dismiss="modal" aria-hidden="true" style="margin-top: -10px;">×</button>
      			<div class="bootbox-body">
                    <h3 class="text-danger text-center"><i class="ace-icon fa fa-exclamation-triangle bigger-120"></i> Please Given Section Priority
                    </h3>
                </div>
      		</div>
      		<div class="modal-footer background-blue"></div>
      	</div>
    </div>
</div><!-- -->

<!--Modal Appears When Not Selected All Question Section ID-->
<div id="modal-check-template-structure-2" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
      	<div class="modal-content">
      		<div class="modal-body">
      			<button type="button" class="bootbox-close-button close" data-dismiss="modal" aria-hidden="true" style="margin-top: -10px;">×</button>
      			<div class="bootbox-body">
                    <h3 class="text-danger text-center"><i class="ace-icon fa fa-exclamation-triangle bigger-120"></i> Please Select Questions
                    </h3>
                </div>
      		</div>
      		<div class="modal-footer background-blue"></div>
      	</div>
    </div>
</div><!-- -->

<!--Modal Appears When Section Priority Is Not Unique-->
<div id="modal-check-template-structure-3" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
      	<div class="modal-content">
      		<div class="modal-body">
      			<button type="button" class="bootbox-close-button close" data-dismiss="modal" aria-hidden="true" style="margin-top: -10px;">×</button>
      			<div class="bootbox-body">
                    <h3 class="text-danger text-center"><i class="ace-icon fa fa-exclamation-triangle bigger-120"></i> Section Priority Is Not Unique
                    </h3>
                </div>
      		</div>
      		<div class="modal-footer background-blue"></div>
      	</div>
    </div>
</div><!-- -->

<!--Modal Appears When Not Selected Section-->
<div id="modal-check-template-structure-4" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="bootbox-close-button close" data-dismiss="modal" aria-hidden="true" style="margin-top: -10px;">×</button>
                <div class="bootbox-body">
                    <h3 class="text-danger text-center"><i class="ace-icon fa fa-exclamation-triangle bigger-120"></i> Please Select Section
                    </h3>
                </div>
            </div>
            <div class="modal-footer background-blue"></div>
        </div>
    </div>
</div><!-- -->

<!--Modal Appears When Not Selected Any Question Section ID-->
<div id="modal-check-template-structure-5" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="bootbox-close-button close" data-dismiss="modal" aria-hidden="true" style="margin-top: -10px;">×</button>
                <div class="bootbox-body">
                    <h3 class="text-danger text-center">
                        <i class="ace-icon fa fa-exclamation-triangle bigger-120"></i> Please Select  At Least 1 Question From Sections
                    </h3>
                </div>
            </div>
            <div class="modal-footer background-blue"></div>
        </div>
    </div>
</div><!-- -->

    </div>
</div>
@endsection

@section('footer-section')
	@include('usManager.includes.footer')
@endsection

@section('page_related_scripts')
<script>
	/*Number Validation In Priority*/
    /*function isNumberKey(evt,val)
    {
        let inputLength = 0;
        if(val.length == 0){
            let inputLength = 1;
        }
        if(evt.target.maxLength == inputLength){
            return true;
        }else{
            return false;
        }

        var charCode = (evt.which) ? evt.which : event.keyCode
        if(charCode == 48)
        	return false;

        if (charCode && charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

         	return true;


    }*///-->

     /*Search Section To Assign*/
    function searchSection(query){
        var value = query.toLowerCase();//$(this).val().toLowerCase();
        $("#all-sections li").filter(function() 
        {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
        $('#search-sections').val(value);	
    }//-->

</script>

<script src="{{ asset( '/' ) }}assets/js/chosen.jquery.js"></script>
<script>
	
	$(document).ready(function(){
		
		/*For Select Box With Search*/
		$('.chosen-select').chosen({allow_single_deselect:true});

        /*For Disable Number Field To Write Any Thing*/
        $(document).on('keydown' ,'input[type=number]' ,function(evt){
            evt.preventDefault();
        });
		
		/*To Show or Hide Predefined Template Select Box*/
        $(document).on('change' ,'input[name=checkedTemplate]:checked' ,function(){
        	
        	if($(this).val() == 'Yes'){
        		var flagID = 'Yes';
        	}else{
        		var flagID = 'No';
        	}
        	
        	$.ajax({
		            url:"{{ url('usManager/getGeneralDiv') }}",
		            type:"GET",
		            //dataType: 'json',
		            beforeSend:function(){
                        $('#processing_div').show();
                    },
		            data:{
		                _token:'{{csrf_token()}}',
		                flag:flagID,
		                },
			            success:function(data){
			             	$('#general-div').html(data);
			                $('#div-btn').hide();
                            $('.chosen-select').chosen({allow_single_deselect:true});
                            $('#processing_div').hide();      
                        }             
	        });
        });
        //-->

        /*create Document Template Title*/
    	$(document).on('change' ,'#project' ,function(){
    		let temp_name  = ($('#template-type').find(':selected').data('template'));
    		let proj_name = ($(this).find(':selected').data('project'));
    		$('#project-id-error').hide();
    		createDocTemplateTitle(temp_name,proj_name);
    	});

    	$(document).on('change' ,'#template-type' ,function(){
    		let temp_name  = ($(this).find(':selected').data('template'));
    		let proj_name = ($('#project').find(':selected').data('project'));
    		$('#template-id-error').hide();
    		createDocTemplateTitle(temp_name,proj_name);
    	});

    	function createDocTemplateTitle(tempName=null , projName=null){
    		if(tempName && projName){
    			let date = new Date();
    			let doc_template_title = projName+' ('+tempName+')-@php echo date("d-M-Y") @endphp';
    			$('#document-template-title').val(doc_template_title);
    			$('#template-title-error').hide();
    			$('#section-pool-div').show();
    			$("#document-template-structure").show();
                $('#div-btn').show();
    		}else{
    			$('#document-template-title').val('');
    			$('#section-pool-div').hide();
    			$("#document-template-structure").hide();
    			$('#div-btn').hide();
    		}
    	}
    	//-->


        /*To Checked All Checkbox*/
        $(document).on('change' ,'#checkbox-add-all' ,function(){
        	var section_id =[];
        	if($(this)[0].checked){
        		
        		$('.checkbox-add-individual').prop('checked',true);
        		
        		$.each($("#all-sections input[name=sections]:checked"), function(){
        			section_id.push($(this).val());
        		});
        		
        		getSectionQuestions(section_id);

        	}else{
        		$('.checkbox-add-individual').prop('checked',false);
        		section_id.splice(0, section_id.length);
        		getSectionQuestions();
        		$('#div-btn').hide();
        	}


        });
        //-->

		
        /*To Checked Single Checkbox*/
        $(document).on('change' ,'.checkbox-add-individual' ,function(){
        	var section_id =[];
        	$.each($("#all-sections input[name=sections]:checked"), function(){
        			section_id.push($(this).val());
        	});
        		
        	if(section_id.length > 0){
        		getSectionQuestions(section_id);
        	}else{
        		getSectionQuestions();
        		$('#div-btn').hide();
        	}
        		
        	if($("#all-sections input[name='sections']:checked").length == $("#all-sections input[name='sections']").length)
        	{
        		$('#checkbox-add-all').prop('checked',true);
        	}else{
        		$('#checkbox-add-all').prop('checked',false);
        	}

        	searchSection("");

        });//-->


        /*Ajax Function To Get Section With Theirs Questions*/
    	function getSectionQuestions(sectionID =null){
    		
    		if(sectionID){
    			$.ajax({
		                url:"{{ url('usManager/getSectionWithQuestionsBySectionId') }}",
		                type:"GET",
		                //dataType: 'json',
		                beforeSend:function(){
                            $('#processing_div').show();
                        },
		                data:{
		                    _token:'{{csrf_token()}}',
		                    section_id:sectionID,
		                    },
			                success:function(data){
			             		$('#document-template-structure').html(data);
			                	$('#div-btn').show();
                                $('#processing_div').hide();
			                }             
	            });


    		}else{
    			$('#document-template-structure').html('');
    		}
    	
    	}//-->
	
    	/*Checked All Assigned Question Dynamically*/
		$(document).on('change' ,'#checkbox-add-all-assigned-questions',function(){
			let sectionID = $(this).val();
			if($(this)[0].checked){
				$('.section-row'+sectionID).prop('checked',true);
			}else{
				$('.section-row'+sectionID).prop('checked',false);
			}
        		
		});
		//-->

        /*Checked Single Assigned Question Dynamically*/
        $(document).on('change' ,'.single-question' , function(){
            let sectionID = $(this).data('sectionid');
            let $totalLength    = $('#all-assigned-sections .section-row'+sectionID).length;
            let $checkedLength  = $('#all-assigned-sections .section-row'+sectionID+':checked').length;
            if($totalLength == $checkedLength){
                $('.all-question'+sectionID).prop('checked',true);
            }else{
                $('.all-question'+sectionID).prop('checked',false);
            }
            
        });
        //-->


		/*check Section Priority Already Assigned Or Not*/
		$(document).on('input' ,'.section-priority',function(){
			let currentPriorityBox = ($(this).val());
			let flag = false;
			$.each($(".section-priority:not(.priority"+$(this).data('id')+")"), function(){
        		
        		if((currentPriorityBox == '') || currentPriorityBox == $(this).val()){
        			flag = true;
        		}else{

        			if($(this).val() != ''){
        				$('#reserved-priority'+$(this).data('id')).hide();
	        			$('#notreserved-priority'+$(this).data('id')).show();
        				$('#badge'+$(this).data('id')).removeClass("badge-danger").addClass('badge-success');
        			}
        				
        		}

        	});

			if(flag){
	        	$('#reserved-priority'+$(this).data('id')).show();
	        	$('#notreserved-priority'+$(this).data('id')).hide();
	        	$('#badge'+$(this).data('id')).removeClass("badge-success").addClass('badge-danger');
	        	//$('#div-btn').hide();
	        }else{
	        	$('#reserved-priority'+$(this).data('id')).hide();
	        	$('#notreserved-priority'+$(this).data('id')).show();
	        	$('#badge'+$(this).data('id')).removeClass("badge-danger").addClass('badge-success');
	        	//$('#div-btn').show();
	        }
        		
        });
        //-->

        /*Submit Form*/
        $(document).on('click' ,'#btn-assign-section-to-template' ,function(){
        	
        	let tempID = $('#template-type').val();
        	let projID = $('#project').val();
        	let tempName  = $('input[name="document_template_title"]').val();
        	let document_template_status = $('input[name="document_template_status"]');
        	let section_id 				 = [];
        	let sectionPriority 		 = [];
        	let questionSectionID 	 	 = [];
        	if(document_template_status[0].checked){
        		document_template_status = 'Active';
        	}else{
        		document_template_status = 'InActive';
        	}

        	/*Section ID*/
        	$.each($("#all-sections input[name='sections']:checked"), function(){
	            section_id.push($(this).val());
	        });

        	/*Section Priority*/
        	let countPriority = $.each($("#add_all_question input[name='assigned_section_priority[]']"), function(){
	           	if(!($(this).val() == '')){
	           		sectionPriority.push($(this).val());
	           	}
	            
	        });
        	
        	/*Question Section ID*/
			$.each($("#all-assigned-sections input[name='assigned_question_section_id[]']:checked"), function(){
	            questionSectionID.push($(this).val());
	        });	        


        	if(section_id.length == 0){
                $("#modal-check-template-structure-4").modal('show');
            }else{
                $("#modal-check-template-structure-4").modal('hide');
                
                let sectionQuestionFlag = true;
                $.each(section_id,function(index,id){

                   if(!($('#section'+id+' #all-assigned-sections .section-row'+id+':checked').length >= 1)){
                      sectionQuestionFlag = false;
                      //$('#badge'+id).removeClass("badge-success").addClass('badge-danger');  
                    
                   }

                });

                if(sectionQuestionFlag){

                    let ValidationFlag = formValidation(tempID,projID,tempName,sectionPriority,countPriority,questionSectionID);
                    if(ValidationFlag){
                        console.log('Form Submit');
                        $('#div-btn').hide();
                        $('#template-form').submit();

                    }    
                }else{
                    $("#modal-check-template-structure-5").modal('show');
                }
            }

        });
        //-->


         /*Form Validation*/
        function formValidation(tempID,projID,tempName,secPriority,countPriority,questionSectionID){
        	var $return = 0;
        	if(tempID == ''){
        		$('#template-id-error').show();
        		$return++;
        	}else{
        		$('#template-id-error').hide();
        	}

        	if(projID == ''){
        		$('#project-id-error').show();
        		$return++;
        	}else{
        		$('#project-id-error').hide();
        	}

        	if(tempName == ''){
        		$('#template-title-error').show();
        		$return++;
        	}else{
        		$('#template-title-error').hide();
        	}
        	
        	let PriorityFlag = true;
        	if(secPriority.length == 0 || countPriority.length != secPriority.length){
        		$return++;
        		PriorityFlag = false;
        		$("#modal-check-template-structure-1").modal('show');
        	
        	}else if(!(checkIfArrayIsUnique(secPriority))){
        		$return++;
        		PriorityFlag = false;
        		$("#modal-check-template-structure-3").modal('show');
        	}else{
        		PriorityFlag = true;
        		$("#modal-check-template-structure-1").modal('hide');
        	}

        	if(PriorityFlag){

        		if(questionSectionID.length == 0){
        		$return++;
        		$("#modal-check-template-structure-2").modal('show');
        		}else{
        			$("#modal-check-template-structure-2").modal('hide');
        		}	
        	}
        	

        	if($return === 0){
        		return true;	
        	}else{
        		//window.scrollTo({top:0,left:0,behaviour:'smooth'});
        		return false;
        	}
        	
        }//end formValidation-->

        /*Check Priority Array Unique Or Not*/
        function checkIfArrayIsUnique(myArray) {
 		 return myArray.length === new Set(myArray).size;
		}
		//-->


        /*Get Predefined Template*/
		$(document).on('change' , '#predefined-template-id' , function(){
			let document_template_id = $(this).val();
			if(document_template_id != ''){
				
				$.ajax({
		            url:"{{ url('usManager/getPredefinedTemplateByTemplateId') }}",
		            type:"GET",
		            //dataType: 'json',
		            beforeSend:function(){
                        $('#processing_div').show();
                    },
		            data:{
		                _token:'{{csrf_token()}}',
		                document_template_id:document_template_id,
		            },
			        success:function(data){
			    		$('#predefined-response').html(data);
			            $('#div-btn').show();
			            $('.chosen-select').chosen({allow_single_deselect:true});
                        let $totalLength = $('#all-sections input[name=sections]').length;
                        let $totalCheckedLength = $('#all-sections input[name=sections]:checked').length; 
                        if($totalLength == $totalCheckedLength){
                            $('#checkbox-add-all').prop('checked' ,true);
                        }else{
                            $('#checkbox-add-all').prop('checked' ,false);
                        }
                        $('#processing_div').hide();
			        }             
	            });
			
            }
		});

	});
</script>
@endsection