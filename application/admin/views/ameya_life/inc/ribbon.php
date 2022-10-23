	<!-- RIBBON -->
	<div id="ribbon" style="margin-top: 60px;">

		<span class="ribbon-button-alignment"> <span id="refresh" class="btn btn-ribbon" data-title="refresh"  rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Warning! This will reset all your widget settings." data-html="true"><i class="fa fa-refresh"></i></span> </span>

		<!-- breadcrumb -->
		<ol class="breadcrumb">
			<?php
				foreach ($breadcrumbs as $display => $url) {
					$breadcrumb = $url != "" ? '<a class="menu-links" href="'.$url.'panacea_mgmt/to_dashboard">'.$display.'</a>' : $display;
					echo '<li>Home</li>';
				}
				echo '<li>'.$page_title.'</li>';
			?>
		</ol>
		<!-- end breadcrumb -->

		<!-- You can also add more buttons to the
		ribbon for further usability Example below:-->

		<span class="ribbon-button-alignment pull-right">
		<!--<span class="label label-success"><a href="http://www.sifhyd.org" target="_blank"><font color="black">Knowledge & Implementation Partner - Synergy India Foundation</font></a></span>-->
		<a class="partner_info hide" href="http://www.sifhyd.org" target="_blank"><font color="">Knowledge & Implementation Partner</font></a>
		<span id="" class="hide">
		<img src="https://mednote.in/PaaS/bootstrap/dist/img/synergy_logo.jpg" width="50" height="25" alt="SIF">
		</span>
		
		<!--<span id="add" class="btn btn-ribbon hidden-xs" data-title="add"><i class="fa-plus"></i> Add</span>
		<span id="search" class="btn btn-ribbon" data-title="search"><i class="fa-search"></i> <span class="hidden-mobile">Search</span></span>-->
		</span> 
	</div>
	
	<!-- END RIBBON -->