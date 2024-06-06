<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>.:: Hidaya Trust - PDMS ::.</title>

		<meta name="description" content="overview &amp; stats" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="{{ asset( '/' ) }}assets/css/bootstrap.css" />
		<link rel="stylesheet" href="{{ asset( '/' ) }}assets/css/font-awesome.css" />

		<!-- page specific plugin styles -->
		@yield('page_sepecific_plugin')
		<!-- text fonts -->
		<link rel="stylesheet" href="{{ asset( '/' ) }}assets/css/ace-fonts.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="{{ asset( '/' ) }}assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

		<link rel="stylesheet" href="{{asset('/')}}assets/css/jquery-ui.custom.css" />
        <link rel="stylesheet" href="{{asset('/')}}assets/css/jquery-ui.css" />

        <link rel="stylesheet" href="{{ asset( '/' ) }}assets/css/datepicker.css" />
        <link rel="stylesheet" href="{{ asset( '/' ) }}assets/css/bootstrap-datetimepicker.css" />


		<!--[if lte IE 9]>
			<link rel="stylesheet" href="../assets/css/ace-part2.css" class="ace-main-stylesheet" />
		<![endif]-->

		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="../assets/css/ace-ie.css" />
		<![endif]-->

		<!-- inline styles related to this page -->

		<!-- ace settings handler -->
		<script src="{{ asset( '/' ) }}assets/js/ace-extra.js"></script>

		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

		<!--[if lte IE 8]>
		<script src="../assets/js/html5shiv.js"></script>
		<script src="../assets/js/respond.js"></script>
		<![endif]-->
	</head>

	<body class="no-skin">
		<!-- #section:basics/navbar.layout -->
		@yield('navbar-section')

		<!-- /section:basics/navbar.layout -->
		<div class="main-container" id="main-container">
			<script type="text/javascript">
				try{ace.settings.check('main-container' , 'fixed')}catch(e){}
			</script>

			<!-- #section:basics/sidebar -->
			@yield('sidebar-section')			

			<!-- /section:basics/sidebar -->
			<div class="main-content">
				<div class="main-content-inner">
					<!-- #section:basics/content.breadcrumbs -->
					@yield('breadcrumb-section')					

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">
						<!-- #section:settings.box -->
						@yield('settingbox-section')

						<!-- /.ace-settings-container -->

						<!-- /section:settings.box -->
						
						<div class="page-header">
							@yield('pageheader-section')
						</div>						
						<!-- /.page-header -->

						
								<!-- PAGE CONTENT BEGINS -->
								@yield('page-content')

								<!-- PAGE CONTENT ENDS -->
							
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

			@yield('footer-section')

			<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
				<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
			</a>
		</div><!-- /.main-container -->

		<!-- basic scripts -->

		<!--[if !IE]> -->
		<script type="text/javascript">
			window.jQuery || document.write("<script src='{{ asset( '/' ) }}assets/js/jquery.js'>"+"<"+"/script>");
		</script>

		<!-- <![endif]-->

		<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='../assets/js/jquery1x.js'>"+"<"+"/script>");
</script>
<![endif]-->
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='{{ asset( '/' ) }}assets/js/jquery.mobile.custom.js'>"+"<"+"/script>");
		</script>
		<script src="{{ asset( '/' ) }}assets/js/bootstrap.js"></script>

		<!-- page specific plugin scripts -->
		<!-- page specific plugin scripts -->
		<script src="{{ asset( '/' ) }}assets/js/jquery-ui.js"></script>
		<script src="{{ asset( '/' ) }}assets/js/jquery.ui.touch-punch.js"></script>
			
		<!--[if lte IE 8]>
		  <script src="../assets/js/excanvas.js"></script>
		<![endif]-->
		<script src="{{ asset( '/' ) }}assets/js/dataTables/jquery.dataTables.js"></script>
		<script src="{{ asset( '/' ) }}assets/js/dataTables/jquery.dataTables.bootstrap.js"></script>
		<script src="{{ asset( '/' ) }}assets/js/dataTables/extensions/TableTools/js/dataTables.tableTools.js"></script>
		<script src="{{ asset( '/' ) }}assets/js/dataTables/extensions/ColVis/js/dataTables.colVis.js"></script>
		<script src="{{ asset( '/' ) }}assets/js/jquery-ui.custom.js"></script>
		<script src="{{ asset( '/' ) }}assets/js/jquery.ui.touch-punch.js"></script>
		<script src="{{ asset( '/' ) }}assets/js/jquery.easypiechart.js"></script>
		<script src="{{ asset( '/' ) }}assets/js/jquery.sparkline.js"></script>
		<script src="{{ asset( '/' ) }}assets/js/flot/jquery.flot.js"></script>
		<script src="{{ asset( '/' ) }}assets/js/flot/jquery.flot.pie.js"></script>
		<script src="{{ asset( '/' ) }}assets/js/flot/jquery.flot.resize.js"></script>

		<!-- ace scripts -->
		<script src="{{ asset( '/' ) }}assets/js/ace/elements.scroller.js"></script>
		<script src="{{ asset( '/' ) }}assets/js/ace/elements.colorpicker.js"></script>
		<script src="{{ asset( '/' ) }}assets/js/ace/elements.fileinput.js"></script>
		<script src="{{ asset( '/' ) }}assets/js/ace/elements.typeahead.js"></script>
		<script src="{{ asset( '/' ) }}assets/js/ace/elements.wysiwyg.js"></script>
		<script src="{{ asset( '/' ) }}assets/js/ace/elements.spinner.js"></script>
		<script src="{{ asset( '/' ) }}assets/js/ace/elements.treeview.js"></script>
		<script src="{{ asset( '/' ) }}assets/js/ace/elements.wizard.js"></script>
		<script src="{{ asset( '/' ) }}assets/js/ace/elements.aside.js"></script>
		<script src="{{ asset( '/' ) }}assets/js/ace/ace.js"></script>
		<script src="{{ asset( '/' ) }}assets/js/ace/ace.ajax-content.js"></script>
		<script src="{{ asset( '/' ) }}assets/js/ace/ace.touch-drag.js"></script>
		<script src="{{ asset( '/' ) }}assets/js/ace/ace.sidebar.js"></script>
		<script src="{{ asset( '/' ) }}assets/js/ace/ace.sidebar-scroll-1.js"></script>
		<script src="{{ asset( '/' ) }}assets/js/ace/ace.submenu-hover.js"></script>
		<script src="{{ asset( '/' ) }}assets/js/ace/ace.widget-box.js"></script>
		<script src="{{ asset( '/' ) }}assets/js/ace/ace.settings.js"></script>
		<script src="{{ asset( '/' ) }}assets/js/ace/ace.settings-rtl.js"></script>
		<script src="{{ asset( '/' ) }}assets/js/ace/ace.settings-skin.js"></script>
		<script src="{{ asset( '/' ) }}assets/js/ace/ace.widget-on-reload.js"></script>
		<script src="{{ asset( '/' ) }}assets/js/ace/ace.searchbox-autocomplete.js"></script>

		
		<script src="{{ asset( '/' ) }}assets/js/date-time/bootstrap-datepicker.js"></script>
		<script src="{{ asset( '/' ) }}assets/js/date-time/moment.js"></script>
		<script src="{{ asset( '/' ) }}assets/js/date-time/bootstrap-datetimepicker.js"></script>
		



		<!-- inline scripts related to this page -->
		
		@yield('page_related_scripts')	
		<!-- the following scripts are used in demo only for onpage help and you don't need them -->
		<link rel="stylesheet" href="{{ asset( '/' ) }}assets/css/ace.onpage-help.css" />
		<link rel="stylesheet" href="{{ asset( '/' ) }}docs/assets/js/themes/sunburst.css" />

		<script type="text/javascript"> ace.vars['base'] = '..'; </script>
		<script src="{{ asset( '/' ) }}assets/js/ace/elements.onpage-help.js"></script>
		<script src="{{ asset( '/' ) }}assets/js/ace/ace.onpage-help.js"></script>
		<script src="{{ asset( '/' ) }}docs/assets/js/rainbow.js"></script>
		<script src="{{ asset( '/' ) }}docs/assets/js/language/generic.js"></script>
		<script src="{{ asset( '/' ) }}docs/assets/js/language/html.js"></script>
		<script src="{{ asset( '/' ) }}docs/assets/js/language/css.js"></script>
		<script src="{{ asset( '/' ) }}docs/assets/js/language/javascript.js"></script>
	</body>
</html>
