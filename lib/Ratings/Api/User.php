<?php

/**
 * Zikula Application Framework
 *
 * @copyright (c) 2002, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id$
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 */
class Ratings_Api_User extends Zikula_AbstractApi {

    /**
     * Get a rating for a specific item.
     *
     * @author Jim McDonald
     *
     * @param string  $args['modname']    name of the module this rating is for
     * @param string  $args['objectid']   ID of the item this rating is for
     * @param integer $args['areaid']     ID of the hook area
     * @param string  $args['ratingtype'] type of rating (optional)
     * @param integer $args['rid']        ID of the rating
     *
     * This API requires either (modname and objectid) or rid
     *
     * @return int rating the corresponding rating, or void if no rating exists
     */
    public function get($args) {
        
        // Argument check
        if ((!isset($args['modname']) || !isset($args['objectid']))
                && !isset($args['rid'])) {
            return LogUtil::registerArgsError();
        }

        if (!isset($args['ratingtype']) || $args['ratingtype'] == 'default') {
            $args['ratingtype'] = ModUtil::getVar('Ratings', 'defaultstyle');
        }

        $permFilter = array(array('realm' => 0,
                'component_left' => 'Ratings',
                'instance_left' => 'module',
                'instance_middle' => 'ratingtype',
                'instance_right' => 'itemid',
                'level' => ACCESS_READ));

        if (isset($args['modname']) && isset($args['objectid'])) {
            // Database information
            $table = DBUtil::getTables();
            $ratingscolumn = $table['ratings_column'];

            // form the where clause
            $where = "WHERE $ratingscolumn[module] = '" . DataUtil::formatForStore($args['modname']) . "'
                  AND $ratingscolumn[itemid] = '" . DataUtil::formatForStore($args['objectid']) . "'
                  AND $ratingscolumn[ratingtype] = '" . DataUtil::formatForStore($args['ratingtype']) . "'";
            if (isset($args['areaid']) && $args['areaid'] != '') {
                $where .= " AND $ratingscolumn[areaid] = '" . DataUtil::formatForStore($args['areaid']) . "'";
            }

            $ratings = DBUtil::selectObjectArray('ratings', $where, 'rid', 1, -1, '', $permFilter);
            if (isset($ratings[0])) {
                return $ratings[0];
            }
        } else if (isset($args['rid'])) {
            return DBUtil::selectObjectByID('ratings', $args['rid'], 'rid', '', $permFilter);
        }

        return false;
    }

    /**
     * Get all ratings for a given module.
     *
     * @author Mark West
     * @param string  $args['modname']    name of the module this rating is for
     * @param string  $args['ratingtype'] type of rating (optional)
     * @param string  $args['sortby']     column to sort by (optional)
     * @param integer $args['numitems']   number of items to return (optional)
     *
     * @return mixed array of ratings or false
     */
    public function getall($args) {
        
        // default rating type
        if (!isset($args['ratingtype']) || $args['ratingtype'] = 'default') {
            $args['ratingtype'] = ModUtil::getVar('Ratings', 'defaultstyle');
        }
        if (!isset($args['modname'])) {
            $args['modname'] = null;
        }

        $items = array();

        // Security check
        if (!SecurityUtil::checkPermission('Ratings::', "$args[modname]:$args[ratingtype]:", ACCESS_READ)) {
            return $items;
        }

        // Database information
        $table = DBUtil::getTables();
        $ratingscolumn = $table['ratings_column'];

        // set a default for the collateral clause
        if (!isset($args['cclause']) || is_numeric($args['cclause']) || $args['cclause'] != 'ASC') {
            $args['cclause'] = 'DESC';
        }

        // form where clause
        $whereargs = array();
        if (isset($args['modname'])) {
            $whereargs[] = "$ratingscolumn[module] = '" . DataUtil::formatForStore($args['modname']) . "'";
        }
        $whereargs[] = "$ratingscolumn[ratingtype] = '" . DataUtil::formatForStore($args['ratingtype']) . "'";
        $where = null;
        if (count($whereargs) > 0) {
            $where = ' WHERE ' . implode(' AND ', $whereargs);
        }

        // form order by clause
        if (isset($args['sortby'])) {
            $sortstring = " ORDER BY " . $ratingscolumn[$args['sortby']] . " " . $args['cclause'];
        } else {
            $sortstring = '';
        }

        $numitems = (isset($args['numitems']) && is_numeric($args['numitems'])) ? $args['numitems'] : -1;

        // define the permissions filter to apply
        $permFilter = array();
        $permFilter[] = array('realm' => 0,
            'component_left' => 'Ratings',
            'component_middle' => '',
            'component_right' => '',
            'instance_left' => 'module',
            'instance_middle' => 'ratingtype',
            'instance_right' => 'itemid',
            'level' => ACCESS_OVERVIEW);

        $items = DBUtil::selectObjectArray('ratings', $where, $sortstring, $limitOffset = -1, $numitems, '', $permFilter);

        // Check for an error with the database code, and if so set an appropriate error message and return
        if ($items === false) {
            return LogUtil::registerError($this->__('Error! Could not load items.'));
        }

        // Return the items
        return $items;
    }

    /**
     * utility function to count the number of items held by this module
     * @return integer number of items held by this module
     */
    public function countitems($args) {
        // Return the number of items
        return DBUtil::selectObjectCount('ratings', '', 'rid');
    }

    /**
     * Rate an item.
     *
     * @author Jim McDonald
     * @param string  $args['modname']    module name of the item to rate
     * @param string  $args['objectid']   ID of the item to create a rating for
     * @param integer $args['areaid']     hook areaID of the item to create a rating for
     * @param integer $args['id']         ID of the item to rate
     * @param string  $args['ratingtype'] type of rating (optional)
     * @param float   $args['rating']     actual rating
     *
     * @return int the new rating for this item
     */
    public function rate($args) {
        
        // Argument check
        if ((!isset($args['modname'])) ||
                (!isset($args['objectid'])) ||
                (!isset($args['areaid'])) ||
                (!isset($args['rating']))) {
            return LogUtil::registerArgsError();
        }

        if (!isset($args['ratingtype']) || $args['ratingtype'] = 'default') {
            $args['ratingtype'] = ModUtil::getVar('Ratings', 'defaultstyle');
        }

        // Security check
        if (!SecurityUtil::checkPermission('Ratings::', "$args[modname]:$args[ratingtype]:$args[objectid]", ACCESS_READ)) {
            return LogUtil::registerPermissionError();
        }

        // Database information
        $table = DBUtil::getTables();
        $ratingscolumn = $table['ratings_column'];
        $ratingslogcolumn = $table['ratingslog_column'];

        // Multiple rate check
        $seclevel = ModUtil::getVar('Ratings', 'seclevel');
        if ($seclevel == 'high') {
            // get the users user id
            $logid = UserUtil::getVar('uid');
            // get the users ip
            $logip = System::serverGetVar('REMOTE_ADDR');

            $where = "( $ratingslogcolumn[id] = '" . DataUtil::formatForStore($logid) . "' OR
                    $ratingslogcolumn[id] = '" . DataUtil::formatForStore($logip) . "' ) AND
                    $ratingslogcolumn[ratingid] = '" . $args['modname'] . $args['objectid'] . $args['ratingtype'] . "'";
            $row = DBUtil::selectFieldArray('ratingslog', 'id', $where, '');
            if ($row) {
                return false;
            }
        } elseif ($seclevel == 'medium') {
            // Check against session to see if user has voted recently
            if (SessionUtil::getVar("Rated" . $args['modname'] . $args['ratingtype'] . $args['objectid'])) {
                return false;
            }
        } // No check for low
        // check our input
        if ($args['rating'] < 0 || $args['rating'] > 100) {
            return LogUtil::registerArgsError();
        }

        $where = " $ratingscolumn[module] = '" . DataUtil::formatForStore($args['modname']) . "' AND
               $ratingscolumn[itemid] = '" . DataUtil::formatForStore($args['objectid']) . "' AND
               $ratingscolumn[areaid] = '" . DataUtil::formatForStore($args['areaid']) . "' AND
               $ratingscolumn[ratingtype] = '" . DataUtil::formatForStore($args['ratingtype']) . "'";
        $rating = DBUtil::selectObject('ratings', $where);
        // Check for an error with the database code, and if so set an appropriate error message and return
        if ($rating === false) {
            return LogUtil::registerError($this->__('Error! Could not load items.'));
        }

        if ($rating) {
            // Calculate new rating
            $rating['numratings']++;
            $rating['rating'] = (int) ((($rating['rating'] * ($rating['numratings'] - 1)) + $args['rating']) / $rating['numratings']);
            $res = DBUtil::updateObject($rating, 'ratings', '', 'rid');

            if ($res === false) {
                return LogUtil::registerError($this->__('Error! Update attempt failed.'));
            }
        } else {
            $rating = array();
            $rating['module'] = $args['modname'];
            $rating['itemid'] = $args['objectid'];
            $rating['areaid'] = $args['areaid'];
            $rating['ratingtype'] = $args['ratingtype'];
            $rating['rating'] = $args['rating'];
            $rating['numratings'] = 1;

            $res = DBUtil::insertObject($rating, 'ratings', 'rid');
            if ($res === false) {
                return LogUtil::registerError($this->__('Error! Save attempt failed.'));
            }
        }

        // Set note that user has rated this item if required
        if ($seclevel == 'high') {
            if (UserUtil::isLoggedIn()) {
                $logid = UserUtil::getVar('uid');
            } else {
                $logid = System::serverGetVar('REMOTE_ADDR');
            }

            $ratinglog = array();
            $ratinglog['id'] = $logid;
            $ratinglog['ratingid'] = $args['modname'] . $args['objectid'] . $args['ratingtype'];
            $ratinglog['rating'] = $args['rating'];
            $res = DBUtil::insertObject($ratinglog, 'ratingslog', 'rid');
            if ($res === false) {
                return LogUtil::registerError($this->__('Error! Save attempt failed.'));
            }
        } elseif ($seclevel == 'medium') {
            SessionUtil::setVar("Rated" . $args['modname'] . $args['ratingtype'] . $args['objectid'], true);
        }

        return $rating['rating'];
    }

}