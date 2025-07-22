<?php

$hash = password_hash("Abc123!@#",PASSWORD_BCRYPT);

echo $hash; 
echo "<br>hash length ".strlen($hash);
?>