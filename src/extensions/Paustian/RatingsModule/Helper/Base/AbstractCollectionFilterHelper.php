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

namespace Paustian\RatingsModule\Helper\Base;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\RequestStack;
use Zikula\ExtensionsModule\Api\ApiInterface\VariableApiInterface;
use Zikula\UsersModule\Api\ApiInterface\CurrentUserApiInterface;
use Zikula\UsersModule\Constant as UsersConstant;
use Zikula\UsersModule\Entity\RepositoryInterface\UserRepositoryInterface;
use Zikula\UsersModule\Entity\UserEntity;
use Paustian\RatingsModule\Helper\PermissionHelper;

/**
 * Entity collection filter helper base class.
 */
abstract class AbstractCollectionFilterHelper
{
    /**
     * @var RequestStack
     */
    protected $requestStack;
    
    /**
     * @var PermissionHelper
     */
    protected $permissionHelper;
    
    /**
     * @var CurrentUserApiInterface
     */
    protected $currentUserApi;
    
    /**
     * @var UserRepositoryInterface
     */
    protected $userRepository;
    
    /**
     * @var bool Fallback value to determine whether only own entries should be selected or not
     */
    protected $showOnlyOwnEntries = false;
    
    public function __construct(
        RequestStack $requestStack,
        PermissionHelper $permissionHelper,
        CurrentUserApiInterface $currentUserApi,
        UserRepositoryInterface $userRepository,
        VariableApiInterface $variableApi
    ) {
        $this->requestStack = $requestStack;
        $this->permissionHelper = $permissionHelper;
        $this->currentUserApi = $currentUserApi;
        $this->userRepository = $userRepository;
        $this->showOnlyOwnEntries = (bool) $variableApi->get('PaustianRatingsModule', 'showOnlyOwnEntries');
    }
    
    /**
     * Returns an array of additional template variables for view quick navigation forms.
     */
    public function getViewQuickNavParameters(string $objectType = '', string $context = '', array $args = []): array
    {
        if (!in_array($context, ['controllerAction', 'api', 'actionHandler', 'block', 'contentType'], true)) {
            $context = 'controllerAction';
        }
    
        if ('rating' === $objectType) {
            return $this->getViewQuickNavParametersForRating($context, $args);
        }
    
        return [];
    }
    
    /**
     * Adds quick navigation related filter options as where clauses.
     */
    public function addCommonViewFilters(string $objectType, QueryBuilder $qb): QueryBuilder
    {
        if ('rating' === $objectType) {
            return $this->addCommonViewFiltersForRating($qb);
        }
    
        return $qb;
    }
    
    /**
     * Adds default filters as where clauses.
     */
    public function applyDefaultFilters(string $objectType, QueryBuilder $qb, array $parameters = []): QueryBuilder
    {
        if ('rating' === $objectType) {
            return $this->applyDefaultFiltersForRating($qb, $parameters);
        }
    
        return $qb;
    }
    
    /**
     * Returns an array of additional template variables for view quick navigation forms.
     */
    protected function getViewQuickNavParametersForRating(string $context = '', array $args = []): array
    {
        $parameters = [];
        $request = $this->requestStack->getCurrentRequest();
        if (null === $request) {
            return $parameters;
        }
    
        $userId = $request->query->getInt('userId', 0);
    
        $parameters['workflowState'] = $request->query->get('workflowState', '');
        $parameters['userId'] = 0 < $userId ? $this->userRepository->find($userId) : null;
        $parameters['q'] = $request->query->get('q', '');
    
        return $parameters;
    }
    
    /**
     * Adds quick navigation related filter options as where clauses.
     */
    protected function addCommonViewFiltersForRating(QueryBuilder $qb): QueryBuilder
    {
        $request = $this->requestStack->getCurrentRequest();
        if (null === $request) {
            return $qb;
        }
        $routeName = $request->get('_route', '');
        if (false !== mb_strpos($routeName, 'edit')) {
            return $qb;
        }
    
        $parameters = $this->getViewQuickNavParametersForRating();
        foreach ($parameters as $k => $v) {
            if (null === $v) {
                continue;
            }
            if (in_array($k, ['q', 'searchterm'], true)) {
                // quick search
                if (!empty($v)) {
                    $qb = $this->addSearchFilter('rating', $qb, $v);
                }
                continue;
            }
    
            if (is_array($v)) {
                continue;
            }
    
            // field filter
            if ((!is_numeric($v) && '' !== $v) || (is_numeric($v) && 0 < $v)) {
                if ($v instanceof UserEntity) {
                    $v = $v->getUid();
                } else {
                    $v = (string) $v;
                }
                if ('workflowState' === $k && 0 === mb_strpos($v, '!')) {
                    $qb->andWhere('tbl.' . $k . ' != :' . $k)
                       ->setParameter($k, mb_substr($v, 1));
                } elseif (0 === mb_strpos($v, '%')) {
                    $qb->andWhere('tbl.' . $k . ' LIKE :' . $k)
                       ->setParameter($k, '%' . mb_substr($v, 1) . '%');
                } else {
                    if (in_array($k, ['userId'], true)) {
                        $qb->leftJoin('tbl.' . $k, 'tbl' . ucfirst($k))
                           ->andWhere('tbl' . ucfirst($k) . '.uid = :' . $k)
                           ->setParameter($k, $v);
                    } else {
                        $qb->andWhere('tbl.' . $k . ' = :' . $k)
                           ->setParameter($k, $v);
                    }
                }
            }
        }
    
        return $this->applyDefaultFiltersForRating($qb, $parameters);
    }
    
    /**
     * Adds default filters as where clauses.
     */
    protected function applyDefaultFiltersForRating(QueryBuilder $qb, array $parameters = []): QueryBuilder
    {
        $request = $this->requestStack->getCurrentRequest();
        if (null === $request) {
            return $qb;
        }
    
        $showOnlyOwnEntries = (bool) $request->query->getInt('own', (int) $this->showOnlyOwnEntries);
        if ($showOnlyOwnEntries) {
            $qb = $this->addCreatorFilter($qb);
        }
    
        $routeName = $request->get('_route', '');
        $isAdminArea = false !== mb_strpos($routeName, 'paustianratingsmodule_rating_admin');
        if ($isAdminArea) {
            return $qb;
        }
    
        if (!array_key_exists('workflowState', $parameters) || empty($parameters['workflowState'])) {
            // per default we show approved ratings only
            $onlineStates = ['approved'];
            $qb->andWhere('tbl.workflowState IN (:onlineStates)')
               ->setParameter('onlineStates', $onlineStates);
        }
    
        return $qb;
    }
    
    /**
     * Adds a where clause for search query.
     */
    public function addSearchFilter(string $objectType, QueryBuilder $qb, string $fragment = ''): QueryBuilder
    {
        if ('' === $fragment) {
            return $qb;
        }
    
        $filters = [];
        $parameters = [];
    
        if ('rating' === $objectType) {
            $filters[] = 'tbl.moduleName LIKE :searchModuleName';
            $parameters['searchModuleName'] = '%' . $fragment . '%';
            if (is_numeric($fragment)) {
                $filters[] = 'tbl.objectId = :searchObjectId';
                $parameters['searchObjectId'] = $fragment;
            }
            if (is_numeric($fragment)) {
                $filters[] = 'tbl.rating = :searchRating';
                $parameters['searchRating'] = $fragment;
            }
        }
    
        $qb->andWhere('(' . implode(' OR ', $filters) . ')');
    
        foreach ($parameters as $parameterName => $parameterValue) {
            $qb->setParameter($parameterName, $parameterValue);
        }
    
        return $qb;
    }
    
    /**
     * Adds a filter for the createdBy field.
     */
    public function addCreatorFilter(QueryBuilder $qb, ?int $userId = null): QueryBuilder
    {
        if (null === $userId) {
            $userId = $this->currentUserApi->isLoggedIn()
                ? (int) $this->currentUserApi->get('uid')
                : UsersConstant::USER_ID_ANONYMOUS
            ;
        }
    
        $qb->andWhere('tbl.createdBy = :userId')
           ->setParameter('userId', $userId);
    
        return $qb;
    }
}
