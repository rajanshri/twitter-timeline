<?php
class functions {
	
	function dateDiff($time1, $time2, $precision = 6) {
		if (!is_int($time1)) {
			$time1 = strtotime($time1);
		}
		if (!is_int($time2)) {
			$time2 = strtotime($time2);
		}
		if ($time1 > $time2) {
			$ttime = $time1;
			$time1 = $time2;
			$time2 = $ttime;
		}
		$intervals = array(
			'year',
			'month',
			'day',
			'hour',
			'minute',
			'second'
		);
		$diffs     = array();
		foreach ($intervals as $interval) {
			$diffs[$interval] = 0;
			$ttime            = strtotime("+1 " . $interval, $time1);
			while ($time2 >= $ttime) {
				$time1 = $ttime;
				$diffs[$interval]++;
				$ttime = strtotime("+1 " . $interval, $time1);
			}
		}
		$count = 0;
		$times = array();
		foreach ($diffs as $interval => $value) {
			if ($count >= $precision) {
				break;
			}
			if ($value > 0) {
				if ($value != 1) {
					$interval .= "s";
				}
				$times[] = $value . " " . $interval;
				$count++;
			}
		}
		return implode(", ", $times);
	}
	
	function date_diff($date) {

        /* compares two timestamps and returns array with differencies (year, month, day, hour, minute, second)
         */
        //check higher timestamp and switch if neccessary
        $date = str_replace(' ', '-', $date);
        $date = str_replace(':', '-', $date);
        $date = explode('-', $date);
        //print_r($date);

        $old_date = mktime($date[3], $date[4], $date[5], $date[1], $date[2], $date[0]);
        $now = time();
        if ($old_date < $now) {
            $temp = $now;
            $now = $old_date;
            $old_date = $temp;
        } else {
            $temp = $old_date; //temp can be used for day count if required
        }
        $old_date = date_parse(date("Y-m-d H:i:s", $old_date));
        $now = date_parse(date("Y-m-d H:i:s", $now));
        //seconds
        if ($old_date['second'] >= $now['second']) {
            $diff['second'] = $old_date['second'] - $now['second'];
        } else {
            $old_date['minute']--;
            $diff['second'] = 60 - $now['second'] + $old_date['second'];
        }
        //minutes
        if ($old_date['minute'] >= $now['minute']) {
            $diff['minute'] = $old_date['minute'] - $now['minute'];
        } else {
            $old_date['hour']--;
            $diff['minute'] = 60 - $now['minute'] + $old_date['minute'];
        }
        //hours
        if ($old_date['hour'] >= $now['hour']) {
            $diff['hour'] = $old_date['hour'] - $now['hour'];
        } else {
            $old_date['day']--;
            $diff['hour'] = 24 - $now['hour'] + $old_date['hour'];
        }
        //days
        if ($old_date['day'] >= $now['day']) {
            $diff['day'] = $old_date['day'] - $now['day'];
        } else {
            $old_date['month']--;
            $diff['day'] = date("t", $temp) - $now['day'] + $old_date['day'];
        }
        //months
        if ($old_date['month'] >= $now['month']) {
            $diff['month'] = $old_date['month'] - $now['month'];
        } else {
            $old_date['year']--;
            $diff['month'] = 12 - $now['month'] + $old_date['month'];
        }
        //years
        $diff['year'] = $old_date['year'] - $now['year'];
        //return $diff;
        if ($diff['year'] != 0) {
            if ($diff['year'] > 1)
                return $diff['year'] . ' years ago';
            else
                return $diff['year'] . ' year ago';
        }
        else if ($diff['month'] != 0) {
            if ($diff['month'] > 1)
                return $diff['month'] . ' months ago';
            else
                return $diff['month'] . ' month ago';
        }
        else if ($diff['day'] != 0) {
            if ($diff['day'] > 1)
                return $diff['day'] . ' days ago';
            else
                return $diff['day'] . ' day ago';
        }
        else if ($diff['hour'] != 0) {
            if ($diff['hour'] > 1)
                return $diff['hour'] . ' hours ago';
            else
                return $diff['hour'] . ' hour ago';
        }
        else if ($diff['minute'] != 0) {
            if ($diff['minute'] > 1)
                return $diff['minute'] . ' minutes ago';
            else
                return $diff['minute'] . ' minute ago';
        }
        else if ($diff['second'] != 0) {
            if ($diff['second'] > 1)
                return $diff['second'] . ' seconds ago';
            else
                return $diff['second'] . ' second ago';
        }
    }
	
	function __destruct() { 
		
	}
			
}
?>