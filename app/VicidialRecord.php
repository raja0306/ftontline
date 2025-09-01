<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class VicidialRecord extends Model
{
	    protected $connection = "mysql2";
	        protected $table = "recording_log";

	        public static function FindURLRCD($id, $end_epoch, $date = "", $phone = "")
			    {
				            $records = VicidialRecord::select("filename", "start_time")
						                ->where("lead_id", $id)
							            ->where("end_epoch", $end_epoch)
							                ->orderBy("recording_id", "desc")
								            ->first();
					            if (empty($records)) {
							                $records = VicidialRecord::select("filename", "start_time")
										                ->where("start_time", ">", $date)
											                ->where("filename", "like", "%" . $phone . "%")
												                ->orderBy("recording_id", "desc")
													                ->first();
									        }
					            $wav_url = "";
					            if ($records) {
							                $call_date = date("Y-m-d", strtotime($records->start_time));
									            if ($call_date >= "2024-11-21") {
											                    $wav_url =
														                        "/RECORDINGS/MP3/" .
																	                    $call_date .
																			                        "/" .
																						                    $records->filename .
																								                        "-all.mp3";
													                } else {
																                $wav_url = "/RECORDINGS/MP3/" . $records->filename . "-all.mp3";
																		            }
									            return "$wav_url";
									        }
						        }

		    public static function FindURL($id, $uniqueid)
			        {
					        $records = VicidialRecord::select("filename")
							            ->where("lead_id", $id)
							                ->where("vicidial_id", $uniqueid)
								            ->first();
						        $wav_url = "";
						        if ($records) {
								            $wav_url = "/RECORDINGS/MP3/" . $records->filename . "-all.mp3";
									            }
							        return "$wav_url";
							    }

		    public static function OutEvaluate($leadid, $listid)
			        {
					        if ($listid == "1004" || $listid == "1005" || $listid == "1006") {
							            $url = url("/") . "/evaluation/outsales/" . $leadid;
								            } else {
										                $url = url("/") . "/evaluation/outservice/" . $leadid;
												        }
						        return "$url";
						    }

		    public static function InEvaluate($leadid)
			        {
					        $url = "#";
						        $inquiry = DB::table("opportunity")
								            ->select("enquiry_category")
								                ->where("id_process_lead", $leadid)
									            ->first();
						        if ($inquiry) {
								            $category = $inquiry->enquiry_category;
									                if ($category == "1" || $category == "191" || $category == "193") {
												                $url = url("/") . "/evaluation/insales/" . $leadid;
														            } elseif (
																                    $category == "2" ||
																		                    $category == "192" ||
																				                    $category == "194"
																						                ) {
																									                $url = url("/") . "/evaluation/inservice/" . $leadid;
																											            }
									            }
						        return "$url";
						    }
}

