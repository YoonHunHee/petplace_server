<!DOCTYPE html>
<html lang="ko">
<head>

<?php if(isset($message) && $message) {?>
<script type="text/javascript">
alert('<?=$message?>');
history.back();
</script>
<?php }else{?>
<script type="text/javascript">
history.back();
</script>
<?php }?>
</head>
<body>
</body>
</html>