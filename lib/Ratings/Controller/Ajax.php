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
     * Log a rating and display the results.
     *
     * @author Mark West
     * @param string    modname    the module name
     * @param string    objectid   the current object id
     * @param integer   areaid     the current hook area id
     * @param float     rating     the rating to be persisted
     * @param string    ratingtype the type of this rating
     * @param returnurl string     the url to return to
     *
     * @return string output for updated rating display.
     */
    public function rate()
    {
        $modName    = $this->request->request->get('modname', null);
        $objectid   = $this->request->request->get('objectid', null);
        $areaid     = $this->request->request->get('areaid', null);
        $rating     = $this->request->request->get('rating', null);
        $ratingType = $this->request->request->get('ratingtype', $this->getVar('defaultstyle'));
        $returnUrl  = $this->request->request->get('returnurl', null);

        $instance = $modName . ':' . $ratingType . ':' . $objectid;
        $this->throwForbiddenUnless(SecurityUtil::checkPermission($this->name . '::', $instance, ACCESS_COMMENT));

        // log rating of item
        $newRating = ModUtil::apiFunc($this->name, 'user', 'rate',
                                array('modname'    => $modName,
                                      'objectid'   => $objectid,
                                      'areaid'     => $areaid,
                                      'ratingtype' => $ratingType,
                                      'rating'     => $rating));

        // get the new output
        $result = ModUtil::func($this->name, 'user', 'display',
                                array('objectid'  => $objectid,
                                      'areaid'    => $areaid,
                                      'extrainfo' => array('module'     => $modName,
                                                           'returnurl'  => $returnUrl)));


        // return the new rating content
        $output = array('result' => $result);
        return new Zikula_Response_Ajax($output);
    }
}
