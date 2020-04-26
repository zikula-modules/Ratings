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

namespace Paustian\RatingsModule\Form\Type\QuickNavigation\Base;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Zikula\Common\Translator\TranslatorInterface;
use Zikula\Common\Translator\TranslatorTrait;
use Zikula\UsersModule\Entity\UserEntity;
use Paustian\RatingsModule\Entity\Factory\EntityFactory;
use Paustian\RatingsModule\Helper\EntityDisplayHelper;
use Paustian\RatingsModule\Helper\ListEntriesHelper;
use Paustian\RatingsModule\Helper\PermissionHelper;

/**
 * Rating quick navigation form type base class.
 */
abstract class AbstractRatingQuickNavType extends AbstractType
{
    use TranslatorTrait;

    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @var EntityFactory
     */
    protected $entityFactory;

    /**
     * @var PermissionHelper
     */
    protected $permissionHelper;

    /**
     * @var EntityDisplayHelper
     */
    protected $entityDisplayHelper;

    /**
     * @var ListEntriesHelper
     */
    protected $listHelper;

    public function __construct(
        TranslatorInterface $translator,
        RequestStack $requestStack,
        EntityFactory $entityFactory,
        PermissionHelper $permissionHelper,
        EntityDisplayHelper $entityDisplayHelper,
        ListEntriesHelper $listHelper
    ) {
        $this->setTranslator($translator);
        $this->requestStack = $requestStack;
        $this->entityFactory = $entityFactory;
        $this->permissionHelper = $permissionHelper;
        $this->entityDisplayHelper = $entityDisplayHelper;
        $this->listHelper = $listHelper;
    }

    public function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('GET')
            ->add('all', HiddenType::class)
            ->add('own', HiddenType::class)
            ->add('tpl', HiddenType::class)
        ;

        $this->addOutgoingRelationshipFields($builder, $options);
        $this->addListFields($builder, $options);
        $this->addUserFields($builder, $options);
        $this->addSearchField($builder, $options);
        $this->addSortingFields($builder, $options);
        $this->addAmountField($builder, $options);
        $builder->add('updateview', SubmitType::class, [
            'label' => $this->__('OK'),
            'attr' => [
                'class' => 'btn btn-default btn-sm'
            ]
        ]);
    }

    /**
     * Adds fields for outgoing relationships.
     */
    public function addOutgoingRelationshipFields(FormBuilderInterface $builder, array $options = [])
    {
        $mainSearchTerm = '';
        $request = $this->requestStack->getCurrentRequest();
        if ($request->query->has('q')) {
            // remove current search argument from request to avoid filtering related items
            $mainSearchTerm = $request->query->get('q');
            $request->query->remove('q');
        }
        $entityDisplayHelper = $this->entityDisplayHelper;
        $objectType = 'ratingSystem';
        // select without joins
        $entities = $this->entityFactory->getRepository($objectType)->selectWhere('', '', false);
        $permLevel = ACCESS_READ;
        
        $entities = $this->permissionHelper->filterCollection(
            $objectType,
            $entities,
            $permLevel
        );
        $choices = [];
        foreach ($entities as $entity) {
            $choices[$entity->getId()] = $entity;
        }
        
        $builder->add('ratingSystemVal', ChoiceType::class, [
            'choices' => $choices,
            'choice_label' => function ($entity) use ($entityDisplayHelper) {
                return $entityDisplayHelper->getFormattedTitle($entity);
            },
            'placeholder' => $this->__('All'),
            'required' => false,
            'label' => $this->__('Rating system val'),
            'attr' => [
                'class' => 'input-sm'
            ]
        ]);
    
        if ('' !== $mainSearchTerm) {
            // readd current search argument
            $request->query->set('q', $mainSearchTerm);
        }
    }

    /**
     * Adds list fields.
     */
    public function addListFields(FormBuilderInterface $builder, array $options = [])
    {
        $listEntries = $this->listHelper->getEntries('rating', 'workflowState');
        $choices = [];
        $choiceAttributes = [];
        foreach ($listEntries as $entry) {
            $choices[$entry['text']] = $entry['value'];
            $choiceAttributes[$entry['text']] = ['title' => $entry['title']];
        }
        $builder->add('workflowState', ChoiceType::class, [
            'label' => $this->__('State'),
            'attr' => [
                'class' => 'input-sm'
            ],
            'required' => false,
            'placeholder' => $this->__('All'),
            'choices' => $choices,
            'choice_attr' => $choiceAttributes,
            'multiple' => false,
            'expanded' => false
        ]);
    }

    /**
     * Adds user fields.
     */
    public function addUserFields(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add('userId', EntityType::class, [
            'label' => $this->__('User id'),
            'attr' => [
                'class' => 'input-sm'
            ],
            'required' => false,
            'placeholder' => $this->__('All'),
            'class' => UserEntity::class,
            'choice_label' => 'uname'
        ]);
    }

    /**
     * Adds a search field.
     */
    public function addSearchField(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add('q', SearchType::class, [
            'label' => $this->__('Search'),
            'attr' => [
                'maxlength' => 255,
                'class' => 'input-sm'
            ],
            'required' => false
        ]);
    }


    /**
     * Adds sorting fields.
     */
    public function addSortingFields(FormBuilderInterface $builder, array $options = [])
    {
        $builder
            ->add('sort', ChoiceType::class, [
                'label' => $this->__('Sort by'),
                'attr' => [
                    'class' => 'input-sm'
                ],
                'choices' => [
                    $this->__('Module name') => 'moduleName',
                    $this->__('Object id') => 'objectId',
                    $this->__('Rating') => 'rating',
                    $this->__('Rating system') => 'ratingSystem',
                    $this->__('Creation date') => 'createdDate',
                    $this->__('Creator') => 'createdBy',
                    $this->__('Update date') => 'updatedDate',
                    $this->__('Updater') => 'updatedBy'
                ],
                'required' => true,
                'expanded' => false
            ])
            ->add('sortdir', ChoiceType::class, [
                'label' => $this->__('Sort direction'),
                'empty_data' => 'asc',
                'attr' => [
                    'class' => 'input-sm'
                ],
                'choices' => [
                    $this->__('Ascending') => 'asc',
                    $this->__('Descending') => 'desc'
                ],
                'required' => true,
                'expanded' => false
            ])
        ;
    }

    /**
     * Adds a page size field.
     */
    public function addAmountField(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add('num', ChoiceType::class, [
            'label' => $this->__('Page size'),
            'empty_data' => 20,
            'attr' => [
                'class' => 'input-sm text-right'
            ],
            'choices' => [
                5 => 5,
                10 => 10,
                15 => 15,
                20 => 20,
                30 => 30,
                50 => 50,
                100 => 100
            ],
            'required' => false,
            'expanded' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return 'paustianratingsmodule_ratingquicknav';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false
        ]);
    }
}
