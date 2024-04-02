<?php

namespace GemeenteAmsterdam\FixxxSchuldhulp\Entity;

use Doctrine\ORM\Mapping as ORM;
use GemeenteAmsterdam\FixxxSchuldhulp\Traits\ExportAble;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table
 */
class Voorlegger
{
    use ExportAble;

    const STATUS_SHV_OPEN = 0;
    const STATUS_SHV_BEZIG = 1;
    const STATUS_SHV_OPGEVOERD = 2;
    const STATUS_SHV_GECONTROLEERD = 3;

    const STATUS_GKA_OPEN = 0;
    const STATUS_GKA_OPGEVOERD = 2;
    const STATUS_GKA_GECONTROLEERD = 3;

    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Dossier
     * @ORM\OneToOne(targetEntity="Dossier", inversedBy="voorlegger", orphanRemoval=true)
     * @ORM\JoinColumn(name="dossier_id", referencedColumnName="id", nullable=false)
     */
    private $dossier;

    // ---


    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $legitimatieOntvangenShv;

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $legitimatieOntvangenGka;

    // ---

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $vtlbOntvangenShv;

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $vtlbOntvangenGka;

    /**
     * @var float
     * @ORM\Column(type="decimal", precision=8, scale=2, nullable=true)
     * @Assert\Type("numeric")
     * @Assert\Range(min=-100000, max=100000)
     */
    private $vtlbBedrag;

    // ---

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $inkomstenspecificatieOntvangenShv;

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $inkomstenspecificatieOntvangenGka;

    // ---

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $arbeidsovereenkomstOntvangenShv;

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $arbeidsovereenkomstOntvangenGka;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $arbeidsovereenkomstNvt;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(min=0, max=255)
     */
    private $arbeidsovereenkomstWerkgever = null;

    /**
     * @var \Date|null
     * @ORM\Column(type="date", nullable=true)
     */
    private $arbeidsovereenkomstEinddatum;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(min=0, max=255)
     * @Assert\Choice(callback="getContractOpties")
     */
    private $arbeidsovereenkomstContract;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(min=0, max=255)
     */
    private $arbeidsovereenkomstPartnerWerkgever = null;

    /**
     * @var \Date|null
     * @ORM\Column(type="date", nullable=true)
     */
    private $arbeidsovereenkomstPartnerEinddatum;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(min=0, max=255)
     * @Assert\Choice(callback="getContractOpties")
     */
    private $arbeidsovereenkomstPartnerContract;

    // ---

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $beschikkingUwvOntvangenShv;

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $beschikkingUwvOntvangenGka;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $beschikkingUwvNvt;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $beschikkingInkomenUitWerk;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $beschikkingUwvZw;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $beschikkingUwvWw;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $beschikkingUwvWia;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $beschikkingUwvWajong;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $beschikkingGemeenteAmsterdamWPI;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $beschikkingSVBAOW;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $beschikkingSVBANW;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $beschikkingGemeenteAmsterdamIOAW;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(min=0, max=255)
     */
    private $beschikkingUwvOverig;

    // ---

    /**
     * @var integer|null
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Length(min=0, max=255)
     */
    private ?int $verloningsDag;

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $voorlopigeTeruggaafBelastingdienstOntvangenShv;

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $voorlopigeTeruggaafBelastingdienstOntvangenGka;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $voorlopigeTeruggaafBelastingdienstNvt;

    // ---

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $alimentatieOntvangenShv;

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $alimentatieOntvangenGka;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $alimentatieNvt;

    // ---

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $kostgeldOntvangenShv;

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $kostgeldOntvangenGka;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $kostgeldNvt;

    // ---

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $toeslagenOntvangenShv;

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $toeslagenOntvangenGka;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $toeslagenNvt;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $toeslagenHuur;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $toeslagenZorg;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $toeslagenKinderopvang;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $toeslagenKindgebondenBudget;

    // ---

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $huurspecificatieOntvangenShv;

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $huurspecificatieOntvangenGka;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $huurspecificatieNvt;

    // ---

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $polisbladZorgverzekeringOntvangenShv;

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $polisbladZorgverzekeringOntvangenGka;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $polisbladZorgverzekeringNvt;

    // ---

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $alimentatieEchtscheidingsconvenantOntvangenShv;

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $alimentatieEchtscheidingsconvenantOntvangenGka;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $alimentatieEchtscheidingsconvenantNvt;

    // ---

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $overeenkomstKinderopvangOntvangenShv;

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $overeenkomstKinderopvangOntvangenGka;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $overeenkomstKinderopvangNvt;

    // ---

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $kwijtscheldingGemeenteBelastingOntvangenShv;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $kwijtscheldingGemeenteBelastingNvt;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $kwijtscheldingGemeenteBelasting;

    // ---

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $corrigerenGemeenteBelastingOntvangenGka;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $corrigerenGemeenteBelasting;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $corrigerenGemeenteBelastingNvt;

    // ---

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $autoNvt;

    // ---

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $autoTaxatieOntvangenShv;

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $autoTaxatieOntvangenGka;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $autoTaxatieNvt;

    // ---

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $autolastenKmWoonwerkverkeerOntvangenShv;

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $autolastenKmWoonwerkverkeerOntvangenGka;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $autolastenKmWoonwerkverkeerNvt;

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Type("numeric")
     */
    private $autolastenKmWoonwerkverkeer;

    // ---

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $verklaringWerkgeverOntvangenShv;

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $verklaringWerkgeverOntvangenGka;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $verklaringWerkgeverNvt;

    // ---

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $vrijwaringsbewijsOntvangenShv;

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $vrijwaringsbewijsOntvangenGka;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $vrijwaringsbewijsNvt;

    // ---

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $schuldenoverzichtOntvangenShv;

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $schuldenoverzichtOntvangenGka;

    // ---

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $vorderingenOntvangenShv;

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $vorderingenOntvangenGka;

    // ---

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $inzageToetsingBkrOntvangenShv;

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $inzageToetsingBkrOntvangenGka;

    // ---

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $stabilisatieovereenkomstOntvangenShv;

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $stabilisatieovereenkomstOntvangenGka;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $stabilisatieovereenkomstNvt;

    // ---

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $cjibOntvangenShv;

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $cjibOntvangenGka;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $cjib;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $cjibNvt;

    // ---

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $meterstandenEnergieOntvangenShv;

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $meterstandenEnergieOntvangenGka;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $meterstandenEnergieNvt;

    // ---

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $waternetOntvangenShv;

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $waternetOntvangenGka;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $waternetNvt;

    // ---

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $retourbewijsModemOntvangenShv;

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $retourbewijsModemOntvangenGka;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $retourbewijsModemNvt;

    // ---

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $toelichtingAanvraagSchuldsaneringClientOntvangenShv;

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $toelichtingAanvraagSchuldsaneringClientOntvangenGka;

    // ---
    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(min=0, max=255)
     */
    private $ontstaanVanSchulden;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(min=0, max=255)
     */
    private $inspanningsverplichting;

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $toelichtingAanvraagSchuldsaneringShvOntvangenShv;

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $toelichtingAanvraagSchuldsaneringShvOntvangenGka;

    // ---

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $beschikkingOnderBewindstellingOntvangenShv;

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $beschikkingOnderBewindstellingOntvangenGka;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $beschikkingOnderBewindstellingNvt;

    // ---

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $budgetbeheerOntvangenShv;

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $budgetbeheerOntvangenGka;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $budgetbeheerNvt;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $budgetbeheerPlangroep;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $budgetbeheerCav;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $budgetbeheerFibu;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $budgetbeheerOverig;

    // ---

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $gereserveerdeGeldenOntvangenShv;

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $gereserveerdeGeldenOntvangenGka;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $gereserveerdeGeldenNvt;

    /**
     * @var float
     * @ORM\Column(type="decimal", nullable=true, precision=8, scale=2)
     * @Assert\Type("numeric")
     * @Assert\Range(min=-100000, max=100000)
     */
    private $gereserveerdeGelden;

    // ---

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $ondertekendAanvraagFormulierOntvangenShv;

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $ondertekendAanvraagFormulierOntvangenGka;

    // ---

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $aanvullendeInformatie;

    // ---

    /**
     * @var string
     * @ORM\Column(type="string", length=125, nullable=true)
     * @Assert\Length(min=0, max=125)
     */
    private $energieBedrijf;

    /**
     * @var string
     * @ORM\Column(type="string", length=25, nullable=true)
     * @Assert\Length(min=0, max=25)
     */
    private $energieBedrijfKlantnummer;

    /**
     * @var \Date
     * @ORM\Column(type="date", nullable=true)
     */
    private $energieBedrijfDatumOpname;

    /**
     * @var int
     * @ORM\Column(type="bigint", nullable=true)
     * @Assert\Type("numeric")
     * @Assert\Range(min=-100000, max=100000)
     */
    private $energieBedrijfT1;

    /**
     * @var int
     * @ORM\Column(type="bigint", nullable=true)
     * @Assert\Type("numeric")
     * @Assert\Range(min=-100000, max=100000)
     */
    private $energieBedrijfT2;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     * @Assert\Type("numeric")
     * @Assert\Range(min=-100000, max=100000)
     */
    private $energieBedrijfGas;

    /**
     * @var string
     * @ORM\Column(type="string", length=125, nullable=true)
     * @Assert\Length(min=0, max=125)
     */
    private $warmteBedrijf;

    /**
     * @var string
     * @ORM\Column(type="string", length=25, nullable=true)
     * @Assert\Length(min=0, max=25)
     */
    private $warmteBedrijfKlantnummer;

    /**
     * @var \Date
     * @ORM\Column(type="date", nullable=true)
     */
    private $warmteBedrijfDatumOpname;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     * @Assert\Type("numeric")
     * @Assert\Range(min=-100000, max=100000)
     */
    private $warmteBedrijfOpname;

    /**
     * @var string
     * @ORM\Column(type="string", length=25, nullable=true)
     * @Assert\Length(min=0, max=25)
     */
    private $drinkwaterKlantnummer;

    /**
     * @var \Date
     * @ORM\Column(type="date", nullable=true)

     */
    private $drinkwaterDatumOpname;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     * @Assert\Type("numeric")
     * @Assert\Range(min=-100000, max=100000)
     */
    private $drinkwaterOpname;

    /**
     * @var boolean|null
     * @ORM\Column(type="boolean", nullable=true, options={"default" : false})
     */
    private $aangifteBelastingdienst;

    /**
     * @var integer|null
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $aangifteBelastingdienstShv;

    /**
     * @var integer|null
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $aangifteBelastingdienstGka;

    /**
     * @var boolean|null
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $aangifteBelastingdienstNvt;

    /**
     * @var boolean|null
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $jongerenSchuldenvrijeStart;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=125, nullable=true)
     * @Assert\Length(min=0, max=125)
     */
    private ?string $jssAdviseurNaam;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=125, nullable=true)
     * @Assert\Length(min=0, max=125)
     */
    private ?string $jssAdviseurTelefoon;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=125, nullable=true)
     * @Assert\Length(min=0, max=125)
     */
    private ?string $jssAdviseurEmail;

    /**
     * @var boolean|null
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $kindregeling;

    /**
     * @var boolean|null
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $saneringsKrediet;

    /**
     * @var boolean|null
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $principebeslissing;

    /**
     * @var boolean|null
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $schuldenOpDeWerkvloer;

    /**
     * @var boolean|null
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $herfinancering;

    // ---

    public function __construct()
    {
        $this->legitimatieOntvangenShv = self::STATUS_SHV_OPEN;
        $this->legitimatieOntvangenGka = false;
        $this->vtlbOntvangenShv = self::STATUS_SHV_OPEN;
        $this->vtlbOntvangenGka = false;
        $this->vtlbBedrag = 0.00;
        $this->inkomstenspecificatieOntvangenShv = self::STATUS_SHV_OPEN;
        $this->inkomstenspecificatieOntvangenGka = false;
        $this->arbeidsovereenkomstOntvangenShv = self::STATUS_SHV_OPEN;
        $this->arbeidsovereenkomstOntvangenGka = false;
        $this->arbeidsovereenkomstNvt = false;
        $this->beschikkingUwvOntvangenShv = self::STATUS_SHV_OPEN;
        $this->beschikkingUwvOntvangenGka = false;
        $this->beschikkingUwvNvt = false;
        $this->beschikkingInkomenUitWerk = false;
        $this->beschikkingUwvWw = false;
        $this->beschikkingUwvWia = false;
        $this->beschikkingUwvZw = false;
        $this->beschikkingUwvWajong = false;
        $this->beschikkingGemeenteAmsterdamIOAW = false;
        $this->beschikkingGemeenteAmsterdamWPI = false;
        $this->beschikkingSVBANW = false;
        $this->beschikkingSVBAOW = false;
        $this->voorlopigeTeruggaafBelastingdienstOntvangenShv = self::STATUS_SHV_OPEN;
        $this->voorlopigeTeruggaafBelastingdienstOntvangenGka = false;
        $this->voorlopigeTeruggaafBelastingdienstNvt = false;
        $this->alimentatieOntvangenShv = self::STATUS_SHV_OPEN;
        $this->alimentatieOntvangenGka = false;
        $this->alimentatieNvt = false;
        $this->kostgeldOntvangenShv = self::STATUS_SHV_OPEN;
        $this->kostgeldOntvangenGka = false;
        $this->kostgeldNvt = false;
        $this->huurspecificatieOntvangenShv = self::STATUS_SHV_OPEN;
        $this->huurspecificatieOntvangenGka = false;
        $this->huurspecificatieNvt = false;
        $this->polisbladZorgverzekeringOntvangenShv = self::STATUS_SHV_OPEN;
        $this->polisbladZorgverzekeringOntvangenGka = false;
        $this->polisbladZorgverzekeringNvt = false;
        $this->alimentatieEchtscheidingsconvenantOntvangenShv = self::STATUS_SHV_OPEN;
        $this->alimentatieEchtscheidingsconvenantOntvangenGka = false;
        $this->alimentatieEchtscheidingsconvenantNvt = false;
        $this->overeenkomstKinderopvangOntvangenShv = self::STATUS_SHV_OPEN;
        $this->overeenkomstKinderopvangOntvangenGka = false;
        $this->overeenkomstKinderopvangNvt = false;
        $this->kwijtscheldingGemeenteBelastingOntvangenShv = self::STATUS_SHV_OPEN;
        $this->kwijtscheldingGemeenteBelastingNvt = false;
        $this->kwijtscheldingGemeenteBelasting = false;
        $this->corrigerenGemeenteBelastingOntvangenGka = false;
        $this->corrigerenGemeenteBelastingNvt = false;
        $this->corrigerenGemeenteBelasting = false;
        $this->autoNvt = false;
        $this->autoTaxatieOntvangenShv = self::STATUS_SHV_OPEN;
        $this->autoTaxatieOntvangenGka = false;
        $this->autoTaxatieNvt = false;
        $this->autolastenKmWoonwerkverkeerOntvangenShv = self::STATUS_SHV_OPEN;
        $this->autolastenKmWoonwerkverkeerOntvangenGka = false;
        $this->autolastenKmWoonwerkverkeerNvt = false;
        $this->autolastenKmWoonwerkverkeer = 0;
        $this->verklaringWerkgeverOntvangenShv = self::STATUS_SHV_OPEN;
        $this->verklaringWerkgeverOntvangenGka = false;
        $this->verklaringWerkgeverNvt = false;
        $this->vrijwaringsbewijsOntvangenShv = self::STATUS_SHV_OPEN;
        $this->vrijwaringsbewijsOntvangenGka = false;
        $this->vrijwaringsbewijsNvt = false;
        $this->schuldenoverzichtOntvangenShv = self::STATUS_SHV_OPEN;
        $this->schuldenoverzichtOntvangenGka = false;
        $this->vorderingenOntvangenShv = false;
        $this->vorderingenOntvangenGka = false;
        $this->inzageToetsingBkrOntvangenShv = self::STATUS_SHV_OPEN;
        $this->inzageToetsingBkrOntvangenGka = false;
        $this->stabilisatieovereenkomstOntvangenShv = self::STATUS_SHV_OPEN;
        $this->stabilisatieovereenkomstOntvangenGka = false;
        $this->stabilisatieovereenkomstNvt = false;
        $this->cjibOntvangenShv = self::STATUS_SHV_OPEN;
        $this->cjibOntvangenGka = false;
        $this->cjib = false;
        $this->cjibNvt = false;
        $this->meterstandenEnergieOntvangenShv = self::STATUS_SHV_OPEN;
        $this->meterstandenEnergieOntvangenGka = false;
        $this->meterstandenEnergieNvt = false;
        $this->waternetOntvangenShv = false;
        $this->waternetOntvangenGka = false;
        $this->waternetNvt = false;
        $this->retourbewijsModemOntvangenShv = self::STATUS_SHV_OPEN;
        $this->retourbewijsModemOntvangenGka = false;
        $this->retourbewijsModemNvt = false;
        $this->toelichtingAanvraagSchuldsaneringClientOntvangenShv = self::STATUS_SHV_OPEN;
        $this->toelichtingAanvraagSchuldsaneringClientOntvangenGka = false;
        $this->toelichtingAanvraagSchuldsaneringShvOntvangenShv = self::STATUS_SHV_OPEN;
        $this->toelichtingAanvraagSchuldsaneringShvOntvangenGka = false;
        $this->beschikkingOnderBewindstellingOntvangenShv = self::STATUS_SHV_OPEN;
        $this->beschikkingOnderBewindstellingOntvangenGka = false;
        $this->beschikkingOnderBewindstellingNvt = false;
        $this->budgetbeheerOntvangenShv = self::STATUS_SHV_OPEN;
        $this->budgetbeheerOntvangenGka = false;
        $this->budgetbeheerNvt = false;
        $this->budgetbeheerPlangroep = false;
        $this->budgetbeheerCav = false;
        $this->budgetbeheerFibu = false;
        $this->gereserveerdeGeldenOntvangenShv = self::STATUS_SHV_OPEN;
        $this->gereserveerdeGeldenOntvangenGka = false;
        $this->gereserveerdeGeldenNvt = false;
        $this->gereserveerdeGelden = 0.00;
        $this->ondertekendAanvraagFormulierOntvangenShv = self::STATUS_SHV_OPEN;
        $this->ondertekendAanvraagFormulierOntvangenGka = false;
        $this->toeslagenOntvangenShv = self::STATUS_SHV_OPEN;
        $this->toeslagenOntvangenGka = false;
        $this->toeslagenNvt = false;
        $this->toeslagenHuur = false;
        $this->toeslagenZorg = false;
        $this->toeslagenKinderopvang = false;
        $this->toeslagenKindgebondenBudget = false;
        $this->aangifteBelastingdienst = false;
        $this->aangifteBelastingdienstShv = self::STATUS_SHV_OPEN;
        $this->aangifteBelastingdienstGka = false;
        $this->aangifteBelastingdienstNvt = false;
        $this->jongerenSchuldenvrijeStart = false;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \GemeenteAmsterdam\FixxxSchuldhulp\Entity\Dossier
     */
    public function getDossier()
    {
        return $this->dossier;
    }

    public function getLegitimatieOntvangenGka()
    {
        return $this->legitimatieOntvangenGka;
    }

    public function getLegitimatieOntvangenShv()
    {
        return $this->legitimatieOntvangenShv;
    }

    public function getVtlbOntvangenShv()
    {
        return $this->vtlbOntvangenShv;
    }

    public function getVtlbOntvangenGka()
    {
        return $this->vtlbOntvangenGka;
    }

    public function getVtlbBedrag()
    {
        return $this->vtlbBedrag;
    }

    public function getInkomstenspecificatieOntvangenShv()
    {
        return $this->inkomstenspecificatieOntvangenShv;
    }

    public function getInkomstenspecificatieOntvangenGka()
    {
        return $this->inkomstenspecificatieOntvangenGka;
    }

    public function getArbeidsovereenkomstOntvangenShv()
    {
        return $this->arbeidsovereenkomstOntvangenShv;
    }

    public function getArbeidsovereenkomstOntvangenGka()
    {
        return $this->arbeidsovereenkomstOntvangenGka;
    }

    public function getBeschikkingUwvOntvangenShv()
    {
        return $this->beschikkingUwvOntvangenShv;
    }

    public function getBeschikkingUwvOntvangenGka()
    {
        return $this->beschikkingUwvOntvangenGka;
    }

    public function isArbeidsovereenkomstNvt()
    {
        return $this->arbeidsovereenkomstNvt;
    }

    public function getArbeidsovereenkomstWerkgever()
    {
        return $this->arbeidsovereenkomstWerkgever;
    }

    public function getArbeidsovereenkomstContract()
    {
        return $this->arbeidsovereenkomstContract;
    }

    public function getArbeidsovereenkomstPartnerWerkgever()
    {
        return $this->arbeidsovereenkomstPartnerWerkgever;
    }

    public function getArbeidsovereenkomstPartnerContract()
    {
        return $this->arbeidsovereenkomstPartnerContract;
    }

    public function isBeschikkingUwvNvt()
    {
        return $this->beschikkingUwvNvt;
    }

    public function isBeschikkingInkomenUitWerk()
    {
        return $this->beschikkingInkomenUitWerk;
    }

    public function isBeschikkingUwvWw()
    {
        return $this->beschikkingUwvWw;
    }

    public function isBeschikkingUwvWia()
    {
        return $this->beschikkingUwvWia;
    }

    public function isBeschikkingUwvZw()
    {
        return $this->beschikkingUwvZw;
    }

    public function isBeschikkingUwvWajong()
    {
        return $this->beschikkingUwvWajong;
    }

    public function isBeschikkingGemeenteAmsterdamWPI()
    {
        return $this->beschikkingGemeenteAmsterdamWPI;
    }

    public function isBeschikkingSVBANW()
    {
        return $this->beschikkingSVBANW;
    }

    public function isBeschikkingSVBAOW()
    {
        return $this->beschikkingSVBAOW;
    }

    public function isBeschikkingGemeenteAmsterdamIOAW()
    {
        return $this->beschikkingGemeenteAmsterdamIOAW;
    }

    public function getBeschikkingUwvOverig()
    {
        return $this->beschikkingUwvOverig;
    }

    public function getVoorlopigeTeruggaafBelastingdienstOntvangenShv()
    {
        return $this->voorlopigeTeruggaafBelastingdienstOntvangenShv;
    }

    public function getVoorlopigeTeruggaafBelastingdienstOntvangenGka()
    {
        return $this->voorlopigeTeruggaafBelastingdienstOntvangenGka;
    }

    public function isVoorlopigeTeruggaafBelastingdienstNvt()
    {
        return $this->voorlopigeTeruggaafBelastingdienstNvt;
    }

    public function getAlimentatieOntvangenShv()
    {
        return $this->alimentatieOntvangenShv;
    }

    public function getAlimentatieOntvangenGka()
    {
        return $this->alimentatieOntvangenGka;
    }

    public function isAlimentatieNvt()
    {
        return $this->alimentatieNvt;
    }

    public function getKostgeldOntvangenShv()
    {
        return $this->kostgeldOntvangenShv;
    }

    public function getKostgeldOntvangenGka()
    {
        return $this->kostgeldOntvangenGka;
    }

    public function isKostgeldNvt()
    {
        return $this->kostgeldNvt;
    }


    public function getHuurspecificatieOntvangenShv()
    {
        return $this->huurspecificatieOntvangenShv;
    }

    public function getHuurspecificatieOntvangenGka()
    {
        return $this->huurspecificatieOntvangenGka;
    }

    public function isHuurspecificatieNvt()
    {
        return $this->huurspecificatieNvt;
    }

    public function getPolisbladZorgverzekeringOntvangenShv()
    {
        return $this->polisbladZorgverzekeringOntvangenShv;
    }

    public function getPolisbladZorgverzekeringOntvangenGka()
    {
        return $this->polisbladZorgverzekeringOntvangenGka;
    }

    public function isPolisbladZorgverzekeringNvt()
    {
        return $this->polisbladZorgverzekeringNvt;
    }

    public function getAlimentatieEchtscheidingsconvenantOntvangenShv()
    {
        return $this->alimentatieEchtscheidingsconvenantOntvangenShv;
    }

    public function getAlimentatieEchtscheidingsconvenantOntvangenGka()
    {
        return $this->alimentatieEchtscheidingsconvenantOntvangenGka;
    }

    public function isAlimentatieEchtscheidingsconvenantNvt()
    {
        return $this->alimentatieEchtscheidingsconvenantNvt;
    }

    public function getOvereenkomstKinderopvangOntvangenShv()
    {
        return $this->overeenkomstKinderopvangOntvangenShv;
    }

    public function getOvereenkomstKinderopvangOntvangenGka()
    {
        return $this->overeenkomstKinderopvangOntvangenGka;
    }

    public function isOvereenkomstKinderopvangNvt()
    {
        return $this->overeenkomstKinderopvangNvt;
    }

    public function getKwijtscheldingGemeenteBelastingOntvangenShv()
    {
        return $this->kwijtscheldingGemeenteBelastingOntvangenShv;
    }

    public function isKwijtscheldingGemeenteBelastingNvt()
    {
        return $this->kwijtscheldingGemeenteBelastingNvt;
    }

    public function isKwijtscheldingGemeenteBelasting()
    {
        return $this->kwijtscheldingGemeenteBelasting;
    }

    public function getCorrigerenGemeenteBelastingOntvangenGka()
    {
        return $this->corrigerenGemeenteBelastingOntvangenGka;
    }

    public function isCorrigerenGemeenteBelastingNvt()
    {
        return $this->corrigerenGemeenteBelastingNvt;
    }

    public function isCorrigerenGemeenteBelasting()
    {
        return $this->corrigerenGemeenteBelasting;
    }

    public function isAutoNvt()
    {
        return $this->autoNvt;
    }

    public function getAutoTaxatieOntvangenShv()
    {
        return $this->autoTaxatieOntvangenShv;
    }

    public function getAutoTaxatieOntvangenGka()
    {
        return $this->autoTaxatieOntvangenGka;
    }

    public function isAutoTaxatieNvt()
    {
        return $this->autoTaxatieNvt;
    }

    public function getAutolastenKmWoonwerkverkeerOntvangenShv()
    {
        return $this->autolastenKmWoonwerkverkeerOntvangenShv;
    }

    public function getAutolastenKmWoonwerkverkeerOntvangenGka()
    {
        return $this->autolastenKmWoonwerkverkeerOntvangenGka;
    }

    public function isAutolastenKmWoonwerkverkeerNvt()
    {
        return $this->autolastenKmWoonwerkverkeerNvt;
    }

    public function getAutolastenKmWoonwerkverkeer()
    {
        return $this->autolastenKmWoonwerkverkeer;
    }

    public function getVerklaringWerkgeverOntvangenShv()
    {
        return $this->verklaringWerkgeverOntvangenShv;
    }

    public function getVerklaringWerkgeverOntvangenGka()
    {
        return $this->verklaringWerkgeverOntvangenGka;
    }

    public function isVerklaringWerkgeverNvt()
    {
        return $this->verklaringWerkgeverNvt;
    }

    public function getVrijwaringsbewijsOntvangenShv()
    {
        return $this->vrijwaringsbewijsOntvangenShv;
    }

    public function getVrijwaringsbewijsOntvangenGka()
    {
        return $this->vrijwaringsbewijsOntvangenGka;
    }

    public function isVrijwaringsbewijsNvt()
    {
        return $this->vrijwaringsbewijsNvt;
    }

    public function getSchuldenoverzichtOntvangenShv()
    {
        return $this->schuldenoverzichtOntvangenShv;
    }

    public function getSchuldenoverzichtOntvangenGka()
    {
        return $this->schuldenoverzichtOntvangenGka;
    }

    public function getVorderingenOntvangenShv()
    {
        return $this->vorderingenOntvangenShv;
    }

    public function getVorderingenOntvangenGka()
    {
        return $this->vorderingenOntvangenGka;
    }

    public function getInzageToetsingBkrOntvangenShv()
    {
        return $this->inzageToetsingBkrOntvangenShv;
    }

    public function getInzageToetsingBkrOntvangenGka()
    {
        return $this->inzageToetsingBkrOntvangenGka;
    }

    public function getStabilisatieovereenkomstOntvangenShv()
    {
        return $this->stabilisatieovereenkomstOntvangenShv;
    }

    public function getStabilisatieovereenkomstOntvangenGka()
    {
        return $this->stabilisatieovereenkomstOntvangenGka;
    }

    public function isStabilisatieovereenkomstNvt()
    {
        return $this->stabilisatieovereenkomstNvt;
    }

    public function getCjibOntvangenShv()
    {
        return $this->cjibOntvangenShv;
    }

    public function getCjibOntvangenGka()
    {
        return $this->cjibOntvangenGka;
    }

    public function isCjib()
    {
        return $this->cjib;
    }

    public function isCjibNvt()
    {
        return $this->cjibNvt;
    }

    public function getMeterstandenEnergieOntvangenShv()
    {
        return $this->meterstandenEnergieOntvangenShv;
    }

    public function getMeterstandenEnergieOntvangenGka()
    {
        return $this->meterstandenEnergieOntvangenGka;
    }

    public function isMeterstandenEnergieNvt()
    {
        return $this->meterstandenEnergieNvt;
    }

    public function getWaternetOntvangenShv()
    {
        return $this->waternetOntvangenShv;
    }

    public function getWaternetOntvangenGka()
    {
        return $this->waternetOntvangenGka;
    }

    public function isWaternetNvt()
    {
        return $this->waternetNvt;
    }

    public function getRetourbewijsModemOntvangenShv()
    {
        return $this->retourbewijsModemOntvangenShv;
    }

    public function getRetourbewijsModemOntvangenGka()
    {
        return $this->retourbewijsModemOntvangenGka;
    }

    public function isRetourbewijsModemNvt()
    {
        return $this->retourbewijsModemNvt;
    }

    public function getToelichtingAanvraagSchuldsaneringClientOntvangenShv()
    {
        return $this->toelichtingAanvraagSchuldsaneringClientOntvangenShv;
    }

    public function getToelichtingAanvraagSchuldsaneringClientOntvangenGka()
    {
        return $this->toelichtingAanvraagSchuldsaneringClientOntvangenGka;
    }

    public function getOntstaanVanSchulden()
    {
        return $this->ontstaanVanSchulden;
    }

    public function getInspanningsverplichting()
    {
        return $this->inspanningsverplichting;
    }

    public function getToelichtingAanvraagSchuldsaneringShvOntvangenShv()
    {
        return $this->toelichtingAanvraagSchuldsaneringShvOntvangenShv;
    }

    public function getToelichtingAanvraagSchuldsaneringShvOntvangenGka()
    {
        return $this->toelichtingAanvraagSchuldsaneringShvOntvangenGka;
    }

    public function getBeschikkingOnderBewindstellingOntvangenShv()
    {
        return $this->beschikkingOnderBewindstellingOntvangenShv;
    }

    public function getBeschikkingOnderBewindstellingOntvangenGka()
    {
        return $this->beschikkingOnderBewindstellingOntvangenGka;
    }

    public function isBeschikkingOnderBewindstellingNvt()
    {
        return $this->beschikkingOnderBewindstellingNvt;
    }

    public function getBudgetbeheerOntvangenShv()
    {
        return $this->budgetbeheerOntvangenShv;
    }

    public function getBudgetbeheerOntvangenGka()
    {
        return $this->budgetbeheerOntvangenGka;
    }

    public function isBudgetbeheerNvt()
    {
        return $this->budgetbeheerNvt;
    }

    public function isBudgetbeheerPlangroep()
    {
        return $this->budgetbeheerPlangroep;
    }

    public function isBudgetbeheerCav()
    {
        return $this->budgetbeheerCav;
    }

    public function isBudgetbeheerFibu()
    {
        return $this->budgetbeheerFibu;
    }

    public function getBudgetbeheerOverig()
    {
        return $this->budgetbeheerOverig;
    }

    public function getGereserveerdeGeldenOntvangenShv()
    {
        return $this->gereserveerdeGeldenOntvangenShv;
    }

    public function getGereserveerdeGeldenOntvangenGka()
    {
        return $this->gereserveerdeGeldenOntvangenGka;
    }

    public function isGereserveerdeGeldenNvt()
    {
        return $this->gereserveerdeGeldenNvt;
    }

    public function getGereserveerdeGelden()
    {
        return $this->gereserveerdeGelden;
    }

    public function getOndertekendAanvraagFormulierOntvangenShv()
    {
        return $this->ondertekendAanvraagFormulierOntvangenShv;
    }

    public function getOndertekendAanvraagFormulierOntvangenGka()
    {
        return $this->ondertekendAanvraagFormulierOntvangenGka;
    }

    public function getAanvullendeInformatie()
    {
        return $this->aanvullendeInformatie;
    }

    public function getEnergieBedrijf()
    {
        return $this->energieBedrijf;
    }

    public function getEnergieBedrijfKlantnummer()
    {
        return $this->energieBedrijfKlantnummer;
    }

    /**
     * @return \DateTime
     */
    public function getEnergieBedrijfDatumOpname()
    {
        return $this->energieBedrijfDatumOpname;
    }

    public function getEnergieBedrijfT1()
    {
        return $this->energieBedrijfT1;
    }

    public function getEnergieBedrijfT2()
    {
        return $this->energieBedrijfT2;
    }

    public function getEnergieBedrijfGas()
    {
        return $this->energieBedrijfGas;
    }

    public function getWarmteBedrijf()
    {
        return $this->warmteBedrijf;
    }

    public function getWarmteBedrijfKlantnummer()
    {
        return $this->warmteBedrijfKlantnummer;
    }

    /**
     * @return \DateTime
     */
    public function getWarmteBedrijfDatumOpname()
    {
        return $this->warmteBedrijfDatumOpname;
    }

    public function getWarmteBedrijfOpname()
    {
        return $this->warmteBedrijfOpname;
    }

    public function getDrinkwaterKlantnummer()
    {
        return $this->drinkwaterKlantnummer;
    }

    /**
     * @return \DateTime
     */
    public function getDrinkwaterDatumOpname()
    {
        return $this->drinkwaterDatumOpname;
    }

    public function getDrinkwaterOpname()
    {
        return $this->drinkwaterOpname;
    }

    /**
     * @return string[]
     */
    public static function getContractOpties()
    {
        return [
            'Tijdelijk contract' => 'Tijdelijk contract',
            'Vast contract' => 'Vast contract',
        ];
    }

    /**
     * @param Dossier $dossier
     */
    public function setDossier(Dossier $dossier = null)
    {
        $oldDossier = $this->dossier;
        $this->dossier = $dossier;
        if ($oldDossier !== null && $oldDossier->getVoorlegger() === $this) {
            $oldDossier->setVoorlegger(null);
        }
        if ($dossier !== null && $dossier->getVoorlegger() !== $this) {
            $dossier->setVoorlegger(null);
        }
    }

    public function setLegitimatieOntvangenGka($legitimatieOntvangenGka)
    {
        $this->legitimatieOntvangenGka = $legitimatieOntvangenGka;
    }

    public function setLegitimatieOntvangenShv($legitimatieOntvangenShv)
    {
        $this->legitimatieOntvangenShv = $legitimatieOntvangenShv;
    }

    public function setVtlbOntvangenShv($vtlbOntvangenShv)
    {
        $this->vtlbOntvangenShv = $vtlbOntvangenShv;
    }

    public function setVtlbOntvangenGka($vtlbOntvangenGka)
    {
        $this->vtlbOntvangenGka = $vtlbOntvangenGka;
    }

    public function setVtlbBedrag($vtlbBedrag)
    {
        $this->vtlbBedrag = $vtlbBedrag;
    }

    public function setInkomstenspecificatieOntvangenShv($inkomstenspecificatieOntvangenShv)
    {
        $this->inkomstenspecificatieOntvangenShv = $inkomstenspecificatieOntvangenShv;
    }

    public function setInkomstenspecificatieOntvangenGka($inkomstenspecificatieOntvangenGka)
    {
        $this->inkomstenspecificatieOntvangenGka = $inkomstenspecificatieOntvangenGka;
    }

    public function setArbeidsovereenkomstOntvangenShv($arbeidsovereenkomstOntvangenShv)
    {
        $this->arbeidsovereenkomstOntvangenShv = $arbeidsovereenkomstOntvangenShv;
    }

    public function setArbeidsovereenkomstOntvangenGka($arbeidsovereenkomstOntvangenGka)
    {
        $this->arbeidsovereenkomstOntvangenGka = $arbeidsovereenkomstOntvangenGka;
    }

    public function setArbeidsovereenkomstNvt($arbeidsovereenkomstNvt)
    {
        $this->arbeidsovereenkomstNvt = $arbeidsovereenkomstNvt;
    }

    public function setArbeidsovereenkomstWerkgever($arbeidsovereenkomstWerkgever)
    {
        $this->arbeidsovereenkomstWerkgever = $arbeidsovereenkomstWerkgever;
    }

    public function setArbeidsovereenkomstContract($arbeidsovereenkomstContract)
    {
        $this->arbeidsovereenkomstContract = $arbeidsovereenkomstContract;
    }

    public function setArbeidsovereenkomstPartnerWerkgever($arbeidsovereenkomstPartnerWerkgever)
    {
        $this->arbeidsovereenkomstPartnerWerkgever = $arbeidsovereenkomstPartnerWerkgever;
    }

    public function setArbeidsovereenkomstPartnerContract($arbeidsovereenkomstPartnerContract)
    {
        $this->arbeidsovereenkomstPartnerContract = $arbeidsovereenkomstPartnerContract;
    }

    public function setBeschikkingUwvOntvangenShv($beschikkingUwvOntvangenShv)
    {
        $this->beschikkingUwvOntvangenShv = $beschikkingUwvOntvangenShv;
    }

    public function setBeschikkingUwvOntvangenGka($beschikkingUwvOntvangenGka)
    {
        $this->beschikkingUwvOntvangenGka = $beschikkingUwvOntvangenGka;
    }

    public function setBeschikkingUwvNvt($beschikkingUwvNvt)
    {
        $this->beschikkingUwvNvt = $beschikkingUwvNvt;
    }

    public function setBeschikkingInkomenUitWerk($beschikkingInkomenUitWerk)
    {
        $this->beschikkingInkomenUitWerk = $beschikkingInkomenUitWerk;
    }

    public function setBeschikkingUwvWw($beschikkingUwvWw)
    {
        $this->beschikkingUwvWw = $beschikkingUwvWw;
    }

    public function setBeschikkingUwvWia($beschikkingUwvWia)
    {
        $this->beschikkingUwvWia = $beschikkingUwvWia;
    }

    public function setBeschikkingUwvZw($beschikkingUwvZw)
    {
        $this->beschikkingUwvZw = $beschikkingUwvZw;
    }

    public function setBeschikkingUwvWajong($beschikkingUwvWajong)
    {
        $this->beschikkingUwvWajong = $beschikkingUwvWajong;
    }

    public function setBeschikkingGemeenteAmsterdamWPI($beschikkingGemeenteAmsterdamWPI)
    {
        $this->beschikkingGemeenteAmsterdamWPI = $beschikkingGemeenteAmsterdamWPI;
    }

    public function setBeschikkingSVBAOW($beschikkingSVBAOW)
    {
        $this->beschikkingSVBAOW = $beschikkingSVBAOW;
    }

    public function setBeschikkingSVBANW($beschikkingSVBANW)
    {
        $this->beschikkingSVBANW = $beschikkingSVBANW;
    }

    public function setBeschikkingGemeenteAmsterdamIOAW($beschikkingGemeenteAmsterdamIOAW)
    {
        $this->beschikkingGemeenteAmsterdamIOAW = $beschikkingGemeenteAmsterdamIOAW;
    }

    public function setBeschikkingUwvOverig($beschikkingUwvOverig)
    {
        $this->beschikkingUwvOverig = $beschikkingUwvOverig;
    }
    /**
     * @return int|null
     */
    public function getVerloningsDag(): ?int
    {
        return $this->verloningsDag;
    }

    /**
     * @param int|null $verloningsDag
     */
    public function setVerloningsDag(?int $verloningsDag): void
    {
        $this->verloningsDag = $verloningsDag;
    }

    public function setVoorlopigeTeruggaafBelastingdienstOntvangenShv($voorlopigeTeruggaafBelastingdienstOntvangenShv)
    {
        $this->voorlopigeTeruggaafBelastingdienstOntvangenShv = $voorlopigeTeruggaafBelastingdienstOntvangenShv;
    }

    public function setVoorlopigeTeruggaafBelastingdienstOntvangenGka($voorlopigeTeruggaafBelastingdienstOntvangenGka)
    {
        $this->voorlopigeTeruggaafBelastingdienstOntvangenGka = $voorlopigeTeruggaafBelastingdienstOntvangenGka;
    }

    public function setVoorlopigeTeruggaafBelastingdienstNvt($voorlopigeTeruggaafBelastingdienstNvt)
    {
        $this->voorlopigeTeruggaafBelastingdienstNvt = $voorlopigeTeruggaafBelastingdienstNvt;
    }

    public function setAlimentatieOntvangenShv($alimentatieOntvangenShv)
    {
        $this->alimentatieOntvangenShv = $alimentatieOntvangenShv;
    }

    public function setAlimentatieOntvangenGka($alimentatieOntvangenGka)
    {
        $this->alimentatieOntvangenGka = $alimentatieOntvangenGka;
    }

    public function setAlimentatieNvt($alimentatieNvt)
    {
        $this->alimentatieNvt = $alimentatieNvt;
    }

    public function setKostgeldOntvangenShv($kostgeldOntvangenShv)
    {
        $this->kostgeldOntvangenShv = $kostgeldOntvangenShv;
    }

    public function setKostgeldOntvangenGka($kostgeldOntvangenGka)
    {
        $this->kostgeldOntvangenGka = $kostgeldOntvangenGka;
    }

    public function setKostgeldNvt($kostgeldNvt)
    {
        $this->kostgeldNvt = $kostgeldNvt;
    }


    public function setHuurspecificatieOntvangenShv($huurspecificatieOntvangenShv)
    {
        $this->huurspecificatieOntvangenShv = $huurspecificatieOntvangenShv;
    }

    public function setHuurspecificatieOntvangenGka($huurspecificatieOntvangenGka)
    {
        $this->huurspecificatieOntvangenGka = $huurspecificatieOntvangenGka;
    }

    public function setHuurspecificatieNvt($huurspecificatieNvt)
    {
        $this->huurspecificatieNvt = $huurspecificatieNvt;
    }

    public function setPolisbladZorgverzekeringOntvangenShv($polisbladZorgverzekeringOntvangenShv)
    {
        $this->polisbladZorgverzekeringOntvangenShv = $polisbladZorgverzekeringOntvangenShv;
    }

    public function setPolisbladZorgverzekeringOntvangenGka($polisbladZorgverzekeringOntvangenGka)
    {
        $this->polisbladZorgverzekeringOntvangenGka = $polisbladZorgverzekeringOntvangenGka;
    }

    public function setPolisbladZorgverzekeringNvt($polisbladZorgverzekeringNvt)
    {
        $this->polisbladZorgverzekeringNvt = $polisbladZorgverzekeringNvt;
    }

    public function setAlimentatieEchtscheidingsconvenantOntvangenShv($alimentatieEchtscheidingsconvenantOntvangenShv)
    {
        $this->alimentatieEchtscheidingsconvenantOntvangenShv = $alimentatieEchtscheidingsconvenantOntvangenShv;
    }

    public function setAlimentatieEchtscheidingsconvenantOntvangenGka($alimentatieEchtscheidingsconvenantOntvangenGka)
    {
        $this->alimentatieEchtscheidingsconvenantOntvangenGka = $alimentatieEchtscheidingsconvenantOntvangenGka;
    }

    public function setAlimentatieEchtscheidingsconvenantNvt($alimentatieEchtscheidingsconvenantNvt)
    {
        $this->alimentatieEchtscheidingsconvenantNvt = $alimentatieEchtscheidingsconvenantNvt;
    }

    public function setOvereenkomstKinderopvangOntvangenShv($overeenkomstKinderopvangOntvangenShv)
    {
        $this->overeenkomstKinderopvangOntvangenShv = $overeenkomstKinderopvangOntvangenShv;
    }

    public function setOvereenkomstKinderopvangOntvangenGka($overeenkomstKinderopvangOntvangenGka)
    {
        $this->overeenkomstKinderopvangOntvangenGka = $overeenkomstKinderopvangOntvangenGka;
    }

    public function setOvereenkomstKinderopvangNvt($overeenkomstKinderopvangNvt)
    {
        $this->overeenkomstKinderopvangNvt = $overeenkomstKinderopvangNvt;
    }

    public function setKwijtscheldingGemeenteBelastingOntvangenShv($kwijtscheldingGemeenteBelastingOntvangenShv)
    {
        $this->kwijtscheldingGemeenteBelastingOntvangenShv = $kwijtscheldingGemeenteBelastingOntvangenShv;
    }

    public function setKwijtscheldingGemeenteBelastingNvt($kwijtscheldingGemeenteBelastingNvt)
    {
        $this->kwijtscheldingGemeenteBelastingNvt = $kwijtscheldingGemeenteBelastingNvt;
    }

    public function setKwijtscheldingGemeenteBelasting($kwijtscheldingGemeenteBelasting)
    {
        $this->kwijtscheldingGemeenteBelasting = $kwijtscheldingGemeenteBelasting;
    }

    public function setCorrigerenGemeenteBelastingOntvangenGka($corrigerenGemeenteBelastingOntvangenGka)
    {
        $this->corrigerenGemeenteBelastingOntvangenGka = $corrigerenGemeenteBelastingOntvangenGka;
    }

    public function setCorrigerenGemeenteBelastingNvt($corrigerenGemeenteBelastingNvt)
    {
        $this->corrigerenGemeenteBelastingNvt = $corrigerenGemeenteBelastingNvt;
    }

    public function setCorrigerenGemeenteBelasting($corrigerenGemeenteBelasting)
    {
        $this->corrigerenGemeenteBelasting = $corrigerenGemeenteBelasting;
    }

    public function setAutoNvt($autoNvt)
    {
        $this->autoNvt = $autoNvt;
    }

    public function setAutoTaxatieOntvangenShv($autoTaxatieOntvangenShv)
    {
        $this->autoTaxatieOntvangenShv = $autoTaxatieOntvangenShv;
    }

    public function setAutoTaxatieOntvangenGka($autoTaxatieOntvangenGka)
    {
        $this->autoTaxatieOntvangenGka = $autoTaxatieOntvangenGka;
    }

    public function setAutoTaxatieNvt($autoTaxatieNvt)
    {
        $this->autoTaxatieNvt = $autoTaxatieNvt;
    }

    public function setAutolastenKmWoonwerkverkeerOntvangenShv($autolastenKmWoonwerkverkeerOntvangenShv)
    {
        $this->autolastenKmWoonwerkverkeerOntvangenShv = $autolastenKmWoonwerkverkeerOntvangenShv;
    }

    public function setAutolastenKmWoonwerkverkeerOntvangenGka($autolastenKmWoonwerkverkeerOntvangenGka)
    {
        $this->autolastenKmWoonwerkverkeerOntvangenGka = $autolastenKmWoonwerkverkeerOntvangenGka;
    }

    public function setAutolastenKmWoonwerkverkeerNvt($autolastenKmWoonwerkverkeerNvt)
    {
        $this->autolastenKmWoonwerkverkeerNvt = $autolastenKmWoonwerkverkeerNvt;
    }

    public function setAutolastenKmWoonwerkverkeer($autolastenKmWoonwerkverkeer)
    {
        $this->autolastenKmWoonwerkverkeer = $autolastenKmWoonwerkverkeer;
    }

    public function setVerklaringWerkgeverOntvangenShv($verklaringWerkgeverOntvangenShv)
    {
        $this->verklaringWerkgeverOntvangenShv = $verklaringWerkgeverOntvangenShv;
    }

    public function setVerklaringWerkgeverOntvangenGka($verklaringWerkgeverOntvangenGka)
    {
        $this->verklaringWerkgeverOntvangenGka = $verklaringWerkgeverOntvangenGka;
    }

    public function setVerklaringWerkgeverNvt($verklaringWerkgeverNvt)
    {
        $this->verklaringWerkgeverNvt = $verklaringWerkgeverNvt;
    }

    public function setVrijwaringsbewijsOntvangenShv($vrijwaringsbewijsOntvangenShv)
    {
        $this->vrijwaringsbewijsOntvangenShv = $vrijwaringsbewijsOntvangenShv;
    }

    public function setVrijwaringsbewijsOntvangenGka($vrijwaringsbewijsOntvangenGka)
    {
        $this->vrijwaringsbewijsOntvangenGka = $vrijwaringsbewijsOntvangenGka;
    }

    public function setVrijwaringsbewijsNvt($vrijwaringsbewijsNvt)
    {
        $this->vrijwaringsbewijsNvt = $vrijwaringsbewijsNvt;
    }

    public function setSchuldenoverzichtOntvangenShv($schuldenoverzichtOntvangenShv)
    {
        $this->schuldenoverzichtOntvangenShv = $schuldenoverzichtOntvangenShv;
    }

    public function setSchuldenoverzichtOntvangenGka($schuldenoverzichtOntvangenGka)
    {
        $this->schuldenoverzichtOntvangenGka = $schuldenoverzichtOntvangenGka;
    }

    public function setVorderingenOntvangenShv($vorderingenOntvangenShv)
    {
        $this->vorderingenOntvangenShv = $vorderingenOntvangenShv;
    }

    public function setVorderingenOntvangenGka($vorderingenOntvangenGka)
    {
        $this->vorderingenOntvangenGka = $vorderingenOntvangenGka;
    }

    public function setInzageToetsingBkrOntvangenShv($inzageToetsingBkrOntvangenShv)
    {
        $this->inzageToetsingBkrOntvangenShv = $inzageToetsingBkrOntvangenShv;
    }

    public function setInzageToetsingBkrOntvangenGka($inzageToetsingBkrOntvangenGka)
    {
        $this->inzageToetsingBkrOntvangenGka = $inzageToetsingBkrOntvangenGka;
    }

    public function setStabilisatieovereenkomstOntvangenShv($stabilisatieovereenkomstOntvangenShv)
    {
        $this->stabilisatieovereenkomstOntvangenShv = $stabilisatieovereenkomstOntvangenShv;
    }

    public function setStabilisatieovereenkomstOntvangenGka($stabilisatieovereenkomstOntvangenGka)
    {
        $this->stabilisatieovereenkomstOntvangenGka = $stabilisatieovereenkomstOntvangenGka;
    }

    public function setStabilisatieovereenkomstNvt($stabilisatieovereenkomstNvt)
    {
        $this->stabilisatieovereenkomstNvt = $stabilisatieovereenkomstNvt;
    }

    public function setCjibOntvangenShv($cjibOntvangenShv)
    {
        $this->cjibOntvangenShv = $cjibOntvangenShv;
    }

    public function setCjibOntvangenGka($cjibOntvangenGka)
    {
        $this->cjibOntvangenGka = $cjibOntvangenGka;
    }

    public function setCjib($cjib)
    {
        $this->cjib = $cjib;
    }

    public function setCjibNvt($cjibNvt)
    {
        $this->cjibNvt = $cjibNvt;
    }

    public function setMeterstandenEnergieOntvangenShv($meterstandenEnergieOntvangenShv)
    {
        $this->meterstandenEnergieOntvangenShv = $meterstandenEnergieOntvangenShv;
    }

    public function setMeterstandenEnergieOntvangenGka($meterstandenEnergieOntvangenGka)
    {
        $this->meterstandenEnergieOntvangenGka = $meterstandenEnergieOntvangenGka;
    }

    public function setMeterstandenEnergieNvt($meterstandenEnergieNvt)
    {
        $this->meterstandenEnergieNvt = $meterstandenEnergieNvt;
    }

    public function setWaternetOntvangenShv($waternetOntvangenShv)
    {
        $this->waternetOntvangenShv = $waternetOntvangenShv;
    }

    public function setWaternetOntvangenGka($waternetOntvangenGka)
    {
        $this->waternetOntvangenGka = $waternetOntvangenGka;
    }

    public function setWaternetNvt($waternetNvt)
    {
        $this->waternetNvt = $waternetNvt;
    }

    public function setRetourbewijsModemOntvangenShv($retourbewijsModemOntvangenShv)
    {
        $this->retourbewijsModemOntvangenShv = $retourbewijsModemOntvangenShv;
    }

    public function setRetourbewijsModemOntvangenGka($retourbewijsModemOntvangenGka)
    {
        $this->retourbewijsModemOntvangenGka = $retourbewijsModemOntvangenGka;
    }

    public function setRetourbewijsModemNvt($retourbewijsModemNvt)
    {
        $this->retourbewijsModemNvt = $retourbewijsModemNvt;
    }

    public function setToelichtingAanvraagSchuldsaneringClientOntvangenShv($toelichtingAanvraagSchuldsaneringClientOntvangenShv)
    {
        $this->toelichtingAanvraagSchuldsaneringClientOntvangenShv = $toelichtingAanvraagSchuldsaneringClientOntvangenShv;
    }

    public function setToelichtingAanvraagSchuldsaneringClientOntvangenGka($toelichtingAanvraagSchuldsaneringClientOntvangenGka)
    {
        $this->toelichtingAanvraagSchuldsaneringClientOntvangenGka = $toelichtingAanvraagSchuldsaneringClientOntvangenGka;
    }

    public function setOntstaanVanSchulden($ontstaan)
    {
        $this->ontstaanVanSchulden = $ontstaan;
    }

    public function setInspanningsverplichting($inspanningsverplichting)
    {
        $this->inspanningsverplichting = $inspanningsverplichting;
    }

    public function setToelichtingAanvraagSchuldsaneringShvOntvangenShv($toelichtingAanvraagSchuldsaneringShvOntvangenShv)
    {
        $this->toelichtingAanvraagSchuldsaneringShvOntvangenShv = $toelichtingAanvraagSchuldsaneringShvOntvangenShv;
    }

    public function setToelichtingAanvraagSchuldsaneringShvOntvangenGka($toelichtingAanvraagSchuldsaneringShvOntvangenGka)
    {
        $this->toelichtingAanvraagSchuldsaneringShvOntvangenGka = $toelichtingAanvraagSchuldsaneringShvOntvangenGka;
    }

    public function setBeschikkingOnderBewindstellingOntvangenShv($beschikkingOnderBewindstellingOntvangenShv)
    {
        $this->beschikkingOnderBewindstellingOntvangenShv = $beschikkingOnderBewindstellingOntvangenShv;
    }

    public function setBeschikkingOnderBewindstellingOntvangenGka($beschikkingOnderBewindstellingOntvangenGka)
    {
        $this->beschikkingOnderBewindstellingOntvangenGka = $beschikkingOnderBewindstellingOntvangenGka;
    }

    public function setBeschikkingOnderBewindstellingNvt($beschikkingOnderBewindstellingNvt)
    {
        $this->beschikkingOnderBewindstellingNvt = $beschikkingOnderBewindstellingNvt;
    }

    public function setBudgetbeheerOntvangenShv($budgetbeheerOntvangenShv)
    {
        $this->budgetbeheerOntvangenShv = $budgetbeheerOntvangenShv;
    }

    public function setBudgetbeheerOntvangenGka($budgetbeheerOntvangenGka)
    {
        $this->budgetbeheerOntvangenGka = $budgetbeheerOntvangenGka;
    }

    public function setBudgetbeheerNvt($budgetbeheerNvt)
    {
        $this->budgetbeheerNvt = $budgetbeheerNvt;
    }

    public function setBudgetbeheerPlangroep($budgetbeheerPlangroep)
    {
        $this->budgetbeheerPlangroep = $budgetbeheerPlangroep;
    }

    public function setBudgetbeheerCav($budgetbeheerCav)
    {
        $this->budgetbeheerCav = $budgetbeheerCav;
    }

    public function setBudgetbeheerFibu($budgetbeheerFibu)
    {
        $this->budgetbeheerFibu = $budgetbeheerFibu;
    }

    public function setBudgetbeheerOverig($budgetbeheerOverig)
    {
        $this->budgetbeheerOverig = $budgetbeheerOverig;
    }

    public function setGereserveerdeGeldenOntvangenShv($gereserveerdeGeldenOntvangenShv)
    {
        $this->gereserveerdeGeldenOntvangenShv = $gereserveerdeGeldenOntvangenShv;
    }

    public function setGereserveerdeGeldenOntvangenGka($gereserveerdeGeldenOntvangenGka)
    {
        $this->gereserveerdeGeldenOntvangenGka = $gereserveerdeGeldenOntvangenGka;
    }

    public function setGereserveerdeGeldenNvt($gereserveerdeGeldenNvt)
    {
        $this->gereserveerdeGeldenNvt = $gereserveerdeGeldenNvt;
    }

    public function setGereserveerdeGelden($gereserveerdeGelden)
    {
        $this->gereserveerdeGelden = $gereserveerdeGelden;
    }

    public function setOndertekendAanvraagFormulierOntvangenShv($ondertekendAanvraagFormulierOntvangenShv)
    {
        $this->ondertekendAanvraagFormulierOntvangenShv = $ondertekendAanvraagFormulierOntvangenShv;
    }

    public function setOndertekendAanvraagFormulierOntvangenGka($ondertekendAanvraagFormulierOntvangenGka)
    {
        $this->ondertekendAanvraagFormulierOntvangenGka = $ondertekendAanvraagFormulierOntvangenGka;
    }

    public function setAanvullendeInformatie($aanvullendeInformatie)
    {
        $this->aanvullendeInformatie = $aanvullendeInformatie;
    }

    public function setEnergieBedrijf($energieBedrijf)
    {
        $this->energieBedrijf = $energieBedrijf;
    }

    public function setEnergieBedrijfKlantnummer($energieBedrijfKlantnummer)
    {
        $this->energieBedrijfKlantnummer = $energieBedrijfKlantnummer;
    }

    /**
     * @param \DateTime $energieBedrijfDatumOpname
     */
    public function setEnergieBedrijfDatumOpname(\DateTime $energieBedrijfDatumOpname = null)
    {
        $this->energieBedrijfDatumOpname = $energieBedrijfDatumOpname;
    }

    public function setEnergieBedrijfT1($energieBedrijfT1)
    {
        $this->energieBedrijfT1 = $energieBedrijfT1;
    }

    public function setEnergieBedrijfT2($energieBedrijfT2)
    {
        $this->energieBedrijfT2 = $energieBedrijfT2;
    }

    public function setEnergieBedrijfGas($energieBedrijfGas)
    {
        $this->energieBedrijfGas = $energieBedrijfGas;
    }

    public function setWarmteBedrijf($warmteBedrijf)
    {
        $this->warmteBedrijf = $warmteBedrijf;
    }

    public function setWarmteBedrijfKlantnummer($warmteBedrijfKlantnummer)
    {
        $this->warmteBedrijfKlantnummer = $warmteBedrijfKlantnummer;
    }

    /**
     * @param \DateTime $warmteBedrijfDatumOpname
     */
    public function setWarmteBedrijfDatumOpname(\DateTime $warmteBedrijfDatumOpname = null)
    {
        $this->warmteBedrijfDatumOpname = $warmteBedrijfDatumOpname;
    }

    public function setWarmteBedrijfOpname($warmteBedrijfOpname)
    {
        $this->warmteBedrijfOpname = $warmteBedrijfOpname;
    }

    public function setDrinkwaterKlantnummer($drinkwaterKlantnummer)
    {
        $this->drinkwaterKlantnummer = $drinkwaterKlantnummer;
    }

    /**
     * @param \DateTime $drinkwaterDatumOpname
     */
    public function setDrinkwaterDatumOpname(\DateTime $drinkwaterDatumOpname = null)
    {
        $this->drinkwaterDatumOpname = $drinkwaterDatumOpname;
    }

    public function setDrinkwaterOpname($drinkwaterOpname)
    {
        $this->drinkwaterOpname = $drinkwaterOpname;
    }

    public function gettoeslagenOntvangenShv()
    {
        return $this->toeslagenOntvangenShv;
    }

    public function settoeslagenOntvangenShv($toeslagenOntvangenShv)
    {
        $this->toeslagenOntvangenShv = $toeslagenOntvangenShv;
    }

    public function getToeslagenOntvangenGka()
    {
        return $this->toeslagenOntvangenGka;
    }

    public function setToeslagenOntvangenGka($toeslagenOntvangenGka)
    {
        $this->toeslagenOntvangenGka = $toeslagenOntvangenGka;
    }

    public function isToeslagenNvt()
    {
        return $this->toeslagenNvt;
    }

    public function setToeslagenNvt($toeslagenNvt)
    {
        $this->toeslagenNvt = $toeslagenNvt;
    }

    public function isToeslagenHuur()
    {
        return $this->toeslagenHuur;
    }

    public function setToeslagenHuur($toeslagenHuur)
    {
        $this->toeslagenHuur = $toeslagenHuur;
    }

    public function isToeslagenZorg()
    {
        return $this->toeslagenZorg;
    }

    public function setToeslagenZorg($toeslagenZorg)
    {
        $this->toeslagenZorg = $toeslagenZorg;
    }

    public function isToeslagenKinderopvang()
    {
        return $this->toeslagenKinderopvang;
    }

    public function setToeslagenKinderopvang($toeslagenKinderopvang)
    {
        $this->toeslagenKinderopvang = $toeslagenKinderopvang;
    }

    public function isToeslagenKindgebondenBudget()
    {
        return $this->toeslagenKindgebondenBudget;
    }

    public function setToeslagenKindgebondenBudget($toeslagenKindgebondenBudget)
    {
        $this->toeslagenKindgebondenBudget = $toeslagenKindgebondenBudget;
    }

    /**
     * @return \DateTime|null
     */
    public function getArbeidsovereenkomstEinddatum(): ?\DateTime
    {
        return $this->arbeidsovereenkomstEinddatum;
    }

    /**
     * @param \Date|null $arbeidsovereenkomstEinddatum
     * @return Voorlegger
     */
    public function setArbeidsovereenkomstEinddatum(?\DateTime $arbeidsovereenkomstEinddatum): Voorlegger
    {
        $this->arbeidsovereenkomstEinddatum = $arbeidsovereenkomstEinddatum;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getArbeidsovereenkomstPartnerEinddatum(): ?\DateTime
    {
        return $this->arbeidsovereenkomstPartnerEinddatum;
    }

    /**
     * @param \DateTime|null $arbeidsovereenkomstPartnerEinddatum
     * @return Voorlegger
     */
    public function setArbeidsovereenkomstPartnerEinddatum(?\DateTime $arbeidsovereenkomstPartnerEinddatum): Voorlegger
    {
        $this->arbeidsovereenkomstPartnerEinddatum = $arbeidsovereenkomstPartnerEinddatum;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getAangifteBelastingdienst(): ?bool
    {
        return $this->aangifteBelastingdienst;
    }

    /**
     * @param bool|null $aangifteBelastingdienst
     * @return Voorlegger
     */
    public function setAangifteBelastingdienst(?bool $aangifteBelastingdienst): Voorlegger
    {
        $this->aangifteBelastingdienst = $aangifteBelastingdienst;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getAangifteBelastingdienstShv(): ?int
    {
        return $this->aangifteBelastingdienstShv;
    }

    /**
     * @param int|null $aangifteBelastingdienstShv
     * @return Voorlegger
     */
    public function setAangifteBelastingdienstShv(?int $aangifteBelastingdienstShv): Voorlegger
    {
        $this->aangifteBelastingdienstShv = $aangifteBelastingdienstShv;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getAangifteBelastingdienstGka(): ?int
    {
        return $this->aangifteBelastingdienstGka;
    }

    /**
     * @param int|null $aangifteBelastingdienstGka
     * @return Voorlegger
     */
    public function setAangifteBelastingdienstGka(?int $aangifteBelastingdienstGka): Voorlegger
    {
        $this->aangifteBelastingdienstGka = $aangifteBelastingdienstGka;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getAangifteBelastingdienstNvt(): ?bool
    {
        return $this->aangifteBelastingdienstNvt;
    }

    /**
     * @param bool|null $aangifteBelastingdienstNvt
     * @return Voorlegger
     */
    public function setAangifteBelastingdienstNvt(?bool $aangifteBelastingdienstNvt): Voorlegger
    {
        $this->aangifteBelastingdienstNvt = $aangifteBelastingdienstNvt;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getJongerenSchuldenvrijeStart(): ?bool
    {
        return $this->jongerenSchuldenvrijeStart;
    }

    /**
     * @param bool|null $jongerenSchuldenvrijeStart
     * @return Voorlegger
     */
    public function setJongerenSchuldenvrijeStart(?bool $jongerenSchuldenvrijeStart): Voorlegger
    {
        $this->jongerenSchuldenvrijeStart = $jongerenSchuldenvrijeStart;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getKindregeling(): ?bool
    {
        return $this->kindregeling;
    }

    /**
     * @param bool|null $kindregeling
     */
    public function setKindregeling(?bool $kindregeling): void
    {
        $this->kindregeling = $kindregeling;
    }

    /**
     * @return bool|null
     */
    public function getSaneringsKrediet(): ?bool
    {
        return $this->saneringsKrediet;
    }

    /**
     * @param bool|null $saneringsKrediet
     */
    public function setSaneringsKrediet(?bool $saneringsKrediet): void
    {
        $this->saneringsKrediet = $saneringsKrediet;
    }

    /**
     * @return bool|null
     */
    public function getPrincipebeslissing(): ?bool
    {
        return $this->principebeslissing;
    }

    /**
     * @param bool|null $principebeslissing
     */
    public function setPrincipebeslissing(?bool $principebeslissing): void
    {
        $this->principebeslissing = $principebeslissing;
    }

    /**
     * @return bool|null
     */
    public function getSchuldenOpDeWerkvloer(): ?bool
    {
        return $this->schuldenOpDeWerkvloer;
    }

    /**
     * @param bool|null $schuldenOpDeWerkvloer
     */
    public function setSchuldenOpDeWerkvloer(?bool $schuldenOpDeWerkvloer): void
    {
        $this->schuldenOpDeWerkvloer = $schuldenOpDeWerkvloer;
    }

    /**
     * @return bool|null
     */
    public function getHerfinanciering(): ?bool
    {
        return $this->herfinancering;
    }

    /**
     * @param bool|null $herfinancering
     */
    public function setHerfinanciering(?bool $herfinancering): void
    {
        $this->herfinancering = $herfinancering;
    }

    /**
     * @return string|null
     */
    public function getJssAdviseurNaam(): ?string
    {
        return $this->jssAdviseurNaam;
    }

    /**
     * @param string|null $jssAdviseurNaam
     */
    public function setJssAdviseurNaam(?string $jssAdviseurNaam): void
    {
        $this->jssAdviseurNaam = $jssAdviseurNaam;
    }

    /**
     * @return string|null
     */
    public function getJssAdviseurTelefoon(): ?string
    {
        return $this->jssAdviseurTelefoon;
    }

    /**
     * @param string|null $jssAdviseurTelefoon
     */
    public function setJssAdviseurTelefoon(?string $jssAdviseurTelefoon): void
    {
        $this->jssAdviseurTelefoon = $jssAdviseurTelefoon;
    }

    /**
     * @return string|null
     */
    public function getJssAdviseurEmail(): ?string
    {
        return $this->jssAdviseurEmail;
    }

    /**
     * @param string|null $jssAdviseurEmail
     */
    public function setJssAdviseurEmail(?string $jssAdviseurEmail): void
    {
        $this->jssAdviseurEmail = $jssAdviseurEmail;
    }
}
