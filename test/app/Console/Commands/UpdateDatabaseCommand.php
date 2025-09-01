<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateDatabaseCommand extends Command
{
	    protected $signature = 'db:update-every-30-seconds';
	        protected $description = 'Update the database every 30 seconds';

	        public function __construct()
			    {
				            parent::__construct();
					        }

		    public function handle()
			        {
					        $start = time();

						//while (time() - $start < 60) { 
						while(true){
								            $this->pause_agents();
									                sleep(10);
									            }

						        return 0;
						    }


		    public function pause_agents()
			        {
					        $pauseagents = DB::connection('mysql2')->table('vicidial_live_agents')->select('live_agent_id','user','extension','status','campaign_id','conf_exten','calls_today','pause_code')->where('pause_code','DCMX')->get();
						        DB::table('cronlog')->insert(['name' => 'Pause','date_added'=>date('Y-m-d H:i:s')]);
						        foreach($pauseagents as $agent){
								            $curl = curl_init();
									                curl_setopt_array($curl, array(
												              CURLOPT_URL => 'https://172.16.4.163/agent/api.php?source=test&user=6666&pass=centrixplus&agent_user='.$agent->user.'&function=external_pause&value=RESUME',
													                    CURLOPT_RETURNTRANSFER => true,
															                  CURLOPT_ENCODING => '',
																	                CURLOPT_MAXREDIRS => 10,
																			              CURLOPT_SSL_VERIFYHOST=>0,
																				                    CURLOPT_SSL_VERIFYPEER=>0,
																						                  CURLOPT_TIMEOUT => 0,
																								                CURLOPT_FOLLOWLOCATION => true,
																										              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
																											                    CURLOPT_CUSTOMREQUEST => 'GET',
																													                ));

									                $response = curl_exec($curl);

									                curl_close($curl);
											        }
							    }
}

