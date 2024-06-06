
@if(isset($action) && $action == 'getDistrictOperationByCountryId')
    @if($roleType == 3 && $country!=1)
    <label class="col-sm-5 control-label " for="form-field-1"> Location</label>
        <div class="col-sm-7">
            <?php $arrayDistrictOperation = array(""=>'-- Select Location --'); ?>
                @forelse($districtOperations as $districtOperation)
                    <?php $arrayDistrictOperation[$districtOperation['district_operation_id']] = $districtOperation['district_operation_full_name'] ?>
                @empty
                @endforelse
            {{Form::select('district_operation'.$id, $arrayDistrictOperation,null,array('class'=>'form-control','required'=>'required'))}}        
        </div>
    @elseif($roleType != 3)
    <label class="col-sm-5 control-label " for="form-field-1"> District Operation</label>
        <div class="col-sm-7">
            <?php $arrayDistrictOperation = array(""=>'-- Select District Operation --'); ?>
                @forelse($districtOperations as $districtOperation)
                    <?php $arrayDistrictOperation[$districtOperation['district_operation_id']] = $districtOperation['district_operation_full_name'] ?>
                @empty
                @endforelse
            {{Form::select('district_operation'.$id, $arrayDistrictOperation,null,array('class'=>'form-control','required'=>'required'))}}        
        </div>
    @endif
@elseif(isset($action) && $action == 'getCountryByRoleType')
		<label class="col-sm-3 control-label " > Country </label>
			<div class="col-sm-9">
				<?php $array_country = array(""=>'-- Select Country --'); ?>					
				@forelse($countries as $country)
					<?php
					$array_country[$country['cont_id']] = $country['cont_name'];
					
					?>
				@empty
				@endforelse
					
				{{Form::select('country'.$id, $array_country,null,array('class'=>'form-control','id'=>'country','required'=>'required'))}}
			</div>
@elseif(isset($action) && $action == 'districtOperationProjects')
	
	<div id="errorMsg"class="alert alert-block alert-danger hide">Please Select At Least One Project</div>
	
	<table class="table table-striped table-bordered table-hover" id="project-table">
		<thead>
			@php $flag = true; @endphp
			@if(isset($districtOperationProjects[0]))
				@php $flag = false; @endphp
			<input type="text" id="search_projects" class="form-control search-query" placeholder="Search Project" />
			<tr>
				<th>
					<input type ="checkbox" class="ace ace-checkbox-2" id="select_all" >
					<span class="lbl"></span>
				</th>
				<th>
        			{{--"(".$districtOperationProjects[0]->district_operation_full_name.") Projects"--}}
					{{"Projects (".$districtOperationProjects[0]->district_operation_short_name.")"}}
				</th>
			</tr>
			@endif
		</thead>
		<tbody style="overflow-y:auto;height:50px;">
	@forelse($districtOperationProjects as $districtOperationProject)
		<tr>
			<td>
				<input type = 'checkbox' class ='checkbox ace ace-checkbox-2' data-district-operation-project-id="{{$districtOperationProject->district_operation_project_id}}"
				@forelse($userAssignedProjects as $userAssignedProject) <?php echo ($userAssignedProject->district_operation_project_id == $districtOperationProject->district_operation_project_id)?'checked disabled':'' ?> @empty @endforelse
				/>
				<span class="lbl"></span>		
			</td>
			<td>
				{{$districtOperationProject->proj_name}}		
			</td>
		</tr>			
	@empty
	<tr>
		<th colspan="2">
			{{"No Projects Found"}}	
		</th>
	</tr>
	@endforelse
	</tbody>
	</table>
	<input type="hidden" name="flag" value="{{$flag}}" id="flag" />
	<script type="text/javascript">
		
		//select all checkboxes
		$("#select_all").change(function(){  //"select all" change 
			
			$('#errorMsg').addClass('hide');
			if($(this).prop("checked")){
				$(".checkbox").prop('checked',true);
			}else{
				$('.checkbox').not('[disabled]').prop('checked',false);	
			}
		});
		//-->

		//".checkbox" change 
		$('.checkbox').change(function(){ 
			$('#errorMsg').addClass('hide');
			//uncheck "select all", if one of the listed checkbox item is unchecked
			if(false == $(this).prop("checked")){ //if this item is unchecked
				$("#select_all").prop('checked', false); //change "select all" checked status to false
			}
			//check "select all" if all checkbox items are checked
			if ($('.checkbox:checked').length == $('.checkbox').length ){
				$("#select_all").prop('checked', true);
			}
		});
		//-->

		/*Search Question To Assign*/
        $(document).on("keyup",'#search_projects', function() 
        {
            var value = $(this).val().toLowerCase();
            $("#project-table tbody > tr").filter(function() 
            {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            }); 

        });
        //-->
	</script>

@elseif(isset($action) && $action == 'getuserProjects')
            	<ul>
	        		@forelse($userAssignedProjects as $userAssignedProject)	                                		
	        			@if(!empty($userAssignedProject))
	            			
	            			<li>
                				{{$userAssignedProject->proj_name}} <a class = "deleteUserProject" href = "{{$userAssignedProject->project_user_id}}"><i style="color:#f32013;float:right" class = 'fa fa-trash'></i></a>
                			</li>
	            			
	        			@endif

	        		@empty
	        			<li>{{'Project Not Assigned'}}</li>
	        		@endforelse
        		</ul>

@endif
