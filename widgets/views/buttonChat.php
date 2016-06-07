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
