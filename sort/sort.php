<?php 

$solution = $_POST['s'];
$arr      = $_POST['a'];

switch ($solution) {
	case 'quick':
		echo json_encode(quick($arr));
		break;
	
	default:
		# code...
		break;
}

function quick($arr)
{
	$stack = array();
	$start = 0;
	$end   = count($arr) - 1;
	$signS = $start;
	$signE = $end - 1;
	$opes  = array();
	$opes[] = array('area', $start, $end, $arr[$end]);
	while(TRUE)
	{
		if($end - $start < 2)
		{
			if($end - $start > 0)
			{
				$opes[] = ['area', $start, $end, $arr[$end]];
				$opes[] = ['compare', $start, $end];
				if($arr[$start] > $arr[$end])
				{
					$opes[] = ['trans', $start, $end];
					$tmp         = $arr[$start];
					$arr[$start] = $arr[$end];
					$arr[$end]   = $tmp;
				}
			}
			if(count($stack) <= 0)
				break;
			$pop = array_pop($stack);
			$start = $signS = $pop[0];
			$end   = $pop[1];
			$signE = $end - 1;
			if($end - $start >1)
			{
				$opes[] = ['area', $start, $end, $arr[$end]];
			}
		}
		else
		{
			while($signS < $signE)
			{
				while($signS < $signE && $arr[$signS] <= $arr[$end])
				{
					$opes[] = ['compare', $signS, $end];
					$signS++;
				}
				if($signS < $signE)
					$opes[] = ['compare', $signS, $end];
				while($signS < $signE && $arr[$signE] >= $arr[$end])
				{
					$opes[] = ['compare', $signE, $end];
					$signE--;
				}
				if($signS < $signE)
					$opes[] = ['compare', $signE, $end];
				$opes[] = ['compare', $signS, $signE];
				if($signS != $signE)
				{
					$opes[] = ['trans', $signS, $signE];
					$tmp         = $arr[$signE];
					$arr[$signE] = $arr[$signS];
					$arr[$signS] = $tmp;
				}
			}
			$opes[] = ['compare', $signE, $end];
			if($arr[$signE] > $arr[$end])
			{
				$opes[]      = ['trans', $signE, $end];
				$tmp         = $arr[$signE];
				$arr[$signE] = $arr[$end];
				$arr[$end]   = $tmp;
			}
			if($end - $signE > 1)
			{
				$stack[] = [$signE + 1, $end];
			}
			$signS = $start;
			$end   = $signE;
			$signE = $end - 1;
			if($end - $start >1)
			{
				$opes[] = ['area', $start, $end, $arr[$end]];
			}
		}
	}
	$opes[] = ['area', -1,-1, -1];
	return $opes;
}
