<div id="sidebar" class="sidebar responsive">
				<script type="text/javascript">
					try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
				</script>

				<div class="sidebar-shortcuts" id="sidebar-shortcuts">
					<div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
						<button class="btn btn-success">
							<i class="ace-icon fa fa-signal"></i>
						</button>

						<button class="btn btn-info">
							<i class="ace-icon fa fa-pencil"></i>
						</button>

						<!-- #section:basics/sidebar.layout.shortcuts -->
						<button class="btn btn-warning">
							<i class="ace-icon fa fa-users"></i>
						</button>

						<button class="btn btn-danger">
							<i class="ace-icon fa fa-cogs"></i>
						</button>

						<!-- /section:basics/sidebar.layout.shortcuts -->
					</div>

					<div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
						<span class="btn btn-success"></span>

						<span class="btn btn-info"></span>

						<span class="btn btn-warning"></span>

						<span class="btn btn-danger"></span>
					</div>
				</div><!-- /.sidebar-shortcuts -->

				<ul class="nav nav-list">
					<li class="{{(Request::segment(1) == 'operationManager' && Request::segment(2) == null)?'active':''}}">
						<a href="{{url('/operationManager')}}">
							<i class="menu-icon fa fa-tachometer"></i>
							<span class="menu-text"> Dashboard </span>
						</a>

						<b class="arrow"></b>
					</li>
					<?php
						$getUri = Route::current()->uri();
						$url = explode("/",$getUri);

					?>
					<li class = "<?php if(($getUri == 'operationManager/viewUsers') || ($getUri == 'operationManager/addUserForm') || (isset($url[1]) && $url[1] == 'editUser')){
						echo 'active';
					}?>">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-users"></i>
							<span class="menu-text">
								Manage Users
							</span>

							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>

						<ul class="submenu">
							
							<li class="">
								<a href="/operationManager/addUserForm">
									<i class="menu-icon fa fa-caret-right"></i>
									Add User
								</a>

								<b class="arrow"></b>
							</li>

							<li class="">
								<a href="/operationManager/viewUsers">
									<i class="menu-icon fa fa-caret-right"></i>
									View Users
								</a>

								<b class="arrow"></b>
							</li>

						</ul>
					</li>

					<!-- Manage Project Instances-Asad -->
					<li class="{{(Request::segment(1) == 'operationManager') && (Request::segment(2) == 'viewAssignedProjectInstances' || Request::segment(2) == 'viewProjectInstanceDetail' || Request::segment(2) == 'viewAssignedProjectInstanceDetail')?'active':''}}">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-building"></i>
							<span class="menu-text">
								Project Instance 
							</span>

							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>

						<ul class="submenu">
							
							<li class="{{(Request::segment(2) == 'viewAssignedProjectInstances' || Request::segment(2) == 'viewProjectInstanceDetail' || Request::segment(2) == 'viewAssignedProjectInstanceDetail')?'active':''}}">
								<a href="{{url('operationManager/viewAssignedProjectInstances')}}">
									<i class="menu-icon fa fa-caret-right"></i>
									Assigned Project Instances
								</a>
								<b class="arrow"></b>
							</li>


						</ul>
					</li>
					<!--end-->

					<!-- Switch Role-Asad -->
					<li class="">
						<a href="{{url('operationManager/profile/'.Crypt::encryptString(Auth::user()->user_id))}}">
							<i class="menu-icon fa fa-key"></i>
							<span class="menu-text"> Switch Roles </span>
						</a>
						<b class="arrow"></b>
					</li>
					<!--end-->
					
				</ul><!-- /.nav-list -->

				<!-- #section:basics/sidebar.layout.minimize -->
				<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
					<i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
				</div>

				<!-- /section:basics/sidebar.layout.minimize -->
				<script type="text/javascript">
					try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
				</script>
			</div>