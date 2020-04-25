<?php
/**
 * Ratings.
 *
 * @copyright Timothy Paustian (Paustian)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Timothy Paustian <tdpaustian@gmail.com>.
 * @link https://www.microbiologytext.com/
 * @link https://ziku.la
 * @version Generated by ModuleStudio 1.3.2 (https://modulestudio.de).
 */

namespace Paustian\RatingsModule\Controller;

use Paustian\RatingsModule\Controller\Base\AbstractAjaxController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Ajax controller implementation class.
 *
 * @Route("/ajax")
 */
class AjaxController extends AbstractAjaxController
{
    
    /**
     * @inheritDoc
     * @Route("/getItemListAutoCompletion", methods = {"GET"}, options={"expose"=true})
     */
    public function getItemListAutoCompletionAction(Request $request)
    {
        return parent::getItemListAutoCompletionAction($request);
    }
    
    /**
     * @inheritDoc
     * @Route("/attachHookObject", methods = {"POST"}, options={"expose"=true})
     */
    public function attachHookObjectAction(Request $request)
    {
        return parent::attachHookObjectAction($request);
    }
    
    /**
     * @inheritDoc
     * @Route("/detachHookObject", methods = {"POST"}, options={"expose"=true})
     */
    public function detachHookObjectAction(Request $request)
    {
        return parent::detachHookObjectAction($request);
    }

    // feel free to add your own ajax controller methods here
}
