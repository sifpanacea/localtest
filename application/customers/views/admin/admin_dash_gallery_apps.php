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
  				<div class="panel-heading"><strong><?php echo lang('admin_dash_list_apps');?></strong>
  					<!--<button type="button" class="close" data-dismiss="panel" aria-hidden="true">&times;</button>-->
  					<div class="box-icon" style="float:right">
						<a href="#" class="btn-minimize"><i class="icon-chevron-up"></i></a>
						<a href="#" class="btn-close"><i class="glyphicon glyphicon-remove"></i></a>
					</div>
  				</div>
  				<div class="box-content" >
					<table  border="0" cellpadding="10" cellspacing="60%">
					<tr>
						<th><?php echo lang('index_app_th');?></th>
						<!--<th><?php //echo lang('index_status_th');?></th> -->
						<th><?php echo lang('index_action_th');?></th>
					</tr>
					<?php $a = 0;?>
					<?php if ($galleryapps): ?>
					<?php foreach ($galleryapps as $app):?>
					<tr>
						<td><?php echo $app['app_name'];?></td>
						
						
						<td><?php echo $app['shared_by'];?></td>
					</tr>
					<?php $a++;?>
					<?php endforeach;?>
					<?php else: ?>
        			<p>
          				<?php echo lang('admin_no_apps');?>
        			</p>
        			<?php endif ?>
					</table>
  				</div>
			</div>					
		</div><hr>
		
        <div class="sortable row-fluid">
        	
			<a class="quick-button span2" href="<?php echo(URL.'dashboard/user')?>">
				<i class="icon-group"></i>
				<p>Users</p>
				<span class="notification"><?php echo count($users);?></span>
			</a>
            
          
			<a class="quick-button span2" href="<?php echo (URL.'dashboard/apps')?>">
				<i class="icon-comments-alt"></i>
				<p>Applications</p>
				<span class="notification green"><?php echo count($apps);?></span>
			</a>
           
          
			<a class="quick-button span2" href="<?php echo (URL.'dashboard/docs')?>">
				<i class="icon-shopping-cart"></i>
				<p>Documents</p>
			</a>
        
			<a class="quick-button span2">
				<i class="icon-barcode"></i>
				<p>Analytics</p>
			</a>
           
			<a class="quick-button span2">
				<i class="icon-briefcase"></i>
				<p>Gallery</p>
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
