<?php if($stats->yipps != NULL && ($stats->reading_award > 0 || $stats->comment_award > 0)): ?>
<p><?php include_partial( "userAwardBig", array( "user" => $user, "stats" =>  $stats ) );  ?></p>
<?php else: ?>
<p>Keine Auszeichnungen vorhanden</p>
<?php endif; ?>