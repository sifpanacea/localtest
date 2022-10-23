<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Edit Profile</title>
 	<?php
         $this->load->view('includes/admin_css');
    ?>
	<link href="<?php echo(CSS.'admin_dash.css'); ?>" rel="stylesheet" type="text/css" />
  </head>
<body>
            <?php
            $this->load->view('includes/admin_header');
            ?>
	<div id="content" class="span10">
	<!-- start: Content -->
		<div><hr>
			<ul class="breadcrumb">
				<li>
				<?php echo anchor('auth/index', lang('common_admin_dash_link'))?><span class="divider"></span>
				</li>
				<li>
				<?php echo lang('edit_admin_profile_nav');?>
				</li>
			</ul><hr>
		</div>
		<?php if($message) { ?>
        <div class="row-fluid">		
			<div class="alert alert-dismissable">
  				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  				<strong><?php echo lang('common_message');?> <div id="infoMessage"><?php echo $message;?></div></strong> 
			</div>				
		</div><hr><?php } ?>
		<div class="row-fluid">
			<div class="panel panel-default">
  				<div class="panel-heading"><strong><?php echo lang('edit_admin_profile_heading');?></strong>
  					<!--<button type="button" class="close" data-dismiss="panel" aria-hidden="true">&times;</button>-->
  					<div class="box-icon" style="float:right">
						<a href="#" class="btn-minimize"><i class="icon-chevron-up"></i></a>
						<a href="#" class="btn-close"><i class="glyphicon glyphicon-remove"></i></a>
					</div>
  				</div>
  				<div class="box-content">

<?php echo form_open(uri_string());?>

      <p>
            <?php echo lang('edit_profile_company_name_label', 'company_name');?>
            <?php echo form_input($companyname);?>
      </p>

      <p>
            <?php echo lang('edit_profile_address_label', 'company_address');?>
            <?php echo form_input($companyaddress);?>
      </p>

      <p>
            <?php echo lang('edit_profile_website_label', 'companywebsite');?>
            <?php echo form_input($companywebsite);?>
      </p>

      <p>
            <?php echo lang('edit_profile_contactp_label', 'contact_person');?>
            <?php echo form_input($contactperson);?>
      </p>

        <p>
            <?php echo lang('edit_profile_email_label', 'email');?>
            <?php echo form_input($email);?>
      </p>  
         
      <p>
            <?php echo lang('edit_profile_mobile_label', 'mobile_number');?>
            <?php echo form_input($mobile);?>
      </p>

      <p>
            <?php echo lang('edit_profile_username_label', 'username');?>
            <?php echo form_input($username);?>
      </p>
      <?php echo form_hidden($csrf); ?>

      <p><?php echo form_submit('submit', lang('edit_profile_submit_btn'));?></p>

<?php echo form_close();?>
</div>
			</div>					
		</div><hr>
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
	<script type="text/javascript" src="<?php echo(JS.'jquery.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo(JS.'adminheader.js'); ?>"></script>
    <?php
       $this->load->view('includes/admin_footer');
    ?>
       
  </body>
</html>