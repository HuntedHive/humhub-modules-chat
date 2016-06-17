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

<a class="sender hidden" href="<?= Yii::app()->createUrl("/chat/chat/index"); ?>">
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
        }, 800);
        $("#activityContents").on("scroll", function(e) {
            deleteMoreOneLiveChat();
        });

        function deleteMoreOneLiveChat() {
            var i = 0;
            if($(".chat-activity").length > countLiveChat) {
                $.each($(".chat-activity"), function (index, value) {
                    if (i < countLiveChat) {
                        i = i + 1;
                    } else {
                        $(this).remove();
                    }
                });
            }

            if($(".answer-activity").length > countQandA) {
                $.each($(".answer-activity"), function (index, value) {
                    if (i < countQandA) {
                        i = i + 1;
                    } else {
                        $(this).remove();
                    }
                });
            }

            if($(".question-activity").length > countQandA) {
                $.each($(".question-activity"), function (index, value) {
                    if (i < countQandA) {
                        i = i + 1;
                    } else {
                        $(this).remove();
                    }
                });
            }

            $("#activityLoader").remove();
        }
    });
</script>