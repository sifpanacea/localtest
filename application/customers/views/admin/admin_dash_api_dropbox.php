<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<title>Admin Import</title>
<link href="<?php echo(CSS.'Ajaxfile-upload.css'); ?>" rel="stylesheet" type="text/css" />
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
  				<div class="panel-heading"><strong><?php echo lang('admin_dash_import');?></strong>
  					<!--<button type="button" class="close" data-dismiss="panel" aria-hidden="true">&times;</button>-->
  					<div class="box-icon" style="float:right">
						<a href="#" class="btn-minimize"><i class="icon-chevron-up"></i></a>
						<a href="#" class="btn-close"><i class="glyphicon glyphicon-remove"></i></a>
					</div>
  				</div>
  				<div class="box-content" >
				
				
  				</div>
			</div>					
		</div><hr>
		
		<!-- Includes -->
            <?php
            $this->load->view('includes/admin_bottom');
            ?>
		
		<footer>
		<?php echo lang('common_copy_rights');?>
		</footer>
				
	</div><!--/.fluid-container-->   
  
  <!-- Bootstrap core JavaScript
    ================================================== -->
    <script src="<?php echo(JS.'Ajaxfileupload-jquery-1.3.2.js'); ?>" type="text/javascript"></script>
    <script src="<?php echo(JS.'ajaxupload.3.5.js'); ?>" type="text/javascript"></script>
	<script src="<?php echo(JS.'jquery.js'); ?>"></script>
    <script src="<?php echo(JS.'adminheader.js'); ?>"></script>
    <?php
        $this->load->view('includes/admin_footer');
     ?>
	
	<script src="<?php echo(JS.'bootstrap.file-input.js'); ?>" type="text/javascript"></script>
	<script src="<?php echo(JS.'holder.js'); ?>" type="text/javascript"></script>
    <script src="<?php echo(JS.'dynamic-add-import.js'); ?>" type="text/javascript"></script>	
  </body>
</html>
