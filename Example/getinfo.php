<?php
/*
Sample usage of multichain-api
*/

require_once('config.php');
require_once('multichain-api.php');

// initialize RPC connection to Multichain Instance
$chain = new MultiChain($cfg->rpcUser, $cfg->rpcPass, $cfg->rpcHost, $cfg->rpcPort);

// check for initialization errors (e.g. curl not available)
if (!$chain->initOK) {
	echo("##### ERROR\r\n");
	echo($chain->error);
	exit();
}

// check for RPC connection errors
$info = $chain->getinfo();
if (!$info) {
	echo("##### ERROR\r\n");
	echo($chain->error);
	exit();
}

// show general info about used blockchain and node
echo("##### INFO\r\n");
var_dump($info);

// read items from configured stream
$numItems = 5;
$verbose = true;
$streamItems = $chain->liststreamitems($cfg->stream, $verbose, $numItems, -$numItems);

// show result of previous call
echo("##### STREAM ITEMS\r\n");
var_dump($streamItems);

// iterate through items, decode and show data
echo("##### STREAM DATA\r\n");
foreach ($streamItems as $item) {
	if (is_array($item['data'])) {
// data in JSON or TEXT mode
    if (isset($item['data']['json'])) {
  		$data = $item['data']['json'];
    }
    if (isset($item['data']['text'])) {
  		$data = $item['data']['text'];
    }
		var_dump($data);
	} else {
// data in (old multichain 1.x) binary/text mode
  	$data = hex2bin($item['data']);
	  echo("$data\r\n");
	}  
}

?>