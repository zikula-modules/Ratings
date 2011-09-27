<?php

/**
 * Zikula Application Framework
 *
 * @copyright (c) 2002, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id$
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 */
class Ratings_Installer extends Zikula_AbstractInstaller {

    /**
     * Initialisation process
     *
     * @return boolean true if successful or false
     */
    public function Install() {

        // register hooks
        HookUtil::registerProviderBundles($this->version->getHookProviderBundles());

        // register the module delete hook
        EventUtil::registerPersistentModuleHandler('Ratings', 'installer.module.uninstalled', array('Ratings_EventHandlers', 'moduleDelete'));
        EventUtil::registerPersistentModuleHandler('Ratings', 'installer.subscriberarea.uninstalled', array('Ratings_EventHandlers', 'hookAreaDelete'));

        // Creation of the tables into the database
        if (!DBUtil::createTable('ratings') ||
                !DBUtil::createTable('ratingslog')) {
            return LogUtil::registerError($this->__("Error! Creation attempt of the database tables failed."));
        }

        // Set up module variables
        if (!ModUtil::setVar('Ratings', 'defaultstyle', 'outoffivestars') ||
                !ModUtil::setVar('Ratings', 'useajax', false) ||
                !ModUtil::setVar('Ratings', 'usefancycontrols', false) ||
                !ModUtil::setVar('Ratings', 'displayScoreInfo', false) ||
                !ModUtil::setVar('Ratings', 'seclevel', 'medium') ||
                !ModUtil::setVar('Ratings', 'itemsperpage', 25)) {
            return LogUtil::registerError($this->__("Error! Set up attempt of the module variables failed."));
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
    public function upgrade($oldversion) {
        // Upgrade dependent on old version number
        switch ($oldversion) {
            case '1.0':
            // this upgrade is handled by the generic table alteration
            // Carry out other upgrades

            case '1.1':
                if (!ModUtil::registerHook('module', 'remove', 'API', 'Ratings', 'admin', 'removehook')) {
                    return '1.1';
                }

            case '1.2':
                // get all modules hooked to ratings
                $hookedmodules = ModUtil::apiFunc('Modules', 'admin', 'gethookedmodules', array('hookmodname' => 'Ratings'));
                if (!ModUtil::registerHook('item', 'delete', 'API', 'Ratings', 'admin', 'deletehook')) {
                    return '1.2';
                }
                foreach ($hookedmodules as $modname => $hooktype) {
                    // disable the hooks for this module
                    ModUtil::apiFunc('Modules', 'admin', 'disablehooks', array('callermodname' => $modname, 'hookmodname' => 'Ratings'));
                    // re-enable the hooks for this module
                    ModUtil::apiFunc('Modules', 'admin', 'enablehooks', array('callermodname' => $modname, 'hookmodname' => 'Ratings'));
                }

            case '1.3':
                ModUtil::setVar('Ratings', 'useajax', false);
                ModUtil::setVar('Ratings', 'usefancycontrols', false);
                ModUtil::setVar('Ratings', 'itemsperpage', 25);

            case '2.0':
                if (!DBUtil::changeTable('ratings')) {
                    return '2.0';
                }
                if (!DBUtil::changeTable('ratingslog')) {
                    return '2.0';
                }

            case '2.1':
                $this->setVar('displayScoreInfo', false);

            case '2.2':

            case '2.3.0':
            // Further upgrade routines
        }

        return true;
    }

    /**
     * Deletion process
     *
     * @return boolean true if successful or false
     */
    public function uninstall() {
        // Drop tables into the database
        if (!DBUtil::dropTable('ratings') ||
                !DBUtil::dropTable('ratingslog')) {
            return LogUtil::registerError($this->__('Error! Deletion attempt of the database tables failed.'));
        }

        // delete all module vars for the ezcomments module
        $this->delVars();

        HookUtil::unregisterProviderBundles($this->version->getHookProviderBundles());
        EventUtil::unregisterPersistentModuleHandler('Ratings', 'installer.module.uninstalled', array('Ratings_EventHandlers', 'moduleDelete'));
        EventUtil::unregisterPersistentModuleHandler('Ratings', 'installer.subscriberarea.uninstalled', array('Ratings_EventHandlers', 'hookAreaDelete'));
        
        // Deletion successful
        return true;
    }

}