<?php

namespace App\Form;

use App\Entity\Livre;
use App\Entity\Emprunteur;
use App\Entity\Emprunt;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmpruntType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_emprunt')
            ->add('date_retour')
            ->add('emprunteur', EntityType::class, [
                'class' => Emprunteur::class,
                'choice_label' => function (Emprunteur $emprunteurs) {
                    return "{$emprunteurs->getNom()} {$emprunteurs->getPrenom()}";
                },
                'attr' => [
                    'class' => 'form_scrollable-checkboxes',
                ],
                'by_reference' => true,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.nom', 'ASC');
                },
            ])
            ->add('livre', EntityType::class, [
                'class' => Livre::class,
                'choice_label' => function (Livre $livres) {
                    return "{$livres->getTitre()}";
                },
                'attr' => [
                    'class' => 'form_scrollable-checkboxes',
                ],
                'by_reference' => true,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.titre', 'ASC');
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Emprunt::class,
        ]);
    }
}
