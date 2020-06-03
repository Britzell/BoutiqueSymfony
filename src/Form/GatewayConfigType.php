<?php


namespace App\Form;

use App\Entity\Checkout\GatewayConfig;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class GatewayConfigType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('factoryName', TextType::class, [
                'disabled' => true,
                'data' => 'paypal_express_checkout',
            ])
            ->add('gatewayName', TextType::class)
            ->add('config', ConfigPaypalGatewayConfigType::class, [
                'label' => false,
                'auto_initialize' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GatewayConfig::class,
        ]);
    }
}
