<?php

namespace App\Form;

use App\Entity\GroupePrive;
use App\Entity\Participant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroupePriveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
//            ->add('dateCreation', null, [
//                'widget' => 'single_text'])
//            ->add('createur', EntityType::class, [
//                'class' => Participant::class,
//                'choice_label' => 'id',
//            ])
            ->add('participants', EntityType::class, [
                'class' => Participant::class,
                'choice_label' => function ($participant) {
                    return $participant->getNom() . ' ' . $participant->getPrenom();
                },
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GroupePrive::class,
        ]);
    }
}
