<script>
    $(document).ready(function() {
        var sender = $(".sender").clone();
        $(".sender").remove();
        $(".panel-activities .panel-heading:first").before(sender);
        setTimeout(function() {
            sender.removeClass("hidden");
        }, 500);
    });
</script>

<style>
    .sender {
        margin: 12px;
        width: 93%;
        padding: 10px;
    }

    .sticky-chat {
        background: #1895a4;
        color: white;
        width: 90%;
        padding: 10px;
        border-radius: 10px;
        margin: 0 auto;
        cursor: pointer;
    }

    .sticky-chat .fa {
        margin-right: 10px;
    }
</style>

<a class="sender hidden" href="<?= \yii\helpers\Url::toRoute("/chat/chat/index"); ?>">
    <div class="sticky-chat text-center">
        <h3 class="margin-none"><i class="fa fa-commenting-o"></i> go to live chat</h3>
        <small>five messages in the last ten minutes</small>
    </div>
</a>

<script>
    $(document).ready(function() {
        var countLiveChat = 1;
        var countQandA = 2;
        setTimeout(function() {
            deleteMoreOneLiveChat();

            $("#activityContents").on("scroll", function(e) {
                deleteMoreOneLiveChat();
            });
        }, 2800);

        function deleteMoreOneLiveChat() {
            var i = 0;
            if($(".chat-activity").length > countLiveChat) {
                i = 0;
                $.each($(".chat-activity"), function (index, value) {
                    if (i < countLiveChat) {
                        i = i + 1;
                    } else {
                        $(this).parents("a").remove();
                    }
                });
            }

            if($(".answer-activity").length > countQandA) {
                i = 0;
                $.each($(".answer-activity"), function (index, value) {
                    if (i < countQandA) {
                        i = i + 1;
                    } else {
                        $(this).parents("a").remove();
                    }
                });
            }

            if($(".question-activity").length > countQandA) {
                i = 0;
                $.each($(".question-activity"), function (index, value) {   
                    if (i < countQandA) {
                        i = i + 1;
                    } else {
                        $(this).parents("a").remove();
                    }
                });
            }

            $("#activityLoader").remove();
        }
    });
</script>
