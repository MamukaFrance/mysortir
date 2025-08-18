<?php

namespace App\Form;

use App\Entity\Participant;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('dateHeureDebut', null, [
                'widget' => 'single_text'])
            ->add('duree')
            ->add('dateLimiteInscription', null, [
                'widget' => 'single_text'])
            ->add('nbInscriptionsMax')
            ->add('infosSortie')
            ->add('participants', EntityType::class, [
                'class' => Participant::class,
                'choice_label' => function ($participant) {
                    return $participant->getNom() . ' ' . $participant->getPrenom();
                },
                'multiple' => true,
            ])
            ->add('organisateur', EntityType::class, [
                'class' => Participant::class,
                'choice_label' => function ($participant) {
                    return $participant->getNom() . ' ' . $participant->getPrenom();
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
