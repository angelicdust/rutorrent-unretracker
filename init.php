<?php

$req = new rXMLRPCRequest( array(
	$theSettings->getOnInsertCommand(array('tadd_trackers3'.User::getUser(), getCmd('d.set_custom6').'=$'.getCmd('cat').'=$'.getCmd('d.get_state='))),
	$theSettings->getOnInsertCommand(array('tadd_trackers4'.User::getUser(),
		getCmd('branch').'=$'.getCmd('not').'=$'.getCmd('d.get_custom5').'=,"'.getCmd('cat').'=$'.getCmd('d.stop').'=,\"$'.
			getCmd('execute').'={sh,'.$rootPath.'/plugins/unretracker/run.sh'.','.Utility::getPHP().',$'.getCmd('d.get_hash').'=,'.User::getUser().'}\"" ; '.getCmd('d.set_custom5=')))));
if($req->run() && !$req->fault)
{
	$theSettings->registerPlugin($plugin["name"],$pInfo["perms"]);
	$jResult.= "theWebUI.unretracker = true;";
}
else
	$jResult .= "plugin.disable(); noty('unretracker: '+theUILang.pluginCantStart,'error');";
