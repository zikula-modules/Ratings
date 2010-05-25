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
 * Log a vote and display the results form
 *
 * @author Mark West
 * @param pollid the poll to vote on
 * @param voteid the option to vote on
 * @return string updated display for the block
 */
function ratings_ajax_rate()
{
    $dom = ZLanguage::getModuleDomain('Ratings');
    $modname =    FormUtil::getPassedValue('modname', null, 'POST');
    $objectid =   FormUtil::getPassedValue('objectid', null, 'POST');
    $rating =     FormUtil::getPassedValue('rating', null, 'POST');
    $returnurl =  FormUtil::getPassedValue('returnurl', null, 'POST');

    if (!SecurityUtil::checkPermission('Ratings::', "$modname::$objectid", ACCESS_COMMENT)) {
        AjaxUtil::error(__('Sorry! No authorization to access this module.', $dom));
    }

    // log rating of item
    $newrating = pnModAPIFunc('Ratings', 'user', 'rate',
                              array('modname'    => $modname,
                                    'objectid'   => $objectid,
                                    'rating'     => $rating));

    // get the new output
    $result = pnModFunc('Ratings', 'user', 'display',
                        array('objectid'  => $objectid,
                              'extrainfo' => array('module'     => $modname,
                                                   'returnurl'  => $returnurl)));


    // return the new content for the block
    $output             = array();
    $output['result']   = $result;
    $output['objectid'] = $objectid;
    $output['modname']  = $modname;
    return AjaxUtil::output($output);
}
