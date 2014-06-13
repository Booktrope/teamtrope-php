<?php

/*
	fixes the better directories (0.9.2) member filter 
*/
add_filter( 'bp_use_legacy_user_query', '__return_true' );
?>
