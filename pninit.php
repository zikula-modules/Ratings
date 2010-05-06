<?php
/**
 * Ratings
 *
 * @copyright (c) 2002, Zikula Development Team
 * @link      http://code.zikula.org/ratings/
 * @version   $Id$
 * @license   GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 */

/**
 * Initialisation process
 *
 * @return boolean true if successful or false
 */
function ratings_init()
{
    $dom = ZLanguage::getModuleDomain('Ratings');

    // Creation of the tables into the database
    if (!DBUtil::createTable('ratings') ||
        !DBUtil::createTable('ratingslog')) {
        return LogUtil::registerError(__("Error! Creation attempt of the database tables failed.", $dom));
    }

    // Set up module variables
    if (!pnModSetVar('Ratings', 'defaultstyle', 'outoffivestars') ||
        !pnModSetVar('Ratings', 'useajax', false)                 ||
        !pnModSetVar('Ratings', 'usefancycontrols', false)        ||
        !pnModSetVar('Ratings', 'displayScoreInfo', false)        ||
        !pnModSetVar('Ratings', 'seclevel', 'medium')             ||
        !pnModSetVar('Ratings', 'itemsperpage', 25)) {
        return LogUtil::registerError(__("Error! Set up attempt of the module variables failed.", $dom));
    }

    // Set up module hooks
    if (!pnModRegisterHook('item', 'display', 'GUI', 'Ratings', 'user', 'display')    ||
        !pnModRegisterHook('item', 'delete', 'API', 'Ratings', 'admin', 'deletehook') ||
        !pnModRegisterHook('module', 'remove', 'API', 'Ratings', 'admin', 'removehook')) {
        return LogUtil::registerError(__("Error! Set up attempt of the module hooks failed.", $dom));
    }

    // Initialisation successful
    return true;
}

/**
 * Upgrade the ratings module from an old version
 *
 * @author Jim McDonald
 * @return true if upgrade success, false otherwise
 */
function ratings_upgrade($oldversion)
{
    // Upgrade dependent on old version number
    switch($oldversion) {
        case '1.0':
            // this upgrade is handled by the generic table alteration
            // Carry out other upgrades

        case '1.1':
            if (!pnModRegisterHook('module', 'remove', 'API', 'Ratings', 'admin', 'removehook')) {
                return '1.1';
            }

        case '1.2':
            // get all modules hooked to ratings
            $hookedmodules = pnModAPIFunc('Modules', 'admin', 'gethookedmodules', array('hookmodname'=> 'Ratings'));
            if (!pnModRegisterHook('item', 'delete', 'API', 'Ratings', 'admin', 'deletehook')) {
                return '1.2';
            }
            foreach ($hookedmodules as $modname => $hooktype) {
                // disable the hooks for this module
                pnModAPIFunc('Modules', 'admin', 'disablehooks', array('callermodname' => $modname, 'hookmodname' => 'Ratings'));
                // re-enable the hooks for this module
                pnModAPIFunc('Modules', 'admin', 'enablehooks', array('callermodname' => $modname, 'hookmodname' => 'Ratings'));
            }

        case '1.3':
            pnModSetVar('Ratings', 'useajax', false);
            pnModSetVar('Ratings', 'usefancycontrols', false);
            pnModSetVar('Ratings', 'itemsperpage', 25);

        case '2.0':
            if (!DBUtil::changeTable('ratings')) {
                return '2.0';
            }
            if (!DBUtil::changeTable('ratingslog')) {
                return '2.0';
            }

        case '2.1':
            pnModSetVar('Ratings', 'displayScoreInfo', false);

        case '2.2':
            // drop columns
            if (!DBUtil::dropColumn('ratings', 'pn_ratingtype') ||
                !DBUtil::dropColumn('ratingslog', 'pn_rating')) {
                LogUtil::registerError(__('Error! Drop attempt of the database columns failed.', $dom));
                return '2.2';
            }

            // rename column
            if (!DBUtil::renameColumn('ratingslog', 'pn_id', 'userid')) {
                LogUtil::registerError(__('Error! Rename attempt of the database columns failed.', $dom));
                return '2.2';
            }

            // update the ratings table
            $ratingsUpdateTable = array();
            $ratingsNewInsert   = array();
            $ratingsAll = DBUtil::selectObjectArray('ratings', '', '', -1, -1, 'rid');
            if (!empty($ratingsAll)) {
                foreach ($ratingsAll as $curRid => $curRtable) {
                    $curRitem = $curRtable['itemid'];
                    if (array_key_exists($curRitem, $ratingsUpdateTable)) {
                        $ratingsUpdateTable[$curRitem][] = $curRtable;
                    } else {
                        $ratingsUpdateTable[$curRitem] = array();
                        $ratingsUpdateTable[$curRitem][] = $curRtable;
                    }
                }
                foreach ($ratingsUpdateTable as $curRtables) {
                    $curRtableinsert = array();
                    $curRtableinsert['rid'] = null;
                    $curRtableinsert['module'] = $curRtables[0]['module'];
                    $curRtableinsert['itemid'] = $curRtables[0]['itemid'];
                    $rating = 0;
                    $numratings = 0;
                    $i = 0;
                    foreach ($curRtables as $curRtable) {
                        $i++;
                        $numratings = $numratings + $curRtable['numratings'];
                        $rating = $rating + $curRtable['rating'];
                    }
                    $rating = ($rating/$i);
                    $curRtableinsert['rating'] = $rating;
                    $curRtableinsert['numratings'] = $numratings;
                    $ratingsNewInsert[] = $curRtableinsert;
                }
                if (!DBUtil::deleteWhere('ratings', '') ||
                    !DBUtil::insertObjectArray($ratingsNewInsert, 'ratings')) {
                    LogUtil::registerError(__('Error! Update attempt of the database tables failed.', $dom));
                    return '2.2';
                }
            }

            // update the ratingslog table
            $tables = pnDBGetTables();
            $sqls = array();
            $sqls[] = "UPDATE `$tables[ratingslog]` SET pn_ratingid = REPLACE(pn_ratingid, 'outoffivestars', '');";
            $sqls[] = "UPDATE `$tables[ratingslog]` SET pn_ratingid = REPLACE(pn_ratingid, 'outoftenstars', '');";
            $sqls[] = "UPDATE `$tables[ratingslog]` SET pn_ratingid = REPLACE(pn_ratingid, 'percentage', '');";
            $sqls[] = "UPDATE `$tables[ratingslog]` SET pn_ratingid = REPLACE(pn_ratingid, 'outoffive', '');";
            $sqls[] = "UPDATE `$tables[ratingslog]` SET pn_ratingid = REPLACE(pn_ratingid, 'outoften', '');";
            $sqls[] = "CREATE TEMPORARY TABLE ratingslog_temp(pn_userid VARCHAR(32),pn_ratingid VARCHAR(64)) TYPE=HEAP;
                       INSERT INTO ratingslog_temp(pn_userid,pn_ratingid) SELECT DISTINCT pn_userid,pn_ratingid FROM `$tables[ratingslog]`;
                       DELETE FROM `$tables[ratingslog]`;
                       INSERT INTO `$tables[ratingslog]`(pn_userid,pn_ratingid) SELECT pn_userid,pn_ratingid FROM ratingslog_temp;";
            foreach ($sqls as $sql) {
                if (!DBUtil::executeSQL($sql)) {
                    LogUtil::registerError(__('Error! Update attempt of the database tables failed.', $dom));
                    return '2.2';
                }
            }
            unset($sqls);

            // update tables definition
            if (!DBUtil::changeTable('ratings') ||
                !DBUtil::changeTable('ratingslog')) {
                return '2.2';
            }
    }

    return true;
}

/**
 * Deletion process
 *
 * @return boolean true if successful or false
 */
function ratings_delete()
{
    $dom = ZLanguage::getModuleDomain('Ratings');

    // Remove module hooks
    if (!pnModUnregisterHook('item', 'display', 'GUI', 'Ratings', 'user', 'display')    ||
        !pnModUnRegisterHook('item', 'delete', 'API', 'Ratings', 'admin', 'deletehook') ||
        !pnModUnregisterHook('module', 'remove', 'API', 'Ratings', 'admin', 'removehook')) {
        return LogUtil::registerError (__('Error! Deregister attempt of the module hooks failed.', $dom));
    }

    // Drop tables into the database
    if (!DBUtil::dropTable('ratings')  ||
        !DBUtil::dropTable('ratingslog')) {
        return LogUtil::registerError (__('Error! Deletion attempt of the database tables failed.', $dom));
    }

    // Delete module variables
    pnModDelVar('Ratings');

    // Deletion successful
    return true;
}
