<?php
/**
* Save the file to app/Config/plaid.php
* Please Refer to https://plaid.com/docs for full API documentation.
* Obtain your API key by creating an account at https://plaid.com/signup
*/
$config = array(
	'Plaid' => array(
		'client_id' => 'ENTER CLIENT ID HERE',
		'secret' => 'ENTER SECRET HERE',
		'mode' => 'Test' //Live or Test
	)
);
?>