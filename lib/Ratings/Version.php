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
        $meta['core_min'] = '1.3.0';
        $meta['securityschema'] = array('Ratings::' => 'Module name:Rating type:Item ID');
        $meta['capabilities'] = array();
        $meta['capabilities'][HookUtil::PROVIDER_CAPABLE] = array('enabled' => true);
        $meta['capabilities'][HookUtil::SUBSCRIBER_CAPABLE] = array('enabled' => true);


        return $meta;
    }

    protected function setupHookBundles() {
        $bundle = new Zikula_HookManager_ProviderBundle($this->name, 'provider.ratings.ui_hooks.ratings', 'ui_hooks', $this->__('Ratings rating Hooks'));
        $bundle->addServiceHandler('display_view', 'Ratings_HookHandlers', 'uiView', 'ratings.hooks.ratings');
//        $bundle->addServiceHandler('process_edit', 'EZComments_HookHandlers', 'processEdit', 'ezcomments.hooks.comments');
//        $bundle->addServiceHandler('process_delete', 'EZComments_HookHandlers', 'processDelete', 'ezcomments.hooks.comments');
        $this->registerHookProviderBundle($bundle);

//        $bundle = new Zikula_HookManager_SubscriberBundle($this->name, 'subscriber.ratings.ui_hooks.ratings', 'ui_hooks', $this->__('Ratings ratings Hooks'));
//        $bundle->addEvent('ui_view', 'ratings.ui_hooks.ratings.ui_view');
        /*
        $bundle->addEvent('ui_edit', 'ezcomments.ui_hooks.comments.ui_edit');
        $bundle->addEvent('validate_edit', 'ezcomments.ui_hooks.comments.validate_edit');
        $bundle->addEvent('validate_delete', 'ezcomments.ui_hooks.comments.validate_delete');
        $bundle->addEvent('process_edit', 'ezcomments.ui_hooks.comments.process_edit');
        $bundle->addEvent('process_delete', 'ezcomments.ui_hooks.comments.process_delete');
         * 
         */
//        $this->registerHookSubscriberBundle($bundle);

//        $bundle = new Zikula_HookManager_SubscriberBundle($this->name, 'subscriber.ratings.filter_hooks.ratings', 'filter_hooks', $this->__('Ratings ratings Filter'));
//        $bundle->addEvent('filter', 'ratings.filter_hooks.ratings.filter');
//        $this->registerHookSubscriberBundle($bundle);
    }

}