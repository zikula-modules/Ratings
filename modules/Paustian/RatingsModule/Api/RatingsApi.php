<?php


namespace Paustian\RatingsModule\Api;


class RatingsApi
{
    static function CalculateAverage($ratings, $ratingScale){
        $retArray = [];
        $totalRating = 0;
        $count = count($ratings);
        foreach($ratings as $rating){
            $totalRating += $rating->getRating();
        }
        if($count === 0){
            $retArray['average'] = 0;
            $retArray['avgInt'] = 0;
            $retArray['doHalfStar'] = false;
            $retArray['emptyStars'] = $ratingScale;
        } else {
            $retArray['average'] = $totalRating/$count;
            $averageRemainder = fmod($retArray['average'], 1);
            $retArray['avgInt'] = floor($retArray['average']);
            $retArray['doHalfStar'] = ($averageRemainder > 0.5);
            $max = $ratingScale;
            $retArray['emptyStars'] = $max - Round($retArray['average']);
        }
        return $retArray;
    }

    /**
     * Adjust a Url path. This is important if Zikula is in a subdirectory.
     * 
     * @param $moduleVars
     * @param $docRoot
     */
    static function AdjustUrlPath(&$moduleVars, $docRoot){
        $moduleVars['iconUrl'] = $docRoot . '/' . $moduleVars['iconUrl'];
        $moduleVars['halfIconUrl'] = $docRoot . '/' . $moduleVars['halfIconUrl'];
        $moduleVars['emptyIconUrl'] = $docRoot . '/' . $moduleVars['emptyIconUrl'];
    }
}