<?php

namespace App\Form;

use App\Entity\Participant;
use App\Entity\Site;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Sodium\add;

class ParticipantType extends AbstractType
{
    public function  __construct(private Security $security)
    {

    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('password')
            ->add('telephone')
            ->add('site', EntityType::class, [
                'class' => Site::class,
                'choice_label' => 'nom',
            ])
            ->add('sorties', EntityType::class, [
                'class' => Sortie::class,
                'choice_label' => 'nom',
                'multiple' => true,
            ]);
            if ($this->security->isGranted('ROLE_ADMIN')) {
                $builder
                ->add('roles', ChoiceType::class, [
                    'choices' => [
                        'Utilisateur' => 'ROLE_USER',
                        'Administrateur' => 'ROLE_ADMIN',
                    ],
                    'multiple' => true,     // true si l'utilisateur peut avoir plusieurs rôles
                    'expanded' => false,     // true = cases à cocher, false = liste déroulante
                    'label' => 'Rôles',     // texte du label
                ])
                    ->add('administrateur')
                    ->add('actif')
                    ->add('isVerified');
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}
