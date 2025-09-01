<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;
use DB;

class VicidialUsers extends Model
{
		protected $connection = 'mysql2';
		    protected  $table="vicidial_users";
		    public $timestamps = false;
		      


		        public static function FindName($user="")
				    {
					            $full_name = $user;
						            $list = DB::connection('mysql2')->table('vicidial_users')->select("full_name")->where("user", $user)->first();
						            if (!empty($list)) { $full_name = $list->full_name; }
							            return $full_name;
							        }

		        public static function Options($id)
				    {
					            $lists = VicidialUsers::select("user", "full_name")
							                ->where("active", "Y")
								            ->get();
						            $divhtml = '<option value="All">Select All</option>';

						            if ($lists) {
								                foreach ($lists as $list) {
											                if ($list->user == $id) {
														                    $selected = " selected";
																                    } else {
																			                        $selected = "";
																						                }
													                $divhtml .=
																                    '<option value="' .
																		                        $list->user .
																					                    '"' .
																							                        $selected .
																										                    ">" .
																												                        $list->full_name .
																															                    "</option>";
													            }
										        }
							            return $divhtml;
							        }
}

