<?php

if(count($argv)>2)
	$_SERVER['REMOTE_USER'] = $argv[2];

require_once( dirname(__FILE__)."/../../php/xmlrpc.php" );
require_once( dirname(__FILE__)."/../../php/rtorrent.php" );

function deleteTrackers(&$lst,$todelete)
{
	$ret = false;
	foreach( $lst as $kg=>$group )
	{
		foreach( $group as $kt=>$trk )
		{
			foreach ( $todelete as $kd )
			{
				if(stristr($trk,$kd))
				{
					unset($lst[$kg][$kt]);
					if(!count($lst[$kg]))
					unset($lst[$kg]);
					$ret = true;
				}
			}
		}
	}
	return($ret);
}

$processed = false;
if(count($argv)>1)
{
	$hash = $argv[1];
	$req = new rXMLRPCRequest( array(		
		new rXMLRPCCommand("get_session"),
		new rXMLRPCCommand("d.get_custom4",$hash),
		new rXMLRPCCommand("d.get_tied_to_file",$hash),
		new rXMLRPCCommand("d.get_custom1",$hash),
		new rXMLRPCCommand("d.get_directory_base",$hash),
		new rXMLRPCCommand("d.is_private",$hash),
		new rXMLRPCCommand("d.get_name",$hash),
		) );
	if($req->success())
	{
		$isStart = ($req->val[1]!=0);
		if($req->val[6]!=$hash.".meta")
		{
			$fname = $req->val[0].$hash.".torrent";
			if(empty($req->val[0]) || !is_readable($fname))
			{
				if(strlen($req->val[2]) && is_readable($req->val[2]))
					$fname = $req->val[2];
				else
					$fname = null;
			}
			if($fname)
			{
				$torrent = new Torrent( $fname );		
				if( !$torrent->errors() )
				{
				    $wasDeletion = false;
					$lst = $torrent->announce_list();
					if($lst && deleteTrackers($lst, ["http://retracker.local/announce"]))
					{
						$wasDeletion = true;
						$torrent->announce_list($lst);
					}

					if($wasDeletion)
					{
						if(isset($torrent->{'rtorrent'}))
							unset($torrent->{'rtorrent'});
						$eReq = new rXMLRPCRequest( new rXMLRPCCommand("d.erase", $hash ) );
						if($eReq->success())
						{
							$label = rawurldecode($req->val[3]);
							rTorrent::sendTorrent($torrent, $isStart, false, $req->val[4], $label, false, false, false,
							        array(getCmd("d.set_custom3")."=1") );
							$processed = true;
						}
					}
				}
			}
		}
		if(!$processed && $isStart)
		{
			$req = new rXMLRPCRequest( new rXMLRPCCommand("d.start", $hash ) );
			$req->run();
		}
	}
}
