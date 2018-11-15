<?php // vim:set ts=4 sw=4 sts=4 et:
require_once "config.php";
require_once "html.php";
require_once "db-func.php";
require_once "utils.php";

// Redirect to the login page, if not logged in
$uid = isLoggedIn();

// Start HTML
head("postprod");
?>
    <h3>Puzzles in Postprod and Later</h3>
<?php
$puzzles = getPuzzlesInPostprodAndLater($uid);
displayQueue($uid, $puzzles, "notes finallinks", FALSE);
?>
There used to be a button here, but I removed it.
<!--
    <hr>
    <br>
    <div class="warning">Warning: Please don't press this button. If you were supposed to press this button, you would know.</div>
    <form action="form-submit.php" method="post">
    <input type="hidden" name="uid" value="<?php echo $uid ?>">
    <input type="submit" name="postprodAll" value="Re-postprod ALL puzzles (THIS CANNOT BE UNDONE) [This will take a LONG TIME!]">
    </form>
    <br>
-->
<?php
// End the HTML
foot();
