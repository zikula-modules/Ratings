<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2002, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id: pninit.php 24342 2008-06-06 12:03:14Z markwest $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Zikula_Value_Addons
 * @subpackage Ratings
 */

/**
 * Initialise the ratings module
 *
 * @author Jim McDonald
 * @return true if init success, false otherwise
 */
function ratings_init()
{
    if (!DBUtil::createTable('ratings')) {
        return false;
    }
    if (!DBUtil::createTable('ratingslog')) {
        return false;
    }

    // Set up module variables
    pnModSetVar('Ratings', 'defaultstyle', 'outoffivestars');
    pnModSetVar('Ratings', 'useajax', false);
    pnModSetVar('Ratings', 'usefancycontrols', false);
    pnModSetVar('Ratings', 'seclevel', 'medium');
    pnModSetVar('Ratings', 'itemsperpage', 25);

    // Set up module hooks
    if (!pnModRegisterHook('item', 'display', 'GUI', 'Ratings', 'user', 'display')) {
        return false;
    }
    if (!pnModRegisterHook('item', 'delete', 'API', 'Ratings', 'admin', 'deletehook')) {
        return false;
    }
    if (!pnModRegisterHook('module', 'remove', 'API', 'Ratings', 'admin', 'removehook')) {
        return false;
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
    if (!DBUtil::changeTable('ratings')) {
        return false;
    }
    if (!DBUtil::changeTable('ratingslog')) {
        return false;
    }

    // Upgrade dependent on old version number
    switch($oldversion) {
        case 1.0:
		    // this upgrade is handled by the generic table alteration
            // Carry out other upgrades
            return ratings_upgrade(1.1);
        case 1.1:
            if (!pnModRegisterHook('module', 'remove', 'API', 'Ratings', 'admin', 'removehook')) {
                return false;
            }
            return ratings_upgrade(1.2);
        case 1.2:
            // get all modules hooked to ratings
            $hookedmodules = pnModAPIFunc('Modules', 'admin', 'gethookedmodules', array('hookmodname'=> 'Ratings'));
            if (!pnModRegisterHook('item', 'delete', 'API', 'Ratings', 'admin', 'deletehook')) {
                return false;
            }
            foreach ($hookedmodules as $modname => $hooktype) {
                // disable the hooks for this module
                pnModAPIFunc('Modules', 'admin', 'disablehooks', array('callermodname' => $modname, 'hookmodname' => 'Ratings'));
                // re-enable the hooks for this module
                pnModAPIFunc('Modules', 'admin', 'enablehooks', array('callermodname' => $modname, 'hookmodname' => 'Ratings'));
            }
            return ratings_upgrade(1.3);
        case 1.3:
            pnModSetVar('Ratings', 'useajax', false);
            pnModSetVar('Ratings', 'usefancycontrols', false);
            pnModSetVar('Ratings', 'itemsperpage', 25);
            return ratings_upgrade(2.0);
    }

    return true;
}

/**
 * delete the ratings module
 *
 * @author Jim McDonald
 * @return true if delete success, false otherwise
 */
function ratings_delete()
{
    // Remove module hooks
    if (!pnModUnregisterHook('item', 'display', 'GUI', 'Ratings', 'user', 'display')) {
        LogUtil::registerError (_UNREGISTERFAILED);
        // return false;
    }
    if (!pnModUnRegisterHook('item', 'delete', 'API', 'Ratings', 'admin', 'deletehook')) {
        LogUtil::registerError (_UNREGISTERFAILED);
        // return false;
    }
    if (!pnModUnregisterHook('module', 'remove', 'API', 'Ratings', 'admin', 'removehook')) {
        LogUtil::registerError (_UNREGISTERFAILED);
        // return false;
    }

    if (!DBUtil::dropTable('ratings')) {
        return false;
    }
    if (!DBUtil::dropTable('ratingslog')) {
        return false;
    }

    // Delete module variables
    pnModDelVar('Ratings');

    // Deletion successful
    return true;
}
