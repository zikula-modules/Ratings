<?php

/**
 * Ratings.
 *
 * @copyright Timothy Paustian (Paustian)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Timothy Paustian <tdpaustian@gmail.com>.
 *
 * @see https://www.microbiologytext.com/
 * @see https://ziku.la
 *
 * @version Generated by ModuleStudio 1.5.0 (https://modulestudio.de).
 */

declare(strict_types=1);

namespace Paustian\RatingsModule\Controller\Base;

use DateTime;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Zikula\Bundle\CoreBundle\Controller\AbstractController;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Paustian\RatingsModule\Entity\HookAssignmentEntity;
use Paustian\RatingsModule\Entity\Factory\EntityFactory;
use Paustian\RatingsModule\Helper\ControllerHelper;
use Paustian\RatingsModule\Helper\EntityDisplayHelper;

/**
 * Ajax controller base class.
 */
abstract class AbstractAjaxController extends AbstractController
{
    
    /**
     * Searches for entities for auto completion usage.
     */
    public function getItemListAutoCompletionAction(
        Request $request,
        CacheManager $imagineCacheManager,
        ControllerHelper $controllerHelper,
        EntityFactory $entityFactory,
        EntityDisplayHelper $entityDisplayHelper
    ): JsonResponse {
        if (!$request->isXmlHttpRequest()) {
            return $this->json($this->trans('Only ajax access is allowed!'), Response::HTTP_BAD_REQUEST);
        }
        
        if (!$this->hasPermission('PaustianRatingsModule::Ajax', '::', ACCESS_EDIT)) {
            throw new AccessDeniedException();
        }
        
        $objectType = $request->query->getAlnum('ot', 'rating');
        $contextArgs = ['controller' => 'ajax', 'action' => 'getItemListAutoCompletion'];
        if (!in_array($objectType, $controllerHelper->getObjectTypes('controllerAction', $contextArgs), true)) {
            $objectType = $controllerHelper->getDefaultObjectType('controllerAction', $contextArgs);
        }
        
        $repository = $entityFactory->getRepository($objectType);
        $fragment = $request->query->get('fragment');
        $exclude = $request->query->get('exclude');
        $exclude = !empty($exclude) ? explode(',', str_replace(', ', ',', $exclude)) : [];
        
        // parameter for used sorting field
        $sort = $request->query->get('sort', '');
        if (empty($sort) || !in_array($sort, $repository->getAllowedSortingFields(), true)) {
            $sort = $repository->getDefaultSortingField();
            $request->query->set('sort', $sort);
            // set default sorting in route parameters (e.g. for the pager)
            $routeParams = $request->attributes->get('_route_params');
            $routeParams['sort'] = $sort;
            $request->attributes->set('_route_params', $routeParams);
        }
        $sortParam = $sort;
        if (false === strpos(strtolower($sort), ' asc') && false === strpos(strtolower($sort), ' desc')) {
            $sortParam .= ' asc';
        }
        
        $currentPage = 1;
        $resultsPerPage = 20;
        
        // get objects from database
        list($entities, $objectCount) = $repository->selectSearch($fragment, $exclude, $sortParam, $currentPage, $resultsPerPage, false);
        
        $resultItems = [];
        
        if ((is_array($entities) || is_object($entities)) && count($entities) > 0) {
            $descriptionFieldName = $entityDisplayHelper->getDescriptionFieldName($objectType);
            foreach ($entities as $item) {
                $itemTitle = $entityDisplayHelper->getFormattedTitle($item);
                $itemDescription = isset($item[$descriptionFieldName]) && !empty($item[$descriptionFieldName])
                    ? $item[$descriptionFieldName]
                    : '' //$this->trans('No description yet.')
                ;
                if (!empty($itemDescription)) {
                    $itemDescription = strip_tags($itemDescription);
                    $descriptionLength = 50;
                    if (mb_strlen($itemDescription) > $descriptionLength) {
                        if (false !== ($breakpoint = mb_strpos($itemDescription, ' ', $descriptionLength))) {
                            $descriptionLength = $breakpoint;
                            $itemDescription = rtrim(mb_substr($itemDescription, 0, $descriptionLength)) . '&hellip;';
                        }
                    }
                }
        
                $resultItem = [
                    'id' => $item->getKey(),
                    'title' => $itemTitle,
                    'description' => $itemDescription,
                    'image' => '',
                ];
        
                $resultItems[] = $resultItem;
            }
        }
        
        return $this->json($resultItems);
    }
    
    /**
     * Attachs a given hook assignment by creating the corresponding assignment data record.
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     */
    public function attachHookObjectAction(
        Request $request,
        EntityFactory $entityFactory
    ): JsonResponse {
        if (!$request->isXmlHttpRequest()) {
            return $this->json($this->trans('Only ajax access is allowed!'), Response::HTTP_BAD_REQUEST);
        }
        
        if (!$this->hasPermission('PaustianRatingsModule::Ajax', '::', ACCESS_EDIT)) {
            throw new AccessDeniedException();
        }
        
        $subscriberOwner = $request->request->get('owner');
        $subscriberAreaId = $request->request->get('areaId');
        $subscriberObjectId = $request->request->getInt('objectId');
        $subscriberUrl = $request->request->get('url');
        $assignedEntity = $request->request->get('assignedEntity');
        $assignedId = $request->request->getInt('assignedId');
        
        if (!$subscriberOwner || !$subscriberAreaId || !$subscriberObjectId || !$assignedEntity || !$assignedId) {
            return $this->json($this->trans('Error: invalid input.'), JsonResponse::HTTP_BAD_REQUEST);
        }
        
        $subscriberUrl = !empty($subscriberUrl) ? unserialize($subscriberUrl) : [];
        
        $assignment = new HookAssignmentEntity();
        $assignment->setSubscriberOwner($subscriberOwner);
        $assignment->setSubscriberAreaId($subscriberAreaId);
        $assignment->setSubscriberObjectId($subscriberObjectId);
        $assignment->setSubscriberUrl($subscriberUrl);
        $assignment->setAssignedEntity($assignedEntity);
        $assignment->setAssignedId($assignedId);
        $assignment->setUpdatedDate(new DateTime());
        
        $entityManager = $entityFactory->getEntityManager();
        $entityManager->persist($assignment);
        $entityManager->flush();
        
        // return response
        return $this->json([
            'id' => $assignment->getId(),
        ]);
    }
    
    /**
     * Detachs a given hook assignment by removing the corresponding assignment data record.
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     */
    public function detachHookObjectAction(
        Request $request,
        EntityFactory $entityFactory
    ): JsonResponse {
        if (!$request->isXmlHttpRequest()) {
            return $this->json($this->trans('Only ajax access is allowed!'), Response::HTTP_BAD_REQUEST);
        }
        
        if (!$this->hasPermission('PaustianRatingsModule::Ajax', '::', ACCESS_EDIT)) {
            throw new AccessDeniedException();
        }
        
        $id = $request->request->getInt('id', 0);
        if (!$id) {
            return $this->json($this->trans('Error: invalid input.'), JsonResponse::HTTP_BAD_REQUEST);
        }
        
        $qb = $entityFactory->getEntityManager()->createQueryBuilder();
        $qb->delete('Paustian\RatingsModule\Entity\HookAssignmentEntity', 'tbl')
           ->where('tbl.id = :identifier')
           ->setParameter('identifier', $id);
        
        $query = $qb->getQuery();
        $query->execute();
        
        // return response
        return $this->json([
            'id' => $id,
        ]);
    }
}
