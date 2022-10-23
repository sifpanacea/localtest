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
					<?php echo anchor('dashboard/to_dashboard', lang('common_admin_dash_link'))?>
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
        <div class="sortable row-fluid">
        	
			<a class="quick-button span2" href="<?php echo (URL.'auth/customers')?>">
				<i class="icon-group"></i>
				<p>Customers</p>
				<span class="notification"><?php if(!empty($customers)) {?><?php echo $customers;?><?php } else {?><?php echo "0";?><?php }?></span>
			</a>
            
          <a class="quick-button span2">
				<i class="icon-barcode"></i>
				<p>Analytics</p>
			</a>
           
			<a class="quick-button span2">
				<i class="icon-envelope"></i>
				<p>Messages</p>
			</a>
     </div>
		<hr>
		<!--end: Content -->
	</div><!--/#content.span10-->
</div><!--/fluid-row-->
				
		
		
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
