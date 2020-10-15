<?php
    $list = explode(',','1,2,3,0,4,0,0,-1,1,-2,2');
    
    $results = array();
    for ($item = 0; $item < count($list)-1; $item++) {
    	$val = $list[$item] + $list[$item+1];
    	if ($val == 0) {
    		$new_ar = array();
    		array_push($new_ar,$list[$item]);
    		array_push($new_ar,$list[$item+1]);
    		array_push($results,$new_ar);
    		print 'PASSES: '.$list[$item].' and '.$list[$item+1].' == '.$val.'<br/>';
    	} else {
    		print 'FAILS: '.$list[$item].' and '.$list[$item+1].' == '.$val.'<br/>';
    	}
    }

?>