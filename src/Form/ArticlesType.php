<?php

namespace App\Form;

use App\Entity\Articles;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticlesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('description', null, array('label' => 'Déscription'))
            ->add('auteur')
            // ->add('image')
            //ajout du champ d'upload d'image, il n'est pas lié à la base de données
            ->add('image', FileType::class, [
                'label' => 'Charger une image',
                'multiple' => false,
                'mapped' => false,
                'required' => true
            ])
            ->add('categorie', null, array('label' => 'Catégorie'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Articles::class,
        ]);
    }

    
}
