<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Saved Request Notes";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["pa notes_cc"]['sub']["saved_notes"]["active"] = true;
include("inc/nav.php");

?>

<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->


<div id="main" role="main">
    <?php
        //configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
        //$breadcrumbs["New Crumb"] => "http://url.com"
        include("inc/ribbon.php");
    ?>
    <!-- MAIN CONTENT -->
    <div id="content">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <!-- new widget -->
                    <!-- Widget ID (each widget will need unique ID)-->
                    <div class="jarviswidget" id="wid-id-60" data-widget-editbutton="false">
                        <!-- widget options:
                        usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

                        data-widget-colorbutton="false"
                        data-widget-editbutton="false"
                        data-widget-togglebutton="false"
                        data-widget-deletebutton="false"
                        data-widget-fullscreenbutton="false"
                        data-widget-custombutton="false"
                        data-widget-collapsed="true"
                        data-widget-sortable="false"

                        -->
                        <header>
                            <span class="widget-icon"> <i class="fa fa-bar-chart-o"></i> </span>
                            <h2>Request PIE</h2>

                        </header>

                        <!-- widget div-->
                        <div>

                            <!-- widget edit box -->
                            <div class="jarviswidget-editbox">
                                <!-- This area used as dropdown edit box -->

                            </div>
                            <!-- end widget edit box -->

                            <!-- widget content -->
                            <div class="col-md-12" id="loading_request_pie" style="display:none;">
                                <center><img src="<?php echo(IMG.'ajax-loader.gif'); ?>" id="gif" ></center>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-3 col-md-12 col-lg-12">
                                    <div class="well well-sm well-light">
                                        <form class="smart-form">          
                                            <div class="row">
                                                <section class="col col-3">
                                                    <label class="label" for="">Start Date</label>
                                                        <div class="form-group">
                                                            <div class="input-group">
                                                            <input type="text" id="set_data" name="set_data" placeholder="Select a date" class="form-control datepicker" data-dateformat="yy-mm-dd" value="<?php echo Date('Y-m-d');?>">
                                                                
                                                            <!-- <input type="text" id="set_data" name="set_data" placeholder="Select a date" class="form-control datepicker" data-dateformat="yy-mm-dd" value="<?php echo $today_date?>"> -->
                                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                            </div>
                                                        </div>
                                                </section>
                                                <!-- <section class="col col-3">
                                                    <label class="label" for="">End Date</label>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                        <input type="text" id="set_data_two" name="set_data_two" placeholder="Select a date" class="form-control datepicker" data-dateformat="yy-mm-dd" value="<?php echo $today_date?>">
                                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                        </div>
                                                    </div>
                                                </section> -->
                                                <section class="col col-3">
                                                <button type="button" id="getData" class="btn btn-primary form-control" style="margin-top: 24px">Get Saved Notes</button>
                                                </section>
                                            </div>               
                                        </form>
                                    </div>
                                    <div class="widget-body">
                                        <section class="col col-12" style="margin-left: 10px;margin-right: 10px;margin-top: 20px;">
                                                <div id="selected_span_saved_data"></div>
                                        </section>  
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end widget content -->

                    </div>
                </article>
            </div>
        </div>
    </div>
    <!-- END MAIN CONTENT -->
</div>
<!-- END MAIN PANEL -->
<!-- Modal For Feed Data -->
<?php
    $attributes = array('class' => '','id'=>'followup_form','name'=>'userform');
    echo  form_open('panacea_cc/initiate_hs_request',$attributes);
 ?>
<div class="modal fade" id="followup_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="defaultModalLabel">Raise Request</h4>
        </div>
        <div class="modal-body">
            <div class="row clearfix">
                <div class="list-group">
                    <a href="javascript:void(0);" class="list-group-item active">
                        <h4 class="list-group-item-heading">Feed Data</h4>
                       
                    </a>
                    <a href="javascript:void(0);" class="list-group-item">
                        <h4 class="list-group-item-heading">Student Health ID</h4>
                        <p class="list-group-item-text" id="student_health_id">
                         
                        </p>
                    </a> 
                    <a href="javascript:void(0);" class="list-group-item">
                        <h4 class="list-group-item-heading">Student Name</h4>
                        <p class="list-group-item-text" id="stud_name">                     
                        </p>
                    </a> 
                  
                </div>
            <hr>
                <input type="hidden" name="page1_StudentInfo_SchoolName" id="stud_scl">
                <input type="hidden" name="page1_StudentInfo_District" id="stud_dist">
                <input type="hidden" name="page1_StudentInfo_Name" id="stud_name">
                <input type="hidden" name="student_code" id="student_health_id">
                <input type="hidden" name="page1_StudentInfo_Class" id="stud_cls">
                <input type="hidden" name="page1_StudentInfo_Section" id="stud_sec">

                <div class="col-sm-12">
                    <label style="color:red;"><strong>Select Disease</strong></label>
                    <div class="row">
                        <div class="col col-md-4">
                            <label class="radio radio-inline">  
                            <input type="radio"  class="radiobox" id="normal" name="test1" value="Normal"><span><strong>NORMAL</strong></span>
                            </label>
                        </div>
                        <div class="col col-md-4">
                            <label class="radio radio-inline">          
                            <input type="radio"  class="radiobox" name="test1" id="emergency" value="Emergency"><span><strong>EMERGENCY</strong></span>
                            </label>
                        </div>
                        <div class="col col-md-4">
                            <label class="radio radio-inline">          
                            <input type="radio"  class="radiobox" name="test1" id="chronic" value="Chronic"><span><strong>CHRONIC</strong></span>
                            </label>
                        </div>
                    </div>
                    <div class="general_related">
                                <div class="widget-body">
                                <hr class="simple">
                                <ul id="myTab1" class="nav nav-tabs bordered">
                                <li class="active">
                                <a href="#general" data-toggle="tab"> GENERAL</a>
                                </li>
                                <li>
                                <a href="#head_gn" data-toggle="tab"> HEAD</a>
                                </li>
                                <li>
                                <a href="#eyes" data-toggle="tab"> EYES</a>
                                </li>
                                <li>
                                <a href="#ent" data-toggle="tab"> ENT</a>
                                </li>
                                <li>
                                <a href="#rs" data-toggle="tab"> RS</a>
                                </li>
                                <li>
                                <a href="#cvs" data-toggle="tab"> CVS</a>
                                </li>
                                <li>
                                <a href="#gi" data-toggle="tab"> GI</a>
                                </li>
                                <li>
                                <a href="#gu" data-toggle="tab"> GU</a>
                                </li>
                                <li>
                                <a href="#gyn" data-toggle="tab"> GYN</a>
                                </li>
                                <li>
                                <a href="#endo_cri" data-toggle="tab"> ENDO CRINOLOGY</a>
                                </li>
                                <li>
                                <a href="#msk" data-toggle="tab"> MSK</a>
                                </li>
                                <li>
                                <a href="#cns" data-toggle="tab">CNS</a>
                                </li>
                                <li>
                                <a href="#psychiartic" data-toggle="tab">PSYCHIARTIC</a>
                                </li>

                                </ul>

                                <div id="myTabContent normal" class="tab-content padding-10">
                                <div class="row tab-pane fade in active" id="general">
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_general_identifier[]" value="Fever">
                                <i></i>Fever</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_general_identifier[]" value="Chills">
                                <i></i>Chills</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_general_identifier[]" value="Cold">
                                <i></i>Cold</label>

                                </div>
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_general_identifier[]" value="Loss Of Appetite">
                                <i></i>Loss Of Appetite</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_general_identifier[]" value="Weight Loss">
                                <i></i>Weight Loss</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_general_identifier[]" value="Rashes">
                                <i></i>Rashes</label>
                                </div>
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_general_identifier[]" value="Night Sweats">
                                <i></i>Night Sweats</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_general_identifier[]" value="Fever With Rash">
                                <i></i>Fever With Rash</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_general_identifier[]" value="Fever with Chills">
                                <i></i>Fever with Chills</label>
                                </div>
                                </div>
                                <div class="tab-pane fade" id="head_gn">
                                <div class="row">
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_head_identifier[]" value="Headache">
                                <i></i>Headache</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_head_identifier[]" value="Dizziness">
                                <i></i>Dizziness</label>
                                </div>
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_head_identifier[]" value="Head Swelling">
                                <i></i>Head Swelling</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_head_identifier[]" value="Seizures">
                                <i></i>Seizures</label>
                                </div>
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_head_identifier[]" value="Head Injury">
                                <i></i>Head Injury</label>
                                </div>
                                </div>
                                </div>
                                  <!----EYES Related ----->
                                <div class="tab-pane fade" id="eyes">
                                <div class="row">
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_eyes_identifier[]" value="Eye Pain">
                                <i></i>Eye Pain</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_eyes_identifier[]" value="Eye Discharge">
                                <i></i>Eye Discharge</label>
                                </div>
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_eyes_identifier[]" value="Blurring Of Vission">
                                <i></i>Blurring Of Vission</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_eyes_identifier[]" value="Double Vision">
                                <i></i>Double Vision</label>
                                </div>
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_eyes_identifier[]" value="Conjunctivitis">
                                <i></i>Conjunctivitis</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_eyes_identifier[]" value="Stye">
                                <i></i>Stye</label>
                                </div>
                                </div>
                                </div>

                                <!-- ent related information -->

                                <div class="tab-pane fade" id="ent">
                                <div class="row">

                                <div class="col col-md-3">
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_ent_identifier[]" value="Ear Pain">
                                <i></i>Ear Pain</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_ent_identifier[]" value="Ear Discharge">
                                <i></i>Ear Discharge</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_ent_identifier[]" value="Watering Of Eyes">
                                <i></i>Watering Of Eyes</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_ent_identifier[]" value="Vertigo">
                                <i></i>Vertigo</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_ent_identifier[]" value="Tonsillitis">
                                <i></i>Tonsillitis</label>
                                </div>

                                <div class="col col-md-3">
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_ent_identifier[]" value="Tinnitus">
                                <i></i>Tinnitus</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_ent_identifier[]" value="Nose Bleeding">
                                <i></i>Nose Bleeding</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_ent_identifier[]" value="Nose Discharge">
                                <i></i>Nose Discharge</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_ent_identifier[]" value="Hoarseness of voice">
                                <i></i>Hoarseness of voice</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_ent_identifier[]" value="ASOM">
                                <i></i>ASOM</label>
                                </div>
                                <div class="col col-md-3">

                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_ent_identifier[]" value="Throat Pain">
                                <i></i>Throat Pain</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_ent_identifier[]" value="Bad Smell">
                                <i></i>Bad Smell</label>

                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_ent_identifier[]" value="Neck Swelling">
                                <i></i>Neck Swelling</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_ent_identifier[]" value="Painful swallowing(Odynophagia)">
                                <i></i>Painful swallowing(Odynophagia)</label>
                                </div>
                                <div class="col col-md-3">
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_ent_identifier[]" value="Cracked Angles Of Mouth">
                                <i></i>Cracked Angles Of Mouth</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_ent_identifier[]" value="Ulcerated Lip">
                                <i></i>Ulcerated Lip</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_ent_identifier[]" value="Bleeding Gums">
                                <i></i>Bleeding Gums</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_ent_identifier[]" value="Swollen Gums">
                                <i></i>Swollen Gums</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_ent_identifier[]" value="Difficulty in swallowing(Dysphagia)">
                                <i></i>Difficulty in swallowing(Dysphagia)</label>
                                </div>
                                </div>
                                </div>

                                <!--RS related information -->

                                <div class="row tab-pane fade" id="rs">
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_rs_identifier[]" value="Cough">
                                <i></i>Cough</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_rs_identifier[]" value="Shortness Of Breath">
                                <i></i>Shortness Of Breath</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_rs_identifier[]" value="Sputum From Mouth">
                                <i></i>Sputum From Mouth</label>
                                </div>
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_rs_identifier[]" value="Blood From Mouth">
                                <i></i>Blood From Mouth</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_rs_identifier[]" value="Wheezing">
                                <i></i>Wheezing</label>
                                </div>
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_rs_identifier[]" value="Dry Cough">
                                <i></i>Dry Cough</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_rs_identifier[]" value="Wet Cough">
                                <i></i>Wet Cough(Productive cough)</label>
                                </div>
                                </div>

                                <!--cvs related information-->

                                <div class="row tab-pane fade" id="cvs">
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_cvs_identifier[]" value="Chest Pain">
                                <i></i>Chest Pain</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_cvs_identifier[]" value="Edema Of Feet">
                                <i></i>Edema oF Feet</label>
                                </div>
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_cvs_identifier[]" value="Shortness of Breath">
                                <i></i>Shortness of Breath</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_cvs_identifier[]" value="Cyanosis">
                                <i></i>Cyanosis</label>
                                </div>
                                </div>

                                <!--GI related information -->

                                <div class="row tab-pane fade" id="gi">
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_gi_identifier[]" value="Dysphagia(difficulty in swallowing)">
                                <i></i>Dysphagia(difficulty in swallowing)</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_gi_identifier[]" value="Nausea">
                                <i></i>Nausea</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_gi_identifier[]" value="Vomitings">
                                <i></i>Vomitings</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_gi_identifier[]" value="Abdominal Pain">
                                <i></i>Abdominal Pain</label>
                                </div>
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_gi_identifier[]" value="Blood in vomiting(Hematemesis)">
                                <i></i>Blood in vomiting(Hematemesis)</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_gi_identifier[]" value="Diarrhoea">
                                <i></i>Diarrhoea</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_gi_identifier[]" value="Constipation">
                                <i></i>Constipation</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_gi_identifier[]" value="Piles">
                                <i></i>Piles(Blood in Stools)</label>
                                </div>
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_gi_identifier[]" value="Melena(dark stools)">
                                <i></i>Melena(dark stools)</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_gi_identifier[]" value="Blood Per Rectum">
                                <i></i>Blood Per Rectum</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_gi_identifier[]" value="Perineal swelling">
                                <i></i>Perineal swelling</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_gi_identifier[]" value="Incontinence Of Stools">
                                <i></i>Incontinence Of Stools</label>                       
                                </div>
                                </div>

                                <!--GU related information -->

                                <div class="row tab-pane fade" id="gu">
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_gu_identifier[]" value="Pain On Micturition">
                                <i></i>Pain On Micturition</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_gu_identifier[]" value="Increase Micturition">
                                <i></i>Increase Micturition</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_gu_identifier[]" value="Burning Micturition">
                                <i></i>Burning Micturition</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_gu_identifier[]" value="Dribbling Of Urine">
                                <i></i>Dribbling Of Urine</label>
                                </div>
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_gu_identifier[]" value="Unable To Pass Urine">
                                <i></i>Unable To Pass Urine</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_gu_identifier[]" value="Blood In Urine">
                                <i></i>Blood In Urine</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_gu_identifier[]" value="Flank Pain">
                                <i></i>Flank Pain</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_gu_identifier[]" value="Testis Swelling">
                                <i></i>Testis Swelling</label>
                                </div>
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_gu_identifier[]" value="Suprapubic Pain">
                                <i></i>Suprapubic Pain</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_gu_identifier[]" value="Incontinence Of Urine">
                                <i></i>Incontinence Of Urine</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_gu_identifier[]" value="Urine Retention">
                                <i></i>Urine Retention</label>
                                <label class="checkbox">
                                <input type="checkbox"  id="normal_identifier" name="normal_gu_identifier[]" value="Testis Pain">
                                <i></i>Testis Pain</label>
                                <label class="checkbox">
                                <input type="checkbox"  id="normal_identifier" name="normal_gu_identifier[]" value="Bed Wetting">
                                <i></i>Bed Wetting</label>
                                </div>
                                </div>                      

                                <!--gyn related information -->

                                <div class="row tab-pane fade" id="gyn">
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_gyn_identifier[]" value="Vaginal Bleeding">
                                <i></i>Vaginal Bleeding</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_gyn_identifier[]" value="Absence Of Periods">
                                <i></i>Absence Of Periods</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_gyn_identifier[]" value="Painful Periods">
                                <i></i>Painful Periods</label>
                                </div>
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_gyn_identifier[]" value="Breast Lump">
                                <i></i>Breast Lump</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_gyn_identifier[]" value="Nipple Discharge">
                                <i></i>Nipple Discharge</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_gyn_identifier[]" value="White Discharge">
                                <i></i>White Discharge</label>
                                </div>
                                </div>

                                <!--ENDO CRINOLOGY related information -->

                                <div class="row tab-pane fade" id="endo_cri">
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_cri_identifier[]" value="Polyuria">
                                <i></i>Polyuria</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_cri_identifier[]" value="Polyphagia">
                                <i></i>Polyphagia</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_cri_identifier[]" value="Heat Intolerance">
                                <i></i>Heat Intolerance</label>
                                </div>
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_cri_identifier[]" value="cold intolerance">
                                <i></i>cold intolerance</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_cri_identifier[]" value="Fatigue">
                                <i></i>Fatigue</label>
                                </div>
                                </div>

                                <!--msk related information-->

                                <div class="tab-pane fade" id="msk">
                                <div class="row">
                                <div class="col col-md-3">
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_msk_identifier[]" value="Neck Pain">
                                <i></i>Neck Pain</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_msk_identifier[]" value="Back Pain">
                                <i></i>Back Pain</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_msk_identifier[]" value="Shoulder Pain">
                                <i></i>Shoulder Pain</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_msk_identifier[]" value="Elbow Pain">
                                <i></i>Elbow Pain</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_msk_identifier[]" value="Wrist Pain">
                                <i></i>Wrist Pain</label>
                                </div>
                                <div class="col col-md-3">
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_msk_identifier[]" value="Hip Pain">
                                <i></i>Hip Pain</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_msk_identifier[]" value="Ankle pain">
                                <i></i>Ankle pain</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_msk_identifier[]" value="Finger Pain">
                                <i></i>Finger Pain</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_msk_identifier[]" value="Knee Pain">
                                <i></i>Knee Pain</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_msk_identifier[]" value="Leg Pain">
                                <i></i>Leg Pain</label>
                                </div>
                                <div class="col col-md-3">
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_msk_identifier[]" value="Toe Pain">
                                <i></i>Toe Pain</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_msk_identifier[]" value="Muscle Pain">
                                <i></i>Muscle Pain</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_msk_identifier[]" value="Lower Back Pain">
                                <i></i>Lower Back Pain</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_msk_identifier[]" value="Body Pain">
                                <i></i>Body Pain</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_msk_identifier[]" value="Hand Pain">
                                <i></i>Hand Pain</label>
                                </div>
                                <div class="col col-md-3">
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_msk_identifier[]" value="Numbness">
                                <i></i>Numbness</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_msk_identifier[]" value="Tingling">
                                <i></i>Tingling</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_msk_identifier[]" value="Morning Stiffness">
                                <i></i>Morning Stiffness</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_msk_identifier[]" value="Night Pain">
                                <i></i>Night Pain</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_msk_identifier[]" value="Injury">
                                <i></i>Injury</label>
                                </div>
                                </div>
                                </div>
                                <!--cns related information -->
                                <div class="row tab-pane fade" id="cns">
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_cns_identifier[]" value="Loss Of Consciousness">
                                <i></i>Loss Of Consciousness</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_cns_identifier[]" value="Abnormal_Gait">
                                <i></i>Abnormal_Gait</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_cns_identifier[]" value="Aphasia">
                                <i></i>Aphasia</label>
                                </div>
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_cns_identifier[]" value="Insufficient Sleep">
                                <i></i>Insufficient Sleep</label>
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_cns_identifier[]" value="Abnormal_Sleep">
                                <i></i>Abnormal_Sleep</label>
                                </div>
                                </div>
                                <!-- PSYCHIARTIC-->
                                <div class="row tab-pane fade" id="psychiartic">
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" id="normal_identifier" name="normal_psychiartic_identifier[]" value="Psychiatrist">
                                <i></i>Psychiatrist</label>

                                </div>
                                </div>
                                </div>

                                </div>

                                </div>

                    <!-- End of General related -->  
                    
                    <!-----------------  EMERGENCY PROBLEMS  ------------------------->

                                <div class="emergency_related" style="display: none;">

                                <div class="widget-body">

                                <hr class="simple">
                                <ul id="myTab1" class="nav nav-tabs bordered">
                                <li class="active">
                                <a href="#emergency_disease" data-toggle="tab"> EMERGENCY</a>
                                </li>
                                <li>
                                <a href="#emergency_bites" data-toggle="tab"> BITES</a>
                                </li>

                                </ul>

                                <!-- EYE PROBLEMS --------------------------------------->      
                                <div id="myTabContent1" class="tab-content padding-10"> 
                                <div class="tab-pane fade in active" id="emergency_disease">
                                <div class="row">
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" id="emergency_identifier" name="emergency_identifier[]" value="Covid-19">
                                <i></i>Covid-19</label>
                                <label class="checkbox">
                                <input type="checkbox" id="emergency_identifier" name="emergency_identifier[]" value="Epistaxis">
                                <i></i>Epistaxis</label>
                                <label class="checkbox">
                                <input type="checkbox" id="emergency_identifier" name="emergency_identifier[]" value="Seizures">
                                <i></i>Seizures</label>
                                <label class="checkbox">
                                <input type="checkbox" id="emergency_identifier" name="emergency_identifier[]" value="Mesenteric lymphadenitis">
                                <i></i>Mesenteric lymphadenitis</label>
                                <label class="checkbox">
                                <input type="checkbox" id="emergency_identifier" name="emergency_identifier[]" value="Blood in Sputum">
                                <i></i>Blood in Sputum</label>
                                <label class="checkbox">
                                <input type="checkbox" id="emergency_identifier" name="emergency_identifier[]" value="Asthama">
                                <i></i>Asthama</label>
                                <label class="checkbox">
                                <input type="checkbox" id="emergency_identifier" name="emergency_identifier[]" value="Acute Appendicitis">
                                <i></i>Acute Appendicitis</label>
                                </div>
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" id="emergency_identifier" name="emergency_identifier[]" value="Giddiness">
                                <i></i>Giddiness</label>
                                <label class="checkbox">
                                <input type="checkbox" id="emergency_identifier" name="emergency_identifier[]" value="Chest Pain">
                                <i></i>Chest Pain</label>
                                <label class="checkbox">
                                <input type="checkbox" id="emergency_identifier" name="emergency_identifier[]" value="Breathing Problem">
                                <i></i>Breathing Problem</label>
                                <label class="checkbox">
                                <input type="checkbox" id="emergency_identifier" name="emergency_identifier[]" value="Gall Stones">
                                <i></i>Gall Stones</label>
                                <label class="checkbox">
                                <input type="checkbox" id="emergency_identifier" name="emergency_identifier[]" value="Pancreatitis">
                                <i></i>Pancreatitis</label>
                                <label class="checkbox">
                                <input type="checkbox" id="emergency_identifier" name="emergency_identifier[]" value="Epilepsy">
                                <i></i>Epilepsy</label>
                                </div>
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" id="emergency_identifier" name="emergency_identifier[]" value="Swelling">
                                <i></i>Swelling</label>
                                <label class="checkbox">
                                <input type="checkbox" id="emergency_identifier" name="emergency_identifier[]" value="Suicides">
                                <i></i>Suicides</label>
                                <label class="checkbox">
                                <input type="checkbox" id="emergency_identifier" name="emergency_identifier[]" value="Burns"><i></i>Burns</label>
                                </div>
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" id="emergency_identifier" name="emergency_identifier[]" value="Abdomen Pain">
                                <i></i>Abdomen Pain</label>
                                <label class="checkbox">
                                <input type="checkbox" id="emergency_identifier" name="emergency_identifier[]" value="Falls and Trauma">
                                <i></i>Falls and Trauma</label>
                                <label class="checkbox">
                                <input type="checkbox" id="emergency_identifier" name="emergency_identifier[]" value="Others">
                                <i></i>Others</label>

                                </div>
                                </div>
                                </div>

                                <!-- END EYE PROBLEMS --------------------------------------->  

                                <!--  EAR PROBLEMS  ------------------------->          

                                <div class="tab-pane fade" id="emergency_bites">
                                <div class="row">
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" id="emergency_identifier" name="emergency_bites_identifier[]" value="Scorpion">
                                <i></i>Scorpion</label>
                                <label class="checkbox">
                                <input type="checkbox" id="emergency_identifier" name="emergency_bites_identifier[]" value="Snake">
                                <i></i>Snake</label>
                                <label class="checkbox">
                                <input type="checkbox"  id="emergency_identifier" name="emergency_bites_identifier[]" value="Honey Bee">
                                <i></i>Honey Bee</label>
                                </div>
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" id="emergency_identifier" name="emergency_bites_identifier[]" value="Monkey">
                                <i></i>Monkey</label>
                                <label class="checkbox">
                                <input type="checkbox" id="emergency_identifier" name="emergency_bites_identifier[]" value="Dog">
                                <i></i>Dog</label>
                                <label class="checkbox">
                                <input type="checkbox" id="emergency_identifier" name="emergency_bites_identifier[]" value="Unknown Bite">
                                <i></i>Unknown Bite</label>
                                </div>

                                </div>
                                </div>

                                <!--  END EAR PROBLEMS  ------------------------->


                                </div>

                                </div>
                                </div>
                    <!----------END EMERGENCY------------->

                    <!-- CHRONIC RELATED INFORMATION (ON CLICK RADIO) -->
                                <div class="chronic_related" style="display: none;">

                                <div class="widget-body">

                                <hr class="simple">
                                <ul id="myTab1" class="nav nav-tabs bordered">
                                <li class="active">
                                <a href="#chronic_eyes" data-toggle="tab"> EYES</a>
                                </li>
                                <li>
                                <a href="#chronic_ent" data-toggle="tab"> EAR</a>
                                </li>
                                <li>
                                <a href="#chronic_cns" data-toggle="tab">CNS</a>
                                </li>
                                <li>
                                <a href="#chronic_rs" data-toggle="tab"> RS</a>
                                </li>
                                <li>
                                <a href="#chronic_cvs" data-toggle="tab"> CVS</a>
                                </li>
                                <li>
                                <a href="#chronic_gi" data-toggle="tab"> GI</a>
                                </li>
                                <li>
                                <a href="#chronic_blood" data-toggle="tab"> BLOOD</a>
                                </li>
                                <li>
                                <a href="#chronic_kidney" data-toggle="tab"> KIDNEY</a>
                                </li>
                                <li>
                                <a href="#chronic_vandm" data-toggle="tab"> VITAMINS &MINERAL RELATED</a>
                                </li>
                                <li>
                                <a href="#chronic_bones_chronic" data-toggle="tab"> BONES RELATED</a>
                                </li>
                                <li>
                                <a href="#chronic_skin_chronic" data-toggle="tab">SKIN</a>
                                </li>
                                <li>
                                <a href="#chronic_endo_chronic" data-toggle="tab">ENDO</a>
                                </li>
                                <li>
                                <a href="#others_chronic" data-toggle="tab">OTHERS</a>
                                </li>                       
                                </ul>

                                <!-- EYE PROBLEMS --------------------------------------->      
                                <div id="myTabContent_chronic" class="tab-content padding-10">  
                                <div class="tab-pane fade in active" id="chronic_eyes">
                                <div class="row">
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" id="chronic_identifier" name="chronic_eyes_identifier[]" value="Glaucoma">
                                <i></i>Glaucoma</label>
                                <label class="checkbox">
                                <input type="checkbox" id="chronic_identifier" name="chronic_eyes_identifier[]" value="Proptosis">
                                <i></i>Proptosis</label>
                                <label class="checkbox">
                                <input type="checkbox" id="chronic_identifier" name="chronic_eyes_identifier[]" value="Ptosis">
                                <i></i>Ptosis</label>
                                </div>
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" id="chronic_identifier" name="chronic_eyes_identifier[]" value="Night Blindness">
                                <i></i>Night Blindness</label>
                                <label class="checkbox">
                                <input type="checkbox" id="chronic_identifier" name="chronic_eyes_identifier[]" value="Refractive Errors">
                                <i></i>Refractive Errors</label>
                                <label class="checkbox">
                                <input type="checkbox" id="chronic_identifier" name="chronic_eyes_identifier[]" value="Retinal Pathology">
                                <i></i>Retinal Pathology</label>
                                </div>
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" id="chronic_identifier" name="chronic_eyes_identifier[]" value="Squints">
                                <i></i>Squints</label>
                                <label class="checkbox">
                                <input type="checkbox" id="chronic_identifier" name="chronic_eyes_identifier[]" value="Cataract">
                                <i></i>Cataract</label>
                                <label class="checkbox">
                                <input type="checkbox" id="chronic_identifier" name="chronic_eyes_identifier[]" value="Others">
                                <i></i>Others</label>
                                </div>
                                </div>
                                </div>

                                <!-- END EYE PROBLEMS --------------------------------------->  

                                <!--  EAR PROBLEMS  ------------------------->          

                                <div class="tab-pane fade" id="chronic_ent">
                                <div class="row">
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" id="chronic_identifier" name="chronic_ent_identifier[]" value="Csom">
                                <i></i>Csom</label>
                                <label class="checkbox">
                                <input type="checkbox"  id="chronic_identifier" name="chronic_ent_identifier[]" value="Hearing Loss">
                                <i></i>Hearingloss</label>
                                <label class="checkbox">
                                <input type="checkbox" id="chronic_identifier" name="chronic_ent_identifier[]" value="Sinusitis">
                                <i></i>Sinusitis</label>
                                </div>
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" id="chronic_identifier" name="chronic_ent_identifier[]" value="Rhinitis">
                                <i></i>Rhinitis</label>
                                <label class="checkbox">
                                <input type="checkbox" id="chronic_identifier" name="chronic_ent_identifier[]" value="Dental Caries">
                                <i></i>Dental Caries</label>
                                </div>
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" id="chronic_identifier" name="chronic_ent_identifier[]" value="Penidontal Disease">
                                <i></i>Penidontal Disease</label>
                                <label class="checkbox">
                                <input type="checkbox" id="chronic_identifier" name="chronic_ent_identifier[]" value="Acute Otitis Media">
                                <i></i>Acute Otitis Media</label>
                                </div>
                                </div>
                                </div>

                                <!--  END EAR PROBLEMS  ------------------------->

                                <!--  CNS PROBLEMS  ------------------------->

                                <div class="tab-pane fade" id="chronic_cns">
                                <div class="row">
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" id="chronic_identifier" name="chronic_cns_identifier[]" value="Epilepsy">
                                <i></i>Epilepsy</label>
                                <label class="checkbox">
                                <input type="checkbox" id="chronic_identifier" name="chronic_cns_identifier[]" value="Myasthenia gravis">
                                <i></i>Myasthenia gravis</label>
                                <label class="checkbox">
                                <input type="checkbox" id="chronic_identifier" name="chronic_cns_identifier[]" value="Migraine">
                                <i></i>Migraine</label>
                                <label class="checkbox">
                                <input type="checkbox" id="chronic_identifier" name="chronic_cns_identifier[]" value="OCD">
                                <i></i>OCD</label>
                                <label class="checkbox">
                                <input type="checkbox" id="chronic_identifier" name="chronic_cns_identifier[]" value="Personality Disorders">
                                <i></i>Personality Disorders</label>
                                </div>

                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" id="chronic_identifier" name="chronic_cns_identifier[]" value="Anxiety">
                                <i></i>Anxiety</label>
                                <label class="checkbox">
                                <input type="checkbox" id="chronic_identifier" name="chronic_cns_identifier[]" value="Depression Disorders">
                                <i></i>Depression Disorders</label>

                                <label class="checkbox">
                                <input type="checkbox" id="chronic_identifier" name="chronic_cns_identifier[]" value="Bipolar Disorders">
                                <i></i>Bipolar Disorders</label>
                                <input type="checkbox" id="chronic_identifier" name="chronic_cns_identifier[]" value="Dementia">
                                <i></i>Dementia</label>
                                </div>
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" id="chronic_identifier" name="chronic_cns_identifier[]" value="Schizophrenia">
                                <i></i>Schizophrenia</label>
                                <label class="checkbox">
                                <input type="checkbox" id="chronic_identifier" name="chronic_cns_identifier[]" value="Neuro-Developmental Disorders">
                                <i></i>Neuro-Developmental Disorders</label>
                                <label class="checkbox">
                                <input type="checkbox" id="chronic_identifier" name="chronic_cns_identifier[]" value="Phobic Disorders">
                                <i></i>Phobic Disorders</label>
                                <label class="checkbox">
                                <input type="checkbox" id="chronic_identifier" name="chronic_cns_identifier[]" value="Others">
                                <i></i>Others</label>
                                </div>
                                </div>
                                </div>

                                <!--  END CNS PROBLEMS  ------------------------->

                                <!-- RS PROBLEMS -------------------------------------->
                                <div class="tab-pane fade" id="chronic_rs">
                                <div class="row">
                                <div class="col col-4">
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_rs_identifier[]" value="Asthma">
                                <i></i>Asthma</label>
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_rs_identifier[]" value="Pneumonia">
                                <i></i>Pneumonia</label>
                                </div>
                                <div class="col col-4">
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_rs_identifier[]" value="Emphysema">
                                <i></i>Emphysema</label>
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_rs_identifier[]" value="Chronic Bronchitis">
                                <i></i>Chronic Bronchitis</label>
                                </div>
                                <div class="col col-4">
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_rs_identifier[]" value="Bronchiectasis">
                                <i></i>Bronchiectasis</label>
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_rs_identifier[]" value="Others">
                                <i></i>Others</label>
                                </div>
                                </div>

                                </div>

                                <!--END RS PROBLEMS --------------------------------------> 

                                <!-- CVS PROBLEMS -------------------------------------->

                                <div class="tab-pane fade" id="chronic_cvs">
                                <div class="row">
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_cvs_identifier[]" value="VSD">
                                <i></i>VSD</label>
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_cvs_identifier[]" value="RHD">
                                <!---doubt in cvs -->                   
                                <i></i>RHD</label>
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_cvs_identifier[]" value="MR">
                                <i></i>MR</label>
                                </div>
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_cvs_identifier[]" value="ASD">
                                <i></i>ASD</label>
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_cvs_identifier[]" value="CHD">
                                <i></i>CHD</label>
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_cvs_identifier[]" value="Others">
                                <i></i>Others</label>
                                </div>
                                </div>
                                </div>

                                <!-- END CVS PROBLEMS -------------------------------------->

                                <!-- GI PROBLEMS ------------------------------------------->

                                <div class="tab-pane fade" id="chronic_gi">
                                <div class="row">
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_gi_identifier[]" value="Acid Peptic Disease">
                                <i></i>Acid Peptic Disease</label>
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_gi_identifier[]" value="Appendicitis">
                                <i></i>Appendicitis</label>
                                </div>
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_gi_identifier[]" value="Jaundice">
                                <i></i>Jaundice</label>
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_gi_identifier[]" value="Ascites">
                                <i></i>Ascites</label>
                                </div>
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_gi_identifier[]" value="Others">
                                <i></i>Others</label>
                                </div>
                                </div>
                                </div>

                                <!-- END GI PROBLEMS ------------------------------------------->

                                <!-- BLOOD PROBLEMS -------------------------------------------->

                                <div class="tab-pane fade" id="chronic_blood">
                                <div class="row">
                                <div class="col col-md-3">

                                <label class="checkbox">
                                <input type="checkbox" name="chronic_blood_identifier[]" value="Anemia">
                                <i></i>Anemia</label>


                                <label class="checkbox">
                                <input type="checkbox" name="chronic_blood_identifier[]" value="Mild">
                                <i></i>Mild</label>
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_blood_identifier[]" value="Moderate">
                                <i></i>Moderate</label>
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_blood_identifier[]" value="Severe">
                                <i></i>Severe</label>
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_blood_identifier[]" value="Iron Deficiency">
                                <i></i>Iron Deficiency</label>
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_blood_identifier[]" value="B 12 Deficieny">
                                <i></i>B 12 Deficieny</label>
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_blood_identifier[]" value="Aplastic Anemia">
                                <i></i>Aplastic Anemia</label>
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_blood_identifier[]" value="Sickle Cell Anemia">
                                <i></i>Sickle Cell Anemia</label>
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_blood_identifier[]" value="Anemia of chronic disease">
                                <i></i>Anemia of chronic disease</label>
                                </div>
                                <div class="col col-md-3">
                                <p><strong>PLATELET DISORDER</strong></p>
                                <br>

                                <label class="checkbox">
                                <input type="checkbox" name="chronic_blood_identifier[]" value="Vwb Disorder">
                                <i></i>Vwb Disorder</label>
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_blood_identifier[]" value="Hemophilia">
                                <i></i>Hemophilia</label>
                                </div>
                                <div class="col col-md-3">
                                <p><strong>BLOOD CANCER</strong></p>
                                <br>
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_blood_identifier[]" value="Lymphoma">
                                <i></i>Lymphoma</label>
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_blood_identifier[]" value="Polycythemia Vera">
                                <i></i>Polycythemia Vera</label>
                                </div>
                                <div class="col col-md-3">

                                <label class="checkbox">
                                <input type="checkbox" name="chronic_blood_identifier[]" value="Leukemia">
                                <i></i>Leukemia</label>
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_blood_identifier[]" value="CLL">
                                <i></i>CLL</label>
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_blood_identifier[]" value="ALL">
                                <i></i>ALL</label>
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_blood_identifier[]" value="AML">
                                <i></i>AML</label>
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_blood_identifier[]" value="CML">
                                <i></i>CML</label>
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_blood_identifier[]" value="Others">
                                <i></i>Others</label>
                                </div>
                                </div>
                                </div>

                                <!--END  BLOOD PROBLEMS -------------------------------------------->

                                <!--KIDNEY PROBLEMS -------------------------------------------->
                                <div class="tab-pane fade" id="chronic_kidney">
                                <div class="row">
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_kidney_identifier[]" value="CKD(Chronic Kidney Disease)">
                                <i></i>CKD(Chronic Kidney Disease)</label>
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_kidney_identifier[]" value="ARF(Acute Renal Failure)">
                                <i></i>ARF(Acute Renal Failure)</label>
                                </div>
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_kidney_identifier[]" value="Renal Stones">
                                <i></i>Renal Stones</label>
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_kidney_identifier[]" value="Nephrotic Syndrome">
                                <i></i>Nephrotic Syndrome</label>
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_kidney_identifier[]" value="Nephritic Syndrome">
                                <i></i>Nephritic Syndrome</label>
                                </div>
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_kidney_identifier[]" value="Polycystic Kidney Disease">
                                <i></i>Polycystic Kidney Disease</label>
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_kidney_identifier[]" value="Urinary Tract Infections">
                                <i></i>Urinary Tract Infections</label>
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_kidney_identifier[]" value="Others">
                                <i></i>Others</label>
                                </div>
                                </div>
                                </div>

                                <!--END KIDNEY PROBLEMS -------------------------------------------->

                                <!--VITAMINS & MINERALS DEFECIENCY PROBLEMS -------------------------------------------->
                                <div class="tab-pane fade" id="chronic_vandm">
                                <div class="row">
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_vandm_identifier[]" value="Vitamins D">
                                <i></i>Vitamin D</label>
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_vandm_identifier[]" value="Vitamins B12">
                                <i></i>Vitamins B12</label>
                                </div>

                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_vandm_identifier[]" value="Vitamin A">
                                <i></i>Vitamin A</label>
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_vandm_identifier[]" value="Vitamin C">
                                <i></i>Vitamin C</label>
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_vandm_identifier[]" value="Vitamin B Complex">
                                <i></i>vitamin B Complex</label>
                                </div>
                                </div>

                                </div>
                                <!--END VITAMINS & MINERALS DEFECIENCY PROBLEMS -------------------------------------------->

                                <!--  BONES PROBLEMS  -------------------------------------------------------->
                                <div class="tab-pane fade" id="chronic_bones_chronic">
                                <div class="row">
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_bones_identifier[]" value="Osteoporosis">
                                <i></i>Osteoporosis</label>
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_bones_identifier[]" value="Fracture">
                                <i></i>Fracture</label>
                                </div>
                                <div class="col col-md-4">
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_bones_identifier[]" value="Gout">
                                <i></i>Gout</label>
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_bones_identifier[]" value="Bone Tumours">
                                <i></i>Bone Tumours</label>
                                </div>
                                </div>
                                </div>
                                <!-- END BONES PROBLEMS  -------------------------------------------------------->

                                <!-- SKIN PROBLEMS  -------------------------------------------------------->

                                <div class="tab-pane fade" id="chronic_skin_chronic">
                                <div class="row">
                                <div class="col col-md-3">
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_skin_identifier[]" value="Acne">
                                <i></i>Acne</label>
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_skin_identifier[]" value="Eczema">
                                <i></i>Eczema</label>
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_skin_identifier[]" value="Psoriasis">
                                <i></i>Psoriasis</label>
                                </div>
                                <div class="col col-md-3">
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_skin_identifier[]" value="Vitiligo">
                                <i></i>Vitiligo</label>
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_skin_identifier[]" value="Measles">
                                <i></i>Measles</label>
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_skin_identifier[]" value="Scabies">
                                <i></i>Scabies</label>
                                </div>
                                <div class="col col-md-3">
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_skin_identifier[]" value="Chicken Pox">
                                <i></i>Chicken Pox</label>
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_skin_identifier[]" value="Warts">
                                <i></i>Warts</label>
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_skin_identifier[]" value="Cancers">
                                <i></i>Cancers</label>
                                </div>
                                <div class="col col-md-1">
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_skin_identifier[]" value="Others">
                                <i></i>Others</label>
                                </div>
                                </div>
                                </div>
                                <!-- END SKIN PROBLEMS  -------------------------------------------------------->


                                <div class="tab-pane fade" id="chronic_endo_chronic">
                                <div class="row">
                                <div class="col col-4">
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_endo_identifier[]" value="Diabetes Milletus Type 1">
                                <i></i>Diabetes Milletus Type 1</label>
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_endo_identifier[]" value="Others">
                                <i></i>Others</label>
                                </div>
                                <div class="col col-4">
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_endo_identifier[]" value="Hypothyroidism">
                                <i></i>Hypo Thyroidism</label>
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_endo_identifier[]" value="Hyperthyroidism">
                                <i></i>Hyper Thyroidism</label>
                                </div>
                                </div>
                                </div>


                                <!--OTHER CHRONIC PROBLEMS  -------------------------------------------------------->
                                <div class="tab-pane fade" id="others_chronic">
                                <div class="row">
                                <div class="col col-3">
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_others_identifier[]" value="HIV">
                                <i></i>HIV</label>
                                <label class="checkbox">
                                <input type="checkbox" name="chronic_others_identifier[]" value="TB">
                                <i></i>TB</label>

                                <label class="checkbox">
                                    <input type="checkbox" name="chronic_others_identifier[]" value="XDR">
                                    <i></i>XDR</label>
                                </div>
                                <div class="col col-3">
                                    <label class="checkbox">
                                    <input type="checkbox" name="chronic_others_identifier[]" value="MDR">
                                    <i></i>MDR</label>
                                    
                                    <label class="checkbox">
                                    <input type="checkbox" name="chronic_others_identifier[]" value="Leprosy">
                                        <i></i>LEPROSY</label>
                                    <label class="checkbox">
                                    <input type="checkbox"  name="chronic_others_identifier[]" value="Any Abscess">
                                        <i></i>Any Abscess</label>
                                </div>
                                <div class="col col-3">
                                    <label class="checkbox">
                                        <input type="checkbox" name="chronic_others_identifier[]" value="Polio">
                                        <i></i>Polio</label>
                                    <label class="checkbox">
                                        <input type="checkbox" name="chronic_others_identifier[]" value="Dysentry">
                                            <i></i>Dysentry</label>
                                    <label class="checkbox">
                                        <input type="checkbox" name="chronic_others_identifier[]" value="Malaria">
                                        <i></i>Malaria</label>
                                </div>
                                <div class="col col-3">
                                    <label class="checkbox">
                                        <input type="checkbox" name="chronic_others_identifier[]" value="Typhoid">
                                        <i></i>Typhoid</label>
                                        <label class="checkbox">
                                            <input type="checkbox" name="chronic_others_identifier[]" value="Cholera">
                                            <i></i>Cholera</label>
                                            <label class="checkbox">
                                                <input type="checkbox"  name="chronic_others_identifier[]" value="Others">
                                                <i></i>Others</label>
                                            </div>
                                        </div>
                                        
                                    </div>

                                    <!--END OTHER CHRONIC PROBLEMS  -------------------------------------------------------->
                                </div>
                            </div>
                        </div>
                        <!-- Chornic End-->
                        
                    </fieldset>
            <BR>
            <fieldset>
                <legend><h3 class="text-primary">PROBLEM INFO - DESCRIPTION</h3></legend>
                <section>
                    <label class="textarea">
                    <textarea rows="8" cols="130" name="page2_ProblemInfo_Description" id="page2_ProblemInfo_Description" class="custom-scroll" placeholder="Describe problem details..."></textarea> 
                    </label>

                </section>
            </fieldset>

            <fieldset>

                <legend><h3 class="text-primary">REVIEW INFO</h3></legend>
                <section class="col-sm-12">
                    <label class="label">REQUEST TYPE</label>
                    <label class="select">
                    <select name="page2_ReviewInfo_RequestType" id="page2_ReviewInfo_RequestType">
                        <option value="0">Choose an option</option>
                        <option value="Normal">Normal</option>
                        <option value="Emergency">Emergency</option>
                        <option value="Chronic">Chronic</option>
                        <option value="Deficiency">Deficiency</option>
                        <option value="Defects">Defects</option>
                    </select> <i></i> </label>
                </section>

                <section class="col-sm-12">
                    <label class="label">STATUS</label>
                    <label class="select">
                        <select name="page2 ReviewInfo Status" id="selected_status">
                            <option value="0">Choose an option</option>
                            <option selected value="Initiated">Initiated</option>
                             <option value="Out-Patient">Out-Patient</option>
                            <option value="Prescribed">Prescribed</option>
                             <option value="Review">Review</option>
                            <option value="Follow-up">Follow-up</option>
                            <option value="Hospitalized">Hospitalized</option>
                            <option value="Under Medication">Under Medication</option>
                            <option value="Surgery-Needed">Surgery-Needed</option>
                            <option value="Discharge">Discharge</option>
                            <option value="Cured">Cured</option>
                             <option value="Expired">Expired</option>
                        </select> <i></i> </label>
                    </section>
            </fieldset>
                <!-- end status -->




                <div class="col-sm-12">
                    <fieldset class="demo-switcher-1">
                        <div class="panel panel-default">
                            <div class="panel-heading  text-center"><strong>Attachments</strong></div>

                                <!-- <div class="form-group ">


                                <input type="file" id="files"  name="hs_req_attachments[]" style="display:none;" multiple>
                                <label for="files" class="btn btn-primary btn-lg" style="margin-left: 100px; width: -webkit-fill-available; margin-right: 100px;">
                                Browse.....
                                </label>
                                </div> -->
                                <ul class="nav nav-tabs md-tabs nav-justified primary-color" role="tablist">
                                <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#panel668" role="tab">
                                <!-- <i class="fa fa-heart pr-2"> </i>-->Prescriptions</a>
                                </li>
                                <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#panel555" role="tab">
                                <!-- <i class="fa fa-user pr-2"></i> -->Lab Reports</a>
                                </li>
                                <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#panel666" role="tab">
                                <!-- <i class="fa fa-heart pr-2"> </i>-->X-ray/MRI/Digital Images</a>
                                </li>
                                <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#panel667" role="tab">
                                <!-- <i class="fa fa-heart pr-2"> </i>-->Payments/Bills</a>
                                </li>
                                <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#panel669" role="tab">
                                <!-- <i class="fa fa-heart pr-2"> </i>-->Discharge Summary</a>
                                </li>
                                <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#panel780" role="tab">
                                <!-- <i class="fa fa-heart pr-2"> </i>-->Special Diet</a>
                                </li>
                                <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#panel770" role="tab">
                                <!-- <i class="fa fa-heart pr-2"> </i>-->Others</a>
                                </li>
                                </ul>
                                <!-- Nav tabs -->

                                <!-- Tab panels -->
                                <div class="tab-content">

                                <!-- Panel 1 -->
                                <div class="tab-pane fade" id="panel555" role="tabpanel">

                                <!-- Nav tabs -->

                                <input type="file" id="files_labs"  name="Lab_Reports[]" style="display:none;" multiple/>

                                <label for="files_labs" class="btn btn-primary btn-lg" style="margin-left: 100px; width: -webkit-fill-available; margin-right: 100px;">
                                Labs Reports.....
                                </label>



                                </div>
                                <!-- Panel 1 -->

                                <!-- Panel 2 -->
                                <div class="tab-pane fade" id="panel666" role="tabpanel" >

                                <input type="file" id="files_xray"  name="Digital_Images[]" style="display:none;" multiple>


                                <label for="files_xray" class="btn btn-primary btn-lg" style="margin-left: 100px; width: -webkit-fill-available; margin-right: 100px;">
                                X-ray/MRI/ Digital Images.....

                                </label>

                                </div>
                                <!-- Panel 2 -->
                                <!-- Panel 2 -->
                                <div class="tab-pane fade" id="panel667" role="tabpanel">
                                <input type="file" id="files_bills"  name="Payments_Bills[]" style="display:none;" multiple>
                                <label for="files_bills" class="btn btn-primary btn-lg" style="margin-left: 100px; width: -webkit-fill-available; margin-right: 100px;">
                                Payments Bills attachments.....
                                </label>
                                </div>
                                <div class="tab-pane fade" id="panel669" role="tabpanel">
                                <input type="file" id="files_ds"  name="Discharge_Summary[]" style="display:none;" multiple>
                                <label for="files_ds" class="btn btn-primary btn-lg" style="margin-left: 100px; width: -webkit-fill-available; margin-right: 100px;">
                                Discharge Summary.....
                                </label>
                                </div>
                                <!-- Panel 2 -->
                                <!-- Panel 2 -->
                                <div class="tab-pane fade" id="panel668" role="tabpanel">
                                <input type="file" id="files_prescriptions"  name="Prescriptions[]" style="display:none;" multiple>
                                <label for="files_prescriptions" class="btn btn-primary btn-lg" style="margin-left: 100px; width: -webkit-fill-available; margin-right: 100px;">
                                Prescriptions.....
                                </label>
                                </div>

                                <div class="tab-pane fade"  id="panel780" role="tabpanel">
                                <input type="file" id="special_diet"  name="special_diet_pics[]" style="display:none;" multiple>
                                <label for="special_diet" class="btn btn-primary btn-lg" style="margin-left: 100px; width: -webkit-fill-available; margin-right: 100px;">
                                Special Diet Pics.....
                                </label>
                                </div>

                                <div class="tab-pane fade"  id="panel770" role="tabpanel">
                                <input type="file" id="files"  name="hs_req_attachments[]" style="display:none;" multiple>
                                <label for="files" class="btn btn-primary btn-lg" style="margin-left: 100px; width: -webkit-fill-available; margin-right: 100px;">
                                Others.....
                                </label>
                                </div>
                              

                            </div>


                        </div>

                    </fieldset>
                </div>

            </div>

        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-link waves-effect">Raise Request</button>
            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal" id="reset_close">CLOSE</button>
        </div>
    </div>
</div>
</div>
<?php form_close(); ?>      
            

<!-- ==========================CONTENT ENDS HERE ========================== -->
<?php 
    //include required scripts
    include("inc/scripts.php"); 
?>

<script src="<?php echo JS; ?>jquery-ui.min - pie.js"></script>
<script src="<?php echo JS; ?>sweetalert.min.js"></script>

<?php 
    //include footer
    include("inc/footer.php"); 
?>


<script type="text/javascript">

    date_wise_saved_notes();

    $(document).ready(function(){
        $(document).on('click','.schedule_followup',function(){

         var uid = $(this).attr('uniqueID');
         var name = $(this).attr('studname');
         var scl = $(this).attr('scl_name');
         var cls = $(this).attr('cls');
         var sec = $(this).attr('sec');
         var types = $(this).attr('req_type');
         var district = $(this).attr('dist');
         var discription = $(this).attr('dis_data');
         
        /* var cid = $(this).attr('cid');
         var hs_num = $(this).attr('hs_num');
         var prc_num = $(this).attr('prc_num');
          //alert(hs_num);
        
         var medicine = $(this).attr('medicine');
         var description = $(this).attr('description');
         console.log("medicine", medicine);*/

         $('#student_health_id').text(uid);
         $('#stud_name').text(name);
         $('#stud_scl').text(scl);
         $('#stud_cls').text(cls);
         $('#stud_sec').text(sec);
         $('#req_type').text(types);
         $('#stud_dist').text(district);
         $('#page2_ProblemInfo_Description').text(discription);

         /*$('#case_id').val(cid);
         $('#student_hs_num').text(hs_num);
         $('#student_hs_numb').val(hs_num);
         $('#student_prc_num').text(prc_num);
         $('#student_prc_numb').val(prc_num);
         $('#medicine').text(medicine);
         $('#description').text(description);*/
         $("#followup_modal").modal("show")
          
        });
    });

    $('.datepicker').datepicker({
        minDate: new Date(1900, 10 - 1, 25)
     });

    $('#normal').click(function(){

        $('.general_related').show();
        $('.emergency_related').hide();

        $('#chronic_eyes').attr('checked',false);
        $('.chronic_related').hide();
        

        /*var oldoptions = [];

        $("[type=radio]").on('click', function () {
            $("#page2_ReviewInfo_RequestType").append(oldoptions);
            oldoptions = $("#page2_ReviewInfo_RequestType option:not(:contains(" + $(this).val() + "))").detach();
        });*/


    });

    $('#emergency').click(function(){
        $('.emergency_related').show();
        $('.general_related').hide();
        $('.chronic_related').hide();
    });
    //CHRONIC RELATED SCRIPT
    
    $('#chronic').click(function(){
        $('.chronic_related').show();
        $('.general_related').hide();
        $('.emergency_related').hide();
    });

    $('#getData').click(function(){

        date_wise_saved_notes();
    });
     /*Data table showing*/

    function date_wise_saved_notes(){

        var start_date = $('#set_data').val();
        //var end_date = $('#set_data_two').val();

        

        $.ajax({
        url: 'date_wise_saved_notes',
        type: 'POST',
        data: {'start_date': start_date },
        success: function (data) {          

            var result = $.parseJSON(data);
            console.log('datafronnnnnnnnnnnnnnn', result);
            saved_data_in_table(result);
            
            },
            error:function(XMLHttpRequest, textStatus, errorThrown)
            {
             console.log('error', errorThrown);
            }
        });
     }

    function saved_data_in_table(result){

        data_table = '<table id="table_id" class="table table-striped table-bordered display" width="100%"><thead><tr><th>Unique ID</th><th>Student Name</th><th>School Name</th><th>Class</th><th>Section</th><th>Request Type</th><th>Sub Request Type</th><th>Problem Info</th><th>Status</th><th>Raise Request</th></tr></thead><tbody>';

        $.each(result, function() {
            data_table = data_table + '<tr>';
            data_table = data_table + '<td>'+this.doc_data['unique_id']+'</td>';
            data_table = data_table + '<td>'+this.doc_data['student_name']+'</td>';
            data_table = data_table + '<td>'+this.doc_data['school_name'] +'</td>';
            data_table = data_table + '<td>'+this.doc_data['class'] +'</td>';
            data_table = data_table + '<td>'+this.doc_data['section'] +'</td>';
            data_table = data_table + '<td>'+this.doc_data['request_type']+'</td>';
            data_table = data_table + '<td>'+this.doc_data['sub_type'] +'</td>';
            data_table = data_table + '<td>'+this.doc_data['problem_info'] +'</td>';
            data_table = data_table + '<td>'+this.doc_data['status'] +'</td>';

           /* var splitString = this.doc_data['student_name'].split(' , ');
            var nameOfStudent = splitString[0];
            var idOfStudent = splitString[1];*/
            //console.log("splt name checking", nameOfStudent);
            //console.log("splt ID checking", idOfStudent);

            var condition = this.doc_data['requestRaisedOrNot'];
            var alreadyRaised = this.doc_data['req_already_raised_by_hs'];

            if (alreadyRaised == "Yes") {
                data_table = data_table + '<td><button uniqueID ='+this.doc_data['unique_id']+' studname ='+this.doc_data['student_name']+' scl_name='+this.doc_data['school_name']+' cls='+this.doc_data['class']+' sec='+this.doc_data['section']+' req_type='+this.doc_data['request_type']+' dist='+this.doc_data['district']+' dis_data = '+this.doc_data['problem_info']+' class="btn bg-color-yellow txt-color-white btn-xs schedule_followup" raiseType="already_raised">Update</button></td>';
            }else{

                if (condition == 0){
                    data_table = data_table + '<td><button uniqueID ='+this.doc_data['unique_id']+' studname ='+this.doc_data['student_name']+' scl_name='+this.doc_data['school_name']+' cls='+this.doc_data['class']+' sec='+this.doc_data['section']+' req_type='+this.doc_data['request_type']+' dist='+this.doc_data['district']+' dis_data = '+this.doc_data['problem_info']+' class="btn bg-color-greenDark txt-color-white btn-xs schedule_followup">New Request</button></td>';
                }else if(condition == 1){
                    data_table = data_table + '<td><button uniqueID ='+this.doc_data['unique_id']+' studname ='+this.doc_data['student_name']+' scl_name='+this.doc_data['school_name']+' cls='+this.doc_data['class']+' sec='+this.doc_data['section']+' req_type='+this.doc_data['request_type']+' dist='+this.doc_data['district']+' dis_data = '+this.doc_data['problem_info']+' class="btn bg-color-blue txt-color-white btn-xs schedule_followup">Update Request</button></td>';
                }
            }
            
            
            data_table = data_table + '</tr>';
       });

        data_table = data_table + '</tbody></table>';

        $('#selected_span_saved_data').html(data_table);


    }




</script>
