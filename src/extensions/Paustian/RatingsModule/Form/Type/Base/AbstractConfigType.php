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

namespace Paustian\RatingsModule\Form\Type\Base;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Translation\Extractor\Annotation\Ignore;
use Translation\Extractor\Annotation\Translate;
use Zikula\Bundle\FormExtensionBundle\Form\Type\IconType;
use Paustian\RatingsModule\AppSettings;

/**
 * Configuration form type base class.
 */
abstract class AbstractConfigType extends AbstractType
{

    public function __construct(
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addRatingSettingsFields($builder, $options);
        $this->addListViewsFields($builder, $options);
        $this->addModerationFields($builder, $options);

        $this->addSubmitButtons($builder, $options);
    }

    /**
     * Adds fields for rating settings fields.
     */
    public function addRatingSettingsFields(FormBuilderInterface $builder, array $options = []): void
    {
        
        $builder->add('ratingScale', IntegerType::class, [
            'label' => 'Rating scale:',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => 'The number of divisions in the scale. For example there are five divisions in a 1 to 5 scale, four divisions in a four-star scale'
            ],
            'help' => 'The number of divisions in the scale. For example there are five divisions in a 1 to 5 scale, four divisions in a four-star scale',
            'empty_data' => 5,
            'attr' => [
                'maxlength' => 11,
                'class' => '',
                'title' => 'Enter the rating scale. Only digits are allowed.'
            ],
            'required' => true,
        ]);
        
        $builder->add('iconFa', IconType::class, [
            'label' => 'Icon fa:',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => 'A font-awesome css string that is used to display ratings. Either this or iconUrls must be specified.'
            ],
            'help' => 'A font-awesome css string that is used to display ratings. Either this or iconUrls must be specified.',
            'empty_data' => 'fas fa-star',
            'attr' => [
                'maxlength' => 255,
                'class' => '',
                'title' => 'Enter the icon fa.'
            ],
            'required' => false,
        ]);
        
        $builder->add('halfIconFa', IconType::class, [
            'label' => 'Half icon fa:',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => 'A font-awesome css string that is used to display ratings. Either this or iconUrls must be specified.'
            ],
            'help' => 'A font-awesome css string that is used to display ratings. Either this or iconUrls must be specified.',
            'empty_data' => 'fas fa-star-half',
            'attr' => [
                'maxlength' => 255,
                'class' => '',
                'title' => 'Enter the half icon fa.'
            ],
            'required' => false,
        ]);
        
        $builder->add('emptyIconFa', IconType::class, [
            'label' => 'Empty icon fa:',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => 'A font-awesome css string that is used to display ratings. Either this or iconUrls must be specified.'
            ],
            'help' => 'A font-awesome css string that is used to display ratings. Either this or iconUrls must be specified.',
            'empty_data' => 'fas fa-star-empty',
            'attr' => [
                'maxlength' => 255,
                'class' => '',
                'title' => 'Enter the empty icon fa.'
            ],
            'required' => false,
        ]);
        
        $builder->add('iconUrl', TextType::class, [
            'label' => 'Icon url:',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => 'A url to a rating icon to be used for a rating. Either this or IconFas must be designated.'
            ],
            'help' => 'A url to a rating icon to be used for a rating. Either this or IconFas must be designated.',
            'empty_data' => '',
            'attr' => [
                'maxlength' => 255,
                'class' => '',
                'title' => 'Enter the icon url.'
            ],
            'required' => false,
        ]);
        
        $builder->add('halfIconUrl', TextType::class, [
            'label' => 'Half icon url:',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => 'A url to a rating icon to be used for a rating. Either this or IconFas must be designated.'
            ],
            'help' => 'A url to a rating icon to be used for a rating. Either this or IconFas must be designated.',
            'empty_data' => '',
            'attr' => [
                'maxlength' => 255,
                'class' => '',
                'title' => 'Enter the half icon url.'
            ],
            'required' => false,
        ]);
        
        $builder->add('emptyIconUrl', TextType::class, [
            'label' => 'Empty icon url:',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => 'A url to a rating icon to be used for a rating. Either this or IconFas must be designated.'
            ],
            'help' => 'A url to a rating icon to be used for a rating. Either this or IconFas must be designated.',
            'empty_data' => '',
            'attr' => [
                'maxlength' => 255,
                'class' => '',
                'title' => 'Enter the empty icon url.'
            ],
            'required' => false,
        ]);
    }

    /**
     * Adds fields for list views fields.
     */
    public function addListViewsFields(FormBuilderInterface $builder, array $options = []): void
    {
        
        $builder->add('ratingEntriesPerPage', IntegerType::class, [
            'label' => 'Rating entries per page:',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => 'The amount of ratings shown per page'
            ],
            'help' => 'The amount of ratings shown per page',
            'empty_data' => 10,
            'attr' => [
                'maxlength' => 11,
                'class' => '',
                'title' => 'Enter the rating entries per page. Only digits are allowed.'
            ],
            'required' => true,
        ]);
        
        $builder->add('linkOwnRatingsOnAccountPage', CheckboxType::class, [
            'label' => 'Link own ratings on account page:',
            'label_attr' => [
                'class' => 'tooltips switch-custom',
                'title' => 'Whether to add a link to ratings of the current user on his account page'
            ],
            'help' => 'Whether to add a link to ratings of the current user on his account page',
            'attr' => [
                'class' => '',
                'title' => 'The link own ratings on account page option'
            ],
            'required' => false,
        ]);
        
        $builder->add('showOnlyOwnEntries', CheckboxType::class, [
            'label' => 'Show only own entries:',
            'label_attr' => [
                'class' => 'tooltips switch-custom',
                'title' => 'Whether only own entries should be shown on view pages by default or not'
            ],
            'help' => 'Whether only own entries should be shown on view pages by default or not',
            'attr' => [
                'class' => '',
                'title' => 'The show only own entries option'
            ],
            'required' => false,
        ]);
    }

    /**
     * Adds fields for moderation fields.
     */
    public function addModerationFields(FormBuilderInterface $builder, array $options = []): void
    {
        
        $builder->add('allowModerationSpecificCreatorForRating', CheckboxType::class, [
            'label' => 'Allow moderation specific creator for rating:',
            'label_attr' => [
                'class' => 'tooltips switch-custom',
                'title' => 'Whether to allow moderators choosing a user which will be set as creator.'
            ],
            'help' => 'Whether to allow moderators choosing a user which will be set as creator.',
            'attr' => [
                'class' => '',
                'title' => 'The allow moderation specific creator for rating option'
            ],
            'required' => false,
        ]);
        
        $builder->add('allowModerationSpecificCreationDateForRating', CheckboxType::class, [
            'label' => 'Allow moderation specific creation date for rating:',
            'label_attr' => [
                'class' => 'tooltips switch-custom',
                'title' => 'Whether to allow moderators choosing a custom creation date.'
            ],
            'help' => 'Whether to allow moderators choosing a custom creation date.',
            'attr' => [
                'class' => '',
                'title' => 'The allow moderation specific creation date for rating option'
            ],
            'required' => false,
        ]);
    }

    /**
     * Adds submit buttons.
     */
    public function addSubmitButtons(FormBuilderInterface $builder, array $options = []): void
    {
        $builder->add('save', SubmitType::class, [
            'label' => 'Update configuration',
            'icon' => 'fa-check',
            'attr' => [
                'class' => 'btn-success'
            ]
        ]);
        $builder->add('reset', ResetType::class, [
            'label' => 'Reset',
            'icon' => 'fa-sync',
            'attr' => [
                'formnovalidate' => 'formnovalidate'
            ]
        ]);
        $builder->add('cancel', SubmitType::class, [
            'label' => 'Cancel',
            'validate' => false,
            'icon' => 'fa-times'
        ]);
    }

    public function getBlockPrefix()
    {
        return 'paustianratingsmodule_config';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // define class for underlying data
            'data_class' => AppSettings::class,
            'translation_domain' => 'config'
        ]);
    }
}
