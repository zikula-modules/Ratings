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
 * EZComments Hooks Handlers.
 */
class Ratings_HookHandlers extends Zikula_Hook_AbstractHandler {

    /**
     * Display hook for view.
     *
     * Subject is the object being viewed that we're attaching to.
     * args[id] Is the id of the object.
     * args[caller] the module who notified of this event.
     *
     * @param Zikula_Hook $hook The hook.
     *
     * @return void
     */
    public function uiView(Zikula_DisplayHook $hook) {

        $mod = $hook->getCaller();
        $objectid = $hook->getId();
        $areaid = $hook->getAreaId();

        $modUrl = $hook->getUrl();
        $returnurl = (!is_null($modUrl)) ? $modUrl->getUrl() : '';

        // Get the new output
        $result = ModUtil::func('Ratings', 'user', 'display', array('objectid' => $objectid,
                    'areaid' => $areaid,
                    'modname' => $mod,
                    'extrainfo' => array('returnurl' => $returnurl)));

        // Create output object
        $view = Zikula_View::getInstance('Ratings', false, null, true);

        $view->assign('areaid', $areaid)
             ->assign('result', $result);

        $template = 'ratings_hook_display.tpl';
        PageUtil::addVar('stylesheet', 'modules/Ratings/style/star_rating.css');
        $hook->setResponse(new Zikula_Response_DisplayHook('provider.ratings.ui_hooks.ratings', $view, $template));
    }

}
