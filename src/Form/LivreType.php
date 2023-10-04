<?php

namespace App\Form;

use App\Entity\Livre;
use App\Entity\Genre;
use App\Entity\Auteur;
use Symfony\Component\Form\AbstractType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LivreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            // ->add('anee_edition')
            ->add('nombre_pages')
            ->add('code_isbn')
            ->add('auteur', EntityType::class, [
                'class' => Auteur::class,
                'choice_label' => function (Auteur $auteurs) {
                    return "{$auteurs->getNom()} {$auteurs->getPrenom()}";
                },
                'attr' => [
                    'class' => 'form_scrollable_checkboxes',
                ],
                'by_reference' => true,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.nom', 'ASC');
                },
            ])
            ->add('genres', EntityType::class, [
                'class' => Genre::class,
                'choice_label' => function (Genre $genres) {
                    return "{$genres->getNom()}";
                },
                'expanded' => true,
                'multiple' => true,
                'attr' => [
                    'class' => 'form_scrollable-checkboxes',
                ],
                'by_reference' => false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.nom', 'ASC');
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livre::class,
        ]);
    }
}
