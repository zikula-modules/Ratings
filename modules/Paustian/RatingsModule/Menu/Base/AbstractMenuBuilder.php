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

namespace Paustian\RatingsModule\Menu\Base;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Zikula\Common\Translator\TranslatorInterface;
use Zikula\Common\Translator\TranslatorTrait;
use Zikula\ExtensionsModule\Api\ApiInterface\VariableApiInterface;
use Zikula\UsersModule\Api\ApiInterface\CurrentUserApiInterface;
use Zikula\UsersModule\Constant as UsersConstant;
use Paustian\RatingsModule\Entity\RatingEntity;
use Paustian\RatingsModule\RatingsEvents;
use Paustian\RatingsModule\Event\ConfigureItemActionsMenuEvent;
use Paustian\RatingsModule\Event\ConfigureViewActionsMenuEvent;
use Paustian\RatingsModule\Helper\EntityDisplayHelper;
use Paustian\RatingsModule\Helper\ModelHelper;
use Paustian\RatingsModule\Helper\PermissionHelper;

/**
 * Menu builder base class.
 */
class AbstractMenuBuilder
{
    use TranslatorTrait;
    
    /**
     * @var FactoryInterface
     */
    protected $factory;
    
    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;
    
    /**
     * @var RequestStack
     */
    protected $requestStack;
    
    /**
     * @var PermissionHelper
     */
    protected $permissionHelper;
    
    /**
     * @var EntityDisplayHelper
     */
    protected $entityDisplayHelper;
    
    /**
     * @var CurrentUserApiInterface
     */
    protected $currentUserApi;
    
    /**
     * @var VariableApiInterface
     */
    protected $variableApi;
    
    /**
     * @var ModelHelper
     */
    protected $modelHelper;
    
    public function __construct(
        TranslatorInterface $translator,
        FactoryInterface $factory,
        EventDispatcherInterface $eventDispatcher,
        RequestStack $requestStack,
        PermissionHelper $permissionHelper,
        EntityDisplayHelper $entityDisplayHelper,
        CurrentUserApiInterface $currentUserApi,
        VariableApiInterface $variableApi,
        ModelHelper $modelHelper
    ) {
        $this->setTranslator($translator);
        $this->factory = $factory;
        $this->eventDispatcher = $eventDispatcher;
        $this->requestStack = $requestStack;
        $this->permissionHelper = $permissionHelper;
        $this->entityDisplayHelper = $entityDisplayHelper;
        $this->currentUserApi = $currentUserApi;
        $this->variableApi = $variableApi;
        $this->modelHelper = $modelHelper;
    }
    
    public function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }
    
    /**
     * Builds the item actions menu.
     *
     * @param array $options List of additional options
     *
     * @return ItemInterface The assembled menu
     */
    public function createItemActionsMenu(array $options = [])
    {
        $menu = $this->factory->createItem('itemActions');
        if (!isset($options['entity'], $options['area'], $options['context'])) {
            return $menu;
        }
    
        $entity = $options['entity'];
        $routeArea = $options['area'];
        $context = $options['context'];
        $menu->setChildrenAttribute('class', 'list-inline item-actions');
    
        $this->eventDispatcher->dispatch(
            RatingsEvents::MENU_ITEMACTIONS_PRE_CONFIGURE,
            new ConfigureItemActionsMenuEvent($this->factory, $menu, $options)
        );
    
        if ($entity instanceof RatingEntity) {
            $routePrefix = 'paustianratingsmodule_rating_';
            
            if ('admin' === $routeArea) {
                $previewRouteParameters = $entity->createUrlArgs();
                $previewRouteParameters['preview'] = 1;
                $menu->addChild($this->__('Preview', 'paustianratingsmodule'), [
                    'route' => $routePrefix . 'display',
                    'routeParameters' => $previewRouteParameters
                ])
                    ->setLinkAttribute('target', '_blank')
                    ->setLinkAttribute(
                        'title',
                        $this->__('Open preview page', 'paustianratingsmodule')
                    )
                    ->setAttribute('icon', 'fa fa-search-plus')
                ;
            }
            if ('display' !== $context) {
                $entityTitle = $this->entityDisplayHelper->getFormattedTitle($entity);
                $menu->addChild($this->__('Details', 'paustianratingsmodule'), [
                    'route' => $routePrefix . $routeArea . 'display',
                    'routeParameters' => $entity->createUrlArgs()
                ])
                    ->setLinkAttribute(
                        'title',
                        str_replace('"', '', $entityTitle)
                    )
                    ->setAttribute('icon', 'fa fa-eye')
                ;
            }
            if ($this->permissionHelper->mayEdit($entity)) {
                $menu->addChild($this->__('Edit', 'paustianratingsmodule'), [
                    'route' => $routePrefix . $routeArea . 'edit',
                    'routeParameters' => $entity->createUrlArgs()
                ])
                    ->setLinkAttribute(
                        'title',
                        $this->__('Edit this rating', 'paustianratingsmodule')
                    )
                    ->setAttribute('icon', 'fa fa-pencil-square-o')
                ;
                $menu->addChild($this->__('Reuse', 'paustianratingsmodule'), [
                    'route' => $routePrefix . $routeArea . 'edit',
                    'routeParameters' => ['astemplate' => $entity->getKey()]
                ])
                    ->setLinkAttribute(
                        'title',
                        $this->__('Reuse for new rating', 'paustianratingsmodule')
                    )
                    ->setAttribute('icon', 'fa fa-files-o')
                ;
            }
            if ('display' === $context) {
                $menu->addChild($this->__('Ratings list', 'paustianratingsmodule'), [
                    'route' => $routePrefix . $routeArea . 'view'
                ])
                    ->setAttribute('icon', 'fa fa-reply')
                ;
            }
        }
    
        $this->eventDispatcher->dispatch(
            RatingsEvents::MENU_ITEMACTIONS_POST_CONFIGURE,
            new ConfigureItemActionsMenuEvent($this->factory, $menu, $options)
        );
    
        return $menu;
    }
    
    /**
     * Builds the view actions menu.
     *
     * @param array $options List of additional options
     *
     * @return ItemInterface The assembled menu
     */
    public function createViewActionsMenu(array $options = [])
    {
        $menu = $this->factory->createItem('viewActions');
        if (!isset($options['objectType'], $options['area'])) {
            return $menu;
        }
    
        $objectType = $options['objectType'];
        $routeArea = $options['area'];
        $menu->setChildrenAttribute('class', 'list-inline view-actions');
    
        $this->eventDispatcher->dispatch(
            RatingsEvents::MENU_VIEWACTIONS_PRE_CONFIGURE,
            new ConfigureViewActionsMenuEvent($this->factory, $menu, $options)
        );
    
        $query = $this->requestStack->getMasterRequest()->query;
        $currentTemplate = $query->getAlnum('tpl', '');
        if ('rating' === $objectType) {
            $routePrefix = 'paustianratingsmodule_rating_';
            if (!in_array($currentTemplate, [])) {
                $canBeCreated = $this->modelHelper->canBeCreated($objectType);
                if ($canBeCreated) {
                    if ($this->permissionHelper->hasComponentPermission($objectType, ACCESS_EDIT)) {
                        $menu->addChild($this->__('Create rating', 'paustianratingsmodule'), [
                            'route' => $routePrefix . $routeArea . 'edit'
                        ])
                            ->setAttribute('icon', 'fa fa-plus')
                        ;
                    }
                }
                $routeParameters = $query->all();
                if (1 === $query->getInt('own')) {
                    $routeParameters['own'] = 1;
                } else {
                    unset($routeParameters['own']);
                }
                if (1 === $query->getInt('all')) {
                    unset($routeParameters['all']);
                    $menu->addChild($this->__('Back to paginated view', 'paustianratingsmodule'), [
                        'route' => $routePrefix . $routeArea . 'view',
                        'routeParameters' => $routeParameters
                    ])
                        ->setAttribute('icon', 'fa fa-table')
                    ;
                } else {
                    $routeParameters['all'] = 1;
                    $menu->addChild($this->__('Show all entries', 'paustianratingsmodule'), [
                        'route' => $routePrefix . $routeArea . 'view',
                        'routeParameters' => $routeParameters
                    ])
                        ->setAttribute('icon', 'fa fa-table')
                    ;
                }
                if ($this->permissionHelper->hasComponentPermission($objectType, ACCESS_EDIT)) {
                    $routeParameters = $query->all();
                    if (1 === $query->getInt('own')) {
                        unset($routeParameters['own']);
                        $menu->addChild($this->__('Show also entries from other users', 'paustianratingsmodule'), [
                            'route' => $routePrefix . $routeArea . 'view',
                            'routeParameters' => $routeParameters
                        ])
                            ->setAttribute('icon', 'fa fa-users')
                        ;
                    } else {
                        $routeParameters['own'] = 1;
                        $menu->addChild($this->__('Show only own entries', 'paustianratingsmodule'), [
                            'route' => $routePrefix . $routeArea . 'view',
                            'routeParameters' => $routeParameters
                        ])
                            ->setAttribute('icon', 'fa fa-user')
                        ;
                    }
                }
            }
        }
    
        $this->eventDispatcher->dispatch(
            RatingsEvents::MENU_VIEWACTIONS_POST_CONFIGURE,
            new ConfigureViewActionsMenuEvent($this->factory, $menu, $options)
        );
    
        return $menu;
    }
}