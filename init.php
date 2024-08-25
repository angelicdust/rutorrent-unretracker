<?php

$req = new rXMLRPCRequest( array(
	$theSettings->getOnInsertCommand(array('tadd_trackers1'.User::getUser(), getCmd('d.set_custom4').'=$'.getCmd('cat').'=$'.getCmd('d.get_state='))),
	$theSettings->getOnInsertCommand(array('tadd_trackers2'.User::getUser(),
		getCmd('branch').'=$'.getCmd('not').'=$'.getCmd('d.get_custom3').'=,"'.getCmd('cat').'=$'.getCmd('d.stop').'=,\"$'.
			getCmd('execute').'={sh,'.$rootPath.'/plugins/unretracker/run.sh'.','.Utility::getPHP().',$'.getCmd('d.get_hash').'=,'.User::getUser().'}\"" ; '.getCmd('d.set_custom3=')))));
if($req->run() && !$req->fault)
{
	$theSettings->registerPlugin($plugin["name"],$pInfo["perms"]);
	$jResult.= "theWebUI.unretracker = true;";
}
else
	$jResult .= "plugin.disable(); noty('unretracker: '+theUILang.pluginCantStart,'error');";
