{{--dd($sectionWithQuestions)--}}
<div class="page-header">
	<h1>Template Design Structure</h1>
</div>
<div class="tabbable">
	<ul class="nav nav-tabs" id="myTab">
		@php $sectionCount = 0; @endphp
		@foreach($sectionWithQuestions as $key => $singleSection)
		<li class="{{($sectionCount == 0)?'active':''}}">
			<a data-toggle="tab" href="#section{{$singleSection['singleSection'][0]->section_id}}" aria-expanded="{{($sectionCount == 0)?'true':'false'}}">
				{{$singleSection['singleSection'][0]->section_title}}
				<span class="badge badge-danger" id="badge{{$singleSection['singleSection'][0]->section_id}}">{{count($singleSection['sectionQuestions'])}}</span>
			</a>
		</li>
		@php $sectionCount++; @endphp
		@endforeach
	</ul>
		
	<div class="tab-content">
		@php $indexCount = 0; @endphp
		@foreach($sectionWithQuestions as $key => $singleSection)
		<div id="section{{$singleSection['singleSection'][0]->section_id}}" class="tab-pane fade {{($indexCount == 0)?'active in':''}}">
			<div class="widget-body">
				<!-- <input type="text" id="search-section-questions" class="form-control search-query" placeholder="Search Section Question"> -->
				
				<div class="widget-main padding-6" id="scroll_bar" style="overflow-y:auto;height:410px">
					<div class="col-sm-12 text-right" style="margin-bottom:5px">
						<div class="text-danger text-center label label-sm label-danger arrowed-in-right arrowed-in" id="reserved-priority{{$singleSection['singleSection'][0]->section_id}}" style="display:none">
							This Priority Is Already Assigned
						</div>
						<div class="text-success text-center label label-lg label-success arrowed-in-right arrowed-in" id="notreserved-priority{{$singleSection['singleSection'][0]->section_id}}" style="display:none">
							Priority Reserved
						</div>
					</div>
					<ul class="list-unstyled spaced2 item-list" id="all-assigned-sections">
						
						<li id="add_all_question">
							
							<label><input class="ace ace-checkbox-2 all-question{{$singleSection['singleSection'][0]->section_id}}" type="checkbox" name='checkbox-add-all-assigned-questions'id="checkbox-add-all-assigned-questions"  value="{{$singleSection['singleSection'][0]->section_id}}" checked="" />
	                        <span class='lbl'>&nbsp;<b>Select All / UnSelect All</b></span></label>
							<span class="pull-right">
							<label class="text-danger"><b>Priority: </b></label>
							<input type="number" class="section-priority priority{{$singleSection['singleSection'][0]->section_id}}" name="assigned_section_priority[]" min="1" max="{{$sectionCount}}" placeholder="Enter Number Only" data-id="{{$singleSection['singleSection'][0]->section_id}}" maxlength="{{($sectionCount >9)?'2':'1'}}" required="" /> 
							<input type="hidden" name="assigned_sections_id[]" value="{{$singleSection['singleSection'][0]->section_id}}" />
							</span>
						</li><br/>
						@php $questionCount = 1; @endphp
						@forelse($singleSection['sectionQuestions'] as $key => $sectionQuestion)
						<li class="item-{{($sectionQuestion->question_status == 'Active')?'green':'red'}} clearfix">
	                        <label style="cursor:pointer">
	                        	<input class="ace ace-checkbox-2 single-question section-row{{$singleSection['singleSection'][0]->section_id}}" type="checkbox" name='assigned_question_section_id[]' value="{{$sectionQuestion->question_section_id}}" 
	                        	data-sectionid ="{{$singleSection['singleSection'][0]->section_id}}" checked="" />
	                        <span class='lbl'>&nbsp;<b>Question {{$questionCount++}}</b></span></label>
	                        <p>{{$sectionQuestion->question}}</p>
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
		@php $indexCount++; @endphp
		@endforeach
	</div>
</div>