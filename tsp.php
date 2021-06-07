<!doctype html>
<html>

<head>
	<meta charset="utf-8">
	<title>Traveling Salesman Problem</title>
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="style.css"> 
</head>

<body>
	<div class="main">
		
		<?php

			function array_sort($array, $cities, $order = SORT_ٍٍُُِِDESC)
			{
				
				$result = [];
				$sortable_array = [];
				if (count($array) > 0) {
					foreach ($array as $k => $v) {
						if (is_array($v)) {
							foreach ($v as $k2 => $v2) {
								if ($k2 == $cities) {
									$sortable_array[$k] = $v2;
								}
							}
						} else {
							$sortable_array[$k] = $v;
						}
					} 
					switch ($order) {
						case SORT_ASC:
							asort($sortable_array);
							break;
						case SORT_DESC:
							arsort($sortable_array);
							break;
					}

					foreach ($sortable_array as $k => $v) {
						$result[$k] = $array[$k];
					}
				}

				return $result;
			}
			function pr($data,$exit=false)
			{
				echo "<pre>";
				print_r($data);
				echo "</pre>";
				if($exit){
					exit();
				}

			}
			function char($number)
			{
				return chr(65+ $number); 
			}

			if (isset($_POST['submit'])) {

				$cities= $_POST['city']; //city
				$npop = $_POST['npop']; //npop
				$crossover = $_POST['crossover']; //crossover
				$nummutation = $_POST['nummutation'];
				$numiterations = $_POST['iterations'];
				$result_html = '';  
				$td_colspan = $cities+ 1; 

				///////////////////////////////////////////////city painting//////////////////

				$points = [];
				
				for ($i = 0; $i < $cities; $i++) {
					// $ch = char($i);  
					$randx = rand(-100, 100); //random num for x
					$randy = rand(-100, 100); //random num for y
					$points[$i][0] = $randx;
					$points[$i][1] = $randy;
				}
				
				////////////////////////////////////////////matris/////////////////////////
				$matris = "<tr>
							<td class='color-primary'>City</td>";
				for ($i_matris_title = 0; $i_matris_title < $cities; $i_matris_title++) { 
					$ch = char($i_matris_title); //A,B,C,D,...
					$matris.= "<td class='color-secondary'>$ch</td>"; //name of citis
				}
				$matris.= "</tr>";
				$array1 = []; 
				for ($m = 0; $m < $cities; $m++) {
					$ch = char($m); //A,B,C,D,...
					$matris.= "<tr><td  class='color-secondary'>$ch</td>"; //name of citis
					for ($j = 0; $j < $cities; $j++) {
						$chrm = char($m);
						$chrj = char($j);
						$x = $points[$m][0];
						$x1 = $points[$j][0];
						$y = $points[$m][1];
						$y1 = $points[$j][1];
						$rx = ($x1 - $x) * ($x1 - $x);
						$ry = ($y1 - $y) * ($y1 - $y);
						$total_points = round(sqrt($rx + $ry));
						$array1[$chrm][$chrj] = $total_points;
						if ($j == $m) {
							$matris.= "<td class='color-secondary' >$total_points</td>";
						} else {
							$matris.= "<td class='color-primary'>$total_points</td>";
						}
					}
					$matris.= "</tr>";
				} 
				pr($points);
				pr($array1,1);
				/////////////////////ITERATION = Population اولیه ////////////////////////////////
				$td = $cities+ 1; 
				$iteration = [];
				$iteration1 = [];
				for ($c = 0; $c < $cities; $c++) {
					$chrn = char($c);
					$iteration[$c] = $chrn;
				}
				$npop_html= "";
				for ($a = 0; $a < $npop; $a++) {
					$npop_html.= "<tr>";
					shuffle($iteration);
					for ($i1 = 0; $i1 < $cities; $i1++) {
						$iteration1[$a][$i1] = $iteration[$i1];
						$result1 = $iteration1[$a][$i1];

						if ($a % 2 == 0) {
							$npop_html.= "<td class='color-primary'>$result1</td>";
						} else {
							$npop_html.= "<td class='color-warning'>$result1</td>";
						}
					}
					$npop_html.= "</tr>";
				} 
				/////////////////////////////Total////////////////////////////////
				$arrayTotal = [];   
				$CalculateTotalHtml = "";
				for ($d = 0; $d < $npop; $d++) {
					if ($d % 2 == 0) {
						$color = 'color-primary';
					} else {
						$color = 'color-warning';
					}
					$CalculateTotalHtml.= "<tr class='$color' >";
					$sum = 0;
					for ($b = 0; $b < $cities; $b++) {
						if ($b + 1 == $cities)
							break;
						$g = $iteration1[$d][$b];
						$g1 = $iteration1[$d][$b + 1];
						@$sum += $array1[$g][$g1];
						$CalculateTotalHtml.= "<td >$g</td>";
					}
					$start = current($iteration1[$d]);
					$end = end($iteration1[$d]);
					$sum += $array1[$end][$start];
					array_push($iteration1[$d], $start);
					array_push($iteration1[$d], $sum);
					$v = $iteration1[$d][$b];
					$CalculateTotalHtml.=  "<td >$v</td><td >$start</td><td >$sum</td></tr>";
				}   

				$sortarray = array_sort($iteration1, $cities+ 1, SORT_ASC);
				$result_html.= "<table>";
				$result_html.= "<tr><td class='color-secondary' colspan='$td'> SORT-ASC</td><td class='color-secondary' >Total</td><tr>";
				$counter = 0;
				foreach ($sortarray as $val) {
					if ($counter % 2 == 0){
						$color = 'color-primary';
					} else {
						$color = 'color-warning';
					}
					$result_html.= "<tr class='$color'>";
					foreach ($val as $val1) {
						$result_html.= "<td >$val1</td>";
					}
					$result_html.= "</tr>";
					$counter++;
				}
				$result_html.= "</table>"; 
				$mergearray = [];

				for ($t = 2; $t < $numiterations; $t++) {
					$result_html.= "<div class='new-step'>-------ITERATION STEP-------</div>"; 


					for ($l = 0; $l < $cities; $l++) {
						$chr1 = char($l);
						$mergearray[$l] = $chr1;
					}

					for ($numcross = 0; $numcross < $crossover; $numcross++) {
						$randcom = rand(0, $npop - 1);
						$mainslice = array_slice($sortarray[$randcom], 0, $cities);
						$randcom = rand(0, $npop - 1);
						$mainslice1 = array_slice($sortarray[$randcom], 0, $cities);

						$randcom = rand(1, $npop - 1);
						$slice = array_slice($mainslice, $randcom);
						$slice1 = array_slice($mainslice1, $randcom);

						$subslice = array_slice($mainslice, 0, $randcom);
						$subslice1 = array_slice($mainslice1, 0, $randcom);


						$merge = array_merge($slice, $subslice1, $mergearray);
						$merge1 = array_merge($slice1, $subslice, $mergearray);
						$ss = array_values(array_unique($merge));
						$ss1 = array_values(array_unique($merge1));
						array_push($iteration1, $ss);
						array_push($iteration1, $ss1);
					}
					//print_r($iteration1);


					$td = $cities+ 1;
					$result_html.= "<table >";
					$result_html.= "<tr><td class='color-secondary'  colspan='$td'> CROSSOVER</td><td class='color-secondary' >Total</td></tr>";
					$o = $crossover * 2;
					for ($dd = $npop; $dd < $npop + $o; $dd++) {
						if ($dd % 2 == 0) {
							$color = 'color-primary';
						} else {
							$color = 'color-warning';
						}
						$result_html.= "<tr class='$color' >";

						$sum = 0;
						for ($b = 0; $b < $cities; $b++) {
							if ($b + 1 == $cities)
								break;
							$g = $iteration1[$dd][$b];
							$g1 = $iteration1[$dd][$b + 1];
							@$sum += $array1[$g][$g1];
							$result_html.= "<td >$g</td>";
						}
						$start = current($iteration1[$dd]);
						$end = end($iteration1[$dd]);
						$sum += $array1[$end][$start];
						array_push($iteration1[$dd], $start);
						array_push($iteration1[$dd], $sum);
						$v = $iteration1[$dd][$b];
						$result_html.=  "<td  >$v</td><td  >$start</td><td  >$sum</td></tr>";
					}

					$result_html.= "</table>";  

					$result_html.= "<table >";
					$result_html.= "<tr><td class='color-secondary'  colspan='$td'> CROSSOVER + ITERATION </td><td class='color-secondary'>Total</td></tr>";

					for ($f = 0; $f < count($iteration1); $f++) {
						if ($f % 2 == 0) {
							$color = 'color-primary';
						} else {
							$color = 'color-warning';
						}
						$result_html.= "<tr class='$color'>";
						foreach ($iteration1[$f] as $val) {
							$result_html.= "<td>$val</td>";
						}
						$result_html.= "</tr>";
					}
					$result_html.= "</table>";  

					$result_html.= "<table>";
					$result_html.= "<tr><td class='color-secondary'  colspan='$td'> MUTATION</td><td class='color-secondary' >Total</td></tr>";
					for ($nummution = 0; $nummution < $nummutation; $nummution++) {
						if ($nummution % 2 == 0) {
							$color = 'color-primary';
						} else {
							$color = 'color-warning';
						}
						$result_html.= "<tr class='$color' >";
						$sum2 = 0;
						$count = count($iteration1) - 1;
						$randmution = rand(0, $count);
						$mutionarray = $iteration1[$randmution];
						$mutionslice = array_slice($mutionarray, 0, $cities);
						$r1 = rand(0, $cities- 1);
						$r2 = rand(0, $cities- 1);
						$h = $mutionslice[$r1];
						$mutionslice[$r1] = $mutionslice[$r2];
						$mutionslice[$r2] = $h;
						for ($b = 0; $b < $cities; $b++) {
							if ($b + 1 == $cities)
								break;
							$g = $mutionslice[$b];
							$g1 = $mutionslice[$b + 1];
							@$sum2 += $array1[$g][$g1];
						}
						$start = current($mutionslice);
						$end = end($mutionslice);
						$sum2 += $array1[$end][$start];
						$g = $mutionslice[$b];
						$g1 = $mutionslice[$b];
						$sum2 += $array1[$g][$g1];
						array_push($mutionslice, $start);
						array_push($mutionslice, $sum2);
						array_push($iteration1, $mutionslice);
						$v = $mutionslice[$b];
						$c2 = end($iteration1);
						$counter = 0;
						foreach ($c2 as $val1) {
							$result_html.= "<td >$val1</td>";
						}
						$result_html.= "</tr>";
						$counter++;
					}
					$result_html.= "</table>"; 
					$iteration1 = array_sort($iteration1, $cities+ 1, SORT_ASC);
					$result_html.= "<table>";
					$result_html.= "<tr><td class='color-secondary' colspan='$td'> CROSOVER + MUTION + NPOP</td><td class='color-secondary'>Total</td><tr>";
					$counter = 0;
					foreach ($iteration1 as $val) {
						if ($counter % 2 == 0){
							$color = 'color-primary';
						} else {
							$color = 'color-warning';
						}
						$result_html.= "<tr class='$color'>";
						foreach ($val as $val1) {
							$result_html.= "<td>$val1</td>";
						}
						$result_html.= "</tr>";
						$counter++;
					}
					$result_html.= "</table>"; 
					$iteration1 = array_slice($iteration1, 0, $npop);
					$result_html.= "<table>";
					$result_html.= "<tr><td class='color-secondary' colspan='$td'><b>".$t."th</b> ITERATION</td><td class='color-secondary'>Total</td><tr>";
					for ($f = 0; $f < count($iteration1); $f++) {
						if ($f % 2 == 0) {
							$color = 'color-primary';
						} else {
							$color = 'color-warning';
						}
						$result_html.= "<tr class='$color'>";
						foreach ($iteration1[$f] as $val) {
							$result_html.= "<td>$val</td>";
						}
						$result_html.= "</tr>";
					}
					$result_html.= "</table>";
					$result_html.= "</>";
				}
			} else {
				header("location:index.php");
			}
		?>
		<div class="options-box">
			<div class="option-box card">
				<h1><?php echo $npop;?></h1>
			 	<label> NPOP</label>
			 </div>
			<div class="option-box card">
				<h1><?php echo $crossover;?></h1>
				<label> Crossover</label>
			 </div>
			<div class="option-box card">
				<h1><?php echo $nummutation;?></h1>
				<label> mutation</label>
			 </div>
			<div class="option-box card">
				<h1><?php echo $numiterations;?></h1>
				<label> iterations</label>
			 </div>
		</div>
		<div class="section-header">Matris</div>
		<table>
			<?php echo $matris; ?> 
		</table>
		<table>
			<tr> 
				<td colspan='<?php echo $td_colspan; ?>' class='color-secondary'>NPOP</td>
			</tr>
			<?php echo $npop_html; ?> 
		</table>
		<table> 
			<tr>
				<td class='color-secondary' colspan='<?php echo $td_colspan; ?>'>Calculate The Total
				</td>
				<td class='color-secondary'>Total</td>
			</tr>
			<?php echo $CalculateTotalHtml; ?> 

		</table>

		<?php echo $result_html; ?>
	</div>


</body>

</html>