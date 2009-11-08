<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2002, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id: pnadminapi.php 24727 2008-10-08 13:30:41Z Guite $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Zikula_Value_Addons
 * @subpackage Ratings
 */

/**
 * delete a rating
 * @param $args['rid'] ID of the page
 * @return bool true on success, false on failure
 */
function ratings_adminapi_delete($args)
{
    // Argument check
    if (!isset($args['rid'])) {
        return LogUtil::registerError(_MODARGSERROR);
    }

    // Check item exists before attempting deletion
    $item = pnModAPIFunc('Ratings', 'user', 'get', array('rid' => $args['rid']));
    if ($item == false) {
        return LogUtil::registerError(_NOSUCHITEM);
    }

    // Security check
    if (!SecurityUtil::checkPermission('Ratings::', "$item[module]:$item[ratingtype]:$item[itemid]", ACCESS_DELETE)) {
        return LogUtil::registerError(_MODULENOAUTH);
    }

    // form the logid entry.
    $logid = $item['module'] . $item['itemid'] . $item['ratingtype'];

    // delete the log entries first then the main ratings
    if (!DBUtil::deleteObjectByID('ratingslog', $logid, 'ratingid')) {
        return LogUtil::registerError(_DELETEFAILED);
    }
    if (!DBUtil::deleteObjectByID('ratings', $args['rid'], 'rid')) {
        return LogUtil::registerError(_DELETEFAILED);
    }

    // Let any hooks know that we have deleted an item.
    pnModCallHooks('item', 'delete', $args['rid'], array('module' => 'Ratings'));

    return true;
}

/**
 * clean up ratings for a removed module
 *
 * @param    $args['extrainfo']   array extrainfo array
 * @return   array extrainfo array
 */
function Ratings_adminapi_removehook($args)
{
    // optional arguments
    if (!isset($args['extrainfo'])) {
        $args['extrainfo'] = array();
    }

    // When called via hooks, the module name may be empty, so we get it from
    // the current module
    if (empty($args['extrainfo']['module'])) {
        $modname = pnModGetName();
    } else {
        $modname = $args['extrainfo']['module'];
    }

    DBUtil::deleteObjectByID('ratings', $modname, 'module');
    return $args['extrainfo'];
}

/**
 * clean up ratings for a removed item
 *
 * @param    $args['extrainfo']   array extrainfo array
 * @return   array extrainfo array
 */
function Ratings_adminapi_deletehook($args)
{
    // optional arguments
    if (!isset($args['extrainfo'])) {
        $args['extrainfo'] = array();
    }

    // set the object id
    $objectid = $args['objectid'];

    // When called via hooks, the module name may be empty, so we get it from
    // the current module
    if (empty($args['extrainfo']['module'])) {
        $modname = pnModGetName();
    } else {
        $modname = $args['extrainfo']['module'];
    }

    // Database information
    $pntable = pnDBGetTables();
    $ratingscolumn = $pntable['ratings_column'];

    // prepare the item data
    list ($modname, $objectid) = DataUtil::formatForStore($modname, $objectid);
    $where = "$ratingscolumn[module] = '$modname' AND $ratingscolumn[itemid] = '$objectid'";
    DBUtil::deleteWhere ('ratings', $where);

    return $args['extrainfo'];
}

/**
 * get available admin panel links
 *
 * @author Mark West
 * @return array array of admin links
 */
function ratings_adminapi_getlinks()
{
    $links = array();

    pnModLangLoad('Ratings', 'admin');

    if (SecurityUtil::checkPermission('Ratings::', '::', ACCESS_READ)) {
        $links[] = array('url' => pnModURL('Ratings', 'admin', 'view'), 'text' => _RATINGSVIEWITEMS);
    }
    if (SecurityUtil::checkPermission('Ratings::', '::', ACCESS_ADMIN)) {
        $links[] = array('url' => pnModURL('Ratings', 'admin', 'modifyconfig'), 'text' => _MODIFYCONFIG);
    }

    return $links;
}
