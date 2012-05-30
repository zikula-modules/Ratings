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
function Ratings_tables()
{
    // Initialise table array
    $table = array();

    // Main ratings table
    $table['ratings'] = DBUtil::getLimitedTablename('ratings');
    $table['ratings_column'] = array('rid'        => 'rid',
                                     'module'     => 'module',
                                     'itemid'     => 'itemid',
                                     'areaid'     => 'areaid',
                                     'ratingtype' => 'ratingtype',
                                     'rating'     => 'rating',
                                     'numratings' => 'numratings');
    $table['ratings_column_def'] = array('rid'        => 'I NOTNULL AUTO PRIMARY',
                                         'module'     => "C(32)  NOTNULL DEFAULT ''",
                                         'itemid'     => "C(64)  NOTNULL DEFAULT ''",
                                         'areaid'     => "I NOTNULL DEFAULT 0",
                                         'ratingtype' => "C(64)  NOTNULL DEFAULT ''",
                                         'rating'     => "F  NOTNULL DEFAULT '0'",
                                         'numratings' => "I NOTNULL DEFAULT '1'");
    $table['ratings_column_idx'] = array ('module' => 'module',
                                          'itemid' => 'itemid');

    // Ratings log table
    $table['ratingslog'] = DBUtil::getLimitedTablename('ratingslog');
    $table['ratingslog_column'] = array('id'       => 'id',
                                        'ratingid' => 'ratingid',
                                        'rating'   => 'rating');
    $table['ratingslog_column_def'] = array('id'       => "C(32) NOTNULL DEFAULT ''",
                                            'ratingid' => "C(64) NOTNULL DEFAULT ''",
                                            'rating'   => "I NOTNULL DEFAULT '0'");

    // Return table information
    return $table;
}
