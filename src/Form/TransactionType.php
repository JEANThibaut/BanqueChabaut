<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\Transaction;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\Security;

class TransactionType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {  
        $builder

            ->add ('debitAccount', EntityType::class,[
                'class'=>Account::class,
                'query_builder'=> function (EntityRepository $account){
                    $user = $this->security->getUser();
                    return $account->createQueryBuilder('a')
                        ->innerJoin('a.user','u')
                        ->addSelect('u')
                        ->where('u.id = :id')
                        ->setParameter('id', $user->getId()); 
                },
                'choice_label' => 'number',
                "label"=>"Compte à débiter"
            ])

            ->add ('creditAccount', EntityType::class,[
                'class'=>Account::class,
                'query_builder'=> function (EntityRepository $account){
                    $user = $this->security->getUser();
                    return $account->createQueryBuilder('a')
                        ->innerJoin('a.user','u')
                        ->addSelect('u')
                        ->where('u.id = :id')
                        ->setParameter('id', $user->getId());
                },
                'choice_label' => 'number',
                "label" => "Compte à créditer "    
            ])

            // ->add('type', ChoiceType::class, [
            //     'choices'  => [
            //         'Debit' => 'Debit',
            //         'Credit' => 'Credit',
            //     ],
                
            // ])
            ->add('amount', NumberType::class, [
                "label" => "Montant",
                
            ])
            
            ->add('enregistrer', SubmitType::class, [
                "attr" => ['class' => 'btn bgColorSec text-white my-3'],
                'row_attr' => ['class' => 'text-center']
            ]);
     
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Transaction::class,
        ]);
    }
}
