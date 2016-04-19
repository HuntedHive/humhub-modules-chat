<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src='http://yuku-t.com/jquery-overlay/jquery.overlay.js'></script>
<script src='http://yuku-t.com/jquery-textcomplete/media/javascripts/jquery.textcomplete.js'></script>

<style>
    
    #messages {
        height:400px;
        border: 2px solid #9C9FBB !important;
        overflow-y: auto;
    }
    
    .input_text {
        border: 2px solid #9C9FBB;
    }
    .btn-file {
    position: relative;
    overflow: hidden;
    border-radius: 50px;
    font-size:10px;
}
.btn-file input[type=file] {
    position: absolute;
    top: 0;
    right: 0;
    min-width: 100%;
    min-height: 100%;
    font-size: 100px;
    text-align: right;
    filter: alpha(opacity=0);
    opacity: 0;
    outline: none;
    background: white;
    cursor: inherit;
    display: block;
}

.upload-img {
    width: 30px;
}

.block-upload {
    position: absolute !important;
    top:-20px;
    right: 10px;
}

.block-smile {
    position: absolute !important;
    top:-12px;
    right: 70px;
    cursor: pointer;
}

.block-smile img {
    width: 30px;
}
.icons {
    top: -108px;
    left: -65px;
}

.popover-content {
    width: 156px;
    height: 101px;
}

.edit-icon {
    cursor: pointer;
}

.mes:hover {
    background: #E8E5E5;
}

.mention {
    text-decoration: underline;
    color: brown;
    cursor: pointer;
}

.message-edit img {
    width: 30px;
}

.message-default img {
    width: 30px;
}
</style>
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
                var html = $("#messages").html();
                $("#messages").html(html + JSON.parse(e.data)+"<br>");
            }
            
            //after append html add to all messages editable
            $('.message-edit').editable({
                placement: 'right',
                mode: 'inline',
                type: 'textarea',
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
        var wtf    = $('#messages');
        var height = wtf[0].scrollHeight;
        wtf.scrollTop(height);
    });
</script>
<div class="col-lg-5 col-lg-push-3">
    <div class="form-group">
        <div class="form-control" id="messages">
            <?php echo $messages; ?>
        </div>
    </div>
    <div class="form-group col-lg-11">
        <textarea class="form-control input_text" rows="3"></textarea>
        
        <span class="block-smile">
            <img src='http://vignette2.wikia.nocookie.net/olympians/images/2/26/Smile.png/revision/latest?cb=20110611224157'/>
            <div class="popover fade icons top in" role="tooltip" id="popover353330">
                <div class="arrow" style="left: 50%;"></div>
                <div class="popover-content"><?= $htmlImg ?></div>
            </div>
        </span>
    </div>
    <div class="form-group">
        <a class="send-message btn btn-success pull-left" href="#">Send</a>
    </div>
</div>

