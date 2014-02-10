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
     * The main ratings user function.
     *
     * @author Jim McDonald
     *
     * @return HTML String
     */
    public function main()
    {
        // The Ratings module cannot be directly accessed
        return LogUtil::registerError($this->__('Sorry! This module cannot be accessed directly.'), 403);
    }

    /**
     * Display rating for a specific item, and request rating.
     *
     * @author Jim McDonald
     * @param string  $args['objectid']  ID of the item this rating is for
     * @param integer $args['areaid']    ID of the hook area
     * @param mixed   $args['extrainfo'] URL to return to if user chooses to rate
     * @param string  $args['style']     style to display this rating in (optional)
     *
     * @return string output with rating information
     */
    public function display($args)
    {
        extract($args);

        if (!isset($style)) {
            $style = $this->getVar('defaultstyle');
        }

        if (!isset($displayScoreInfo)) {
            $displayScoreInfo = $this->getVar('displayScoreInfo');
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

        $template = 'ratings_user_display.tpl';
        $tplOverride = null;
        if (isset($args['extrainfo']['template'])) {
            $tplOverride = $args['extrainfo']['template'] . '/' . $template;
        }

        // security check
        // first check if the user is allowed to the any ratings for this module/style/objectid
        if (!SecurityUtil::checkPermission($this->name . '::', "$args[modname]:$style:$objectid", ACCESS_READ)) {
            return;
        }
        // if we can we then need to check if the user can add thier own rating
        $permission = false;
        if (SecurityUtil::checkPermission($this->name . '::', "$args[modname]:$style:$objectid", ACCESS_COMMENT)) {
            $permission = true;
        }
 
        // Run API function
        $fullrating = ModUtil::apiFunc($this->name, 'user', 'get', $args);
        $rating = $fullrating['rating'];

        // Create output object
        $this->view->assign('permission', $permission);

        // determine if override template is valid
        if ($this->view->template_exists(DataUtil::formatForOS($tplOverride . '/' . $template))) {
            $template = $tplOverride . '/' . $template;
        }

        // assign the rating style and the max score
        $this->view->assign('style', $style)
                   ->assign('useajax', $this->getVar('useajax'))
                   ->assign('usefancycontrols', $this->getVar('usefancycontrols'))
                   ->assign('displayScoreInfo', $displayScoreInfo);

        if ($displayScoreInfo) {
            $typeScore = '';
            switch ($style) {
                case 'percentage':
                    $this->view->assign('maxScore', '100');
                    $typeScore = '%';
                    break;
                case 'outoffive':
                    $this->view->assign('maxScore', '5');
                    break;
                case 'outoften':
                    $this->view->assign('maxScore', '10');
                    break;
                case 'outoffivestars':
                    $this->view->assign('maxScore', '5');
                    break;
                case 'outoftenstars':
                    $this->view->assign('maxScore', '10');
                    break;
            }
            $this->view->assign('typeScore', $typeScore);
        }

        $showrating = false;
        if (isset($rating)) {
            $this->view->assign('rawrating', $rating);
            // Display current rating
            $showrating = true;
            switch ($style) {
                case 'percentage':
                    $this->view->assign('rating', $rating);
                    break;
                case 'outoffive':
                    $rating = (int) (($rating + 10) / 20);
                    $this->view->assign('rating', $rating);
                    break;
                case 'outoften':
                    $rating = (int) (($rating + 5) / 10);
                    $this->view->assign('rating', $rating);
                    break;
                case 'outoffivestars':
                    $rating = (int) ($rating / 2);
                    $intrating = (int) ($rating / 10);
                    $fracrating = $rating - (10 * $intrating);
                    $fullStars = ($fracrating > 5) ? $intrating + 1 : $intrating;
                    $emptyStars = 5 - $fullStars;
                    $this->view->assign('rating', $intrating)
                               ->assign('fracrating', $fracrating)
                               ->assign('emptyStars', $emptyStars);
                    break;
                case 'outoftenstars':
                    $intrating = (int) ($rating / 10);
                    $fracrating = $rating - (10 * $intrating);
                    $fullStars = ($fracrating > 5) ? $intrating + 1 : $intrating;
                    $emptyStars = 10 - $fullStars;
                    $this->view->assign('rating', $intrating)
                               ->assign('fracrating', $fracrating)
                               ->assign('emptyStars', $emptyStars);
                    break;
            }
        } else {
            $this->view->assign('rawrating', 0)
                       ->assign('rating', 0);
        }
        $this->view->assign('showrating', $showrating)
                   ->assign('showratingform', 0);

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
                return $this->view->fetch($template);
            }
        } elseif ($seclevel == 'medium') {
            // Check against session to see if user has voted recently
            if (SessionUtil::getVar("Rated$args[modname]$style$objectid")) {
                return $this->view->fetch($template);
            }
        }

        // No check for low
        // This user hasn't rated this yet, ask them
        $this->view->assign('showratingform', 1)
                   ->assign('returnurl', $returnurl)
                   ->assign('modname', $args['modname'])
                   ->assign('objectid', $objectid)
                   ->assign('areaid', $areaid)
                   ->assign('ratingtype', $style);

        return $this->view->fetch($template);
    }

    /**
     * Process rating form.
     *
     * Takes input from the rating form and passes this to the API.
     *
     * @author Jim McDonald
     * @param string  $args['modname']    Source module name for which we're rating an oject
     * @param string  $args['objectid']   ID of object in source module
     * @param integer $args['areaid']     ID of hook area of this object
     * @param string  $args['ratingtype'] Specific type of rating for this item (optional)
     * @param string  $args['returnurl']  URL to return to if user chooses to rate
     * @param float   $args['rating']     Rating the user selected
     *
     * @return bool true if rating sucess, false otherwise.
     */
    public function rate($args)
    {
        // Get parameters
        $modname = $this->request->request->get('modname', null);
        $objectid = (int) $this->request->request->get('objectid', null);
        $areaid = $this->request->request->get('areaid', null);
        $ratingtype = $this->request->request->get('ratingtype', null);
        $rating = $this->request->request->get('rating', null);
        $returnurl = $this->request->request->get('returnurl', null);

        $this->checkCsrfToken();

        // Pass to API
        $rateArgs = array('modname' => $modname,
                          'objectid' => $objectid,
                          'areaid' => $areaid,
                          'ratingtype' => $ratingtype,
                          'rating' => $rating);
        $newRating = ModUtil::apiFunc($this->name, 'user', 'rate', $rateArgs);

        // Success
        if ($newRating) {
            LogUtil::registerStatus($this->__('Done! Thank you for rating this item.'));
        }

        return System::redirect($returnurl);
    }
}
