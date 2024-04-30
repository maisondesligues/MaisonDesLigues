<?php
namespace App\Outils\FormTypes;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VacationType extends AbstractType
{

    /**
     * Crée un formulaire pour le bouton radio Vacation
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder

            // Ajoute la liste des libelles des ateliers en bdd sous forme de combobox
            ->add('atelier', ChoiceType::class, [
                'choices' => $options['ateliers'],
                'choice_label' => function ($choice, $key, $value) {
                    return $key;
                },
                'choice_value' => function ($choice) {
                    return $choice;
                },
                'label' => 'Atelier associé'
            ])

            // Ajout de dates...
            ->add('dateDebut', DateTimeType::class)
            ->add('dateFin', DateTimeType::class);
    }

    /**
     * Oblige l'utilisation d'ateliers lors de la création du formulaire
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setRequired('ateliers');
    }
}
