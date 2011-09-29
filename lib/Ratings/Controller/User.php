<?php

/**
 * Zikula Application Framework
 *
 * @copyright (c) 2002, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id$
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Zikula_Value_Addons
 * @subpackage Ratings
 */
class Ratings_Controller_User extends Zikula_AbstractController {

    /**
     * The main ratings user function
     * @author Jim McDonald
     * @return HTML String
     */
    public function main() {

        // ratings module cannot be directly accessed
        return LogUtil::registerError($this->__('Sorry! This module cannot be accessed directly.'), 403);
    }

    /**
     * display rating for a specific item, and request rating
     * @author Jim McDonald
     * @param $args['objectid'] ID of the item this rating is for
     * @param $args['extrainfo'] URL to return to if user chooses to rate
     * @param $args['style'] style to display this rating in (optional)
     * @return string output with rating information
     */
    public function display($args) {

        extract($args);

        if (!isset($style)) {
            $style = ModUtil::getVar('Ratings', 'defaultstyle');
        }

        if (!isset($displayScoreInfo)) {
            $displayScoreInfo = ModUtil::getVar('Ratings', 'displayScoreInfo');
        }

        // work out the return url
        if (is_array($extrainfo) && isset($extrainfo['returnurl'])) {
            $returnurl = $extrainfo['returnurl'];
        } else {
            $returnurl = $extrainfo;
        }

        // work out the calling module
        if (is_array($extrainfo) && isset($extrainfo['module'])) {
            $args['modname'] = $extrainfo['module'];
        } else {
            $args['modname'] = ModUtil::getName();
        }

        // RNG: add template override option
        $template = 'ratings_user_display.tpl';
        $tplOverride = null;
        if (isset($args['extrainfo']['template'])) {
            $tplOverride = $args['extrainfo']['template'] . '/' . $template;
        }
        // RNG End
        // security check
        // first check if the user is allowed to the any ratings for this module/style/objectid
        if (!SecurityUtil::checkPermission('Ratings::', "$args[modname]:$style:$objectid", ACCESS_READ)) {
            return;
        }
        // if we can we then need to check if the user can add thier own rating
        $permission = false;
        if (SecurityUtil::checkPermission('Ratings::', "$args[modname]:$style:$objectid", ACCESS_COMMENT)) {
            $permission = true;
        }
 
        // Run API function
        $fullrating = ModUtil::apiFunc('Ratings', 'user', 'get', $args);
        $rating = $fullrating['rating'];

        // Create output object
        $view = Zikula_View::getInstance('Ratings', false);
        $view->assign('permission', $permission);

        // RNG: determine if override template is valid
        if ($view->template_exists(DataUtil::formatForOS($tplOverride . '/' . $template))) {
            $template = $tplOverride . '/' . $template;
        }
        // RNG End
        // assign the rating style
        $view->assign('style', $style);
        $view->assign('useajax', ModUtil::getVar('Ratings', 'useajax'));
        $view->assign('usefancycontrols', ModUtil::getVar('Ratings', 'usefancycontrols'));

        // assign type/max score
        $view->assign('displayScoreInfo', $displayScoreInfo);
        if ($displayScoreInfo) {
            switch ($style) {
                case 'percentage':
                    $view->assign('maxScore', '100');
                    $view->assign('typeScore', '%');
                    break;
                case 'outoffive':
                    $view->assign('maxScore', '5');
                    $view->assign('typeScore', '');
                    break;
                case 'outoften':
                    $view->assign('maxScore', '10');
                    $view->assign('typeScore', '');
                    break;
                case 'outoffivestars':
                    $view->assign('maxScore', '5');
                    $view->assign('typeScore', '');
                    break;
                case 'outoftenstars':
                    $view->assign('maxScore', '10');
                    $view->assign('typeScore', '');
                    break;
            }
        }

        $showrating = false;
        if (isset($rating)) {
            $view->assign('rawrating', $rating);
            // Display current rating
            $showrating = true;
            switch ($style) {
                case 'percentage':
                    $view->assign('rating', $rating);
                    break;
                case 'outoffive':
                    $rating = (int) (($rating + 10) / 20);
                    $view->assign('rating', $rating);
                    break;
                case 'outoften':
                    $rating = (int) (($rating + 5) / 10);
                    $view->assign('rating', $rating);
                    break;
                case 'outoffivestars':
                    $rating = (int) ($rating / 2);
                    $intrating = (int) ($rating / 10);
                    $fracrating = $rating - (10 * $intrating);
                    $fullStars = ($fracrating > 5) ? $intrating + 1 : $intrating;
                    $emptyStars = 5 - $fullStars;
                    $view->assign('rating', $intrating);
                    $view->assign('fracrating', $fracrating);
                    $view->assign('emptyStars', $emptyStars);
                    break;
                case 'outoftenstars':
                    $intrating = (int) ($rating / 10);
                    $fracrating = $rating - (10 * $intrating);
                    $fullStars = ($fracrating > 5) ? $intrating + 1 : $intrating;
                    $emptyStars = 10 - $fullStars;
                    $view->assign('rating', $intrating);
                    $view->assign('fracrating', $fracrating);
                    $view->assign('emptyStars', $emptyStars);
                    break;
            }
        } else {
            $view->assign('rawrating', 0);
            $view->assign('rating', 0);
        }
        $view->assign('showrating', $showrating);
        $view->assign('showratingform', 0);

        // Multiple rate check
        $seclevel = ModUtil::getVar('Ratings', 'seclevel');

        if ($seclevel == 'high') {
            // Database information
            $table = DBUtil::getTables();
            $ratingslogcolumn = $table['ratingslog_column'];

            // Check against table to see if user has already voted
            // we need to check against both ip and id
            $logid = UserUtil::getVar('uid');
            // get the users ip
            $logip = System::serverGetVar('REMOTE_ADDR');

            $where = "($ratingslogcolumn[id] = '" . DataUtil::formatForStore($logid) . "' OR
                   $ratingslogcolumn[id] = '" . DataUtil::formatForStore($logip) . "' ) AND
		           $ratingslogcolumn[ratingid] = '" . $args['modname'] . $objectid . $style . "'";
            $rating = DBUtil::selectField('ratingslog', 'id', $where);
            if ($rating) {
                return $view->fetch($template);
            }
        } elseif ($seclevel == 'medium') {
            // Check against session to see if user has voted recently
            if (SessionUtil::getVar("Rated$args[modname]$style$objectid")) {
                return $view->fetch($template);
            }
        }

        // No check for low
        // This user hasn't rated this yet, ask them
        $view->assign('showratingform', 1);
        $view->assign('returnurl', $returnurl);
        $view->assign('modname', $args['modname']);
        $view->assign('objectid', $objectid);
        $view->assign('ratingtype', $style);

        return $view->fetch($template);
    }

    /**
     * Process rating form
     *
     * Takes input from the rating form and passes this to the API
     * @author Jim McDonald
     * @param $args['modname'] Source module name for which we're rating an oject
     * @param $args['objectid'] ID of object in source module
     * @param $args['ratingtype'] specific type of rating for this item (optional)
     * @param $args['returnurl'] URL to return to if user chooses to rate
     * @param $args['rating'] rating user selected
     * @return bool true if rating sucess, false otherwise
     */
    public function rate($args) {


        // Get parameters
        $modname = FormUtil::getPassedValue('modname', null, 'POST');
        $objectid = (int) FormUtil::getPassedValue('objectid', null, 'POST');
        $ratingtype = FormUtil::getPassedValue('ratingtype', null, 'POST');
        $rating = FormUtil::getPassedValue('rating', null, 'POST');
        $returnurl = FormUtil::getPassedValue('returnurl', null, 'POST');

        $this->checkCsrfToken();

        // Pass to API
        $newrating = ModUtil::apiFunc('Ratings', 'user', 'rate', array('modname' => $modname,
                    'objectid' => $objectid,
                    'ratingtype' => $ratingtype,
                    'rating' => $rating));

        // Success
        if ($newrating) {
            LogUtil::registerStatus($this->__('Done! Thank you for rating this item.'));
        }

        return System::redirect($returnurl);
    }

}