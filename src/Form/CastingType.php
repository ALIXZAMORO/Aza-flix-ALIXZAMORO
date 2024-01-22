<?php

namespace App\Form;

use App\Entity\Casting;
use App\Entity\Movie;
use App\Entity\Person;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CastingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('role')
            ->add('creditOrder')
            ->add('person', EntityType::class, [
                "multiple" => false,
                "expanded" => false, // dropdown
                "class" => Person::class,
                // TODO : on veut le prénom + nom
                // 1ere solution faire une function anonyme QUE pour le formulaire
                // l'argument $entity sera chaque instance des objets de la relation
                "choice_label" => function ($entity)
                {
                    /** @var Person $entity */
                    return $entity->getFirstname() . " " . $entity->getLastname();
                }
                
                // 2eme solution, créer une function dans notre entité
                // et l'utiliser comme propriété ici
                // "choice_label" => "fullname"
                
            ])
            ->add('movie', EntityType::class, [
                "multiple" => false,
                "expanded" => false, // dropdown
                "class" => Movie::class,
                "choice_label" => "title",
                // ? https://symfony.com/doc/5.4/reference/forms/types/entity.html#query-builder
                "query_builder" => function(EntityRepository $entityrepository){
                    // TODO : requete perso : tri par titre
                    return $entityrepository->createQueryBuilder('m')
                        ->orderBy('m.title', 'ASC');
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Casting::class,
        ]);
    }
}
