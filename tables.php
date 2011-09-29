<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2002, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id$
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 */

/**
 * Get ratings pntable array
 * @author Jim McDonald
 * @return array
 */
function ratings_tables()
{
    // Initialise table array
    $table = array();

    // Main ratings table
    $table['ratings'] = DBUtil::getLimitedTablename('ratings');
    $table['ratings_column'] = array('rid'        => 'pn_rid',
                                       'module'     => 'pn_module',
                                       'itemid'     => 'pn_itemid',
                                       'ratingtype' => 'pn_ratingtype',
                                       'rating'     => 'pn_rating',
                                       'numratings' => 'pn_numratings');
    $table['ratings_column_def'] = array('rid'        => 'I NOTNULL AUTO PRIMARY',
                                           'module'     => "C(32)  NOTNULL DEFAULT ''",
                                           'itemid'     => "C(64)  NOTNULL DEFAULT ''",
                                           'ratingtype' => "C(64)  NOTNULL DEFAULT ''",
                                           'rating'     => "F  NOTNULL DEFAULT '0'",
                                           'numratings' => "I NOTNULL DEFAULT '1'");
    $table['ratings_column_idx'] = array ('module' => 'module',
                                            'itemid' => 'itemid');

    // Ratings log table
    $table['ratingslog'] = DBUtil::getLimitedTablename('ratingslog');
    $table['ratingslog_column'] = array('id'       => 'pn_id',
                                          'ratingid' => 'pn_ratingid',
                                          'rating'   => 'pn_rating');
    $table['ratingslog_column_def'] = array('id'       => "C(32) NOTNULL DEFAULT ''",
                                              'ratingid' => "C(64) NOTNULL DEFAULT ''",
                                              'rating'   => "I NOTNULL DEFAULT '0'");

    // Return table information
    return $table;
}
