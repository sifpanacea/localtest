<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Admin Dashboard</title>
	<?php
        $this->load->view('includes/admin_css');
    ?>
    
    <link href="<?php echo(CSS.'admin_dash.css'); ?>" rel="stylesheet" type="text/css" />
    
  </head>
<body>
			<!-- Includes -->
            <?php
            $this->load->view('includes/admin_header');
            ?>
	<div id="content" class="span10">
	<!-- start: Content -->
		<div><hr>
			<ul class="breadcrumb">
				<li>
					<?php echo anchor('auth/index', lang('common_admin_dash_link'))?>
				</li>
			</ul><hr>
		</div>
		<?php if($message) { ?>
        <div class="row-fluid">		
			<div class="alert alert-dismissable">
  				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  				<strong><?php echo lang('common_message');?> </strong> <div id="infoMessage"><?php echo $message;?></div>
			</div>				
		</div><hr><?php } ?>
		<div class="row-fluid">
			<div class="panel panel-default">
  				<div class="panel-heading"><strong><?php echo lang('admin_dash_list_users');?></strong>
  					<!--<button type="button" class="close" data-dismiss="panel" aria-hidden="true">&times;</button>-->
  					<div class="box-icon" style="float:right">
						<a href="#" class="btn-minimize"><i class="icon-chevron-up"></i></a>
						<a href="#" class="btn-close"><i class="glyphicon glyphicon-remove"></i></a>
					</div>
  				</div>
  				<div class="box-content" >
					<table border="0" cellpadding="10" cellspacing="60%">
					<tr>
						<th><?php echo lang('index_fname_th');?></th>
						<th><?php echo lang('index_lname_th');?></th>
						<th><?php echo lang('index_email_th');?></th>
						<th><?php echo lang('index_groups_th');?></th>
						<th><?php echo lang('index_status_th');?></th>
						<th><?php echo lang('index_action_th');?></th>
					</tr>
					<?php $u = 0;?>
					<?php foreach ($users as $user):?>
					<tr>
						<td><?php echo $user->first_name;?></td>
						<td><?php echo $user->last_name;?></td>
						<td><?php echo $user->email;?></td>
						<td>
							<?php foreach ($user->groups as $group):?>
							<?php echo anchor("auth/edit_group/".$group->id, $group->name) ;?><br />
                			<?php endforeach?>
						</td>
						<td>
							<?php echo ($user->active) ? anchor("auth/deactivate/".$user->id, lang('index_active_link')) : anchor("auth/activate/". $user->id, lang('index_inactive_link'));?>
                        </td>
						<td><?php echo anchor("auth/edit_user/".$user->id, 'Edit User') ;?></td>
                        <td><?php echo anchor("auth/delete_user/".$user->id, 'Delete User') ;?></td>
					</tr>
					<?php $u++;?>
					<?php endforeach;?>
					</table>
  				</div>
			</div>					
		</div><hr>
		
        <div class="sortable row-fluid">
        	
			<a class="quick-button span2" href="<?php echo (URL.'auth/user')?>">
				<i class="icon-group"></i>
				<p>Users</p>
				<span class="notification"><?php echo $u;?></span>
			</a>
            
          
			<a class="quick-button span2" href="<?php echo (URL.'auth/apps')?>">
				<i class="icon-comments-alt"></i>
				<p>Applications</p>
				<span class="notification green"><?php if(!empty($apps)) {?><?php echo count($apps);?><?php } else {?><?php echo "0";?><?php }?></span>
			</a>
           
          
			<a class="quick-button span2" href="<?php echo (URL.'auth/docs')?>">
				<i class="icon-shopping-cart"></i>
				<p>Documents</p>
			</a>
        
			<a class="quick-button span2">
				<i class="icon-barcode"></i>
				<p>Analytics</p>
			</a>
           
			<a class="quick-button span2">
				<i class="icon-envelope"></i>
				<p>Messages</p>
			</a>
            
			<a class="quick-button span2">
				<i class="icon-calendar"></i>
				<p>Calendar</p>
				<span class="notification red">68</span>
			</a>
            
         </div>
		<hr>
		<!-- end: Content -->
	</div><!--/#content.span10-->
</div><!--/fluid-row-->
				
		<div class="modal hide fade" id="myModal">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">Ã—</button>
				<h3>Settings</h3>
			</div>
			<div class="modal-body">
				<p>Here settings can be configured...</p>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn" data-dismiss="modal">Close</a>
				<a href="#" class="btn btn-primary">Save changes</a>
			</div>
		</div>
        <!--<hr>-->
		
		<div class="clearfix"></div>
		
		<footer>
		<?php echo lang('common_copy_rights');?>
		</footer>
				
	</div><!--/.fluid-container-->
    <!-- Bootstrap core JavaScript
    ================================================== -->
    
    <?php
       $this->load->view('includes/admin_footer');
    ?>
    
       
  </body>
</html>
