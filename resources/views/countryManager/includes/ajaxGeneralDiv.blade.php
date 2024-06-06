{{--dd($flag)--}}
{{--dd($activeSections)--}}
@if($flag == 'No')
<!-- For Just Add -->
<div class="form-group">
	<label class="col-sm-3 col-xs-3 control-label no-padding-right" for="form-field-1"><b>Template Types <span class="text-danger">(*)</span></b></label>

	<div class="col-sm-9 col-xs-9">
		<select class="chosen-select form-control" id="template-type" name="template_type_id" data-placeholder="Choose a State...">
			<option value="">Select Template Type</option>
			@foreach($activeTemplates as $template)
			<option value="{{$template['template_type_id']}}" data-template="{{$template['template_type']}}">{{$template['template_type']}}</option>
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
			<option value="{{$project['proj_id']}}" data-project="{{$project['proj_name']}}">{{$project['proj_name']}}</option>
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
		<input type="text" id="document-template-title" name="document_template_title" placeholder="Template Title" class="col-xs-10 col-sm-5 form-control" readonly="">
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
	&nbsp;<input name='document_template_status' class='ace ace-switch ace-switch-6' type='checkbox' checked='checked' value='Active'>
	<span class='lbl middle' style="margin-top:5px;"></span>
</div>

<!-- Section Pool Div Start-->
<div class="col-md-12 col-sm-12 col-xs-12" id="section-pool-div" style="display:none">
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
					
					@if($activeSections)
					<li id="add_all">
						<label><input class='ace ace-checkbox-2' type="checkbox" name='checkbox-add-all'  id="checkbox-add-all" />
                        <span class='lbl'>&nbsp;<b>Select All / UnSelect All</b></span></label>
					</li>
					@endif
					@forelse($activeSections as $section)
					<li class="item-green clearfix">
                        <label style="cursor: pointer">
                        	<input class='ace ace-checkbox-2 checkbox-add-individual'type="checkbox" name='sections' value="{{ $section['section_id']}}" />
                        <span class='lbl'>&nbsp;<b><?php echo $section['section_title'] ?></b></span></label><p>{{ $section['section_description']}}</p>
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
		<!-- Ajax Response -->
		<!-- <div id="document-template-structure-ajax" style="display:none">
			<div class="page-header">
				<h1>Template Design Structure</h1>
			</div>
			<div class="tabbable">
				<ul class="nav nav-tabs" id="myTab">
				</ul>
				<div class="tab-content"></div>
			</div>
		</div> -->	
		<!-- Ajax Response -->
	</div>
</div>
<!-- Template Design Structure End-->
<!-- For Just Add -->

@elseif($flag == 'Yes')
<div class="form-group">
	<label class="col-sm-3 col-xs-3 control-label no-padding-right" for="predefined-template"><b>Predefined Templates <span class="text-danger">(*)</span></b></label>

	<div class="col-sm-9 col-xs-9">
		<select class="chosen-select form-control" id="predefined-template-id" data-placeholder="Select Predefined Template">
			<option value="">Select Predefined Template</option>
			@foreach($predefinedTemplates as $template)
				@php   $userName = getUserAndRoleByRoleUserId($template['added_by']); @endphp
			<option value="{{$template['document_template_id']}}">{{$template['document_template_title']}}</option>
			@endforeach
		</select>
	</div>
</div>
<!-- Ajax Response -->
<div id="predefined-response">
	
</div>
<!-- Ajax Response -->
@endif