<?php

namespace Infuse\DictionaryBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class WordType extends AbstractType
{
    private $securityContext;

    public function __construct(SecurityContext $securityContext) {
        $this->securityContext = $securityContext;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('word')
            ->add('definition')
            ->add('example')
            ->add('username')
        ;

        $securityContext = $this->securityContext;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) use ($securityContext) {
            if (false !== $securityContext->isGranted('ROLE_ADMIN')) {
                $event->getForm()->add('status');
            }
        });

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
