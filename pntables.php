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
 * Get ratings pntable array
 * @author Jim McDonald
 * @return array
 */
function ratings_pntables()
{
    // Initialise table array
    $pntable = array();

    // Main ratings table
    $pntable['ratings'] = DBUtil::getLimitedTablename('ratings');
    $pntable['ratings_column'] = array('rid'        => 'pn_rid',
                                       'module'     => 'pn_module',
                                       'itemid'     => 'pn_itemid',
                                       'rating'     => 'pn_rating',
                                       'numratings' => 'pn_numratings');
    $pntable['ratings_column_def'] = array('rid'        => 'I NOTNULL AUTO PRIMARY',
                                           'module'     => "C(32) NOTNULL DEFAULT ''",
                                           'itemid'     => "C(32) NOTNULL DEFAULT ''",
                                           'rating'     => "F NOTNULL DEFAULT '0'",
                                           'numratings' => "I NOTNULL DEFAULT '1'");
    $pntable['ratings_column_idx'] = array ('module' => 'module',
                                            'itemid' => 'itemid');

    // Ratings log table
    $pntable['ratingslog'] = DBUtil::getLimitedTablename('ratingslog');
    $pntable['ratingslog_column'] = array('userid'   => 'pn_userid',
                                          'ratingid' => 'pn_ratingid');
    $pntable['ratingslog_column_def'] = array('userid'   => "I NOTNULL DEFAULT '1'",
                                              'ratingid' => "C(64) NOTNULL DEFAULT ''");
    $pntable['ratingslog_column_idx'] = array ('userid' => 'userid');

    // Return table information
    return $pntable;
}
