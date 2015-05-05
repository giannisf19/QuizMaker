<?php
/**
 * Created by PhpStorm.
 * User: Giannis
 * Date: 5/5/2015
 * Time: 6:59 μμ
 */

namespace Quiz\CoreBundle\Form\Type;


use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class EditFormType extends BaseType
{

    private $class;


    public function __constructor($class) {
        $this->class = $class;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('plainPassword', 'repeated', array(
            'type' => 'password',
            'options' => array('translation_domain' => 'FOSUserBundle'),
            'first_options' => array('label' => 'form.new_password'),
            'second_options' => array('label' => 'form.new_password_confirmation'),
            'invalid_message' => 'fos_user.password.mismatch',
        ))
            ->add('email', null, [])
            ->add('firstName', null, [])
            ->add('lastName', null, []);

    }




    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Quiz\CoreBundle\Entity\UserEntity',
            'intention'  => 'resetting',
        ));
    }
    // BC for SF < 2.7
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {


        $this->configureOptions($resolver);
    }



    public function getName()
    {
        return 'ap_edit_profile';
    }
}