@extends( 'layouts.master' )

@section('page_sepecific_plugin')
@endsection


@section('navbar-section')
	@include('countryManager.includes.navBar')
@endsection

@section('sidebar-section')
	@include('countryManager.includes.sideBar')
@endsection

@section('breadcrumb-section')
	@section('breadcrumb-content')
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="#">Home</a>
	</li>
	<li class="active">{{$title}}</li>
	@endsection
	@include('countryManager.includes.breadCrumb')
@endsection

@section('settingbox-section')
	@include('countryManager.includes.settingBox')
@endsection

@section('pageheader-section')
<h1>
	{{$title}} 
</h1>
@endsection


@section('page-content')
	<!--Spinner-->
		<!-- By Abdul Ghani --Start-- -->
				<div id = "overlay" style = "position: fixed;top: 0;z-index: 100;width: 100%;height:100%;display: none;background: rgba(205, 212, 207,0.6);">

                    <div id = "loader" style = "position: fixed;top: 50%;left: 50%;transform: translate(-50%, -50%);">
                    	<img width="300px" id="avatar" alt="No Image" src="{{asset('../assets/img/spinner.gif')}}" >
  
                    	<h3 style = "position: fixed;top: 60%;left: 52%;transform: translate(-50%, -50%);">Loading...</h3>
                    </div>

                </div>
        <!-- By Abdul Ghani --End-- -->            
    <!--Spinner-->	
	@if(Session('success'))
		<div class="alert alert-success" role="alert">
		  {{Session::get('success')}}
		</div>
	@elseif(Session('danger'))
		<div class="alert alert-danger" role="alert">
		  {{Session::get('danger')}}
		</div>
	@endif
	
	{{ Form::open(array('url' => $form_action,'class'=>'form-horizontal','role'=>'form','method'=>'POST','files'=>true)) }}
	
		{{Form::hidden('user_id',(isset($user['user_id']))?$user['user_id']:'')}}
	
	<div class = "row" >
	
		<div class = "col-md-9">
			<div class="form-group">
			<label class="col-sm-2 control-label" for="first_name"> First Name </label>
			<div class="col-sm-7">
				{{ Form::text('first_name',(isset($user['first_name'])?$user['first_name']:''),array('class'=>'form-control','placeholder'=>'Enter Your First Name','id'=>'first_name')) }}
				@if($errors->has('first_name'))
    			<div class="error text-danger">{{ $errors->first('first_name') }}</div>
				@endif
			</div>
			
		</div>
		<div class = "form-group">
			<label class="col-sm-2 control-label " for="last_name"> Last Name </label>
			<div class="col-sm-7">
				{{ Form::text('last_name',(isset($user['last_name'])?$user['last_name']:''),array('class'=>'form-control','placeholder'=>'Enter Your First Last','id'=>'last_name')) }}
				@if($errors->has('last_name'))
    			<div class="error text-danger">{{ $errors->first('last_name') }}</div>
				@endif
			</div>
		</div>
		<div class = "form-group">
			<label class="col-sm-2 control-label " for="email"> Email </label>
			<div class="col-sm-7">
				{{ Form::text('email',(isset($user['email'])?$user['email']:''),array('class'=>'form-control','placeholder'=>'Enter Your Email','id'=>'email')) }}
				@if($errors->has('email'))
    			<div class="error text-danger">{{ $errors->first('email') }}</div>
				@endif
				
			</div>
		</div>
		<div class = "form-group">
			<label class="col-sm-2 control-label " for="password"> Password </label>
			<div class="col-sm-7">
				{{ Form::password('password',array('class'=>'form-control','placeholder'=>'Enter Your Password','id'=>'password')) }}
				@if($errors->has('password'))
    			<div class="error text-danger">{{ $errors->first('password') }}</div>
				@endif
			</div>
		</div>
		<div class = "form-group">
			<label class="col-sm-2 control-label " for="number"> Phone </label>
			<div class="col-sm-7">
				{{ Form::text('number',(isset($user['phone'])?$user['phone']:''),array('class'=>'form-control','placeholder'=>'Enter Your Phone Number','id'=>'number')) }}
				@if($errors->has('number'))
    			<div class="error text-danger">{{ $errors->first('number') }}</div>
				@endif
			</div>
		</div>
		<div class = "form-group">
			<label class="col-sm-2 control-label " for="image"> Image </label>
			<div class="col-sm-7">
				{{ Form::file('image',array('class'=>'form-control')) }}
				@if(isset($user['image']))
					<br />
					<img style="width:100px;height:100px" class = "img thumbnail" src = "{{asset('storage/UserImage/'.$user['image'])}}">
				@endif
				@if($errors->has('image'))
    			<div class="error text-danger">{{ $errors->first('image') }}</div>
				@endif
		</div>
		</div>
		<div class = "form-group">
			<label class="col-sm-2 control-label " > Status </label>

            <div class="col-sm-3">
                <div class = "checkbox">
                    <label>
                    	@if(isset($user['status']))
                    		@if($user['status'] == 'Active')
                    			<?php $status = true; ?>
                    		@else
                    			<?php $status = false; ?>
                    		@endif	
                    	@else
                    		<?php $status = true?>			
                    	@endif
                    	{{Form::checkbox('user_status', 1, $status,array('class'=>'ace ace-switch ace-switch-6'))}}
                        
                        <span class="lbl"></span>
                    </label>
               </div>
            </div>
        </div>
		</div>
		
		<!-- #section:elements.form -->						
</div>
	@if($form_action == '/updateUserByCountryManager')
	<div class = "col-md-12">
		<h4>User Roles</h4>
		<hr />
	</div>
	<?php $x = 1; ?>

	@foreach($user['roles'] as $roles_selected)
		

		<div class = "row" id = "parent_<?php echo $x; ?>">

		<div class = "col-md-2" style = "width:150px">
			<div class = "form-group">
			<div class="col-sm-5">
                <div class = "checkbox">	
                    <label>
                        Status
                    </label>
                </div>
            </div>

            <div class="col-sm-3" >
                <div class = "checkbox">
                    <label>
                	 	@if(isset($roles_selected['status']))

	                		@if($roles_selected['status'] == 'Active')
	                			<?php $status = true;
	                			 ?>
	                		@else
	                			<?php $status = false; ?>
	                		@endif	
                    	@else
                    		<?php $status = true; ?>			
                    	@endif
                        {{Form::checkbox('status'.$x,'on', $status,array('class'=>'ace ace-switch ace-switch-6'))}}
                        <span class="lbl"></span>
                    </label>
                   
               </div>

            </div>
        </div>
	</div>
		<div class = "col-md-3" >
			<label class="col-sm-4 control-label" for="role_type"> Role Type  </label>
			<div class="col-sm-8">
				{{Form::hidden('role_user_id'.$x,$roles_selected['role_user_id'])}}
				<?php $array_role = array(""=>'Select Role'); ?>					
				@forelse($roles as $role)
					<?php
						if($role['role_id'] == 1 || $role['role_id'] == 2 || $role['role_id'] == 3){
				
							continue;
						}
					$array_role[$role['role_id']] = $role['role'];

					?>
				@empty
				@endforelse
				
				{{Form::
					select('role_type'.$x, 
					$array_role,
					$roles_selected['role_id'],
					array('class'=>'form-control','id'=>'role_type'))}}
				@if($errors->has('role_type'))
    			<div class="error text-danger">{{ $errors->first('role_type') }}</div>
				@endif
			</div>

	</div>
	<div class = "col-md-3 countryDiv" >
		@if(isset($roles_selected['country_id']))
		<label class="col-sm-3 control-label " > Country </label>
			<div class="col-sm-9">
					
				<?php $array_country = array(""=>'Select Country'); ?>					
				@forelse($countries as $country)
					<?php
					$array_country[$country['cont_id']] = $country['cont_name'];
					
					?>
				@empty
				@endforelse
					
				{{Form::select('country'.$x, $array_country,$roles_selected['country_id'],array('class'=>'form-control','id'=>'country','required'=>'required'))}}
			</div>
		@endif	
							

	</div>
	<div class = "col-md-4 districtOperationDiv">
		@if(isset($roles_selected['district_operation_id']))
				<label class="col-sm-5 control-label " for="form-field-1"> District Operation </label>
			<div class="col-sm-7">
				<?php $arrayDistrictOperation = array(); ?>
					@forelse($districtOperations as $districtOperation)
						<?php $arrayDistrictOperation[$districtOperation['district_operation_id']] = $districtOperation['district_operation_full_name'] ?>
					@empty
					@endforelse
				{{Form::select('district_operation'.$x, $arrayDistrictOperation,$roles_selected['district_operation_id'],array('class'=>'form-control','required'=>'required'))}}
			</div>
		@endif	
							

	</div>
	</div>
	<?php $x++; ?>
	@endforeach
	<span id = "row_counter" style = "display: none"><?php echo $x; ?></span>
	@else

<div class = "row" id = "parent_1">
	<div class = "col-md-12">
		<h4>User Roles</h4>
		<hr />
	</div>

		<div class = "col-md-2" style = "width:150px">
			<div class = "form-group">
			<div class="col-sm-5">
                <div class = "checkbox">	
                    <label>
                        Status
                    </label>
                </div>
            </div>

            <div class="col-sm-3" >
                <div class = "checkbox">
                    <label>
                        {{Form::checkbox('status1','on', true,array('class'=>'ace ace-switch ace-switch-6'))}}
                        <span class="lbl"></span>
                    </label>
                   
               </div>

            </div>
        </div>
	</div>
		<div class = "col-md-3" >
			<label class="col-sm-4 control-label" for="role_type"> Role Type  </label>
			<div class="col-sm-8">
				<?php $array_role = array(""=>'Select Role'); ?>					
				@forelse($roles as $role)
					<?php
					if($role['role_id'] == 1 || $role['role_id'] == 2 || $role['role_id'] == 3){
						continue;
					}
					$array_role[$role['role_id']] = $role['role'];
					?>
				@empty
				@endforelse
				{{Form::select('role_type1', $array_role,null,array('class'=>'form-control','id'=>'role_type'))}}
				@if($errors->has('role_type1'))
    			<div class="error text-danger">{{ $errors->first('role_type1') }}</div>
				@endif
			</div>

	</div>
	<div class = "col-md-3 countryDiv" >
		
							

	</div>
	<div class = "col-md-4 districtOperationDiv">
			
							

	</div>
	</div>
	@endif
	<div class = "input_fields_wrap"></div>
	<div class = "row">
		<div class = "col-md-10"></div>
		<div class = "col-md-2">
			{{Form::button('Add More User Role',array('id'=>'add_field_button','class'=>'btn btn-info'))}}
		</div>
		<div class = "col-sm-4"></div>
		<div class = "col-sm-4">
			@csrf
			{{Form::submit('Save',array('name'=>'save_user','class'=>'btn btn-success'))}}
				&nbsp;&nbsp;

			@if($form_action == '/updateUserByCountryManager')
				<a href = "/countryManager/viewUsers" class = "btn btn-secondary"> Back</a>
			@else	
			{{Form::reset('Cancel',array('name'=>'cancel','class'=>'btn btn-secondary'))}}
			@endif
		</div>
		<div class = "col-sm-4"></div>
	</div>
</div>
{{ Form::close() }}
				
				<div id = "roleTypeJsonData" style = "display:none"><?php echo json_encode($array_role);	?>
				</div>
@endsection


@section('footer-section')
	@include('countryManager.includes.footer')
@endsection

@section('page_related_scripts')
<script type="text/javascript">
	jQuery(function($) {

		$("form").submit(function(){
  			$("#overlay").show();
		});	
		$(document).on('change','#role_type',function(){
			var roleType = $(this).children('option:selected').val();
			var parent = $(this).parent().parent().parent().attr('id');
			if(roleType != "" || roleType != null){
				$.ajax({
					url:'/countryManager/getCountryByRoleType',
					type:'POST',
					data:{roleType:roleType,_token:'{{csrf_token()}}',parent:parent},
					success:function(data){
						if(data != 0){
							$("#"+parent+" .countryDiv").html(data)
						}
						else{
							$("#"+parent+" .countryDiv").html(" ")	
						}

					}
				});
			}
		});

		$(document).on('change','#country',function(){
			var country = $(this).children('option:selected').val();
			
			var parent = $(this).parent().parent().parent().attr('id');
			
			var roleType = $("#"+parent+" #role_type option:selected").val();
		
			if(country != 0){

				
				$.ajax({
					url:'/countryManager/getDistrictOperationByCountryId',
					type:'POST',
					data:{country:country,_token:'{{csrf_token()}}',roleType:roleType,parent:parent},
					success:function(data){
						if(data != 0){
							$("#"+parent+" .districtOperationDiv").html(data)
						}
						else{
							$("#"+parent+" .districtOperationDiv").html(" ")	
						}

					},

				});
			}
			else{

			}
		});

	var max_fields      = 1/0;
	var wrapper   		= $(".input_fields_wrap");
	var add_button      = $("#add_field_button"); 
	
/*	var row_counter = $('#row_counter').html();
	console.log(row_counter);
	if(row_counter != undefined || row_counter != null){
		var x = row_counter-1;
		}
	else{
		var x = 1;
	}*/	
	var form_action = $('.form-horizontal').attr('action');
	if(form_action == 'http://localhost:8000/updateUserByCountryManager'){	
	 var x = 0;
	}
	else{
		x = 1;
  	}

	$(add_button).click(function(){ 
		
		
		if(form_action == 'http://localhost:8000/updateUserByCountryManager'){
		var countryData = $("#countryJsonData").html();
		var roleTypeData = $("#roleTypeJsonData").html();

		var roleTypes = "<option value = ''> Select Role </option>";
	
		$.each(JSON.parse(roleTypeData),function(index,value){
			if(index != ''){
				roleTypes += "<option value = '"+index+"'>"+value+"</option>";	
			}	
			
			
		} )
		if(x < max_fields){ 
			x++; 
			$(wrapper).append("<div class = 'row' id = 'parent_new"+x+"'><div class = 'col-md-2' style = 'width:150px'><div class = 'form-group'><div class='col-sm-5'><div class = 'checkbox'><label>Status</label></div></div><div class='col-sm-3'><div class ='checkbox'><label><input checked name = 'statusnew"+x+"' type = 'checkbox' class = 'ace ace-switch ace-switch-6'><span class='lbl' ></span></label></div></div> </div> </div> <div class = 'col-md-3'><label class ='col-sm-4 control-label' for='form-field-1'> Role Type</label> <div class='col-sm-8'><select class = 'form-control role_type' name = 'role_typenew"+x+"' id = 'role_type'>"+roleTypes+"</select> </div></div> <div class = 'col-md-3 countryDiv'></div> <div class = 'col-md-4 districtOperationDiv'></div><a id = 'remove_field' class = 'btn btn-danger btn-xs'><i class = 'fa fa-trash'></i></a> </div>"); 

		}
		}
		else{
		var countryData = $("#countryJsonData").html();
		var roleTypeData = $("#roleTypeJsonData").html();

		var roleTypes = "<option value = ''> Select Role </option>";
	
		$.each(JSON.parse(roleTypeData),function(index,value){
			if(index != ''){
				roleTypes += "<option value = '"+index+"'>"+value+"</option>";	
			}	
			
			
		} )
		if(x < max_fields){ 
			x++; 
			$(wrapper).append("<div class = 'row' id = 'parent_"+x+"'><div class = 'col-md-2' style = 'width:150px'><div class = 'form-group'><div class='col-sm-5'><div class = 'checkbox'><label>Status</label></div></div><div class='col-sm-3'><div class ='checkbox'><label><input checked name = 'status"+x+"' type = 'checkbox' class = 'ace ace-switch ace-switch-6'><span class='lbl' ></span></label></div></div> </div> </div> <div class = 'col-md-3'><label class ='col-sm-4 control-label' for='form-field-1'> Role Type</label> <div class='col-sm-8'><select class = 'form-control role_type' name = 'role_type"+x+"' id = 'role_type'>"+roleTypes+"</select> </div></div> <div class = 'col-md-3 countryDiv'></div> <div class = 'col-md-4 districtOperationDiv'></div><a id = 'remove_field' class = 'btn btn-danger btn-xs'><i class = 'fa fa-trash'></i></a> </div>"); 

		}
		}

	});
	
		

	$(wrapper).on("click","#remove_field", function(){ 
		 $(this).parent('div').remove(); x--;
	});

			
			})
		</script>
@endsection
