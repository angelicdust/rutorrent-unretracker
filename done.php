<?php

$req = new rXMLRPCRequest(array(
	rTorrentSettings::get()->getOnInsertCommand(array('tadd_trackers3'.User::getUser(), getCmd('cat='))),
	rTorrentSettings::get()->getOnInsertCommand(array('tadd_trackers4'.User::getUser(), getCmd('cat=')))
	));
$req->run();
