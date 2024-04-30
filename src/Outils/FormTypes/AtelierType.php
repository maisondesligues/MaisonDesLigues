<?php
namespace App\Outils\FormTypes;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AtelierType extends AbstractType
{
    /**
     * Crée un formulaire pour le bouton radio Atelier
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle', TextType::class)
            ->add('nombrePlaces', TextType::class);
    }
}
