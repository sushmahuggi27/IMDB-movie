<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder      
      ->add('image', FileType::class ,['data_class' => null,'required'=>false])
      ->add('vedio', TextType::class) 
      ->add('name', TextType::class)
      ->add('save', SubmitType::class, ['label' => 'Submit']);       
  }    
}
?>