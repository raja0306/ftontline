<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Excel;
use App\Exports\CustomArrayExport;

class ExportController extends Controller
{
	    public function listexport()
		        {
				        $fromdate = date("Y-m-d");
					        if (!empty($_GET["fromdate"])) {
							            $fromdate = $_GET["fromdate"];
								            }
					        $todate = date("Y-m-d");
					        if (!empty($_GET["todate"])) {
							            $todate = $_GET["todate"];
								            }
						        $todate1 = date("Y-m-d", strtotime("+1 day", strtotime($todate)));
						        $datetype = "entry_date";
							        if (!empty($_GET["datetype"])) {
									            $datetype = $_GET["datetype"];
										            }

							        $lists = \App\VicidialList::whereBetween($datetype, [
									            $fromdate,
										                $todate1,
												        ])
													            ->select(
															                    "lead_id",
																	                    "entry_date",
																			                    "modify_date",
																					                    "list_id",
																							                    "status",
																									                    "phone_number",
																											                    "user",
																													                    "called_count",
																															                    "batchno",
																																	                    "vendor_lead_code"
																																			                )
																																					            ->where("lead_id", ">", "0")
																																					                ->get();
//print_r($lists); exit();
							        $customData[] = [
									                "Lead Id",
											                "Entry Date",
													                "Phone",
															                "List Name",
																	                "Status",
																			                "User",
																					                "Call Count",
																							                "Enq Id",
																									                "Customer Name",
																											                "Mobile Number",
																													                "Categories",
																															                "Sub Category",
																																	                "Description",
																																			                "Campaign",
																																					                "Date Added",
																																							                "Appointment Booked",
																																									                "Source of Business",
																																											                "Brand",
																																													                "Model",
																																															                "Showroom",
																																																	                "Salesman",
																																																			                "Service Center",
																																																					                "Advisor Name",
																																																							                "Appointment Date",
																																																									                "Appointment Time",
																																																											                "Appointment Type",
																																																													                "Appointment Code",
																																																															            ];
							        foreach ($lists as $log) {
									            $id_opp = "";
										                $cname = "";
										                $mobile_number = "";
												            $description = "";
												            $date_add = "";
													                $enq_category = "";
													                $enq_subcategory = "";
															            $enq_subcategory2 = "";
															            $enq_subcategory3 = "";
																                $appointment_booked = "";

																                $source = "";
																		            $brand = "";
																		            $interest = "";
																			                $showroom = "";
																			                $salesman = "";
																					            $servicecenter = "";
																					            $advisorname = "";
																						                $source = "";
																						                $appdate = "";
																								            $apptime = "";
																								            $apptype = "";
																									                $appcode = "";
																									                $campaign_id = "";

																											            $inquiry = DB::table("opportunity")
																													                    ->select(
																																                        "id_opp",
																																			                    "first_name",
																																					                        "last_name",
																																								                    "mobile_number",
																																										                        "description",
																																													                    "date_add",
																																															                        "enquiry_category",
																																																		                    "enquiry_subcategory",
																																																				                        "enquiry_subcategory2",
																																																							                    "enquiry_subcategory3",
																																																									                        "appointment_booked",
																																																												                    "campaign_id"
																																																														                    )
																																																																                    ->where("id_process_lead", $log->lead_id)
																																																																	                    ->first();
																											            if ($inquiry) {
																													                    $id_opp = $inquiry->id_opp;
																															                    $cname = $inquiry->first_name . " " . $inquiry->last_name;
																															                    $mobile_number = $inquiry->mobile_number;
																																	                    $description = $inquiry->description;
																																	                    $date_add = $inquiry->date_add;
																																			                    $enq_category = \App\Enquiry::FindName(
																																						                        $inquiry->enquiry_category
																																									                );
																																			                    $enq_subcategory = \App\Enquiry::FindName(
																																						                        $inquiry->enquiry_subcategory
																																									                );
																																			                    $enq_subcategory2 = \App\Enquiry::FindName(
																																						                        $inquiry->enquiry_subcategory2
																																									                );
																																			                    $enq_subcategory3 = \App\Enquiry::FindName(
																																						                        $inquiry->enquiry_subcategory3
																																									                );
																																			                    $appointment_booked = $inquiry->appointment_booked;
																																			                    if (
																																						                        $inquiry->appointment_booked == "Yes" ||
																																									                    $inquiry->appointment_booked == "No-Details/Intrested"
																																											                    ) {
																																														                        $appdetail = DB::table("appointments")
																																																		                        ->select(
																																																						                            "source_name",
																																																									                                "brand",
																																																													                            "interest",
																																																																                                "showroom",
																																																																				                            "salesman",
																																																																							                                "servicecenter",
																																																																											                            "advisorname",
																																																																														                                "appointment_date",
																																																																																		                            "appointment_time",
																																																																																					                                "appointmenttype",
																																																																																									                            "appointmentcode"
																																																																																												                            )
																																																																																															                            ->where("opp_id", $inquiry->id_opp)
																																																																																																	                            ->first();
																																																	                    if ($appdetail) {
																																																				                            $source = $appdetail->source_name;
																																																							                            $brand = $appdetail->brand;
																																																							                            $interest = $appdetail->interest;
																																																										                            $showroom = $appdetail->showroom;
																																																										                            $salesman = $appdetail->salesman;
																																																													                            $servicecenter = $appdetail->servicecenter;
																																																													                            $advisorname = $appdetail->advisorname;
																																																																                            $appdate = $appdetail->appointment_date;
																																																																                            $apptime = $appdetail->appointment_time;
																																																																			                            $apptype = $appdetail->appointmenttype;
																																																																			                            $appcode = $appdetail->appointmentcode;
																																																																						                        }
																																																	                }
																																					                }
																											            $customData[] = [
																													                    $log->lead_id,
																															                    $log->entry_date,
																																	                    $log->phone_number,
																																			                    \App\VicidialLists::FindName($log->list_id),
																																					                    \App\VicidialLists::Status($log->status),
																																							                    $log->user,
																																									                    $log->called_count,
																																											                    $id_opp,
																																													                    $cname,
																																															                    $mobile_number,
																																																	                    $enq_category,

																																																			                    $enq_subcategory .
																																																					                    " " .
																																																							                    $enq_subcategory2 .
																																																									                    " " .
																																																											                    $enq_subcategory3,
																																																													                    $description,
																																																															                    $campaign_id,
																																																																	                    $date_add,
																																																																			                    $appointment_booked,
																																																																					                    $source,
																																																																							                    $brand,
																																																																									                    $interest,
																																																																											                    $showroom,
																																																																													                    $salesman,
																																																																															                    $servicecenter,
																																																																																	                    $advisorname,
																																																																																			                    $appdate,
																																																																																					                    $apptime,
																																																																																							                    $apptype,
																																																																																									                    $appcode,
																																																																																											                ];
																											        }
							        return Excel::download(
									            new CustomArrayExport($customData),
										                "custom_data.xlsx"
												        );
							    }
}

