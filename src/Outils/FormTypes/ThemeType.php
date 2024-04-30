<?php
namespace App\Outils\FormTypes;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ThemeType extends AbstractType
{
    /**
     * Crée un formulaire pour le bouton radio Theme
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        $builder

            // Ajoute la liste des libelles des ateliers en bdd sous forme de combobox
            ->add('atelier', ChoiceType::class, [
                'choices' => $options['ateliers'],

                // Affichage des libbellés
                'choice_label' => function ($choice, $key, $value) {
                    return $key; 
                },

                // Renvoie le choix de l'utilsateur depuis la combobox (id)
                'choice_value' => function ($choice) {
                    return $choice;
                },
                'label' => 'Atelier associé'
            ])

            // Ajoute une zone de texte "libelle"
            ->add('libelle', TextType::class);
    }

    /**
     * Oblige l'utilisation d'ateliers lors de la création du formulaire
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setRequired('ateliers');
    }
}
