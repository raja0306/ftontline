<div class="row">
<div class="col-lg-12">
<div class="card">
<div class="accordion-item">
<h2 class="accordion-header" id="flush-agentcall_summary">
    <button class="accordion-button font-size-16 ps-3" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseagentcall_summary" aria-expanded="true" aria-controls="flush-collapseagentcall_summary">
    Agent Calls Summary
    </button>
</h2>
<div id="flush-collapseagentcall_summary" class="accordion-collapse collapse show" aria-labelledby="flush-agentcall_summary">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle table-nowrap mb-0">
                <thead class="table-light">
                    <tr>
                        <th> Agent Name</th>
                        <th> Calls</th>
                        <th>Inbounds</th>
                        <th>Outbounds</th>
                        <th>Outbound Connected</th>
                        <th>Incoming Avg</th>
                        <th>Outgooing Avg</th>
                        <th> In Percentage</th>
                        <th>Service Level</th>
                        <th>Occupancy Time</th>
                        <th>Appointment Count</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                      $ainboundall = App\VicidialCloserLog::whereBetween('call_date',[$fromdate, $todate1])->count();
                      $aoutboundall = App\VicidialLog::whereBetween('call_date',[$fromdate, $todate1])->count();
                      $allcalls= $ainboundall+$aoutboundall;
                ?>
                @foreach($top_agents as $agent)
                      <?php 
                      $full_name = App\VicidialUsers::FindName($agent->user);
                      $ainbound = App\VicidialCloserLog::whereBetween('call_date',[$fromdate, $todate1])->where('user',$agent->user)->count();
                      $ainboundsec = App\VicidialCloserLog::whereBetween('call_date',[$fromdate, $todate1])->where('user',$agent->user)->sum('length_in_sec');
                      $aoutbound = App\VicidialLog::whereBetween('call_date',[$fromdate, $todate1])->where('user',$agent->user)->count();
                      $aoutboundc = App\VicidialLog::whereBetween('call_date',[$fromdate, $todate1])->where('length_in_sec','>',0)->where('user',$agent->user)->count();
                      $aoutboundsec = App\VicidialLog::whereBetween('call_date',[$fromdate, $todate1])->where('user',$agent->user)->sum('length_in_sec');     
                      $Slevel = App\VicidialCloserLog::whereBetween('call_date',[$fromdate, $todate1])->where('queue_seconds','<=','15')->where('user',$agent->user)->count();  
                      $appointments = App\Appointment::whereBetween('created_at',[$fromdate, $todate1])->where('agent',$agent->user)->count();

                      $occ_time =$agent->talk_secs+$agent->pause_secs+$agent->dispo_secs;
                      $log_time =$occ_time+$agent->wait_secs;

                      if ($log_time != '0') {
                        $logperc = 100/$log_time;
                        $occperc = $occ_time*$logperc;
                        $occperc = number_format((float)$occperc, 2, '.', '');
                      }
                      else{
                        $occperc = 0;
                      }

                      if ($ainbound != '0') {
                        $inbperc = 100/$ainbound;
                        $Slevelperc = $Slevel*$inbperc;
                        $Slevelperc = number_format((float)$Slevelperc, 2, '.', '');
                      }
                      else{
                        $Slevelperc = 0;
                      }

                      $parttime = $full_name;
                      
$acalls = $ainbound+$aoutbound;
?>
                    <tr>
                        <td>{!!$parttime!!}</td>
                        <td>{{$acalls}}</td>
                        <td>{{$ainbound}}</td>
                        <td>{{$aoutbound}}</td>
                        <td>{{$aoutboundc}}</td>
                        <td>@if($ainbound>0){{gmdate("H:i:s", ($ainboundsec/$ainbound))}}@endif</td>
                        <td>@if($aoutbound>0){{gmdate("H:i:s", ($aoutboundsec/$aoutbound))}}@endif</td>
                        <td>{{App\Average::MathPER($agent->calls,$allcalls)}}%</td>
                        <td>{{$Slevelperc}} %</td>
                        <td>{{$occperc}} %</td>
                        <td>{{$appointments}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
</div>
</div>
</div>

