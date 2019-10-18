<?php

namespace App\Form;

use App\Entity\Candidat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CandidatureFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'attr'  => ['class' =>  'form-control rounded-0'],
            ])
            ->add('postnom', TextType::class, [
                'attr'  => ['class' =>  'form-control rounded-0'],
            ])
            ->add('prenom', TextType::class, [
                'attr'  => ['class' =>  'form-control rounded-0'],
            ])
            ->add('niveau', TextType::class, [
                'attr'  => ['class' =>  'form-control rounded-0'],
            ])
            ->add('adresse', TextType::class, [
                'attr'  => ['class' =>  'form-control rounded-0'],
            ])
            ->add('tel', TextType::class, [
                'attr'  => ['class' =>  'form-control rounded-0'],
            ])
            ->add('cv', FileType::class, [
                'mapped'    =>  false,
                'attr'      => ['class' =>  'form-control rounded-0'],
            ])
            ->add('lettreDeMotivation', FileType::class, [
                'mapped'    =>  false,
                'attr'      => ['class' =>  'form-control rounded-0'],
            ])
            /* ->add('offre', TextType::class, [
                'attr'  => ['class' =>  'form-control rounded-0'],
            ]) */
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Candidat::class,
        ]);
    }
}
