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
					<li class="{{(Request::segment(1) == 'partnerManager' && Request::segment(2) == null)?'active':''}}">
						<a href="{{url('/partnerManager')}}">
							<i class="menu-icon fa fa-tachometer"></i>
							<span class="menu-text"> Dashboard </span>
						</a>

						<b class="arrow"></b>
					</li>
					
					<!-- Manage Project Instances -->
					<li class="{{(Request::segment(1) == 'partnerManager') && (Request::segment(2) == 'viewAssignedProjectInstances' || Request::segment(2) == 'viewProjectInstanceDetail'  || Request::segment(2) == 'viewAssignedProjectInstanceDetail')?'active':''}}">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-building"></i>
							<span class="menu-text">
								Project Instance 
							</span>

							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>

						<ul class="submenu">
							
							<li class="{{(Request::segment(2) == 'viewAssignedProjectInstances' || Request::segment(2) == 'viewProjectInstanceDetail'||Request::segment(2) == 'viewAssignedProjectInstanceDetail')?'active':''}}">
								<a href="{{url('partnerManager/viewAssignedProjectInstances')}}">
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
						<a href="{{url('partnerManager/profile/'.Crypt::encryptString(Auth::user()->user_id))}}">
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