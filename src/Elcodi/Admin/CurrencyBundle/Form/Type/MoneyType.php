<?php

/*
 * This file is part of the Elcodi package.
 *
 * Copyright (c) 2014-2015 Elcodi.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @author Aldo Chiecchia <zimage@tiscali.it>
 * @author Elcodi Team <tech@elcodi.com>
 */

namespace Elcodi\Admin\CurrencyBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Elcodi\Admin\CurrencyBundle\Form\DataMapper\MoneyDataMapper;
use Elcodi\Component\Core\Wrapper\Interfaces\WrapperInterface;
use Elcodi\Component\Currency\Entity\Money;

/**
 * Class MoneyType
 */
class MoneyType extends AbstractType
{
    /**
     * @var WrapperInterface
     *
     * Currency Wrapper
     */
    protected $defaultCurrencyWrapper;

    /**
     * @var string
     *
     * currency namespace
     */
    protected $currencyNamespace;

    /**
     * Construct method
     *
     * @param WrapperInterface $defaultCurrencyWrapper Default Currency Wrapper
     * @param string           $currencyNamespace      Currency namespace
     */
    public function __construct(
        WrapperInterface $defaultCurrencyWrapper,
        $currencyNamespace
    )
    {
        $this->defaultCurrencyWrapper = $defaultCurrencyWrapper;
        $this->currencyNamespace = $currencyNamespace;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount', 'money', [
                'divisor'  => 100,
                'currency' => false,
            ])
            ->add('currency', 'entity', [
                'class'         => $this->currencyNamespace,
                'query_builder' => function (EntityRepository $repository) {
                    return $repository
                        ->createQueryBuilder('c')
                        ->where('c.enabled = :enabled')
                        ->setParameter('enabled', true);
                },
                'required'      => true,
                'multiple'      => false,
                'property'      => 'symbol',
            ])
            ->setDataMapper(new MoneyDataMapper());
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options.
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Elcodi\Component\Currency\Entity\Money',
            'empty_data' => Money::create(
                0,
                $this
                    ->defaultCurrencyWrapper
                    ->get()
            ),
        ]);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'money_object';
    }
}
