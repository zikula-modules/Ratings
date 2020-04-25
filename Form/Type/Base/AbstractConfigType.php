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

namespace Paustian\RatingsModule\Form\Type\Base;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Zikula\Common\Translator\TranslatorInterface;
use Zikula\Common\Translator\TranslatorTrait;
use Paustian\RatingsModule\AppSettings;

/**
 * Configuration form type base class.
 */
abstract class AbstractConfigType extends AbstractType
{
    use TranslatorTrait;

    /**
     * ConfigType constructor.
     *
     * @param TranslatorInterface $translator Translator service instance
     */
    public function __construct(
        TranslatorInterface $translator
    ) {
        $this->setTranslator($translator);
    }

    /**
     * Sets the translator.
     *
     * @param TranslatorInterface $translator Translator service instance
     */
    public function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addListViewsFields($builder, $options);
        $this->addModerationFields($builder, $options);

        $this->addSubmitButtons($builder, $options);
    }

    /**
     * Adds fields for list views fields.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addListViewsFields(FormBuilderInterface $builder, array $options = [])
    {
        
        $builder->add('ratingSystemEntriesPerPage', IntegerType::class, [
            'label' => $this->__('Rating system entries per page') . ':',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => $this->__('The amount of rating systems shown per page')
            ],
            'help' => $this->__('The amount of rating systems shown per page'),
            'empty_data' => 10,
            'attr' => [
                'maxlength' => 11,
                'class' => '',
                'title' => $this->__('Enter the rating system entries per page.') . ' ' . $this->__('Only digits are allowed.')
            ],
            'required' => true,
            'scale' => 0
        ]);
        
        $builder->add('linkOwnRatingSystemsOnAccountPage', CheckboxType::class, [
            'label' => $this->__('Link own rating systems on account page') . ':',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => $this->__('Whether to add a link to rating systems of the current user on his account page')
            ],
            'help' => $this->__('Whether to add a link to rating systems of the current user on his account page'),
            'attr' => [
                'class' => '',
                'title' => $this->__('The link own rating systems on account page option')
            ],
            'required' => false,
        ]);
        
        $builder->add('ratingEntriesPerPage', IntegerType::class, [
            'label' => $this->__('Rating entries per page') . ':',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => $this->__('The amount of ratings shown per page')
            ],
            'help' => $this->__('The amount of ratings shown per page'),
            'empty_data' => 10,
            'attr' => [
                'maxlength' => 11,
                'class' => '',
                'title' => $this->__('Enter the rating entries per page.') . ' ' . $this->__('Only digits are allowed.')
            ],
            'required' => true,
            'scale' => 0
        ]);
        
        $builder->add('linkOwnRatingsOnAccountPage', CheckboxType::class, [
            'label' => $this->__('Link own ratings on account page') . ':',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => $this->__('Whether to add a link to ratings of the current user on his account page')
            ],
            'help' => $this->__('Whether to add a link to ratings of the current user on his account page'),
            'attr' => [
                'class' => '',
                'title' => $this->__('The link own ratings on account page option')
            ],
            'required' => false,
        ]);
        
        $builder->add('showOnlyOwnEntries', CheckboxType::class, [
            'label' => $this->__('Show only own entries') . ':',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => $this->__('Whether only own entries should be shown on view pages by default or not')
            ],
            'help' => $this->__('Whether only own entries should be shown on view pages by default or not'),
            'attr' => [
                'class' => '',
                'title' => $this->__('The show only own entries option')
            ],
            'required' => false,
        ]);
    }

    /**
     * Adds fields for moderation fields.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addModerationFields(FormBuilderInterface $builder, array $options = [])
    {
        
        $builder->add('allowModerationSpecificCreatorForRatingSystem', CheckboxType::class, [
            'label' => $this->__('Allow moderation specific creator for rating system') . ':',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => $this->__('Whether to allow moderators choosing a user which will be set as creator.')
            ],
            'help' => $this->__('Whether to allow moderators choosing a user which will be set as creator.'),
            'attr' => [
                'class' => '',
                'title' => $this->__('The allow moderation specific creator for rating system option')
            ],
            'required' => false,
        ]);
        
        $builder->add('allowModerationSpecificCreationDateForRatingSystem', CheckboxType::class, [
            'label' => $this->__('Allow moderation specific creation date for rating system') . ':',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => $this->__('Whether to allow moderators choosing a custom creation date.')
            ],
            'help' => $this->__('Whether to allow moderators choosing a custom creation date.'),
            'attr' => [
                'class' => '',
                'title' => $this->__('The allow moderation specific creation date for rating system option')
            ],
            'required' => false,
        ]);
        
        $builder->add('allowModerationSpecificCreatorForRating', CheckboxType::class, [
            'label' => $this->__('Allow moderation specific creator for rating') . ':',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => $this->__('Whether to allow moderators choosing a user which will be set as creator.')
            ],
            'help' => $this->__('Whether to allow moderators choosing a user which will be set as creator.'),
            'attr' => [
                'class' => '',
                'title' => $this->__('The allow moderation specific creator for rating option')
            ],
            'required' => false,
        ]);
        
        $builder->add('allowModerationSpecificCreationDateForRating', CheckboxType::class, [
            'label' => $this->__('Allow moderation specific creation date for rating') . ':',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => $this->__('Whether to allow moderators choosing a custom creation date.')
            ],
            'help' => $this->__('Whether to allow moderators choosing a custom creation date.'),
            'attr' => [
                'class' => '',
                'title' => $this->__('The allow moderation specific creation date for rating option')
            ],
            'required' => false,
        ]);
    }

    /**
     * Adds submit buttons.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addSubmitButtons(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add('save', SubmitType::class, [
            'label' => $this->__('Update configuration'),
            'icon' => 'fa-check',
            'attr' => [
                'class' => 'btn btn-success'
            ]
        ]);
        $builder->add('reset', ResetType::class, [
            'label' => $this->__('Reset'),
            'icon' => 'fa-refresh',
            'attr' => [
                'class' => 'btn btn-default',
                'formnovalidate' => 'formnovalidate'
            ]
        ]);
        $builder->add('cancel', SubmitType::class, [
            'label' => $this->__('Cancel'),
            'icon' => 'fa-times',
            'attr' => [
                'class' => 'btn btn-default',
                'formnovalidate' => 'formnovalidate'
            ]
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getBlockPrefix()
    {
        return 'paustianratingsmodule_config';
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                // define class for underlying data
                'data_class' => AppSettings::class,
            ]);
    }
}
