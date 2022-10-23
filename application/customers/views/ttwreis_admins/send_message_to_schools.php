<?php $current_page = "send_message"; ?>
<?php $main_nav = ""; ?>
<?php include('inc/header_bar.php'); ?>
<?php include('inc/sidebar.php'); ?>

<!-- Bootstrap Material Datetime Picker Css -->
<link href="<?php echo(MDB_PLUGINS.'bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css'); ?>" rel="stylesheet" />


<section class="content">
    <div class="container-fluid">
        <div class="block-header">
           <!--  <h2>BASIC FORM ELEMENTS</h2> -->
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                    <h3 class="col-green">Send Message to Schools</h3>
                    <ul class="header-dropdown m-r--5">
                        <div class="button-demo">
                        <button type="button" class="btn bg-pink waves-effect" onclick="window.history.back();">Back</button>
                        </div>
                    </ul>
                    </div>
                    <div class="body">
                        <div class="row clearfix">
                            <div class="col-sm-3">
                                <label>Select District</label>
                                 <select id="select_dt_name" class="form-control">
                                    <option value="All" selected="">All</option>
                                    <?php if(isset($distslist)): ?>
                                   
                                        <?php foreach ($distslist as $dist):?>
                                        <option value='<?php echo $dist['_id']; ?>' ><?php echo ucfirst($dist['dt_name'])?></option>
                                        <?php endforeach;?>
                                        <?php else: ?>
                                        <option value="1"  disabled="">No District entered yet</option>
                                    <?php endif ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <label>Select School</label>                                
                                <select class="form-control show-tick" id="school_name" disabled=true >
                                    <option value="All"  selected="">All</option>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <label>Whom to send</label>                                
                                <select class="form-control show-tick" id="check">
                                    <option value="All"  selected="">All</option>
                                    <option value="HS">HS</option>
                                    <option value="PRINCIPAL">PRINCIPAL</option>
                                </select>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-sm-3">
                                <h5>Message Regarding</h5>
                                <input type="text" name="" id="regard_id" class="form-control" required>
                            </div>
                            <div class="col-sm-3">
                                <h5>Type Message</h5>
                                <textarea name="message" id="messages" cols="33"></textarea>
                            </div>
                            
                        </div>
                        <div class="row clearfix">
                            <div class="col-sm-3">
                                <br>
                                <br>
                                <button type="submit" class="btn btn-primary waves-effect" id="submit_request" >Send Message</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <div class="row clearfix">
                            <div class="col-sm-2">
                                <h3 class="font-bold col-green">Message Data</h3>
                            </div>
                            <div class="collapse" id="message_collapse">
                                   <div class="col-sm-2">
                                     <?php $end_date  = date ( "Y-m-d", strtotime ( date('yy-m-d') . "-90 days" ) ); ?>
                                        <span id="monitoring_datepicker">
                                         Start Date :
                                        <input type="text" id="passing_date" name="passing_date" class="form-control date" value="<?php echo $end_date; ?>">
                                        </span>
                                   </div>
                                   <div class="col-sm-2">
                                        <span id="monitoring_datepicker">
                                        End Date :
                                        <input type="text" id="passing_end_date" name="passing_end_date" class="form-control date" value="<?php echo date('yy-m-d'); ?>">
                                        </span>
                                   </div>
                                   <div class="col-sm-2">
                                       <label>Whom to send</label>                                
                                       <select class="form-control show-tick" id="check_table">
                                           <option value="All"  selected="">All</option>
                                           <option value="HS">HS</option>
                                           <option value="PRINCIPAL">PRINCIPAL</option>
                                       </select>
                                   </div>
                                   <div class="col-sm-2">
                                       <div class="form-line">
                                             <button type="button" id="date_set" class="btn bg-green btn-circle-lg waves-effect waves-circle waves-float">
                                             <i class="material-icons">search</i>
                                             </button>
                                       </div>
                                   </div>
                            </div>
                            <div class="col-sm-2 pull-right">
                                <ul class="header-dropdown m-r--5">
                                    <li><button class="btn btn-default" type="button" data-toggle="collapse" data-target="#message_collapse" aria-expanded="false" aria-controls="message_collapse"  data-placement="bottom" title="Date Filters"><img src="<?php echo IMG ;?>/funnel.png"></button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                   <div class="body">
                        <div id="stud_report"></div>
                    </div>
                </div>
               
            </div>
        </div>

</div>

</section>

<form style="display: hidden" action="<?php echo URL; ?>panacea_mgmt/get_messages_to_show_schools" method ="POST" id="submit_for_msg">
    <input type="hidden" id="msg" name="msg" value=""/>
</form>

<?php //include('inc/footer_bar.php'); ?>

<?php include("inc/message_status.php"); ?>
    <!-- Jquery Core Js -->
    <script src="<?php echo(MDB_PLUGINS.'jquery/jquery.min.js'); ?>"></script>

    <!-- Bootstrap Core Js -->
    <script src="<?php echo(MDB_PLUGINS.'bootstrap/js/bootstrap.js'); ?>"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="<?php echo(MDB_PLUGINS.'jquery-slimscroll/jquery.slimscroll.js'); ?>"></script>

    <!-- Bootstrap Notify Plugin Js -->
    <script src="<?php echo(MDB_PLUGINS.'bootstrap-notify/bootstrap-notify.js'); ?>"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="<?php echo(MDB_PLUGINS.'node-waves/waves.js'); ?>"></script>
 
  <!-- Jquery DataTable Plugin Js -->
    <script src='<?php echo MDB_PLUGINS."jquery-datatable/jquery.dataTables.js"; ?>'></script>
    <script src="<?php echo MDB_PLUGINS."jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"; ?>"></script>
    <script src='<?php echo MDB_PLUGINS."jquery-datatable/extensions/export/dataTables.buttons.min.js"; ?>'></script>
    <script src='<?php echo MDB_PLUGINS."jquery-datatable/extensions/export/buttons.flash.min.js"; ?>'></script>
    <script src='<?php echo MDB_PLUGINS."jquery-datatable/extensions/export/jszip.min.js"; ?>'></script>
    <script src='<?php echo MDB_PLUGINS."jquery-datatable/extensions/export/pdfmake.min.js"; ?>'></script>
    <script src='<?php echo MDB_PLUGINS."jquery-datatable/extensions/export/vfs_fonts.js"; ?>'></script>
    <script src='<?php echo MDB_PLUGINS."jquery-datatable/extensions/export/buttons.html5.min.js"; ?>'></script>
    <script src='<?php echo MDB_PLUGINS."jquery-datatable/extensions/export/buttons.print.min.js"; ?>'></script>

    <script src="<?php echo(MDB_JS.'admin.js'); ?>"></script>
    <script src='<?php echo MDB_JS."pages/tables/jquery-datatable.js"; ?>'></script>
    <script src='<?php echo MDB_JS."pages/ui/modals.js"; ?>' ></script>

    <!-- Demo Js -->
    <script src="<?php echo(MDB_JS.'demo.js'); ?>"></script>
    <!-- Moment Plugin Js -->
    <script src="<?php echo(MDB_PLUGINS.'momentjs/moment.js'); ?>"></script>
    <script src="<?php echo(MDB_PLUGINS.'bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js'); ?>"></script>
    <script src="<?php echo(MDB_PLUGINS.'bootstrap-datepicker/js/bootstrap-datepicker.js');?>"></script>
    <script src="<?php echo MDB_PLUGINS.'bootstrap-notify/bootstrap-notify.js'; ?>"></script>

 
<script type="text/javascript">

$('#submit_request').click(function(){
   
    send_message_with_selected_filters();
});
   

    $('#select_dt_name').change(function(e){
        /*var datas = $('#select_dt_name').val();
         alert(datas);*/
        dist = $('#select_dt_name').val();
        dt_name = $("#select_dt_name option:selected").text();
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
                options.empty();
                options.append($("<option />").val("All").prop("selected", true).text("All"));
                $.each(result, function() {
                    options.append($("<option />").val(this.school_code).text(this.school_name));
                });
                       
                },
                error:function(XMLHttpRequest, textStatus, errorThrown)
                {
                 console.log('error', errorThrown);
                }
            });
    });

    function send_message_with_selected_filters(){
       
        var dist = $('#select_dt_name').val();
        var scl = $('#school_name').val();
        var sent_type = $('#check').val();
        var message_regards = $('#regard_id').val();
        var message = $('#messages').val();
        /*alert(dist);
        alert(scl);
        alert(sent_type);
        alert(message_regards);
        alert(message);*/
       
        $.ajax({
        url: 'send_message_data_to_schools',
        type: 'POST',
        data: {'dist_name':dist, 'scl_name':scl, 'selected_type':sent_type, 'regarding':message_regards, 'message':message},
        success: function(data){
            console.log(data);
        },
        error:function(XMLHttpRequest, textStatus, errorThrown)
           {
            console.log('error', errorThrown);
           }
        });
    }
</script>


<script type="text/javascript">

    $('.date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });

    list_for_sent_messeges();

    $('#date_set').click(function(){
        list_for_sent_messeges();
    });

    function list_for_sent_messeges(){
        var start = $('#passing_date').val();
        var end = $('#passing_end_date').val();
        var sent_ppl = $('#check_table').val();
        
        $.ajax({
            url:'messages_list_data',
            type:'POST',
            data: {'start_date':start, 'end_date': end, 'sent_type':sent_ppl},
            success:function(data){
                var result = $.parseJSON(data);

                data_table_message(result);

            }
        });

    }

  
    function data_table_message(result)
    {
       // console.log(result);
       
            data_table = '<table id="more_requests" class="table table-striped table-bordered" width="100%">  <thead><tr><th class="hide">id</th> <th>Date and Time</th><th>Message Title</th><th>Sent To</th><th>School count</th><th>Action</th></tr> </thead> <tbody>';

            $.each(result, function() {
                 //console.log(result);
                //console.log(this.doc_data.widget_data["page2"]['Personal Information']['AD No']);
                data_table = data_table + '<tr>';
                var ids = Object.values(this['_id']);
                data_table = data_table + '<td class="hide">'+ids + '</td>';
                data_table = data_table + '<td>'+this.history.time + '</td>';
                data_table = data_table + '<td>'+this.doc_data.widget_data['page1']['title'] + '</td>';                
                data_table = data_table + '<td>'+this.doc_data.widget_data['page1']['sent_to'] + '</td>';

                var sclcount = this.doc_data.widget_data['page1']['schools'];
                var count = sclcount.length;
                data_table = data_table + '<td>'+ count + '</td>';

                data_table = data_table+'<td class="btn btn-primary btn-xs get_mes">Show Message</td>';
               
             /*   var mobile_numb = (typeof this.doc_data.widget_data["page1"]['Personal Information']['Mobile'] !== 'undefined' ? this.doc_data.widget_data["page1"]['Personal Information']['Mobile']['mob_num'] : "Not mention")
                data_table = data_table + '<td>'+ mobile_numb + '</td>';
                
                var urlLink = "https://mednote.in/PaaS/healthcare/index.php/";
                var obj = Object.values(this['_id']);
                data_table = data_table + '<td><a class="btn btn-primary btn-xs" href="'+urlLink+'panacea_mgmt/panacea_reports_display_ehr_uid/?id = '+this.doc_data.widget_data["page1"]['Personal Information']['Hospital Unique ID']+'">Show EHR</a></td>';*/

                data_table = data_table + '</tr>';
                
                    
            });

            data_table = data_table + '</tbody></table><div><button type="button" class="btn btn-primary pull-right" onclick="window.history.back();">Back</button></div>';

            $("#stud_report").html(data_table);

                $('#more_requests').DataTable({
                "paging": true,
                "lengthMenu" : [5, 25, 50, 75, 100]
              });

            $("#more_requests").each(function(){
                $(".get_mes").click(function(){
                    var currentRow = $(this).closest("tr");
                    var msg_id = currentRow.find("td:eq(0)").text();
                    //alert(msg_id);
                    $("#msg").val(msg_id);
                    $("#submit_for_msg").submit();
                    
                });
            });
        
     
    }

</script>