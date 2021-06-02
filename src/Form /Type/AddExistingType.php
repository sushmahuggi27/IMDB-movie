<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType; 
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType; 
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Repository\ActorsRepository;
use App\Repository\DirectorRepository;
use App\Repository\ProductionRepository;
use App\Repository\MediaRepository;
use App\Repository\MoviesRepository;
use App\Entity\Actors;
use App\Entity\Director;
use App\Entity\Production;
use App\Entity\Media;
use App\Entity\Movies;

class AddExistingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {    
        $builder
        ->add('title', TextType::class,['label' => 'Movie Name'])
        ->add('relDate', TextType::class,['label' => 'Release Year'])
        ->add('type', TextType::class,['label' => 'Type'])
        ->add('description', TextareaType::class,['label' => 'Movie Decription'])
        ->add('language', TextType::class,['label' => 'Movie Language'])
        ->add('poster', FileType::class ,['data_class' => null,'required'=>false]) 
            ->add('actors', EntityType::class, [
                'placeholder' => 'Choose a Actors',
                'class' => Actors::class,
                'multiple' => false,
                'expanded' => false,
                'choice_label' => 'name',
                'choice_value' => 'id'])
            ->add('director', EntityType::class, [
                'placeholder' => 'Choose a Director',
                'class' => Director::class,
                'multiple' => false,
                'expanded' => false,
                'choice_label' => 'name',
                'choice_value' => 'id'])
            ->add('production', EntityType::class, [
                'placeholder' => 'Choose a production',
                'class' => Production::class,
                'multiple' => false,
                'expanded' => false,
                'choice_label' => 'name',
                'choice_value' => 'id'])
            ->add('media', EntityType::class, [
                'placeholder' => 'Choose a Media',
                'class' => Media::class,
                'multiple' =>false,
                'expanded' => false,
                'choice_label' => 'name',
                'choice_value' => 'id'])
            ->add('save', SubmitType::class, ['label' => 'Submit']);
    }
}
?>
