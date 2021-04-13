<?php

namespace App\Form;

use App\Entity\Banque;
use App\Entity\Cheque;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChequeType extends AbstractType
{
    /**
     * Factoriser le label et le placeholder des formulaire
     *
     * @param string $label
     * @param string $placeholder
     * @return array
     */
    public function getConfiguration($label, $placeholder)
    {
        return [
            'label' => $label,
            'attr'  => [
                'placeholder' => $placeholder
            ]
            ];
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'num',
                TextType::class,
                $this->getConfiguration("Numero du cheque", "651561gh56"))
            ->add(
                'montant',
                NumberType::class,
                $this->getConfiguration("Montant", "45121.23"))
            ->add(
                'reqAt',
                DateType::class, 
                ['widget' => 'single_text','label'=>'Recu le ',
                'attr'  => [
                    'placeholder' => 'JJ/MM/AAAA']
                ])
            ->add(
                'banques',
                EntityType::class,[
                    'class'=>Banque::class,
                    'query_builder'=>function(EntityRepository $s){
                        return $s->createQueryBuilder('banc')->orderBy('banc.nom');
                    }
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cheque::class,
        ]);
    }
}
