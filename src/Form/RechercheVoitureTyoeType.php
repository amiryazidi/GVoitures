<?php

namespace App\Form;

use App\Entity\RechercheVoiture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RechercheVoitureTyoeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('minAnnee', IntegerType::class,[
                "required" => false,
                "label"=> "année de : "
            ])
            ->add('maxAnnee', IntegerType::class,[
                "required" => false,
                "label"=> "année à : "
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RechercheVoiture::class,
        ]);
    }
}
