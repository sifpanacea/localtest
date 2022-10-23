<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Group Messaging";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["chat"]['sub']['group_msg']["active"] = true;
include("inc/nav.php");

?>
<link href="<?php echo(CSS.'gcm_style.css'); ?>" rel="stylesheet" type="text/css" />
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["BC Welfare Masters"] = "";
		include("inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content">
	
	
	
	
<div class="row">
     				<!-- NEW WIDGET START -->
				<article class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
		
					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-0" data-widget-editbutton="false">
						
						<header>
							<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
							<h2>Messaging </h2>
		
						</header>
		
						<!-- widget div-->
						<div>
		
							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->
		
							</div>
							<!-- end widget edit box -->
		
							<!-- widget content -->
							<div class="widget-body">
							<h2>Sending message to a `topic`</h2>
							<div class="well well-sm well-light">
							
							<div class="box">
                    <div class="usr_container">
                        <ul id="topics">
                            <?php
                            foreach ($groups as $key => $group) {
                                $cls = $key == 0 ? '' : '';
                                ?>
                                <li id="<?= $group['group_name'] ?>" class="<?= $cls ?>">
                                    <label><?= $group['group_name'] ?></label>
                                    <span>topic_<?= $group['group_name'] ?></span>
                                </li>
                                <?php
                            }
                            ?>
                        </ul>
                    </div>
                    <div class="msg_container msg_container_topic">
                        <ul id="topic_messages"></ul>
                    </div>
                    <div class="send_container">
                        <textarea placeholder="Type a message here" id="send_to_topic_message"></textarea>
                        <input id="send_to_topic" type="button" value="Send to Topic"/>
                        <img src="<?php echo(IMG.'loading.gif'); ?>" id="loader_topic" class="loader"/>
                    </div>
                    <div class="clear"></div>
                </div>
                
							</div>
							</div>
							<!-- end widget content -->
		
						</div>
						<!-- end widget div -->
		
					</div>
					<!-- end widget -->
					</article>
        
        </div><!-- ROW -->

	</div>
	<!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->

<!-- ==========================CONTENT ENDS HERE ========================== -->

<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>

<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->
<script src="<?php echo JS; ?>jquery-ui.min - pie.js"></script>
<?php 
	//include footer
	include("inc/footer.php"); 
?>

<script>
var user_id = '<?= $admin_id ?>';
$(document).ready(function () {

    getChatroomMessages($('#topics li:first').attr('id'));

    $('ul#topics li').on('click', function () {
        $('ul#topics li').removeClass('');
        $(this).addClass('');
        getChatroomMessages($(this).prop('id'))
    });

    function getChatroomMessages(id) {
        $.getJSON("get_messages/" + id, function (data) {
            var li = '';
            $.each(data.messages, function (i, message) {
                
                if(message.user.username == user_id ){
                	li += '<li class="self"><label class="name">' + message.user.username + '</label><div class="message">' + message.message + '</div><div class="clear"></div></li>';
                }else{
                	li += '<li class="others"><label class="name">' + message.user.username + '</label><div class="message">' + message.message + '</div><div class="clear"></div></li>';
                }
            });
            $('ul#topic_messages').html(li);
            if (data.messages.length > 0) {
                scrollToBottom('msg_container_topic');
            }
        }).done(function () {

        }).fail(function () {
            $.smallBox({
 				title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Message!",
 				content : "Sorry! Unable to fetch topic messages",
 				color : "#C46A69",
 				iconSmall : "fa fa-bell bounce animated",
 				
 			});
        }).always(function () {

        });

        // attaching the chatroom id to send button
        $('#send_to_topic').attr('chat_room', id);
    }

    $('#send_to_topic').on('click', function () {
        var msg = $('#send_to_topic_message').val();
        if (msg.trim().length === 0) {
            $.smallBox({
 				title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Message!",
 				content : "Enter a message",
 				color : "#C46A69",
 				iconSmall : "fa fa-bell bounce animated",
 			});
            return;
        }

        $('#send_to_topic_message').val('');
        $('#loader_topic').show();

        $.post("get_messages/" + $(this).attr('chat_room') + '/message',
                {user_id: user_id, message: msg},
        function (data) {
                    data = JSON.parse(data);
            if (data.error === false) {
                var li = '<li class="self" tabindex="1"><label class="name">' + data.user.name + '</label><div class="message">' + data.message.message + '</div><div class="clear"></div></li>';
                $('ul#topic_messages').append(li);
                scrollToBottom('msg_container_topic');
            } else {
                $.smallBox({
     				title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Message!",
     				content : "Sorry! Unable to send message",
     				color : "#C46A69",
     				iconSmall : "fa fa-bell bounce animated",
     			});
            }
        }).done(function () {

        }).fail(function () {
            $.smallBox({
 				title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Message!",
 				content : "Sorry! Unable to send message",
 				color : "#C46A69",
 				iconSmall : "fa fa-bell bounce animated",
 			});
        }).always(function () {
            $('#loader_topic').hide();
        });
    });

    function scrollToBottom(cls) {
        $('.' + cls).scrollTop($('.' + cls + ' ul li').last().position().top + $('.' + cls + ' ul li').last().height());
    }
});
</script>