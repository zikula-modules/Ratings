<?php

/**
 * Ratings.
 *
 * @copyright Timothy Paustian (Paustian)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Timothy Paustian <tdpaustian@gmail.com>.
 * @see https://www.microbiologytext.com/
 * @see https://ziku.la
 * @version Generated by ModuleStudio 1.4.0 (https://modulestudio.de).
 */

namespace Paustian\RatingsModule\Controller\Base;

use DateTime;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Zikula\Core\Controller\AbstractController;
use Paustian\RatingsModule\Entity\HookAssignmentEntity;

/**
 * Ajax controller base class.
 */
abstract class AbstractAjaxController extends AbstractController
{
    
    /**
     * Searches for entities for auto completion usage.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getItemListAutoCompletionAction(
        Request $request
    ) {
        if (!$request->isXmlHttpRequest()) {
            return $this->json($this->__('Only ajax access is allowed!'), Response::HTTP_BAD_REQUEST);
        }
        
        if (!$this->hasPermission('PaustianRatingsModule::Ajax', '::', ACCESS_EDIT)) {
            throw new AccessDeniedException();
        }
        
        $objectType = $request->query->getAlnum('ot', 'rating');
        $controllerHelper = $this->get('paustian_ratings_module.controller_helper');
        $contextArgs = ['controller' => 'ajax', 'action' => 'getItemListAutoCompletion'];
        if (!in_array($objectType, $controllerHelper->getObjectTypes('controllerAction', $contextArgs), true)) {
            $objectType = $controllerHelper->getDefaultObjectType('controllerAction', $contextArgs);
        }
        
        $repository = $this->get('paustian_ratings_module.entity_factory')->getRepository($objectType);
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
            $entityDisplayHelper = $this->get('paustian_ratings_module.entity_display_helper');
            $descriptionFieldName = $entityDisplayHelper->getDescriptionFieldName($objectType);
            foreach ($entities as $item) {
                $itemTitle = $entityDisplayHelper->getFormattedTitle($item);
                $itemDescription = isset($item[$descriptionFieldName]) && !empty($item[$descriptionFieldName])
                    ? $item[$descriptionFieldName]
                    : '' //$this->__('No description yet.')
                ;
                if (!empty($itemDescription)) {
                    $itemDescription = strip_tags($itemDescription);
                    $descriptionLength = 50;
                    if (strlen($itemDescription) > $descriptionLength) {
                        if (false !== ($breakpoint = strpos($itemDescription, ' ', $descriptionLength))) {
                            $descriptionLength = $breakpoint;
                            $itemDescription = rtrim(substr($itemDescription, 0, $descriptionLength)) . '&hellip;';
                        }
                    }
                }
        
                $resultItem = [
                    'id' => $item->getKey(),
                    'title' => $itemTitle,
                    'description' => $itemDescription,
                    'image' => ''
                ];
        
                $resultItems[] = $resultItem;
            }
        }
        
        return $this->json($resultItems);
    }
    
    /**
     * Attachs a given hook assignment by creating the corresponding assignment data record.
     *
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     */
    public function attachHookObjectAction(
        Request $request
    ) {
        if (!$request->isXmlHttpRequest()) {
            return $this->json($this->__('Only ajax access is allowed!'), Response::HTTP_BAD_REQUEST);
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
            return $this->json($this->__('Error: invalid input.'), JsonResponse::HTTP_BAD_REQUEST);
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
        
        $entityManager = $this->get('paustian_ratings_module.entity_factory')->getEntityManager();
        $entityManager->persist($assignment);
        $entityManager->flush();
        
        // return response
        return $this->json([
            'id' => $assignment->getId()
        ]);
    }
    
    /**
     * Detachs a given hook assignment by removing the corresponding assignment data record.
     *
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     */
    public function detachHookObjectAction(
        Request $request
    ) {
        if (!$request->isXmlHttpRequest()) {
            return $this->json($this->__('Only ajax access is allowed!'), Response::HTTP_BAD_REQUEST);
        }
        
        if (!$this->hasPermission('PaustianRatingsModule::Ajax', '::', ACCESS_EDIT)) {
            throw new AccessDeniedException();
        }
        
        $id = $request->request->getInt('id', 0);
        if (!$id) {
            return $this->json($this->__('Error: invalid input.'), JsonResponse::HTTP_BAD_REQUEST);
        }
        
        $entityFactory = $this->get('paustian_ratings_module.entity_factory');
        $qb = $entityFactory->getEntityManager()->createQueryBuilder();
        $qb->delete('Paustian\RatingsModule\Entity\HookAssignmentEntity', 'tbl')
           ->where('tbl.id = :identifier')
           ->setParameter('identifier', $id);
        
        $query = $qb->getQuery();
        $query->execute();
        
        // return response
        return $this->json([
            'id' => $id
        ]);
    }
}
