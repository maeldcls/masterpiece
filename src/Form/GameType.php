<?php

namespace App\Form;

use App\Entity\Developer;
use App\Entity\Game;
use App\Entity\Genre;
use App\Entity\Platform;
use App\Entity\Publisher;
use App\Entity\Tag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('summary')
            ->add('releaseDate')
            ->add('website')
            ->add('metacritics')
            ->add('backgroundImage')
            ->add('parentPlatform')
            ->add('gameId')
            ->add('screenshots')
            ->add('tags', EntityType::class, [
                'class' => Tag::class,
'choice_label' => 'id',
'multiple' => true,
            ])
            ->add('platforms', EntityType::class, [
                'class' => Platform::class,
'choice_label' => 'id',
'multiple' => true,
            ])
            ->add('genres', EntityType::class, [
                'class' => Genre::class,
'choice_label' => 'id',
'multiple' => true,
            ])
            ->add('publishers', EntityType::class, [
                'class' => Publisher::class,
'choice_label' => 'id',
'multiple' => true,
            ])
            ->add('developers', EntityType::class, [
                'class' => Developer::class,
'choice_label' => 'id',
'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Game::class,
        ]);
    }
}
