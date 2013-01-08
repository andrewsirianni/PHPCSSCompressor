<?php
	/**
	 * CSS Compressor
	 * Script to combine CSS files
	 */
	# Step 1: Call the class
	require_once 'css.class.php';

	# Step 2: Initiate the class, SELF, Force Re-cache (1/0), Document Directory
	$cssCache = new CSS($_SERVER['PHP_SELF'], 1, dirname($_SERVER['DOCUMENT_ROOT']));

	# Step 3: Add your css files that you want to combine and minize
	$cssCache->add('LINK TO CSS FILE');

	# Step 4: Compile the CSS
	$cssres = $cssCache->compile();

	# Step 5: Include the combined file: $cssres[0] will let you know if it has worked; $cssres[1] will be the name of the combined CSS file.
	if($cssres[0]=='OK'){
		echo '<link href="'.$cssres[1].'" rel="stylesheet" type="text/css" />';
	}
?>