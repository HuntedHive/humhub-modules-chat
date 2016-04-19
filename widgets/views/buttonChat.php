<script>
    $(document).ready(function() {
        var sender = $(".sender").clone();
        $(".panel-activities .panel-heading").before(sender);
        $(".sender:first").remove();
    });
</script>

<style>
    .sender {
        margin: 12px;
        width: 93%;
        padding: 10px;
    }
</style>

<a class='sender btn btn-primary' href="<?= Yii::app()->createUrl("/chat/chat/index"); ?>">GO TO LIVE CHAT</a>
