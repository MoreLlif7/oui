<?php

namespace App\Form;

use App\Entity\Fichier;
use App\Entity\Theme;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;



class AjoutFichierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('nom', FileType::class, array('label'=> 'Fichier à envoyer'))
        ->add('utilisateur',EntityType::class, array('class'=>'App\Entity\Utilisateur', 'choice_label'=>function ($utilisateur){
            return $utilisateur->getNom().' '.$utilisateur->getPrenom();
        }))
        ->add('theme',EntityType::class, array('class'=>'App\Entity\Theme', 'choice_label'=>'nom', 'mapped'=>false))

        ->add('themes',EntityType::class, array('class'=>Theme::class, 'choice_label'=>'nom', 'expanded' => true, 'multiple' => true))
        ->add('Envoyer',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Fichier::class,
        ]);
    }
}  
