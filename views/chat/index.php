<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src='http://yuku-t.com/jquery-overlay/jquery.overlay.js'></script>
<script src='http://yuku-t.com/jquery-textcomplete/media/javascripts/jquery.textcomplete.js'></script>

<link rel="stylesheet" type="text/css"
         href="<?php echo $this->module->assetsUrl; ?>/css/chat.css"/>
<?php if(WBSChat::isChating(Yii::app()->user->id)) { ?>
<script>
    $(document).ready(function() {
        
        //Set connection with server
        var conn = new WebSocket('ws://localhost:8080?code=<?= Yii::app()->user->guid ?>');
        conn.onopen = function(e) {
        };
        
        // On server answer
        conn.onmessage = function(e) {
            //Edit
            if(typeof JSON.parse(e.data) == "object") {
                var pk = JSON.parse(e.data)[0];
                var text = JSON.parse(e.data)[1];
                $(".mes > [data-pk='"+pk+"']").html('').removeAttr("style").html(text);
                
            } else { // Add new message
                $("#messages .part-message:first .mes:last").after(JSON.parse(e.data));
                $("#messages").animate({ scrollTop: $("#messages .part-message").height() }, 2500);
            }
            
            //after append html add to all messages editable
            $('.message-edit').editable({
                placement: 'right',
                mode: 'inline',
                type: 'textarea',
                rows: '1',
                toggle: 'manual',
                url: '<?= Yii::app()->createUrl("chat/chat/edit"); ?>', //history of chat
                dataType: 'post',
                success: function(response, newValue) {
                     // $(this).html(response);
                },
                display: function(value) {
                }
            });
        };
        
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
                    $.post('<?= Yii::app()->createUrl("chat/chat/users"); ?>',function(data) { // get all user name to @mention list
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
        
        // On scroll history to top get last messages
        $("#messages").scroll(function(e) {
            var height = $(this).scrollTop();
            if(height == 0) {
                var count = $(".mes").length;
                $.ajax({
                    type: 'POST',
                    url: '<?= Yii::app()->createUrl("chat/chat/history"); ?>', //history of chat
                    data: {'count':count},
                    success: function(data) {
                        $(".part-message").before(data);

                        $('.message-edit').editable({
                            placement: 'right',
                            mode: 'inline',
                            type: 'textarea',
                            rows: '1',
                            toggle: 'manual',
                            url: '<?= Yii::app()->createUrl("chat/chat/edit"); ?>', //history of chat
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
            url: '<?= Yii::app()->createUrl("chat/chat/edit"); ?>', // Edit message
            dataType: 'post',
            success: function(response, newValue) {
                // $(this).html(123);
            },
             display: function(value) {
                // none
            }
        });
        
        // Rewrite method on editin field inline
        $(document).on("click",".edit-icon", function(e) {
            e.stopPropagation();
            $(".editableform-loading").remove();
            var span = $(this).parents(".mes").find(".message-edit");
            var text = validateText(span.html());
            span.editable('toggle');
            $('.editable-input textarea').val(text);
        });
        
        // Send to server edit text with (pk) and (text)
        $(document).on("click",".editable-submit", function() {
            var text = $(this).parents(".editableform").find(".editable-input textarea").val();
            var pk = $(this).parents(".mes").find(".message-edit").data('pk');
            var items = new Array;
            items[0] = pk;
            items[1] = text;
            var result = JSON.stringify(items);
            conn.send(result);
            $(".editable-container").hide();
            return true;
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
        $("#messages").animate({ scrollTop: $("#messages .part-message").height() }, 2200);
//        var wtf    = $('#messages');
//        var height = wtf[0].scrollHeight;
//        wtf.scrollTop(height);


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

        <?php if(WBSChat::isChating(Yii::app()->user->id)) { ?>
        <div class="form-group">
            <div class="profile-size-sm profile-img-navbar" style="margin-top: 10px;z-index: 100;position: absolute;float: left;">
                <img id="user-account-image profile-size-sm" class="img-rounded" src="<?= User::model()->findByPk(Yii::app()->user->id)->getProfileImage()->getUrl(); ?>" alt="32x32" data-src="holder.js/32x32" height="32" width="32">
                <div class="profile-overlay-img profile-overlay-img-sm"></div>
            </div>
            <textarea class="form-control input_text" rows="3" placeholder="Click here to type a chat message." style="padding-left:60px;"></textarea>

            <span class="block-smile">
                <img src='http://vignette2.wikia.nocookie.net/olympians/images/2/26/Smile.png/revision/latest?cb=20110611224157'/>
                <div class="popover fade icons top in" role="tooltip" id="popover353330">
                    <div class="arrow" style="left: 50%;"></div>
                    <div class="popover-content"><?= $htmlImg ?></div>
                </div>
            </span>
        </div>
        <?php }  else { ?>
        <div class="form-group chat-disabled">
            <textarea disabled class="form-control input_text" rows="3" placeholder="You do not have access to post messages. Please contact site administration if you wish to be allowed to post." style="padding-left:60px;"></textarea>
                <div class="profile-size-sm profile-img-navbar" style="margin-top: -80px;z-index: 100;position: relative;float: left;">
                    <img id="user-account-image profile-size-sm" class="img-rounded" src="<?= User::model()->findByPk(Yii::app()->user->id)->getProfileImage()->getUrl(); ?>" alt="32x32" data-src="holder.js/32x32" height="32" width="32">
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

            <div class="panel panel-default panel-tour" id="getting-started-panel">

    <!-- Display panel menu widget -->
    <ul class="nav nav-pills preferences">
    <li class="dropdown ">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-angle-down"></i></a>
        <ul class="dropdown-menu pull-right">
            <li><a href="javascript:togglePanelUp('getting-started-panel');" class="panel-collapse"><i class="fa fa-minus-square"></i> Collapse</a></li>
            <li><a href="javascript:togglePanelDown('getting-started-panel');" class="panel-expand" style="display:none;"><i class="fa fa-plus-square"></i> Expand</a></li>

            <li><!-- add Tooltip to link -->


<!-- Link to call the confirm modal -->

    <!-- create normal link element -->
    <a id="deleteLinkPost_hide-panel-button" class="" style="" href="#" data-toggle="modal" data-target="#confirmModal_hide-panel-button">
        <i class="fa fa-eye-slash"></i>  Remove panel    </a>


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
<!-- end: Confirm modal --></li>        </ul>
    </li>
</ul>

<script type="text/javascript">

    $(document).ready(function() {

        // check and set panel state from cookie
        checkPanelMenuCookie('getting-started-panel');

        $(".sender").remove();
    });


</script>
    <div class="panel-heading">
        <strong>Welcome</strong> to the TeachConnect live chat room.</div>
    <div class="panel-body">
        <ul>
            <li>What's been on your mind lately?</li>
            <li>What's been going on in your classroom?</li>
        </ul>
        <p>Just type your message and press <strong>[enter]</strong> to join the chat.</p>

        <p>We will be hosting live events here every Wednesday at 15:30 - come and join us.</p>

    </div>
</div>

            <?php
            $this->widget('application.modules_core.activity.widgets.ActivityStreamWidget', array(
                'streamAction' => '//dashboard/dashboard/stream',
            ));
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


        </div>
</div>