<?php

namespace Recruiter\RecruitBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RecruitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('recruit_created_at')
            ->add('recruit_updated_at')
            ->add('recruit_gender')
            ->add('recruit_phone_number')
            ->add('recruit_dob')
            ->add('recruit_job_title')
            ->add('recruit_personal_statement')
            ->add('education_status')
            ->add('user')
            ->add('qualifications')
            ->add('skills')
            ->add('location')
            ->add('job_types')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Recruiter\RecruitBundle\Entity\Recruit'
        ));
    }

    public function getName()
    {
        return 'recruiter_recruitbundle_recruittype';
    }
}
