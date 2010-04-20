<?php
/**
 * Ratings
 *
 * @copyright (c) 2002, Zikula Development Team
 * @link      http://code.zikula.org/ratings/
 * @version   $Id$
 * @license   GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 */

$dom = ZLanguage::getModuleDomain('Ratings');
$modversion['name']           = 'Ratings';
$modversion['displayname']    = __('Ratings', $dom);
$modversion['description']    = __('The Ratings module provides a hook to other modules, allowing your users to rate a module item.', $dom);
$modversion['url']            = __(/*!module name that appears in URL*/'ratings', $dom);
$modversion['version']        = '2.3';

$modversion['credits']        = 'pndocs/credits.txt';
$modversion['help']           = 'pndocs/help.txt';
$modversion['changelog']      = 'pndocs/changelog.txt';
$modversion['license']        = 'pndocs/license.txt';

$modversion['official']       = false;

$modversion['author']         = 'Jim McDonald, Christophe Beaujean';
$modversion['contact']        = 'http://code.zikula.org/ratings';

$modversion['securityschema'] = array('Ratings::' => 'Module name:Rating type:Item ID');
