<?php

use xPaw\SourceQuery\SourceQuery;

class DesktopController extends DooController {

	function admin(){
		//render view
        $this->renderc('admin', array());

	}
	function deploy(){
		//render view
        $this->renderc('deploy', array());
	}

	function status(){

		require dirname(Doo::conf()->SITE_PATH) . "/PHP-Source-Query/SourceQuery/bootstrap.php";
	
		$sql = "SELECT 
			    full_name, ip, port, engines.name as engine_name
			FROM
			    virtualboxes
			        JOIN
			    games ON virtualboxes.games_id = games.id
			        JOIN
			    services ON virtualboxes.id = services.virtualboxes_id
			        JOIN
			    engines ON games.engines_id = engines.id
			WHERE
			    hidden = 0
			ORDER BY full_name, port";

		//query database
		$servers = array();
		foreach(Doo::db()->fetchAll($sql) as $service){

			$arr = array();
			if($service['engine_name'] == "SOURCE"){
				$sq = new SourceQuery( );
				$sq->Connect($service['ip'], $service['port'], 1, SourceQuery :: SOURCE );
				$arr['query'] = $sq->GetInfo();
				$sq->Disconnect();
			} else 	if($service['engine_name'] == "GOLDSOURCE"){
				$sq = new SourceQuery( );
				$sq->Connect($service['ip'], $service['port'], 1, SourceQuery :: GOLDSOURCE );
				$arr['query'] = $sq->GetInfo();
				$sq->Disconnect();
			}

			$arr['data'] = $service;
			$servers[$service['full_name']][]= $arr;
		}

		//calculate user counts
		$counts = array();
		foreach($servers as $game => $services){
			$playercnt = 0;
			$maxcnt = 0;
			foreach($services as $s){
				if(isset($s['query'])){
					$playercnt += intval($s['query']['Players']);
					$maxcnt += intval($s['query']['MaxPlayers']);
					echo $s['query']['MaxPlayers'];
				}
			}

			$counts[$game]['Players'] = $playercnt;
			$counts[$game]['MaxPlayers'] = $maxcnt;
		}

		//render view
        $this->renderc('status', array('servers' => $servers, 'counts' => $counts));
	}

}

?>