<?php
/*
 * check phone number roundish percentage v1.0 by ADLVI
 * contact : adlvi.t.me
 */

/*
 * check the phone roundish amount
 * @param string $phone  the phone number without country code and ZERO suffix
 * @param int $maxLen the max length to check matchs
 * @param int $minLen the min length to check matchs
 * @param int $SOMM  score of matched match
 * @param float|int $descorer1 the decrease of score ( in percent less than 1.0) in special conditions
 * @param float|int $descorer2 the decrease of score ( in percent less than 1.0) in special conditions (use in  rounded value checking conditio)
 * @return array  contains score , matchs indexes
 */
function check_phone_roundish($phone,$maxLen=4, $minLen = 2,$SOMM=50,$descorer1=0.5,$descorer2=0.9)
{
    $fn = function($n,$m,$l,$r) use($maxLen,$descorer1,$descorer2,$SOMM) {
        if($n ==$m ) {
            if($r !=0) {
                $mul=$maxLen-$l;
                $res= pow($descorer1,$mul)*$SOMM;
                $res= pow($descorer2,0-$r)*$res;
                return $res;
            }
            return pow($descorer1,$maxLen-$l)*$SOMM;
        }
        if($r!=0) {
            return 0;
        }
        if(/*$l !=1 and */(abs($m - strrev($n) ) % str_repeat('1',$l) == 0 ) ) {
            return pow($descorer1,$maxLen-$l)*$SOMM;
        }
        elseif( ($m == strrev($n) ) ) {
            return pow($descorer1,$maxLen-$l)*$SOMM;
        }
        if( abs($n-$m) ==1 ) {
            return pow($descorer1,$maxLen-$l)*$SOMM;
        }
        elseif(($l-1)!= 0  and (abs($n-$m) % pow(5,$l-1)==0) ) {
            return pow($descorer1*$descorer2*$descorer2,$maxLen-$l)*$SOMM;
        }
        elseif(($l-1)!= 0  and (abs($n-$m) % pow(2,$l-1)==0) ) {
            return pow($descorer1*$descorer2*$descorer2*0.95,$maxLen-$l)*$SOMM;
        }
        return 0;   
    };


    $match=[];
    $max=strlen($phone);
    $score=0;
    foreach(range($maxLen,$minLen) as $len)
    {
        $i=0;
        while($i<$max)
        {
            foreach(range(0,($len-1)) as $toR)
            {
                if($i+$len>$max or $i+2*$len> $max)
                    break;
                if($toR >=$len) {
                    break;
                }
                $round=-1*$toR;
                $tmp=substr($phone,$i,$len);
                $tmp2=substr($phone,$i+$len,$len);
                $key = $i.",".$len.",".$tmp."-".($i+$len).",".$len.",".$tmp2; 
                if( is_numeric($tmp) and is_numeric($tmp2) and !array_key_exists($key, $match) and $fscr=$fn(round($tmp,$round),round($tmp2,$round),$len,$round))
                {
                    $scr=$fscr;
                    $match[$key] = $scr;
                    $score += $scr;
                    $i+=2*$len;
                    if($score >= 100) {
                        return ['score'=>100,'matchs'=>$match];
                    }
                    break;
                }

            }
            ++$i;
        }
    }

    return ['score'=>$score,'matchs'=>$match];
}
