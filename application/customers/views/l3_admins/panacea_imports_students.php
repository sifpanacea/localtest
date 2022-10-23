<?php $current_page = "Import Students"; ?>
<?php $main_nav = "Imports"; ?>
<?php include("inc/header_bar.php"); ?>
<?php include("inc/sidebar.php"); ?>

<section class="content">
        
        <div class="row clearfix">
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                <div class="card"> 
                    <div class="header">
                        <h2>Import New Students </h2>
                        <ul class="header-dropdown m-r--5">
                            <div class="button-demo">
                            <button type="button" class="btn bg-pink waves-effect" onclick="window.history.back();">Back</button>
                            </div>
                        </ul>
                    </div>
                    <?php 
                    $attributes = array('class' => 'smart-form');
                    echo form_open_multipart('panacea_mgmt/import_students',$attributes);?>
                    <div class="body">
                        <div class="row clearfix">
                            
                                <div class="col-sm-4">
                                     <label for="first_name">District Name</label>
                                    <select class="form-control show-tick" id="select_dt_name" name="select_dt_name">
                                            <option value="" selected="0" disabled="">Select a district</option>
                                                <?php if(isset($districtlist)): ?>
                                                <?php foreach ($districtlist as $dist):?>
                                                <option value='<?php echo $dist['_id']?>' ><?php echo ucfirst($dist['dt_name'])?></option>
                                                <?php endforeach;?>
                                                <?php else: ?>
                                                <option value="1"  disabled="">No district entered yet</option>
                                                <?php endif ?>
                                    </select><i></i><br>
                                </div>
                                <div class="col-sm-8">
                                    <label for="first_name">School Name</label>
                                    <select id="school_name" class="col col-6 form-control show-tick school_names" name="school_name" disabled=true>
                                        <option value="0" selected="" disabled="">Select a district first</option>
                                    </select><i></i>
                                </div>  
                            
                        </div>
                
                <div class="custom-control custom-radio import_type">
                  <input type="radio" class="custom-control-input" id="import_type" name="import_type" value="personal_info" checked <?PHP echo set_radio('import_type','1',TRUE); ?>>
                  <label class="custom-control-label" for="defaultUnchecked">Only Personal Information</label>
                
                </div>

                <div class="panel-body">
                        <form action="/" id="frmFileUpload" class="dropzone" method="post" enctype="multipart/form-data">
                            <div class="dz-message fallback">
             <span class="button"><input name="file" type="file" accept=".xls,.xlsx" onchange="this.parentNode.nextSibling.value = this.value" required style=" border: 1px solid #ccc;display: inline-block;padding: 6px 58px;cursor: pointer;  border-radius: 5px;" multiple />
                               </span>
                            </div><br>
                        <button type="submit" class="btn bg-indigo waves-effect" name="submit" id="sbt" data-toggle="modal" data-target="#import_waiting" data-backdrop="static" data-keyboard="false">
                        Import
                        </button>
                    </form>
                </div>
                <?php echo form_close();?>
                 </div>
                </div>
            </div>

                 <!-- Update student profile -->
        <div class="row clearfix hidden">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <div class="card"> 
                    <div class="header">
                        <h2>Update Students Details</h2>
                    </div>
                   <?php 
                    $attributes = array('class' => 'smart-form');
                    echo form_open_multipart('panacea_mgmt/update_students',$attributes);?>
                    
                     
                        <div class="row clearfix">
                                <div class="col-sm-12">
                                <div class="panel-body">
                                     <p>Select a excel sheet containing Hospital Unique ID of students and there fields to update
                                        </p>
                                        <form action="/" id="frmFileUpload" class="dropzone" method="post" enctype="multipart/form-data">
                                            <div class="dz-message fallback">
                                                <span class="button"><input name="file" type="file" style=" border: 1px solid #ccc;display: inline-block;padding: 6px 58px;cursor: pointer;  border-radius: 5px;" multiple />
                                               </span>
                                            </div>                                        
                                       </form><br>
                                
                                    <p class="alert alert-info no-margin">
                                     Note: Only personal information are updated. All column values should be of <code>text</code> type. 
                                     </p>
                                     <button type="submit" class="btn bg-indigo waves-effect" name="submit" id="sbt" data-toggle="modal" data-target="#import_waiting" data-backdrop="static" data-keyboard="false">
                                        Update
                                     </button>
                                        </div>
                                     <br><br>
                                </div>
                        </div>
                    
                
                <?php echo form_close();?>
                </div>
            </div>
        </div>
        </div>
        </section>
                    
            </div>
                            
                                            
<!-- END MAIN PANEL -->

<!-- ==========================CONTENT ENDS HERE ========================== -->

<?php 
    //include required scripts
    include("inc/scripts.php"); 
        
?>
<script src="<?php echo(JS.'dynamic-add-import.js'); ?>" type="text/javascript"></script>
<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
<script> 
        function displayRadioValue() { 
            var ele = document.getElementsByName('gender'); 
              
            for(i = 0; i < ele.length; i++) { 
                if(ele[i].checked) 
                document.getElementById("result").innerHTML
                        = "Gender: "+ele[i].value; 
            } 
        } 
    </script> 
<script>
//$(document).ready(function() {
<?php if($message) {?>
$.smallBox({
                title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp;Import Failed!",
                content : "<?php echo $message?>",
                color : "#C79121",
                iconSmall : "fa fa-bell bounce animated"
            });
<?php } ?>

    $("#file").prop('disabled', true);

/*For Clss Update*/
    $('#select_dt_name').change(function(e){
        dist = $('#select_dt_name').val();
        console.log(dist, "disttttt");
        //alert(dist);
                
        var options = $("#school_name");
        options.prop("disabled", true);
        options.append($("<option />").val("0").prop("disabled", true).prop("selected", true).text("Fetching schools list..."));
        $.ajax({
            url: 'get_schools_list',
            type: 'POST',
            data: {"dist_id" : dist},
            success: function (data) {          

                result = $.parseJSON(data);
                console.log(result)

                options.prop("disabled", false);
                $("#class_select").prop("disabled", false);
                options.empty();
                options.append($("<option />").val("0").prop("disabled", true).prop("selected", true).text("Select school"));
                $.each(result, function() {
                    options.append($("<option />").val(this.school_name).text(this.school_name));
                });             
                        
                },
                error:function(XMLHttpRequest, textStatus, errorThrown)
                {
                 console.log('error', errorThrown);
                }
        });
    });

    $('#school_name').change(function(e){
        school_name = $("#school_name option:selected").text();
        if(school_name.length != 0)
        {
            $('#file').prop('disabled', false);
        }
    });
    
    
    $('#class_select').change(function(e){
    school_name_sel = $('#school_name').val();
    class_sel = $('#class_select').val();
    $("#upgrade_sbt").prop("disabled", false);
    alert(school_name_sel);
    alert(class_sel);
    });
    
    
//=================================================================



//});
</script>


<?php 
    //include footer
    include("inc/footer_bar.php"); 
?>
