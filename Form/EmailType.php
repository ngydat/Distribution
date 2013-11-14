<?php

namespace Claroline\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Email;

class EmailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('mail', 'email',
            array(
                'required' => true,
                'constraints' => new Email(),
                'theme_options' => array('label_width' => 'col-md-12', 'control_width' => 'col-md-12')
            )
        );
    }

    public function getName()
    {
        return 'email_form';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'translation_domain' => 'platform'
            )
        );
    }
}
