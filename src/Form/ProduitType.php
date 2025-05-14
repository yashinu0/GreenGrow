<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\Produit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantite')
            ->add('nom_produit')
            ->add('description_produit')
            ->add('prix_produit')
            ->add('disponibilte_produit')
            ->add('rating', IntegerType::class, [
                'required' => false,
                'label' => 'Rating',
            ])

            ->add('image_produit', FileType::class, [
                'label' => 'Upload Image',
                'required' => false,
                'mapped' => false, // This tells Symfony that this field is not associated with any entity property
            ])
            ->add('location', TextType::class, [
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('id_categories', EntityType::class, [
                'class' => Categories::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}