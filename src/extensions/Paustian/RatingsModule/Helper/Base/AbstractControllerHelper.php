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

declare(strict_types=1);

namespace Paustian\RatingsModule\Helper\Base;

use Exception;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Zikula\Bundle\CoreBundle\Doctrine\EntityAccess;
use Zikula\Bundle\CoreBundle\Translation\TranslatorTrait;
use Zikula\Component\SortableColumns\SortableColumns;
use Zikula\ExtensionsModule\Api\ApiInterface\VariableApiInterface;
use Zikula\UsersModule\Entity\UserEntity;
use Paustian\RatingsModule\Entity\Factory\EntityFactory;
use Paustian\RatingsModule\Helper\CollectionFilterHelper;
use Paustian\RatingsModule\Helper\PermissionHelper;

/**
 * Helper base class for controller layer methods.
 */
abstract class AbstractControllerHelper
{
    use TranslatorTrait;
    
    /**
     * @var RequestStack
     */
    protected $requestStack;
    
    /**
     * @var RouterInterface
     */
    protected $router;
    
    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;
    
    /**
     * @var VariableApiInterface
     */
    protected $variableApi;
    
    /**
     * @var EntityFactory
     */
    protected $entityFactory;
    
    /**
     * @var CollectionFilterHelper
     */
    protected $collectionFilterHelper;
    
    /**
     * @var PermissionHelper
     */
    protected $permissionHelper;
    
    public function __construct(
        TranslatorInterface $translator,
        RequestStack $requestStack,
        RouterInterface $router,
        FormFactoryInterface $formFactory,
        VariableApiInterface $variableApi,
        EntityFactory $entityFactory,
        CollectionFilterHelper $collectionFilterHelper,
        PermissionHelper $permissionHelper
    ) {
        $this->setTranslator($translator);
        $this->requestStack = $requestStack;
        $this->router = $router;
        $this->formFactory = $formFactory;
        $this->variableApi = $variableApi;
        $this->entityFactory = $entityFactory;
        $this->collectionFilterHelper = $collectionFilterHelper;
        $this->permissionHelper = $permissionHelper;
    }
    
    /**
     * Returns an array of all allowed object types in PaustianRatingsModule.
     *
     * @return string[] List of allowed object types
     */
    public function getObjectTypes(string $context = '', array $args = []): array
    {
        $allowedContexts = ['controllerAction', 'api', 'helper', 'actionHandler', 'block', 'contentType', 'mailz'];
        if (!in_array($context, $allowedContexts, true)) {
            $context = 'controllerAction';
        }
    
        $allowedObjectTypes = [];
        $allowedObjectTypes[] = 'rating';
    
        return $allowedObjectTypes;
    }
    
    /**
     * Returns the default object type in PaustianRatingsModule.
     */
    public function getDefaultObjectType(string $context = '', array $args = []): string
    {
        $allowedContexts = ['controllerAction', 'api', 'helper', 'actionHandler', 'block', 'contentType', 'mailz'];
        if (!in_array($context, $allowedContexts, true)) {
            $context = 'controllerAction';
        }
    
        return 'rating';
    }
    
    /**
     * Processes the parameters for a view action.
     * This includes handling pagination, quick navigation forms and other aspects.
     */
    public function processViewActionParameters(
        string $objectType,
        SortableColumns $sortableColumns,
        array $templateParameters = []
    ): array {
        $contextArgs = ['controller' => $objectType, 'action' => 'view'];
        if (!in_array($objectType, $this->getObjectTypes('controllerAction', $contextArgs), true)) {
            throw new Exception($this->trans('Error! Invalid object type received.'));
        }
    
        $request = $this->requestStack->getCurrentRequest();
        if (null === $request) {
            throw new Exception($this->trans('Error! Controller helper needs a request.'));
        }
        $repository = $this->entityFactory->getRepository($objectType);
    
        // parameter for used sorting field
        list ($sort, $sortdir) = $this->determineDefaultViewSorting($objectType);
        $templateParameters['sort'] = $sort;
        $templateParameters['sortdir'] = strtolower($sortdir);
    
        $templateParameters['all'] = 'csv' === $request->getRequestFormat() ? 1 : $request->query->getInt('all');
        $showOnlyOwnEntriesSetting = (bool)$request->query->getInt(
            'own',
            (int) $this->variableApi->get('PaustianRatingsModule', 'showOnlyOwnEntries')
        );
        $showOnlyOwnEntriesSetting = $showOnlyOwnEntriesSetting ? 1 : 0;
        $templateParameters['own'] = $showOnlyOwnEntriesSetting;
    
        $resultsPerPage = 0;
        if (1 !== $templateParameters['all']) {
            // the number of items displayed on a page for pagination
            $resultsPerPage = $request->query->getInt('num');
            if (in_array($resultsPerPage, [0, 10], true)) {
                $resultsPerPage = $this->variableApi->get('PaustianRatingsModule', $objectType . 'EntriesPerPage', 10);
            }
        }
        $templateParameters['num'] = $resultsPerPage;
        $templateParameters['tpl'] = $request->query->getAlnum('tpl');
    
        $templateParameters = $this->addTemplateParameters(
            $objectType,
            $templateParameters,
            'controllerAction',
            $contextArgs
        );
    
        $urlParameters = $templateParameters;
        foreach ($urlParameters as $parameterName => $parameterValue) {
            if (
                false === stripos($parameterName, 'thumbRuntimeOptions')
                && false === stripos($parameterName, 'featureActivationHelper')
                && false === stripos($parameterName, 'permissionHelper')
            ) {
                continue;
            }
            unset($urlParameters[$parameterName]);
        }
    
        $quickNavFormType = 'Paustian\RatingsModule\Form\Type\QuickNavigation\\'
            . ucfirst($objectType) . 'QuickNavType'
        ;
        $quickNavForm = $this->formFactory->create($quickNavFormType, $templateParameters);
        $quickNavForm->handleRequest($request);
        if ($quickNavForm->isSubmitted()) {
            $quickNavData = $quickNavForm->getData();
            foreach ($quickNavData as $fieldName => $fieldValue) {
                if ('routeArea' === $fieldName) {
                    continue;
                }
                if (in_array($fieldName, ['all', 'own', 'num'], true)) {
                    $templateParameters[$fieldName] = $fieldValue;
                    $urlParameters[$fieldName] = $fieldValue;
                } elseif ('sort' === $fieldName && !empty($fieldValue)) {
                    $sort = $fieldValue;
                } elseif ('sortdir' === $fieldName && !empty($fieldValue)) {
                    $sortdir = $fieldValue;
                } elseif (
                    false === stripos($fieldName, 'thumbRuntimeOptions')
                    && false === stripos($fieldName, 'featureActivationHelper')
                    && false === stripos($fieldName, 'permissionHelper')
                ) {
                    // set filter as query argument, fetched inside CollectionFilterHelper
                    if ($fieldValue instanceof UserEntity) {
                        $fieldValue = $fieldValue->getUid();
                    }
                    $request->query->set($fieldName, $fieldValue);
                    if ($fieldValue instanceof EntityAccess) {
                        $fieldValue = $fieldValue->getKey();
                    }
                    $urlParameters[$fieldName] = $fieldValue;
                }
            }
        }
        $sortableColumns->setOrderBy($sortableColumns->getColumn($sort), strtoupper($sortdir));
        $resultsPerPage = $templateParameters['num'];
        $request->query->set('own', $templateParameters['own']);
    
        $sortableColumns->setAdditionalUrlParameters($urlParameters);
    
        $where = '';
        if (1 === $templateParameters['all']) {
            // retrieve item list without pagination
            $entities = $repository->selectWhere($where, $sort . ' ' . $sortdir, false);
        } else {
            // the current offset which is used to calculate the pagination
            $currentPage = $request->query->getInt('page', 1);
            $templateParameters['currentPage'] = $currentPage;
    
            // retrieve item list with pagination
            $paginator = $repository->selectWherePaginated(
                $where,
                $sort . ' ' . $sortdir,
                $currentPage,
                $resultsPerPage,
                false
            );
            $paginator->setRoute('paustianratingsmodule_' . strtolower($objectType) . '_' . $templateParameters['routeArea'] . 'view');
            $paginator->setRouteParameters($urlParameters);
    
            $templateParameters['paginator'] = $paginator;
            $entities = $paginator->getResults();
        }
    
        $templateParameters['sort'] = $sort;
        $templateParameters['sortdir'] = $sortdir;
        $templateParameters['items'] = $entities;
    
        $templateParameters['sort'] = $sortableColumns->generateSortableColumns();
        $templateParameters['quickNavForm'] = $quickNavForm->createView();
    
        $request->query->set('sort', $sort);
        $request->query->set('sortdir', $sortdir);
        // set current sorting in route parameters (e.g. for the pager)
        $routeParams = $request->attributes->get('_route_params');
        $routeParams['sort'] = $sort;
        $routeParams['sortdir'] = $sortdir;
        $request->attributes->set('_route_params', $routeParams);
    
        return $templateParameters;
    }
    
    /**
     * Determines the default sorting criteria.
     */
    protected function determineDefaultViewSorting(string $objectType): array
    {
        $request = $this->requestStack->getCurrentRequest();
        if (null === $request) {
            return ['', 'ASC'];
        }
        $repository = $this->entityFactory->getRepository($objectType);
    
        $sort = $request->query->get('sort', '');
        if (empty($sort) || !in_array($sort, $repository->getAllowedSortingFields(), true)) {
            $sort = $repository->getDefaultSortingField();
            $request->query->set('sort', $sort);
            // set default sorting in route parameters (e.g. for the pager)
            $routeParams = $request->attributes->get('_route_params');
            $routeParams['sort'] = $sort;
            $request->attributes->set('_route_params', $routeParams);
        }
        $sortdir = $request->query->get('sortdir', 'ASC');
        if (false !== strpos($sort, ' DESC')) {
            $sort = str_replace(' DESC', '', $sort);
            $sortdir = 'desc';
        }
    
        return [$sort, $sortdir];
    }
    
    /**
     * Processes the parameters for a display action.
     */
    public function processDisplayActionParameters(
        string $objectType,
        array $templateParameters = []
    ): array {
        $contextArgs = ['controller' => $objectType, 'action' => 'display'];
        if (!in_array($objectType, $this->getObjectTypes('controllerAction', $contextArgs), true)) {
            throw new Exception($this->trans('Error! Invalid object type received.'));
        }
    
        if (in_array($objectType, ['rating'], true)) {
            $qb = $this->entityFactory->getEntityManager()->createQueryBuilder();
            $qb->select('tbl')
               ->from('Paustian\RatingsModule\Entity\HookAssignmentEntity', 'tbl')
               ->where('tbl.assignedEntity = :objectType')
               ->setParameter('objectType', $objectType)
               ->andWhere('tbl.assignedId = :entityId')
               ->setParameter('entityId', $entity->getKey())
               ->add('orderBy', 'tbl.updatedDate DESC');
    
            $query = $qb->getQuery();
            $hookAssignments = $query->getResult();
    
            $assignments = [];
            foreach ($hookAssignments as $assignment) {
                $url = 'javascript:void(0);';
                $subscriberUrl = $assignment->getSubscriberUrl();
                if (null !== $subscriberUrl && !empty($subscriberUrl)) {
                    $url = $this->router->generate($subscriberUrl['route'], $subscriberUrl['args']);
    
                    $fragment = $subscriberUrl['fragment'];
                    if (!empty($fragment)) {
                        if ('#' !== $fragment[0]) {
                            $fragment = '#' . $fragment;
                    	}
                        $url .= $fragment;
                    }
                }
                $assignments[] = [
                    'url' => $url,
                    'text' => $assignment->getSubscriberOwner(),
                    'date' => $assignment->getUpdatedDate()
                ];
            }
            $templateParameters['hookAssignments'] = $assignments;
        }
    
        return $this->addTemplateParameters($objectType, $templateParameters, 'controllerAction', $contextArgs);
    }
    
    /**
     * Processes the parameters for an edit action.
     */
    public function processEditActionParameters(
        string $objectType,
        array $templateParameters = []
    ): array {
        $contextArgs = ['controller' => $objectType, 'action' => 'edit'];
        if (!in_array($objectType, $this->getObjectTypes('controllerAction', $contextArgs), true)) {
            throw new Exception($this->trans('Error! Invalid object type received.'));
        }
    
        return $this->addTemplateParameters($objectType, $templateParameters, 'controllerAction', $contextArgs);
    }
    
    /**
     * Returns an array of additional template variables which are specific to the object type.
     */
    public function addTemplateParameters(
        string $objectType = '',
        array $parameters = [],
        string $context = '',
        array $args = []
    ): array {
        $allowedContexts = ['controllerAction', 'api', 'helper', 'actionHandler', 'block', 'contentType', 'mailz'];
        if (!in_array($context, $allowedContexts, true)) {
            $context = 'controllerAction';
        }
    
        if ('controllerAction' === $context) {
            if (!isset($args['action'])) {
                $routeName = $this->requestStack->getCurrentRequest()->get('_route');
                $routeNameParts = explode('_', $routeName);
                $args['action'] = end($routeNameParts);
            }
            if (in_array($args['action'], ['index', 'view'])) {
                $parameters = array_merge(
                    $parameters,
                    $this->collectionFilterHelper->getViewQuickNavParameters($objectType, $context, $args)
                );
            }
        }
        $parameters['permissionHelper'] = $this->permissionHelper;
    
        return $parameters;
    }
}