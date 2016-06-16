<?php

class GearmanWorkerCLIController extends DooCliController {


	function test(){

		set_include_path(get_include_path() . PATH_SEPARATOR .dirname(Doo::conf()->SITE_PATH)  . '/include/Net_Gearman');


		require_once 'Net/Gearman/Client.php';

		$client = new Net_Gearman_Client('localhost:4730');
		var_dump($client->deploy(array(
		    'userid' => 5555,
		    'action' => 'new-comment'
		)));

				var_dump($client->start(array(
		    'userid' => 5555,
		    'action' => 'new-comment'
		)));

		var_dump($client->stop(array(
		    'userid' => 5555,
		    'action' => 'new-comment'
		)));





		var_dump($client->update(array(
		    'userid' => 5555,
		    'action' => 'new-comment'
		)));

	}

	//
	// Usage: php cli.php worker function
	//
	function worker(){

		//check for args
		if(count($this->arguments) != 2){
			$this->writeLine("Usage: worker function");
			exit;
		}
		$function = $this->arguments[1];
		
		//include net_gearman
		define('NET_GEARMAN_JOB_PATH', Doo::conf()->SITE_PATH . '/protected/class/gearman/');

		set_include_path(get_include_path() . PATH_SEPARATOR .dirname(Doo::conf()->SITE_PATH)  . '/include/Net_Gearman');
		require_once 'Net/Gearman/Worker.php';

		//create server array
        $servers = array();
		foreach(Doo::db()->find('GearmanJobServers') as $s){
			$servers[] = $s->hostname . ":" . $s->port;
		}
			
		try {
			$worker = new Net_Gearman_Worker($servers);
			$worker->addAbility($function);
			$worker->beginWork();
		} catch (Net_Gearman_Exception $e) {
			echo $e->getMessage() . "\n";
			exit;
		}
	}
	
	//
	// Usage: php cli.php stop_workers 
	//
	function stop_workers(){
	
		//find current workers
		$runningWorkers = Doo::db()->find('GearmanWorkers');
		foreach($runningWorkers as $worker){
		
			//init
			$p = new Process();
			$p->setPid($worker->pid);
			$p->stop();
			$worker->delete();
		}
	}
	
	//
	// Usage: php cli.php check_workers 
	//
	function check_workers(){
	
		//read worker config
		$worker_functions = array();
		foreach(Doo::db()->find('GearmanFunctions', array('where' => 'enabled = 1')) as $f){
			$worker_functions[$f->function_name] = $f->worker_count;
		}

		//Loop enabled config
		foreach($worker_functions as $function => $worker_count){
			
			//find current workers for the function
			$runningWorkers = Doo::db()->find('GearmanWorkers', array('where' => "function_name = '$function'"));
		
			//scan workers to make sure they are still running
			foreach($runningWorkers as $worker){
			
				//init
				$p = new Process();
				$p->setPid($worker->pid);
			
				//check status
				if(!$p->status()){

					//crashed! Lets re init
					$gw = new GearmanWorkers;
					$gw->pid = $this->start_worker($function);
					$gw->function_name = $function;
					$gw->insert();
					
					//remove crashed pid
					$worker->delete();
				}	
			}
			
			//calc delta workers
			$delta = $worker_count - count($runningWorkers);

			//add missing workers
			if($delta > 0){
				
				for($i = 0; $i < $delta ; $i++){
					//run process
					$sw = new GearmanWorkers;
					$sw->pid = $this->start_worker($function);
					$sw->function_name = $function;
					$sw->insert();		
				}	
			}
			//remove extra workers
			if($delta < 0){
				//find current workers for the function
				$runningWorkers = Doo::db()->find('GearmanWorkers', array('where' => "function_name = '$function' AND  = $"));
				for($i = 0; $i < abs($delta) ; $i++){
					//kill process
					$worker = $runningWorkers[$i];
					$p = new Process();
					$p->setPid($worker->pid);
					$p->stop();
					$worker->delete();
				}	
			}
		}

		//disabled config
		foreach(Doo::db()->find('GearmanFunctions', array('where' => 'enabled = 0')) as $f){
			$function = $f["function_name"];

			//find current workers for the function
			$disabledWorkers = Doo::db()->find('GearmanWorkers', array('where' => "function_name = '$function'"));

			//scan workers and kill to disable
			foreach($disabledWorkers as $worker){
			
				//init
				$p = new Process();
				$p->setPid($worker->pid);
				$p->stop();
				$worker->delete();
			}
			
		}
	}
	
	private function start_worker($function){
		$cmd = "php cli.php worker $function > protected/log/gearman_worker.{$function}.log 2>&1";
		$p = new Process($cmd);
		return $p->getPid();
	}
	
}