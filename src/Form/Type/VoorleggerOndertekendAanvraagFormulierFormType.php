<?php
namespace GemeenteAmsterdam\FixxxSchuldhulp\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use GemeenteAmsterdam\FixxxSchuldhulp\Entity\Voorlegger;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class VoorleggerOndertekendAanvraagFormulierFormType extends AbstractType
{

    private bool $featureflagHerfinanciering;

    public function __construct(bool $featureflagHerfinanciering)
    {
        $this->featureflagHerfinanciering = $featureflagHerfinanciering;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('ondertekendAanvraagFormulierOntvangenShv', ShvStatusFormType::class, [
            'required' => true,
            'disabled' => $options['disable_group'] === 'gka'
        ]);
        $builder->add('ondertekendAanvraagFormulierOntvangenGka', GkaStatusFormType::class, [
            'required' => true,
            'disabled' => $options['disable_group'] === 'shv'
        ]);
        $builder->add('jongerenSchuldenvrijeStart', CheckboxType::class, [
            'required' => false,
            'label' => 'Jongeren Schuldenvrije Start (JSS)',
            'help' => 'DB: voorlegger.jongeren_schuldenvrije_start',
            'constraints' => [new Callback(function($value, ExecutionContextInterface $executionContext) {
                /**
                 * @var Voorlegger $voorlegger
                 */
                $voorlegger = $executionContext->getRoot()->getData();

                if (!$voorlegger->getJongerenSchuldenvrijeStart() && ($voorlegger->getJssAdviseurEmail() || $voorlegger->getJssAdviseurNaam() || $voorlegger->getJssAdviseurTelefoon())) {
                    $voorlegger->setJssAdviseurEmail(null);
                    $voorlegger->setJssAdviseurNaam(null);
                    $voorlegger->setJssAdviseurTelefoon(null);

                    $executionContext->buildViolation('De JSS velden dienen alleen ingevuld te worden bij Jongeren Schuldenvrije Start.')
                        ->atPath('jongerenSchuldenvrijeStart')
                        ->addViolation();
                }

                if ($voorlegger->getJongerenSchuldenvrijeStart() && (!$voorlegger->getJssAdviseurEmail() || !$voorlegger->getJssAdviseurNaam() || !$voorlegger->getJssAdviseurTelefoon())) {
                    $executionContext->buildViolation('Vul alle JSS velden in.')
                        ->atPath('jongerenSchuldenvrijeStart')
                        ->addViolation();
                }
            })]
        ]);
        $builder->add('jssAdviseurNaam', TextType::class, [
            'required' => false,
            'label' => 'Naam Jongerenadviseur',
            'help' => 'DB: voorlegger.jss_adviseur_naam',
        ]);
        $builder->add('jssAdviseurTelefoon', TextType::class, [
            'required' => false,
            'label' => 'Telefoonnummer Jongerenadviseur',
            'help' => 'DB: voorlegger.jss_adviseur_telefoon',
        ]);
        $builder->add('jssAdviseurEmail', TextType::class, [
            'required' => false,
            'label' => 'E-mailadres Jongerenadviseur',
            'help' => 'DB: voorlegger.jss_adviseur_email',
        ]);
        $builder->add('kindregeling', CheckboxType::class, [
            'required' => false,
            'label' => 'Kindregeling',
            'help' => 'DB: voorlegger.kindregeling',
        ]);
        $builder->add('saneringsKrediet', CheckboxType::class, [
            'required' => false,
            'label' => 'Saneringskrediet (SK)',
            'help' => 'DB: voorlegger.saneringskrediet',
        ]);
        $builder->add('principebeslissing', CheckboxType::class, [
            'required' => false,
            'label' => 'Principebeslissing',
            'help' => 'DB: voorlegger.pricipebeslissing',
        ]);

        $builder->add('schuldenOpDeWerkvloer', CheckboxType::class, [
            'required' => false,
            'label' => 'Betreft Schulden op de werkvloer?',
            'help' => 'DB: voorlegger.schulden_op_de_werkvloer',
        ]);
        $builder->add('aanvullendeInformatie', TextareaType::class, [
            'required' => false,
            'help' => 'DB: voorlegger.aanvullende_informatie',
        ]);
        $builder->add('file', CollectionType::class, [
            'mapped' => false,
            'entry_type' => DocumentFormType::class,
            'entry_options' => ['required' => false],
            'allow_add' => true,
            'allow_delete' => true,
            'prototype_name' => '__name__',
            'by_reference' => false,
            'constraints' => [
                new Valid()
            ]
        ]);
        $builder->add('removeFile', CollectionType::class, [
            'mapped' => false,
            'entry_type' => HiddenType::class,
            'entry_options' => ['required' => false],
            'allow_add' => true,
            'prototype_name' => '__name__',
            'by_reference' => false,
        ]);
        $builder->add('aantekening', CreateAantekeningFormType::class, [
            'mapped' => false,
            'required' => false,
            'constraints' => [
                new Valid()
            ]
        ]);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            unset($data['file']['__name__']);
            unset($data['removeFile']['__name__']);
            $event->setData($data);
        });

        if ($this->featureflagHerfinanciering) {
            $builder->add('herfinanciering', CheckboxType::class, [
                'required' => false,
                'label' => 'Herfinanciering'
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', Voorlegger::class);
        $resolver->setDefault('choice_translation_domain', false);
        $resolver->setDefault('disable_group', null);
    }
}
