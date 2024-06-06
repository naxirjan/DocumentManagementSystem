{{--dd($singleDocumentTemplates)--}}
{{--dd($activeSections)--}}
{{--dd($overAllSectionQuestion)--}}
<div class="form-group">
	<label class="col-sm-3 col-xs-3 control-label no-padding-right" for="form-field-1"><b>Template Types <span class="text-danger">(*)</span></b></label>

	<div class="col-sm-9 col-xs-9">
		<select class="chosen-select form-control" id="template-type" name="template_type_id" data-placeholder="Choose a State...">
			<option value="">Select Template Type</option>
			@foreach($activeTemplates as $template)
			<option value="{{$template['template_type_id']}}" data-template="{{$template['template_type']}}" 
			@php echo ($singleDocumentTemplates['singleTemplate'][0]->template_type_id == $template['template_type_id'])?'selected':''; @endphp>{{$template['template_type']}}</option>
			@endforeach
		</select>
		<div id="template-id-error" style="display:none">
		<div class="space-4"></div>
		<span  class="col-md-6 col-sm-6 col-xs-6 label label-xs label-danger arrowed arrowed-right"> 
		The Template Type Field Is Required</span>
		<div class="space-10"></div>
		</div>
	</div>
</div>
<div class="form-group">
	<label class="col-sm-3 col-xs-3 control-label no-padding-right" for="form-field-1"><b>Project <span class="text-danger">(*)</span></b></label>

	<div class="col-sm-9 col-xs-9">
		<select class="chosen-select form-control" id="project" name="project_id" data-placeholder="Choose a State...">
			<option value="">Select Project</option>
			@foreach($allProjects as $project)
			<option value="{{$project['proj_id']}}" data-project="{{$project['proj_name']}}" @php echo ($singleDocumentTemplates['singleTemplate'][0]->project_id == $project['proj_id'])?'selected':''; @endphp>{{$project['proj_name']}}</option>
			@endforeach()													
		</select>
		<div id="project-id-error" style="display:none">
		<div class="space-4"></div>
		<span  class="col-md-6 col-sm-6 col-xs-6 label label-xs label-danger arrowed arrowed-right"> 
		The Project Field Is Required</span>
		<div class="space-10"></div>
		</div>
	</div>
</div>
<div class="form-group">
	<label class="col-sm-3 control-label no-padding-right" for="form-field-1"><b>Template Title <span class="text-danger">(*)</span></b></label>

	<div class="col-sm-9">
		@php 
		
		$title = $singleDocumentTemplates['singleTemplate'][0]->document_template_title;
		$title = explode(')',$title);
		$title = $title[0].')-'.date("d-M-Y");
		@endphp
		<input type="text" id="document-template-title" name="document_template_title" placeholder="Template Title" class="col-xs-10 col-sm-5 form-control" readonly="" value="{{$title}}" />
		<div class="space-4"></div>
		<div id="template-title-error" style="display:none">
		<div class="space-4"></div>
		<span  class="col-md-6 col-sm-6 col-xs-6 label label-xs label-danger arrowed arrowed-right"> 
		The Template Title Field Is Required</span>
		<div class="space-10"></div>
		</div>
	</div>
</div>
<div class="form-group">
	<label for="form-field-8" class="col-sm-3 control-label no-padding-right"><b>Template Status: </b></label>
	&nbsp;<input name='document_template_status' class='ace ace-switch ace-switch-6' type='checkbox' 
	@php echo ($singleDocumentTemplates['singleTemplate'][0]->status == 'Active')?'checked':'' @endphp value={{$singleDocumentTemplates['singleTemplate'][0]->status}}>
	<span class='lbl middle' style="margin-top:5px;"></span>
</div>

<!-- Section Pool Div Start-->
<div class="col-md-12 col-sm-12 col-xs-12" id="section-pool-div" style="display:block">
	<div class="page-header">
		<h1>Sections</h1>
	</div>
	<div class="widget-box widget-color-blue light-border">
		<div class="widget-header">
			<h5 class="widget-title smaller">Sections Pool</h5>

			<div class="widget-toolbar">
				<h5 class="badge badge-danger" id="question_pool_count"><?php echo count($activeSections); ?></h5>
			</div>
		</div>

		<div class="widget-body">
			<input type="text" id="search-sections" class="form-control search-query" placeholder="Search Sections" onkeyup="searchSection(this.value)">
			
			<div class="widget-main padding-6" id="scroll_bar" style="overflow-y:auto;height:410px">
				<ul class="list-unstyled spaced2 item-list" id="all-sections">
					
					<li id="add_all">
						<label><input class='ace ace-checkbox-2' type="checkbox" name='checkbox-add-all'  id="checkbox-add-all" />
                        <span class='lbl'>&nbsp;<b>Select All / UnSelect All</b></span></label>
					</li>
					@forelse($activeSections as $section)
						@php $check = ''; @endphp
						@foreach($singleDocumentTemplates['templateSection'] as $old_section)
							@if($old_section->section_id == $section['section_id'])
								@php $check = 'checked'; @endphp
								@break
							@endif
						@endforeach
					<li class="item-green clearfix">
                        <label style="cursor: pointer">
                        	<input class='ace ace-checkbox-2 checkbox-add-individual'type="checkbox" name='sections' value="{{ $section['section_id']}}"  {{$check}} />
                        <span class='lbl'>&nbsp;<b><?php echo $section['section_title']; ?></b></span></label>
                        <p>{{ $section['section_description']}}</p>
                    </li>
                    	
                    @empty
                    <li class="item-orange clearfix">
                        <label>
                        <span class='lbl'>&nbsp;<b>Sorry No Sections Available ...!</b></span></label>
                    </li>

                    @endforelse
				</ul>
			</div>
		</div>
	</div>
	<div class="space"></div>
</div>
<!-- Section Pool Div End-->

<!-- Template Design Structure Start-->
<div class="col-sm-12 col-xs-12">
	<div id="document-template-structure">
		<div class="page-header">
			<h1>Template Design Structure</h1>
		</div>
		<div class="tabbable">
			<ul class="nav nav-tabs" id="myTab">
				@php $sectionCount = 0; @endphp
				@foreach($overAllSectionQuestion as $key => $allSingleSection)
				<li class="{{($sectionCount == 0)?'active':''}}">
					<a data-toggle="tab" href="#section{{$allSingleSection['singleSection'][0]->section_id}}" aria-expanded="{{($sectionCount == 0)?'true':'false'}}">
						{{$allSingleSection['singleSection'][0]->section_title}}
						<span class="badge badge-success" id="badge{{$allSingleSection['singleSection'][0]->section_id}}">{{count($allSingleSection['sectionQuestions'])}}</span>
					</a>
				</li>
				@php $sectionCount++; @endphp
				@endforeach
			</ul>
				
			<div class="tab-content">
				@php $indexCount = 0; @endphp
				@foreach($overAllSectionQuestion as $key => $allSingleSection)

					@foreach($singleDocumentTemplates['templateSection'] as $key => $oldSection)
						@if($allSingleSection['singleSection'][0]->section_id == $oldSection->section_id)
							@php $oldpriority = $oldSection->section_priority; @endphp
							@break
						@endif	
					@endforeach
				<div id="section{{$allSingleSection['singleSection'][0]->section_id}}" class="tab-pane fade {{($indexCount == 0)?'active in':''}}">
					<div class="widget-body">
						<!-- <input type="text" id="search-section-questions" class="form-control search-query" placeholder="Search Section Question"> -->
						
						<div class="widget-main padding-6" id="scroll_bar" style="overflow-y:auto;height:410px">
							<div class="col-sm-12 text-right" style="margin-bottom:5px">
								<div class="text-danger text-center label label-sm label-danger arrowed-in-right arrowed-in" id="reserved-priority{{$allSingleSection['singleSection'][0]->section_id}}" style="display:none">
									This Priority Is Already Assigned
								</div>
								<div class="text-success text-center label label-lg label-success arrowed-in-right arrowed-in" id="notreserved-priority{{$allSingleSection['singleSection'][0]->section_id}}" style="display:none">
									Priority Reserved
								</div>
							</div>
							<ul class="list-unstyled spaced2 item-list" id="all-assigned-sections">
								
								<li id="add_all_question">
									<label><input class="ace ace-checkbox-2 all-question{{$allSingleSection['singleSection'][0]->section_id}}" type="checkbox" name='checkbox-add-all-assigned-questions' id="checkbox-add-all-assigned-questions" value="{{$allSingleSection['singleSection'][0]->section_id}}" />
			                        <span class='lbl'>&nbsp;<b>Select All / UnSelect All</b></span></label>
									<span class="pull-right">
									<label class="text-danger"><b>Priority: </b></label>
									
									<input type="number" class="section-priority priority{{$allSingleSection['singleSection'][0]->section_id}}" name="assigned_section_priority[]" min="1" max="{{$sectionCount}}" placeholder="Enter Number Only" data-id="{{$allSingleSection['singleSection'][0]->section_id}}" maxlength="{{($sectionCount >9)?'2':'1'}}" required="" value="{{$oldpriority}}" /> 
									
									<input type="hidden" name="assigned_sections_id[]" value="{{$allSingleSection['singleSection'][0]->section_id}}" />
									
									</span>
								</li><br/>
								@php $questionCount = 1; @endphp
								@forelse($allSingleSection['sectionQuestions'] as $key => $allQuestion)
									@php $questionCheck = '' @endphp
									@foreach($singleDocumentTemplates['templateSectionQuestions'] as $key => $oldQuestion)
										@if($allQuestion->question_section_id == $oldQuestion->question_section_id)
											@php $questionCheck = 'checked'; @endphp
										@endif
									@endforeach
								<li class="item-{{($allQuestion->question_status == 'Active')?'green':'red'}} clearfix">
			                        <label style="cursor:pointer">
			                        	<input class="ace ace-checkbox-2 single-question section-row{{$allSingleSection['singleSection'][0]->section_id}}" type="checkbox" name='assigned_question_section_id[]' value="{{$allQuestion->question_section_id}}" {{$questionCheck}} data-sectionid ="{{$allSingleSection['singleSection'][0]->section_id}}" />
			                        <span class='lbl'>&nbsp;<b>Question {{$questionCount++}}</b></span></label>
			                        <p>{{$allQuestion->question}}</p>
			                    </li>
			                    @empty
			                    <li class="item-orange clearfix">
			                        <label>
			                        <span class='lbl'>&nbsp;<b>Sorry No Questions Available ...!</b></span></label>
			                    </li>
			                    @endforelse
							</ul>
						</div>
					</div>
				</div>
				<script>
					$(document).ready(function(){
							let $totalLength    = $('#all-assigned-sections .section-row'+@php echo $allSingleSection['singleSection'][0]->section_id @endphp).length;
                            let $checkedLength  = $('#all-assigned-sections .section-row'+@php echo $allSingleSection['singleSection'][0]->section_id @endphp+':checked').length;
                            if($totalLength == $checkedLength){
                            $('.all-question'+@php echo $allSingleSection['singleSection'][0]->section_id @endphp).prop('checked',true);
                            }else{
                            $('.all-question'+@php echo $allSingleSection['singleSection'][0]->section_id @endphp).prop('checked',false);
                            }
					});
				</script>
				@php $indexCount++; @endphp
				@endforeach
			</div>
		</div>
	</div>
</div>
<!-- Template Design Structure End-->