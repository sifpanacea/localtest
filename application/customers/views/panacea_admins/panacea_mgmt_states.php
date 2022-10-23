<?php $current_page="Manage_States"?>
<?php $main_nav="Masters"?>
<?php 
include('inc/header_bar.php');
include('inc/sidebar.php');
?>
 <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                
            </div>
            <!-- Basic Table -->
            <div class="row clearfix">
                <div class="col-lg-6 col-md-3 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                MANAGE STATES
                            </h2>
                            <ul class="header-dropdown m-r--5">
                                <div class="button-demo">
                                <button type="button" class="btn bg-pink waves-effect" onclick="window.history.back();">Back</button>
                                </div>
                            </ul>
                        </div>
                        <div class="body table-responsive">
                        	<!-- <div class="row clearfix">
                        		<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3"> -->
                                    <!-- <button class="btn btn-primary btn-lg btn-block waves-effect" type="button">
                                    All States<span class="badge">1</span></button> -->
                                     <div class="demo-button">
                                    <button type="button" class="btn btn-block btn-lg btn-primary waves-effect"> All States<span class="badge"><?php if(!empty($statcount)) {?><?php echo $statcount;?><?php } else {?><?php echo "0";?><?php }?></span></button>
                                </div>
                               <!--  </div> 
                        	</div> -->
                            <table class="table">
                                <?php if ($states): ?>
                                <tr>
                                    <th>State Code</th>
                                    <th>State Name</th>
                                </tr>
                                <?php foreach ($states as $state):?>
                                <tbody>
                                <tr>
                                    <td><?php echo ucwords($state["st_code"]) ;?></td>
                                    <td><?php echo ucwords($state["st_name"]) ;?></td>
                                    
                                </tr>
                                <?php endforeach;?>
                                <?php else: ?>
                                <p>
                                    <?php echo "No state entered yet.";?>
                                </p>
                                <?php endif ?>
                                </tbody>
                                <?php if($links):?>
                                <tfoot>
                                  <tr>
                                     <td colspan="5">
                                        <?php echo $links; ?>
                                     </td>
                                  </tr>
                                   
                                </tfoot>
                               <?php endif ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Basic Table -->                   
        </div>
    </section>

<?php 
include('inc/footer_bar.php');
?>