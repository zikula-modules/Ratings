<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2001, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id$
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
*/

class Ratings_Controller_Ajax extends Zikula_Controller_AbstractAjax
{
    /**
     * Log a vote and display the results form
     *
     * @author Mark West
     * @param pollid the poll to vote on
     * @param voteid the option to vote on
     * @return string updated display for the block
     */
    public function rate()
    {
        $modName    = $this->request->request->get('modname', null);
        $objectid   = $this->request->request->get('objectid', null);
        $rating     = $this->request->request->get('rating', null);
        $ratingType = $this->request->request->get('ratingtype', $this->getVar('defaultstyle'));
        $returnUrl  = $this->request->request->get('returnurl', null);

        $instance = $modName . ':' . $ratingType . ':' . $objectid;
        $this->throwForbiddenUnless(SecurityUtil::checkPermission($this->name . '::', $instance, ACCESS_COMMENT));

        // log rating of item
        $newRating = ModUtil::apiFunc($this->name, 'user', 'rate',
                                array('modname'    => $modName,
                                      'objectid'   => $objectid,
                                      'ratingtype' => $ratingType,
                                      'rating'     => $rating));

        // get the new output
        $result = ModUtil::func($this->name, 'user', 'display',
                                array('objectid'  => $objectid,
                                      'extrainfo' => array('module'     => $modName,
                                                           'returnurl'  => $returnUrl)));


        // return the new rating content
        $output = array('result' => $result);
        return new Zikula_Response_Ajax($output);
    }
}
