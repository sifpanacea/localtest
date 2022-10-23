	<!-- RIBBON -->
	<div id="ribbon">

		<span class="ribbon-button-alignment"> <span id="refresh" class="btn btn-ribbon" data-title="refresh"  rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Warning! This will reset all your widget settings." data-html="true"><i class="fa fa-refresh"></i></span> </span>

		<!-- breadcrumb -->
		<ol class="breadcrumb">
			<?php
				foreach ($breadcrumbs as $display => $url) {
					$breadcrumb = $url != "" ? '<a href="'.$url.'">'.$display.'</a>' : $display;
					echo '<li>'.$breadcrumb.'</li>';
				}
				echo '<li>'.$page_title.'</li>';
			?>
		</ol>
		<!-- end breadcrumb -->

		<!-- You can also add more buttons to the
		ribbon for further usability

		Example below:

		<span class="ribbon-button-alignment pull-right">
		<span id="search" class="btn btn-ribbon hidden-xs" data-title="search"><i class="fa-grid"></i> Change Grid</span>
		<span id="add" class="btn btn-ribbon hidden-xs" data-title="add"><i class="fa-plus"></i> Add</span>
		<span id="search" class="btn btn-ribbon" data-title="search"><i class="fa-search"></i> <span class="hidden-mobile">Search</span></span>
		</span> -->

				
					<div class="pull-right btn-group dropdown dropdown-large">
													<button class="btn dropdown-toggle btn-xs btn-default btn-circle" data-toggle="dropdown" style="margin-top:5px;">
										 <i class="fa fa-question"></i>
									</button>
													<ul class="dropdown-menu">
														<li class="dropdown-header">
												Dashboard
											</li>
														<li>
															<a href="<?php echo URL."help/user_inbox"?>"><span><i class="icon-leaf"></i>Inbox</span></a>
														</li>
														<li>
															<a href="<?php echo URL."help/user_apps"?>"><span><i class="icon-leaf"></i>Applications</span></a>
														</li>
														<li>
															<a href="<?php echo URL."help/user_installed_apps"?>"><span><i class="icon-leaf"></i>Installed Apps</span></a>
														</li>
														<li>
															<a href="<?php echo URL."help/search_documents"?>"><span><i class="icon-leaf"></i>Search</span></a>
														</li>
														<li class="divider"></li>
														<li class="dropdown-header">
												Docs
											</li>
														<li>
															<a href="<?php echo URL."help/user_access_documents"?>"><span><i class="icon-leaf"></i> Accessing Documents </span></a>
														</li>
														<li>
															<a href="<?php echo URL."help/disapprove_documents"?>"><span><i class="icon-leaf"></i> Disapproving Documents </span></a>
														</li>
														<li class="divider"></li>
														<li class="dropdown-header">
												          Profile
											            </li>
														<li>
															<a href="<?php echo URL."help/user_profile"?>"><span><i class="icon-leaf"></i> Profile </span></a>
														</li>
														<li>
															<a href="<?php echo URL."help/edit_user_profile"?>"><span><i class="icon-leaf"></i> Editing Profile </span></a>
														</li>
														<li class="divider"></li>
														<li>
															<a href="<?php echo URL."help/user_change_password"?>"><span><i class="icon-leaf"></i> Changing Password </span></a>
														</li>
													</ul>
												</div><!-- /btn-group -->
	</div>
	
	<!-- END RIBBON -->