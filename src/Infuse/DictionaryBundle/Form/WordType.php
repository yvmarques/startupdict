<?php

namespace Infuse\DictionaryBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class WordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('word')
            ->add('definition')
            ->add('example')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Infuse\DictionaryBundle\Entity\Word'
        ));
    }

    public function getName()
    {
        return 'infuse_dictionarybundle_wordtype';
    }
}
