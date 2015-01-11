<?php
/**
 * Created by PhpStorm.
 * User: Giannis
 * Date: 8/1/2015
 * Time: 19:51
 */

namespace Quiz\CoreBundle\Form\Type;


use Quiz\CoreBundle\Entity\Department;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegistrationFormType extends AbstractType {


    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'quiz_user_registration';
    }


    public function buildForm(FormBuilderInterface $builderInterface, array $options) {


        $builderInterface->add('firstName', null, [

        ]);

        $builderInterface->add('lastName', null, [

        ]);

        $builderInterface->add('registryNumber', null, [

        ]);

        $builderInterface->add('semester', null, [

        ]);

        $builderInterface->add('department', 'entity', [
            'class' => 'Quiz\CoreBundle\Entity\Department',
            'property' => 'name'
        ]);

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver
            ->setDefaults(array(
                'data_class' => 'Quiz\CoreBundle\Entity\UserEntity',
            ));

    }

    public function getParent()
    {
        return 'fos_user_registration';
    }

}