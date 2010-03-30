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

/**
 * The main ratings user function
 * @author Jim McDonald
 * @return HTML String
 */
function ratings_user_main()
{
    $dom = ZLanguage::getModuleDomain('Ratings');
    // ratings module cannot be directly accessed
    return LogUtil::registerError(__('Sorry! This module cannot be accessed directly.', $dom), 403);
}

/**
 * display rating for a specific item, and request rating
 * @author Jim McDonald
 * @param $args['objectid'] ID of the item this rating is for
 * @param $args['extrainfo'] URL to return to if user chooses to rate
 * @param $args['style'] style to display this rating in (optional)
 * @return string output with rating information
 */
function ratings_user_display($args)
{
    $dom = ZLanguage::getModuleDomain('Ratings');
    extract($args);

    if (!isset($style)) {
        $style = pnModGetVar('Ratings', 'defaultstyle');
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
        $args['modname'] = pnModGetName();
    }

    // RNG: add template override option
    $template = 'ratings_user_display.htm';
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
    $fullrating = pnModAPIFunc('Ratings', 'user', 'get', $args);
    $rating = $fullrating['rating'];

    // Create output object
    $pnRender = pnRender::getInstance('Ratings', false);

    // RNG: determine if override template is valid
    if ($pnRender->template_exists(DataUtil::formatForOS($tplOverride . '/' . $template))) {
        $template = $tplOverride . '/' . $template;
    }
    // RNG End

    // assign the rating style
    $pnRender->assign('style', $style);
    $pnRender->assign('useajax', pnModGetVar('Ratings', 'useajax'));
    $pnRender->assign('usefancycontrols', pnModGetVar('Ratings', 'usefancycontrols'));

    $showrating = false;
    if (isset($rating)) {
        $pnRender->assign('rawrating', $rating);
        // Display current rating
        $showrating = true;
        switch($style) {
            case 'percentage':
                $pnRender->assign('rating', $rating);
                break;
            case 'outoffive':
                $rating = (int)(($rating+10)/20);
                $pnRender->assign('rating', $rating);
                break;
            case 'outoffivestars':
                $rating = (int)($rating/2);
                $intrating = (int)($rating/10);
                $fracrating = $rating - (10*$intrating);
                $pnRender->assign('rating', $intrating);
                $pnRender->assign('fracrating', $fracrating);
                break;
            case 'outoften':
                $rating = (int)(($rating+5)/10);
                $pnRender->assign('rating', $rating);
                break;
            case 'outoftenstars':
                $intrating = (int)($rating/10);
                $fracrating = $rating - (10*$intrating);
                $pnRender->assign('rating', $intrating);
                $pnRender->assign('fracrating', $fracrating);
                break;
        }
    } else {
        $pnRender->assign('rawrating', 0);
        $pnRender->assign('rating', 0);
    }
    $pnRender->assign('showrating', $showrating);
    $pnRender->assign('showratingform', 0);

    // Multiple rate check
    $seclevel = pnModGetVar('Ratings', 'seclevel');

    if ($seclevel == 'high') {
        // Database information
        $pntable = pnDBGetTables();
        $ratingslogcolumn = $pntable['ratingslog_column'];

        // Check against table to see if user has already voted
        // we need to check against both ip and id
        $logid = pnUserGetVar('uid');
        // get the users ip
        $logip = pnServerGetVar('REMOTE_ADDR');

        $where = "($ratingslogcolumn[id] = '" . DataUtil::formatForStore($logid) . "' OR
                   $ratingslogcolumn[id] = '" . DataUtil::formatForStore($logip) . "' ) AND
		           $ratingslogcolumn[ratingid] = '" . $args['modname'] . $objectid . $style . "'";
        $rating = DBUtil::selectField ('ratingslog', 'id', $where);
        if ($rating) {
            return $pnRender->fetch($template);
        }
    } elseif ($seclevel == 'medium') {
        // Check against session to see if user has voted recently
        if (SessionUtil::getVar("Rated$args[modname]$style$objectid")) {
            return $pnRender->fetch($template);
        }
    }
    // No check for low
    // This user hasn't rated this yet, ask them
    $pnRender->assign('showratingform', 1);
    $pnRender->assign('returnurl', $returnurl);
    $pnRender->assign('modname', $args['modname']);
    $pnRender->assign('objectid', $objectid);
    $pnRender->assign('ratingtype', $style);
    $pnRender->assign('permission', $permission);

    return $pnRender->fetch($template);
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
function ratings_user_rate($args)
{
    $dom = ZLanguage::getModuleDomain('Ratings');
    // Get parameters
    $modname    = FormUtil::getPassedValue('modname', null, 'POST');
    $ratingtype = FormUtil::getPassedValue('ratingtype', null, 'POST');
    $returnurl  = FormUtil::getPassedValue('returnurl', null, 'POST');
    $rating     = FormUtil::getPassedValue('rating', null, 'POST');
    $objectid   = (int)FormUtil::getPassedValue('objectid', null, 'POST');

    // Confirm authorisation code
    if (!SecurityUtil::confirmAuthKey()) {
        return LogUtil::registerAuthidError ($returnurl);
    }

    // Pass to API
    $newrating = pnModAPIFunc('Ratings', 'user', 'rate',
                              array('modname'    => $modname,
                                    'objectid'   => $objectid,
                                    'ratingtype' => $ratingtype,
                                    'rating'     => $rating));

    if ($newrating) {
        // Success
        LogUtil::registerStatus (__('Done! Thank you for rating this item.', $dom));
    }

    return pnRedirect($returnurl);
}
