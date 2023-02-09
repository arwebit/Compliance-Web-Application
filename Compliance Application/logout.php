<?php
session_start();
unset($_SESSION['crm_member']);
if (isset($_SERVER['HTTP_REFERER'])) {
    ?>
<script type="text/javascript">
window.location.href = "index.php";
</script>
<?php
    } else {
   ?>
<script type="text/javascript">
window.location.href = "index.php";
</script>
<?php
}
exit;
?>