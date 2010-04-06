<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2002, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id$
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 */

/**
 * Main ratings administration function
 * @return the result of view() function
 */
function ratings_admin_main()
{
    // Security check will be done in view()
    return ratings_admin_view();
}

/**
 * view items
 *
 * @param int $startnum the start item id for the pager
 * @return string HTML output
 */
function ratings_admin_view()
{
    $dom = ZLanguage::getModuleDomain('Ratings');
    // Security check
    if (!SecurityUtil::checkPermission('Ratings::', '::', ACCESS_EDIT)) {
        return LogUtil::registerPermissionError();
    }

    // Get parameters from whatever input we need.
    $startnum = (int)FormUtil::getPassedValue('startnum', isset($args['startnum']) ? $args['startnum'] : null, 'GET');

    // Create output object
    $pnRender = pnRender::getInstance('Ratings', false);

    // we need this value multiple times, so we keep it
    $itemsperpage = pnModGetVar('Ratings', 'itemsperpage');

    // Get all matching ratings
    $items = pnModAPIFunc('Ratings', 'user', 'getall',
                          array('startnum' => $startnum,
                                'numitems' => $itemsperpage));

    if (!$items) {
        $items = array();
    }

    $rows = array();
    foreach ($items as $key => $item) {
        $options = array();
        if (SecurityUtil::checkPermission('Ratings::', "$item[module]::$item[rid]", ACCESS_EDIT)) {
            $modulemeta = pnModAPIFunc($item['module'], 'user', 'getmodulemeta');
            if (isset($modulemeta['displayfunc'])) {
                $options[] = array('url'   => pnModURL($item['module'], 'user', $modulemeta['displayfunc'], array($modulemeta['itemid'] => $item['itemid'])),
                                   'image' => 'demo.gif',
                                   'title' => __('View', $dom));
            }
            if (SecurityUtil::checkPermission('Ratings::', "$item[module]::$item[rid]", ACCESS_DELETE)) {
                $options[] = array('url'   => pnModURL('Ratings', 'admin', 'delete', array('rid' => $item['rid'])),
                                   'image' => '14_layer_deletelayer.gif',
                                   'title' => __('Delete', $dom));
            }
        }

        // Add the calculated menu options to the item array
        $item['options'] = $options;
        $rows[] = $item;
    }

    // Assign the items to the template
    $pnRender->assign('ratings', $rows);

    // Assign the information required to create the pager
    $pnRender->assign('pager', array('numitems'     => pnModAPIFunc('Ratings', 'user', 'countitems'),
                                     'itemsperpage' => $itemsperpage));

    // Return the output that has been generated by this function
    return $pnRender->fetch('ratings_admin_view.htm');
}

/**
 * delete item
 *
 * @param 'rid' the id of the rating
 * @param 'confirmation' confirmation that this item can be deleted
 * @return mixed string HTML output if no confirmation otherwise true
 */
function ratings_admin_delete($args)
{
    $dom = ZLanguage::getModuleDomain('Ratings');
    $rid = FormUtil::getPassedValue('rid', isset($args['rid']) ? $args['rid'] : null, 'REQUEST');
    $objectid = FormUtil::getPassedValue('objectid', isset($args['objectid']) ? $args['objectid'] : null, 'REQUEST');
    $confirmation = FormUtil::getPassedValue('confirmation', null, 'POST');
     if (!empty($objectid)) {
         $rid = $objectid;
     }

    // Get the existing page
    $item = pnModAPIFunc('Ratings', 'user', 'get', array('rid' => $rid));

    if ($item == false) {
        return LogUtil::registerError (__('No such item found.', $dom), 404);
    }

    // Security check
    if (!SecurityUtil::checkPermission('Ratings::', "$item[module]::$rid", ACCESS_DELETE)) {
        return LogUtil::registerPermissionError();
    }

    // Check for confirmation.
    if (empty($confirmation)) {
        // No confirmation yet
        // Create output object
        $pnRender = pnRender::getInstance('Ratings', false);

        // Add a hidden field for the item ID to the output
        $pnRender->assign('rid', $rid);

        // Return the output that has been generated by this function
        return $pnRender->fetch('ratings_admin_delete.htm');
    }

    // If we get here it means that the user has confirmed the action

    // Confirm authorisation code
    if (!SecurityUtil::confirmAuthKey()) {
        return LogUtil::registerAuthidError (pnModURL('Ratings', 'admin', 'view'));
    }

    // Delete the page
    if (pnModAPIFunc('Ratings', 'admin', 'delete', array('rid' =>$rid))) {
        // Success
        LogUtil::registerStatus (__('Done! Item deleted.', $dom));
    }

    return pnRedirect(pnModURL('Ratings', 'admin', 'view'));
}

/**
 * Modify Ratings configuration
 * @author Jim McDonald
 * @return HTML String
 */
function ratings_admin_modifyconfig()
{
    $dom = ZLanguage::getModuleDomain('Ratings');
    // Security check
    if (!SecurityUtil::checkPermission('Ratings::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError();
    }

    // Create output object
    $pnRender = pnRender::getInstance('Ratings', false);

    // assign values for the dropdown
    $pnRender->assign('defaultstylevalues', array('percentage' => __('Percentage', $dom),
                                                  'outoffive' => __('Number out of five', $dom),
                                                  'outoften' => __('Number out of ten', $dom),
                                                  'outoffivestars' => __('Stars out of five', $dom),
                                                  'outoftenstars' => __('Stars out of ten', $dom)));
    $pnRender->assign('securitylevelvalues', array('low' => __('Low (user can vote multiple times)', $dom),
                                                   'medium' => __('Medium (user can vote once per session)', $dom),
                                                   'high' => __('High (user can only vote once)', $dom)));

    $pnRender->assign(pnModGetVar('Ratings'));

    // Return the output that has been generated by this function
    return $pnRender->fetch('ratings_admin_modifyconfig.htm');
}

/**
 * Update configuration
 * @author Jim McDonald
 * @param 'style' ratings style
 * @param 'seclevel' security level for ratings
 * @return true if update config success, false otherwise
 */
function ratings_admin_updateconfig()
{
    $dom = ZLanguage::getModuleDomain('Ratings');
    // Security check
    if (!SecurityUtil::checkPermission('Ratings::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError();
    }

    // Confirm authorisation code
    if (!SecurityUtil::confirmAuthKey()) {
        return LogUtil::registerAuthidError(pnModURL('Ratings', 'admin', 'main'));
    }

    // Update default style
    $defaultstyle = FormUtil::getPassedValue('defaultstyle', 'outoffivestars', 'POST');
    pnModSetVar('Ratings', 'defaultstyle', $defaultstyle);

    // Update default style
    $useajax = (bool)FormUtil::getPassedValue('useajax', false, 'POST');
    pnModSetVar('Ratings', 'useajax', $useajax);

    // Update default style
    $usefancycontrols = (bool)FormUtil::getPassedValue('usefancycontrols', false, 'POST');
    pnModSetVar('Ratings', 'usefancycontrols', $usefancycontrols);

    // Update security level
    $displayScoreInfo = (bool)FormUtil::getPassedValue('displayScoreInfo', false, 'POST');
    pnModSetVar('Ratings', 'displayScoreInfo', $displayScoreInfo);

    // Update security level
    $seclevel = FormUtil::getPassedValue('seclevel', 'medium', 'POST');
    pnModSetVar('Ratings', 'seclevel', $seclevel);

    // Update items per page
    $itemsperpage = (int)FormUtil::getPassedValue('itemsperpage', 25, 'POST');
    if ($itemsperpage < 1) {
        $itemsperpage = 25;
    }
    pnModSetVar('Ratings', 'itemsperpage', $itemsperpage);

    // the module configuration has been updated successfuly
    LogUtil::registerStatus (__('Done! Module configuration updated.', $dom));

    return pnRedirect(pnModURL('Ratings', 'admin', 'main'));
}
