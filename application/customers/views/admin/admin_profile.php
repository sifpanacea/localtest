<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>User Profile</title>
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
		<div>
			<ul class="breadcrumb">
				<li>
					<?php echo anchor('auth/index', lang('common_admin_dash_link'))?>
				</li>
			</ul>
		</div>
		<?php if($message) { ?>
        <div class="row-fluid">		
			<div class="alert alert-dismissable">
  				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  				<strong><?php echo lang('common_message');?> </strong> <div id="infoMessage"><?php echo $message;?></div>
			</div>				
		</div><?php } ?>
		<div class="row-fluid">
			<div class="panel panel-default">
  				<!--<div class="panel-heading"><strong><?php echo "Details";?></strong>
  					<!--<button type="button" class="close" data-dismiss="panel" aria-hidden="true">&times;</button>-->
  					<!--<div class="box-icon" style="float:right">
						<a href="#" class="btn-minimize"><i class="icon-chevron-up"></i></a>
						<a href="#" class="btn-close"><i class="glyphicon glyphicon-remove"></i></a>
					</div>-->
  				</div>
  				<div class="box-content" >
				    <p class="pull-right"><?php echo anchor("dashboard/edit_profile/".$profile_data[0]['company_name'], 'Edit Profile') ;?></p>
					<table border="0" cellpadding="10" cellspacing="60%">
					<?php $u = 0;?>
					<?php foreach ($profile_data as $data):?>
					<tr>
						<th><?php echo lang('index_company_th');?></th><td><?php echo $data['company_name'];?></td>
					</tr>
                    <tr>					
						<th><?php echo lang('index_company_address_th');?></th><td><?php echo $data['company_address'];?></td>
					</tr>
                     <tr>					
						<th><?php echo lang('index_company_website_th');?></th><td><?php echo $data['company_website'];?></td>
					</tr>
					<tr>
						<th><?php echo lang('index_username_th');?></th><td><?php echo $data['username'];?></td>
					</tr>	
					<tr>
						<th><?php echo lang('index_contactp_th');?></th><td><?php echo $data['contact_person'];?></td>
					</tr>
                    <tr>					
						<th><?php echo lang('index_email_th');?></th><td><?php echo $data['email'];?></td>
					</tr>
                    <tr>					
						<th><?php echo lang('index_mobile_th');?></th><td><?php echo $data['mobile_number'];?></td>
					</tr>	
					<tr>					
						<th><?php echo lang('index_plan_th');?></th><td><?php echo $data['plan'];?></td>
					</tr>
					<tr>					
						<th><?php echo lang('index_registered_th');?></th><td><?php echo $data['registered_on'];?></td>
					</tr>
					<tr>					
						<th><?php echo lang('index_plan_expiry_th');?></th><td><?php echo $data['plan_expiry'];?></td>
					</tr>				
					<?php $u++;?>
					<?php endforeach;?>
					</table>
  				</div>
			</div>					
		</div>
		
       
		<!-- end: Content -->
	</div><!--/#content.span10-->
</div><!--/fluid-row-->
				
<div class="clearfix"></div>
		
		<footer>
		<?php echo lang('common_copy_rights');?>
		</footer>
				
	</div><!--/.fluid-container-->
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <script src="<?php echo(JS.'jquery.js'); ?>"></script>
    <script src="<?php echo(JS.'adminheader.js'); ?>"></script>
    <?php
       $this->load->view('includes/admin_footer');
    ?>
    
       
  </body>
</html>
