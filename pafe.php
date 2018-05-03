<?php 

	//all necessary variables defined
	$total_simulation_timeh = $total_simulation_time = $random_arrival_time = $percentage = "";
	$arrival_time = $rand_arr = $prob_arr = $c_prob_arr = "";
	$service_time_1 = $prob_svr = $c_prob_svr = "";
	$tin = $tr = $ts = "";
	$h = $l = $w = $k = "";
	$rand_svr = "";
	$random_service_time_low_1 = $random_service_time_high_1 = "";
	$total_arrival_time = $tot_sr = 0;
	$s1 = 0;
?>

<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>Proactive Air Flight Evaluation</title>
	<link rel="icon" type="image" href="Golden Dream logo3.ico" />
	<link rel="stylesheet" href="bootstrap.css" />
</head>
<body style="background:#59475d;">
	<div class="container-fluid" style="height:auto; padding:0; margin:15px auto; width:90%">
		<div class="well">
			<div class="well">
				<h3><strong><center>Proactive Air Flight Evaluation</center></strong></h3>
			</div>
			<img style="width:100%;height:200px;" src="pafe.png" alt="" />
			
			<!-- -->
			<!--the html input part -->
			<div class="row">
				<div class="col-md-6">
					<div class="well" >
						<form action="pafe.php" method="POST" name="simulate">
							<table class="table-responsive">
								<tr>
									<td>Total Simulation Time</td>
									<td><input type="text" name="tst"> hrs</td>
								</tr>
								<br />
								<tr>
									<td>Arrival Time (1 to </td>
									<td><input type="text" name="rat"> min)</td>
								</tr>
								<br />
								<tr>
									<td>Percentage of Landing</td>
									<td><input type="text" name="pb"> %</td>
								</tr>
								<br />
								<tr>
									<td>Random Service Time </td>
									<td><input type="text" name="rstl"> to <input type="text" name="rsth"> (min)</td>
								</tr>
								<br />
								
								
								<br />
								<tr>
									<td></td>
									<td><input type="submit" class="btn btn-primary pull-right" name="sim" value="Simulate"></td>
								</tr>
								
							</table>
						</form>
					</div>
				</div>
				<div class="col-md-6">
					<div class="well" >
						<table class="table-responsive">
						
<?php
	
	//start simulate
	if(isset($_POST['sim'])){
		
		//get all data posted
		$total_simulation_timeh = $_POST['tst'];
		$random_arrival_time = $_POST['rat'];
		$random_service_time_low_1 = $_POST['rstl'];
		$random_service_time_high_1 = $_POST['rsth'];
		$percentage = $_POST['pb'];
		$total_simulation_time = $total_simulation_timeh*60;  //get total simulation in hour
		
		//create a loop of flights from 0 to most likely infinity
		for($j = 0; $j < getrandmax(); $j++){
			
			//generate random values for arrival time
			$rand_arr = rand(0,1000);
			//a loop for creating arrival time table
			for($i = 1; $i < ($random_arrival_time+1); $i++){
				
				//creating probability
				$prob_arr = 1/$random_arrival_time;
				
				//creating random range
				$c_prob_arr = $prob_arr * $i * 1000;
				$c_prob_arr_1 = $prob_arr * ($i-1) * 1000;
				
				//if the value is in the range
				if($rand_arr > $c_prob_arr_1 && $rand_arr <= $c_prob_arr){
					
					//when the last range comes
					if($rand_arr == 0 || $rand_arr == 1000){
						$arrival_time = $random_arrival_time;
					}
					else{
						$arrival_time = $i;
					}
					//echo $arrival_time." ";
					break;
				}
				else
					continue;
			}
			
			//generate random values for server 1's service time
			$rand_svr = rand(0,100);
			//a loop for creating service time table
			for($m = $random_service_time_low_1; $m < ($random_service_time_high_1+1); $m++){
				
				//creating probability
				$prob_svr = 1/($random_service_time_high_1-$random_service_time_low_1+1);
				
				//creating random range
				$c_prob_svr = $prob_svr * ($m-$random_service_time_low_1+1) * 100;
				$c_prob_svr_1 = $prob_svr * ($m-$random_service_time_low_1) * 100;
						
				//if the value is in the range
				if($rand_svr > $c_prob_svr_1 && $rand_svr <= $c_prob_svr){
					
					//when the last range comes
					if($rand_svr == 0 || $rand_svr == 100){
						$service_time_1 = $random_service_time_high_1;
					}
					else{
						$service_time_1 = $m;
					}
					//echo "<br>".$service_time_1." ";
					break;
				}
				else
					continue;
			}
			
			//generate service time for all the servers
			
			/*
			 * calculate the time service ends for one customer
			 * initially total arrival time is 0
			 * service time added to total arrival time
			 * create time service ends for all three servers
			 * generate it for all customers respectively
			 */
			$service_time_end_1 = $total_arrival_time+$service_time_1;
			
			/*
			 * total arrival time calculated
			 * initially 0
			 * this value passed to another variable $tr
			 */
			$total_arrival_time = $total_arrival_time + $arrival_time;
			 $tr = $total_arrival_time;
			
			/*
			 * start assigning passenger to different desks
			 * we can see in the simulation table that 
			 * when the time service ends for a customer is bigger than the total arrival time for next customer
			 * then the server is called busy
			 * that means if the time service ends for a customer is less than the total arrival time for next customer
			 * the server is free and if equal it wiil be free
			 */
			
			//look if server 1 is free
			if($service_time_end_1 <= $tr){
				//store the time service ends
				$ts = $service_time_end_1;
				//start calculate the passenger comes to this server
				$s1++;
			}
			//if server 1 is busy
			else
				continue;
			
			//echo $tot_sr." ";			
			
			
			/*
			 * look when the simulation time is over
			 * check when the total arrival time equals the simulation time
			 * also check when the time service ends for of any server equals the simulation time
			 * get the total number of customer then 
			 */
			
			if($total_arrival_time >= $total_simulation_time){
				
				//when the total arrival time is bigger then simulation time
				if($total_arrival_time > $total_simulation_time){
					
					//go one step back and get total number of passenger 
					$tin = $j;
					$in = $tin+1;
					//this is the number of flights enters
					
					
					//get the rounded value of flights okayed landing
					$h = ceil(($in*$percentage)/100);
					//and who are not okayed
					$l = $in-$h;
					
									
					?>
					<tr>
						<td>Number of flights in: </td>
						<td><?php echo $in;?></td>
					</tr>
					<br />
					<tr>
						<td>Number of flights can land now: </td>
						<td><?php echo $h;?></td>
					</tr>
					<br />
					<tr>
						<td>Number of flights can't land now: </td>
						<td><?php echo $l;?></td>
					</tr>
							
					<?php
					$tot_sr = $ts;
					if($tot_sr >= $total_simulation_time){
						
						//if the time service ends for of any server is bigger than the simulation time
						if($tot_sr > $total_simulation_time){
							
							$k = $s1-1;
							//and whose service not ended durating the simulation time
							$w = $h-$k;
							?>
							<tr>
								<td>Number of flights out of the runway: </td>
								<td><?php echo $k;?></td>
							</tr>
							<br />
							<tr>
								<td>Number of flights waiting in the runway for another simulation period: </td>
								<td><?php echo $w;?></td>
							</tr>
							<br />
							<?php
						}
						
						//do the same for when the time service ends equals the simulation time
						else{
							$k = $s1;
							//and whose service not ended durating the simulation time
							$w = $h-$k;
							?>
							<tr>
								<td>Number of flights out of the runway: </td>
								<td><?php echo $k;?></td>
							</tr>
							<br />
							<tr>
								<td>Number of flights waiting in the runway for another simulation period: </td>
								<td><?php echo $w;?></td>
							</tr>
							<br />
							<?php
						}
						break;
					}
					else
						continue;
				}
				
				//do the same for when the total arrival time equals the simulation time
				else{
					$tin = $j;
					$in = $tin+1;
					//this is the number of flights enters
					
					
					//get the rounded value of flights okayed landing
					$h = ceil(($in*$percentage)/100);
					//and who are not okayed
					$l = $in-$h;
					
									
					?>
					<tr>
						<td>Number of flights in: </td>
						<td><?php echo $in;?></td>
					</tr>
					<br />
					<tr>
						<td>Number of flights can land now: </td>
						<td><?php echo $h;?></td>
					</tr>
					<br />
					<tr>
						<td>Number of flights can't land now: </td>
						<td><?php echo $l;?></td>
					</tr>
							
					<?php
					$tot_sr = $ts;
					if($tot_sr >= $total_simulation_time){
						
						//if the time service ends for of any server is bigger than the simulation time
						if($tot_sr > $total_simulation_time){
							
							$k = $s1-1;
							//and whose service not ended durating the simulation time
							$w = $h-$k;
							?>
							<tr>
								<td>Number of flights out of the runway: </td>
								<td><?php echo $k;?></td>
							</tr>
							<br />
							<tr>
								<td>Number of flights waiting in the runway for another simulation period: </td>
								<td><?php echo $w;?></td>
							</tr>
							<br />
							<?php
						}
						
						//do the same for when the time service ends equals the simulation time
						else{
							$k = $s1;
							//and whose service not ended durating the simulation time
							$w = $h-$k;
							?>
							<tr>
								<td>Number of flights out of the runway: </td>
								<td><?php echo $k;?></td>
							</tr>
							<br />
							<tr>
								<td>Number of flights waiting in the runway for another simulation period: </td>
								<td><?php echo $w;?></td>
							</tr>
							<br />
							<?php
						}
						break;
					}
					else
						continue;
				}
				break;
			}
			else
				continue;
				
				
				
				
					
		}
		
	}

?>			
							
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>	
</body>
</html>