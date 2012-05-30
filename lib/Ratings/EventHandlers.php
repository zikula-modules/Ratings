<?php
/**
 * Copyright 2009 Zikula Foundation.
 *
 * This work is contributed to the Zikula Foundation under one or more
 * Contributor Agreements and licensed to You under the following license:
 *
 * @license MIT
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

/**
 * EventHandlers class.
 */
class Ratings_EventHandlers
{
    /**
     * Handle module uninstall event "installer.module.uninstalled".
     * Receives $modinfo as $args
     *
     * @param Zikula_Event $event
     *
     * @return void
     */
    public static function moduleDelete(Zikula_Event $event)
    {
        $mod = $event['name'];

        // Database information
        ModUtil::dbInfoLoad('Ratings');
        $tables  = DBUtil::getTables();
        $columns = $tables['ratings_column'];

        // Get items
        $where = "WHERE $columns[module] = '" . DataUtil::formatForStore($mod) . "'";

        DBUtil::deleteWhere('ratings', $where);

    }

    /**
     * Listener for installer.subscriberarea.uninstalled
     *
     * @param Zikula_Event $event
     *
     * @return void
     */
    public static function hookAreaDelete(Zikula_Event $event)
    {
        $areaId = $event['areaid'];

        // Database information
        ModUtil::dbInfoLoad('Ratings');
        $tables  = DBUtil::getTables();
        $columns = $tables['ratings_column'];

        // Get items
        $where = "WHERE $columns[areaid] = '" . DataUtil::formatForStore($areaId) . "'";

        DBUtil::deleteWhere('ratings', $where);
    }
}
