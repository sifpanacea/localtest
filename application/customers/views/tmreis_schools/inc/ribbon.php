	<!-- RIBBON -->
	<div id="ribbon">

		<span class="ribbon-button-alignment"> <span id="refresh" class="btn btn-ribbon" data-title="refresh"  rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Warning! This will reset all your widget settings." data-html="true"><i class="fa fa-refresh"></i></span> </span>

		<!-- breadcrumb -->
		<ol class="breadcrumb">
			<?php
				foreach ($breadcrumbs as $display => $url) {
					$breadcrumb = $url != "" ? '<a class="menu-links" href="'.$url.'tmreis_schools/to_dashboard">'.$display.'</a>' : $display;
					echo '<li>'.$breadcrumb.'</li>';
				}
				echo '<li>'.$page_title.'</li>';
			?>
		</ol>
		<!-- end breadcrumb -->

		<!-- You can also add more buttons to the
		ribbon for further usability

		<span class="ribbon-button-alignment pull-right">
		<span class="label label-success"><a href="http://www.sifhyd.org" target="_blank"><font color="white">Conceptualised and Implemented By Synergy India Foundation</font></a></span>
		<span id="">
		<img src="https://mednote.in/PaaS/bootstrap/dist/img/synergy_logo.jpg" width="50" height="25" alt="SIF">
		</span>
		
		<!--<span id="add" class="btn btn-ribbon hidden-xs" data-title="add"><i class="fa-plus"></i> Add</span>
		<span id="search" class="btn btn-ribbon" data-title="search"><i class="fa-search"></i> <span class="hidden-mobile">Search</span></span>
		</span>--> 
	</div>
	
	<!-- END RIBBON -->