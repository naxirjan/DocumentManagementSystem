@if($action == 'getDistrictOperationByCountryId')
	
		<label class="col-sm-5 control-label " for="form-field-1"> District Operation </label>
			<div class="col-sm-7">
				<?php $arrayDistrictOperation = array(""=>"Select District Operation"); ?>
					@forelse($districtOperations as $districtOperation)
						<?php $arrayDistrictOperation[$districtOperation['district_operation_id']] = $districtOperation['district_operation_full_name'] ?>
					@empty
					@endforelse
				{{Form::select('district_operation'.$id, $arrayDistrictOperation,null,array('class'=>'form-control','required'=>'required'))}}
			</div>
@elseif($action == 'getCountryByRoleType')
		<label class="col-sm-3 control-label " > Country </label>
			<div class="col-sm-9">
				<?php $array_country = array(""=>"Select Country"); ?>					
				@forelse($countries as $country)
					<?php
					$array_country[$country['cont_id']] = $country['cont_name'];
					
					?>
				@empty
				@endforelse
					
				{{Form::select('country'.$id, $array_country,null,array('class'=>'form-control','id'=>'country','required'=>'required'))}}
			</div>


@elseif(isset($action) && $action == 'districtOperationProjects')
	<table class = "table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th>
					<input type = "checkbox" id = "select_all" >
				</th>
				<th>			
					{{"(".$districtOperationProjects[0]->district_operation_full_name.") Projects"}}
				</th>
			</tr>
		</thead>
		<tbody>
	@forelse($districtOperationProjects as $districtOperationProject)
		<tr>
			<td>
				<input type = 'checkbox' class = 'checkbox' data-district-operation-project-id="{{$districtOperationProject->district_operation_project_id}}"
				@forelse($userAssignedProjects as $userAssignedProject) <?php echo ($userAssignedProject->district_operation_project_id == $districtOperationProject->district_operation_project_id)?'checked':'' ?> @empty @endforelse
				>		
			</td>
			<td>
				{{$districtOperationProject->proj_name}}		
			</td>
		</tr>			
	@empty
	<tr>
		<th colspan="2">
			{{"No Any Project Found"}}	
		</th>
	</tr>
		
	@endforelse
	</tbody>
	</table>
	<script type="text/javascript">
		
				    //select all checkboxes
				$("#select_all").change(function(){  //"select all" change 
				    $(".checkbox").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
				});

				//".checkbox" change 
				$('.checkbox').change(function(){ 
					//uncheck "select all", if one of the listed checkbox item is unchecked
				    if(false == $(this).prop("checked")){ //if this item is unchecked
				        $("#select_all").prop('checked', false); //change "select all" checked status to false
				    }
					//check "select all" if all checkbox items are checked
					if ($('.checkbox:checked').length == $('.checkbox').length ){
						$("#select_all").prop('checked', true);
					}
				});
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