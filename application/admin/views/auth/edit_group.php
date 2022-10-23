<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Edit Group</title>
 	<?php
         $this->load->view('includes/admin_css');
    ?>
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
				<?php echo lang('edit_group_nav');?>
				</li>
			</ul><hr>
		</div>
        <div class="row-fluid">		
			<div class="alert alert-dismissable">
  				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  				<strong><?php echo lang('common_message');?> </strong> 
			</div>				
		</div><hr>
		<div class="row-fluid">
			<div class="panel panel-default">
  				<div class="panel-heading"><strong><?php echo lang('edit_group_heading');?></strong>
  					<!--<button type="button" class="close" data-dismiss="panel" aria-hidden="true">&times;</button>-->
  					<div class="box-icon" style="float:right">
						<a href="#" class="btn-minimize"><i class="icon-chevron-up"></i></a>
						<a href="#" class="btn-close"><i class="glyphicon glyphicon-remove"></i></a>
					</div>
  				</div>
  				<div class="box-content">

<p><?php echo lang('edit_group_subheading');?></p>

<div id="infoMessage"><?php echo $message;?></div>

<?php echo form_open(current_url());?>

      <p>
            <?php echo lang('create_group_name_label', 'group_name');?> <br />
            <?php echo form_input($group_name);?>
      </p>

      <p>
            <?php echo lang('edit_group_desc_label', 'description');?> <br />
            <?php echo form_input($group_description);?>
      </p>

      <p><?php echo form_submit('submit', lang('edit_group_submit_btn'));?></p>

<?php echo form_close();?>
</div>
			</div>					
		</div><hr>
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
