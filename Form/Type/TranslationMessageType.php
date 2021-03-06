<?php

namespace Sleepness\UberTranslationAdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class for form edit message translation
 *
 * @author Viktor Novikov <viktor.novikov95@gmail.com>
 */
class TranslationMessageType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('locale', 'text')
            ->add('domain', 'text')
            ->add('key', 'text')
            ->add('translation', 'textarea', array(
                'attr'  => array(
                    'rows' => 4,
                    'cols' => 45,
                )
            ));
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sleepness\UberTranslationAdminBundle\Form\Model\TranslationModel',
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'translation_form';
    }
} 
