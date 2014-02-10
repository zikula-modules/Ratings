<?php

/**
 * Zikula Application Framework
 *
 * @copyright (c) 2002, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id$
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 */
class Ratings_Api_Admin extends Zikula_AbstractApi {

    /**
     * delete a rating
     * @param $args['rid'] ID of the page
     * @return bool true on success, false on failure
     */
    public function delete($args) {
        // Argument check
        if (!isset($args['rid'])) {
            return LogUtil::registerArgsError();
        }

        // Check item exists before attempting deletion
        $item = ModUtil::apiFunc('Ratings', 'user', 'get', array('rid' => $args['rid']));
        if ($item == false) {
            return LogUtil::registerError($this->__('No such item found.'));
        }

        // Security check
        if (!SecurityUtil::checkPermission('Ratings::', "$item[module]:$item[ratingtype]:$item[itemid]", ACCESS_DELETE)) {
            return LogUtil::registerPermissionError();
        }

        // form the logid entry.
        $logid = $item['module'] . $item['itemid'] . $item['ratingtype'];

        // delete the log entries first then the main ratings
        if (!DBUtil::deleteObjectByID('ratingslog', $logid, 'ratingid')) {
            return LogUtil::registerError($this->__('Error! Deletion attempt failed.'));
        }
        if (!DBUtil::deleteObjectByID('ratings', $args['rid'], 'rid')) {
            return LogUtil::registerError($this->__('Error! Deletion attempt failed.'));
        }

        return true;
    }

    /**
     * clean up ratings for a removed module
     *
     * @param    $args['extrainfo']   array extrainfo array
     * @return   array extrainfo array
     */
    public function removehook($args) {
        // optional arguments
        if (!isset($args['extrainfo'])) {
            $args['extrainfo'] = array();
        }

        // When called via hooks, the module name may be empty, so we get it from
        // the current module
        if (empty($args['extrainfo']['module'])) {
            $modname = ModUtil::getName();
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
    public function deletehook($args) {
        // optional arguments
        if (!isset($args['extrainfo'])) {
            $args['extrainfo'] = array();
        }

        // set the object id
        $objectid = $args['objectid'];

        // When called via hooks, the module name may be empty, so we get it from
        // the current module
        if (empty($args['extrainfo']['module'])) {
            $modname = ModUtil::getName();
        } else {
            $modname = $args['extrainfo']['module'];
        }

        // Database information
        $pntable = DBUtil::getTables();
        $ratingscolumn = $pntable['ratings_column'];

        // prepare the item data
        list ($modname, $objectid) = DataUtil::formatForStore($modname, $objectid);
        $where = "$ratingscolumn[module] = '$modname' AND $ratingscolumn[itemid] = '$objectid'";
        DBUtil::deleteWhere('ratings', $where);

        return $args['extrainfo'];
    }

    /**
     * get available admin panel links
     *
     * @author Mark West
     * @return array array of admin links
     */
    public function getlinks() {
        $links = array();

        if (SecurityUtil::checkPermission('Ratings::', '::', ACCESS_READ)) {
            $links[] = array('url' => ModUtil::url('Ratings', 'admin', 'view'), 'text' => $this->__('View all ratings'));
        }
        if (SecurityUtil::checkPermission('Ratings::', '::', ACCESS_ADMIN)) {
            $links[] = array('url' => ModUtil::url('Ratings', 'admin', 'modifyconfig'), 'text' => $this->__('Settings'));
        }

        return $links;
    }

}
