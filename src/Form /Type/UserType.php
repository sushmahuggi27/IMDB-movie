<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType; 
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
   {
     $builder
        ->add('name', TextType::class)
        ->add('email',EmailType::class)
        ->add('password',RepeatedType::class,['type'=>passwordType::class,
        'invalid_message'=>'the password field must match ', 'options' => ['attr' => ['class' => 'password-field']], 
        'required' => true, 'first_options'  => ['label' => 'Password'], 
        'second_options' =>['label' => 'Re-enter']],)
        ->add('save', SubmitType::class, ['label' => 'Submit']) 
        ;       
    }
}
?>