<?php

/**
 * Zikula Application Framework
 *
 * @copyright (c) 2002, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id$
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 */
class Ratings_Version extends Zikula_AbstractVersion {

    public function getMetaData() {
        $meta = array();
        $meta['displayname'] = $this->__("Ratings");
        $meta['description'] = $this->__("Rate Zikula items.");
        $meta['url'] = $this->__("ratings");
        $meta['version'] = '2.3.0';
        $meta['core_min'] = '1.3.0'; // Fixed to 1.3.x range
        $meta['core_max'] = '1.3.99'; // Fixed to 1.3.x range
        $meta['securityschema'] = array('Ratings::' => 'Module name:Rating type:Item ID');
        $meta['capabilities'] = array();
        $meta['capabilities'][HookUtil::PROVIDER_CAPABLE] = array('enabled' => true);
       // $meta['capabilities'][HookUtil::SUBSCRIBER_CAPABLE] = array('enabled' => true);

        return $meta;
    }

    protected function setupHookBundles() {
        $bundle = new Zikula_HookManager_ProviderBundle($this->name, 'provider.ratings.ui_hooks.ratings', 'ui_hooks', $this->__('Ratings rating Hooks'));
        $bundle->addServiceHandler('display_view', 'Ratings_HookHandlers', 'uiView', 'ratings.hooks.ratings');
        $this->registerHookProviderBundle($bundle);
    }

}