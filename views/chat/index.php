<?php
    use humhub\modules\chat\models\WBSChat;
    use humhub\modules\user\models\User;

//    ini_set("display_errors", 1);
?>

<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="<?php echo $this->context->module->assetsUrl; ?>/js/jquery.overlay.js"></script>
<script src="<?php echo $this->context->module->assetsUrl; ?>/js/textcomplete.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo $this->context->module->assetsUrl; ?>/css/chat.css"/>
<?php if(WBSChat::isChating(Yii::$app->user->id)) { ?>
<script>
    $(document).ready(function() {

                var serverAddress = '<?= isset(Yii::$app->params['serverAddress'])?Yii::$app->params['serverAddress']:'localhost/websocket' ?>';
        var port = (window.location.protocol == "https:")?'8443':('<?= isset(Yii::$app->params['port'])?Yii::$app->params['port']:'8080'?>');
        var connectString;
        var protocol;
        var exlodeStr = serverAddress.split("/");

         if(window.location.protocol == "https:") {
             protocol = 'wss';
         } else {
             protocol = 'ws';
         }

        connectString = exlodeStr[0]+"/"+ exlodeStr[1];
        var conn;
        //Set connection with server
        function connect() {
	        conn = new WebSocket(protocol+'://'+connectString+'?code=<?= Yii::$app->user->guid ?>');
	        console.log(conn);
	        conn.onopen = function(e) {
	                console.log('Connected');
	        };

	        conn.onclose = function(e) {
	            console.log('Socket is closed. Reconnect will be attempted in 1 second.', e.reason);
			    setTimeout(function() {
			      connect();
			    }, 1000)

	        }

	        conn.onerror = function(evt) {
	            console.log("The following error occurred: " + evt);
	            conn.close();
	        }


	        // On server answer
	        conn.onmessage = function(e) {
	            if(typeof JSON.parse(e.data) == "object") { //Edit message
	                var pk = JSON.parse(e.data)[0];
	                var text = JSON.parse(e.data)[1];
	                var image = JSON.parse(e.data)[2];
	                var flagImage = JSON.parse(e.data)[3];
	                var htmlTag = $(".mes [data-pk='"+pk+"']");

	                if(htmlTag.find(".mes-body").length) {
	                    htmlTag.removeAttr('style').find(".mes-body").html(text)
	                } else {
	                    htmlTag.removeAttr('style').html(text);
	                }

	                htmlTag.parents(".mes").find(".img-responsive").remove(); // remove image in another user

	                if(flagImage) {
	                    htmlTag.parents(".mes").append(image);
	                }

	            } else { // Add new message
	                $("#messages .mes:last").after(JSON.parse(e.data));
                    	$("#messages").animate({ scrollTop: $("#messages")[0].scrollHeight }, 2200);
	            }

	            //after append html add to all messages editable
	            $('.message-edit').editable({
	                placement: 'right',
	                mode: 'inline',
	                type: 'textarea',
	                rows: '1',
	                toggle: 'manual',
	                url: '<?= Yii::$app->urlManager->createUrl("chat/chat/edit"); ?>', //history of chat
	                dataType: 'post',
	                success: function(response, newValue) {
	                     // $(this).html(response);
	                },
	                display: function(value) {
	                }
	            });
	        };
    	}

    	connect();
        // popup smiles block
        $(".block-smile img").on("click",function() {
            $(".icons").toggle();
        });

        // On click smile add to input text field
        $(".icon").on("click", function() {
            var text = $(".input_text").val();
            $(".input_text").val('');
            $(".input_text").val(text +' '+ $(this).data('symbol') + ' ');
        });

        // On click submit button send to server message
        $(".send-message").on("click", function() {
            conn.send(JSON.stringify($(".input_text").val()));
            $(".input_text").val('');
        });

        // On up mouse message block show icon of editing but of current user
        $("body").on("mouseover", ".mes", function() {
            $(this).find(".edit-icon").show(100);
        });

        // On mouse leave message block, hide icon
        $("body").on("mouseleave", ".mes", function() {
            $(this).find(".edit-icon").hide(70);
        });

        // Mention enter symbol @
        $('.input_text').textcomplete([
            {
                match: /\B@(\w*)$/,
                search: function (term, callback) {
                    $.post('<?= Yii::$app->urlManager->createUrl("chat/chat/users"); ?>',function(data) { // get all user name to @mention list
                        var menu = $.parseJSON('[' + data + ']');
                        callback(menu[0]);
                    });
                },
                index: 1,
                replace: function (data) {
                    return '@' + data + ' ';
                }
            }
        ], { appendTo: 'body' }).overlay([
            {
                match: /\B@\w+/g,
                css: {
                    'background-color': '#d8dfea'
                }
            }
        ]);
	
	 $("#messages").on("mousewheel", function(e) {
            $(this).stop();
        });
	
        // On scroll history to top get last messages
        $("#messages").scroll(function(e) {
            var height = $(this).scrollTop();
            var scrollHeight = $(this).prop('scrollHeight');
            if(height == 0) {
                var count = $(".mes").length;
                $.ajax({
                    type: 'POST',
                    url: '<?= Yii::$app->urlManager->createUrl("chat/chat/history"); ?>', //history of chat
                    data: {'count':count},
                    success: function(data) {
                        $("#messages .mes:first").before(data);

                        setTimeout(function() {
                            var currentScrollHeight = $("#messages").prop('scrollHeight');
                            $("#messages").animate({scrollTop: currentScrollHeight - scrollHeight}, 100)  ;
                            console.log(currentScrollHeight);
                            console.log(scrollHeight);
                        } , 10);

                        $('.message-edit').editable({
                            placement: 'right',
                            mode: 'inline',
                            type: 'textarea',
                            rows: '1',
                            toggle: 'manual',
                            url: '<?= Yii::$app->urlManager->createUrl("chat/chat/edit"); ?>', //history of chat
                            dataType: 'post',
                            success: function(response, newValue) {
                                // $(this).html(345);
                            },
                            display: function(value) {
                                // none
                            }
                        });
                    }
                });
            }
        });

        // On reload page set up editable to all messages-edit
        $('.message-edit').editable({
            placement: 'right',
            mode: 'inline',
            type: 'textarea',
            rows: '1',
            toggle: 'manual',
            url: '<?= Yii::$app->urlManager->createUrl("chat/chat/edit"); ?>', // Edit message
            dataType: 'post',
            success: function(response, newValue) {
                // $(this).html();
            },
             display: function(value) {
                // none
            }
        });

        // Rewrite method on editin field inline
        var span;
        var image;
        $(document).on("click",".edit-icon", function(e) {
            e.stopPropagation();
            $(".editableform-loading").remove();
            span = $(this).parents(".mes").find(".message-edit");
            var text = validateText(span.html());
            span.editable('toggle');
            $('.editable-input textarea').val(text);

            image = $(this).parents(".mes").find(".img-responsive");
            image.toggleClass("hidden");
        });


        // Send to server edit text with (pk) and (text)
        $(document).on("click",".editable-submit", function() {
            var text = $(this).parents(".editableform").find(".editable-input textarea").val();
            var pk = $(this).parents(".mes").find(".message-edit").data('pk');
            var items = new Array;
            items[0] = pk;
            items[1] = text;
            var result = JSON.stringify(items);
            if(image) {
                image.parent("a").remove();
            }
            conn.send(result);

            return true;
        });

        // send message if Ctrl+Enter pressed
        $('.input_text').keydown(function (e) {
            if ((e.ctrlKey || e.metaKey) && (e.keyCode == 13 || e.keyCode == 10)) {
                conn.send(JSON.stringify($(".input_text").val()));
                $(".input_text").val('');
            }
        });

        $(document).on("click",".editable-cancel", function() {
            if(image.length) {
                image.toggleClass("hidden");
            }
        });

        // Unvalid messages
        function validateText(text)
        {
            var value = text.replace(/(<img src="(.*?)" data-symbol="(.*?)">)/g,'$3');
            value = value.replace(/(<span class="mention">(.*?)<\/span>)/g,'$2');
            value = value.replace(/(<a target="_blank" style="color:blue;text-decoration:underline;" href="(.*?)">(.*?)<\/a>)/g,'$2');
            return value;
        }

       // On load page scroll chat to bottom
        $("#messages").animate({ scrollTop: $("#messages")[0].scrollHeight }, 2200);


    });
</script>
<?php } ?>
<div class="container">
    <div class="col-xs-12">
        <h4 class="margin-none padding-bottom-sm"><strong>Chat with the</strong> TeachConnect Community</h4>
    </div>
    <br>
    <div class="col-md-8">

        <div class="form-group">
            <div class="form-control" id="messages">
                <?php echo $messages; ?>
            </div>
        </div>

        <?php if(WBSChat::isChating(Yii::$app->user->id)) { ?>
        <div class="form-group">
            <div class="profile-size-sm profile-img-navbar" style="margin-top: 10px;z-index: 100;position: absolute;float: left;">
                <img id="user-account-image profile-size-sm" class="img-rounded" src="<?= Yii::$app->user->getIdentity()->getProfileImage()->getUrl(); ?>" alt="32x32" data-src="holder.js/32x32" height="32" width="32">
                <div class="profile-overlay-img profile-overlay-img-sm"></div>
            </div>
            <textarea class="form-control input_text" rows="3" placeholder="Click here to type a chat message. Press [send] button or ctrl + enter to join the chat." style="padding-left:60px;"></textarea>

            <span class="block-smile">
                <img src='<?= \Yii::$app->request->getBaseUrl(); ?>/uploads/emojione/263a.png'/>
                <div class="popover fade icons top in" role="tooltip" id="popover353330">
                    <div class="arrow" style="left: 50%;"></div>
                    <div class="popover-emoticons"><?= $htmlImg ?></div>
                </div>
            </span>
        </div>
        <?php }  else { ?>
        <div class="form-group chat-disabled">
            <textarea disabled class="form-control input_text" rows="3" placeholder="You do not have access to post messages. Please contact site administration if you wish to be allowed to post." style="padding-left:60px;"></textarea>
                <div class="profile-size-sm profile-img-navbar" style="margin-top: -80px;z-index: 100;position: relative;float: left;">
                    <img id="user-account-image profile-size-sm" class="img-rounded" src="<?= Yii::$app->user->getIdentity()->getProfileImage()->getUrl(); ?>" alt="32x32" data-src="holder.js/32x32" height="32" width="32">
                    <div class="profile-overlay-img profile-overlay-img-sm"></div>
                </div>
        </div>
        <?php } ?>
        <div class="form-group form-group-send">
            <a class="send-message btn btn-success pull-right" onClick="return false" href="">Send</a>
        </div>
    </div>
    <div class="col-md-4">

        <div class="row">

            <div class="panel panel-default panel-tour" id="getting-started-panel1">

    <!-- Display panel menu widget -->
    <ul class="nav nav-pills preferences">
    <li class="dropdown ">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-angle-down"></i></a>
        <ul class="dropdown-menu pull-right">
            <li><a href="javascript:togglePanelUp('getting-started-panel1');" class="panel-collapse"><i class="fa fa-minus-square"></i> Collapse</a></li>
            <li><a href="javascript:togglePanelDown('getting-started-panel1');" class="panel-expand" style="display:none;"><i class="fa fa-plus-square"></i> Expand</a></li>


<!-- start: Confirm modal -->


<script type="text/javascript">

    $(document).ready(function () {
        // move modal to body
        $('#confirmModal_hide-panel-button').appendTo(document.body);



    });


    $('#confirmModal_hide-panel-button').on('shown.bs.modal', function (e) {

        // execute optional JavaScript code, when modal is showing

        // remove standard modal with
        $('#confirmModal_hide-panel-button .modal-dialog').attr('style', '');
    })


</script>
 </ul>
    </li>
</ul>

<script type="text/javascript">

    $(document).ready(function() {

        // check and set panel state from cookie
        checkPanelMenuCookie('getting-started-panel');
        checkPanelMenuCookie('getting-started-panel1');

        $(".sender").remove();
    });


</script>
    <div class="panel-heading">
        <strong>Welcome</strong> to the <br> TeachConnect live chat room.</div>
    <div class="panel-body">
        <ul>
            <li>What's been on your mind lately?</li>
            <li>What's been going on in your classroom?</li>
        </ul>
        <p>Just type your message and press <strong>[send]</strong> or <strong>ctrl + enter</strong> to join the chat.</p>
        <p>We will be hosting live chat events here throughout the year.</p>

    </div>
</div>
            
            <?php
                \humhub\assets\TeachConnectAsset::register($this);
                echo \humhub\modules\activity\widgets\Stream::widget(['streamAction' => '/dashboard/dashboard/stream']);
            ?>

            <?php
                echo \humhub\modules\tour\widgets\Dashboard::widget();
            ?>

<script type="text/javascript">

    // set niceScroll to activity list
    $("#activityContents").niceScroll({
        cursorwidth: "7",
        cursorborder: "",
        cursorcolor: "#555",
        cursoropacitymax: "0.2",
        railpadding: {top: 0, right: 3, left: 0, bottom: 0}
    });

    // update nicescroll object with new content height after ajax request
    $(document).ajaxComplete(function (event, xhr, settings) {
        $("#activityContents").getNiceScroll().resize();
    })

</script>

<div class="attribution-link">Emoji art supplied by <a href="http://emojione.com" target="_blank">Emoji One</a></div>


        </div>
</div>
