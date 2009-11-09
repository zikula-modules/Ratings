<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2002, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id$
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Zikula_Value_Addons
 * @subpackage Ratings
 */

$dom = ZLanguage::getModuleDomain('Ratings');
$modversion['name']           = 'Ratings';
$modversion['displayname']    = __('Ratings', $dom);
$modversion['description']    = __('Rate Zikula items.', $dom);
$modversion['url'] = __('ratings', $dom);
$modversion['version'] = '2.1';
$modversion['credits'] = 'pndocs/credits.txt';
$modversion['help'] = 'pndocs/help.txt';
$modversion['changelog'] = 'pndocs/changelog.txt';
$modversion['license'] = 'pndocs/license.txt';
$modversion['coding'] = 'pndocs/coding.txt';
$modversion['official'] = 1;
$modversion['author'] = 'Jim McDonald';
$modversion['contact'] = 'http://www.mcdee.net/';
$modversion['securityschema'] = array('Ratings::' => 'Module name:Rating type:Item ID');
