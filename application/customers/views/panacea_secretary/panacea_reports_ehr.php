<?php $current_page="Electronic_Health_Record";?>
<?php $main_nav="Reports";?>
<?php
include('inc/header_bar.php');
include('inc/sidebar.php');
?>
<section class="content">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
        	<div class="container-fluid">
            <div class="header">
				<h2>Search Student's Document by Admission Number </h2>
			</div>
			<?php
			$attributes = array('class' => 'smart-form','id'=>'create_user','name'=>'userform');
			echo  form_open('panacea_mgmt/panacea_reports_display_ehr',$attributes);
			?>
			<h5>
				Please Enter The Admission Number.
			</h5><br>
			<div class="row">
				<div class="col-sm-4">
    				<input type="textarea" class="form-control" placeholder="Addmission Number"/>
    			</div>
        	</div>
        	<br>
				<button type="submit" class="btn bg-indigo waves-effect">SEARCH</button>
                <button type="reset" class="btn bg-light-green waves-effect">CLEAR</button>
			</div>
			<br>
		</div>
	</div>
</section>
		<?php echo form_close();?>

<section class="content">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
        	<div class="container-fluid">
            <div class="header">
				<h2>Search Student's Document by Hospital Unique ID </h2>
			</div>
			<?php
			$attributes = array('class' => 'smart-form','id'=>'create_user','name'=>'userform');
			echo  form_open('panacea_mgmt/panacea_reports_display_ehr_uid',$attributes);
			?>
			<h5>
				Please Enter The Hospital Unique ID.
			</h5>
			<div class="row">
					<div class="col-sm-4">
            			<input type="textarea" class="form-control" placeholder="Hospital Unique id" name="uid" id="uid" value="<?PHP echo set_value('uid'); ?>" required>
           			</div>
      		</div><br>
			<button type="submit" class="btn bg-indigo waves-effect">SEARCH</button>
	        <button type="reset" class="btn bg-light-green waves-effect">CLEAR</button>
			</div><br>

			<?php echo form_close();?>
		</div>
	</div>
</section>
<?php 
	//include footer
	include("inc/footer_bar.php"); 
?>