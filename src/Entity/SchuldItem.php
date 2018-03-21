<?php

namespace GemeenteAmsterdam\FixxxSchuldhulp\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table
 */
class SchuldItem
{
    const TYPE_CONCURRENT = 'concurrent';
    const TYPE_PREFERENT = 'preferent';

    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Dossier
     * @ORM\ManyToOne(targetEntity="Dossier", inversedBy="schuldItems")
     * @ORM\JoinColumn(name="dossier_id", referencedColumnName="id", nullable=false)
     */
    private $dossier;

    /**
     * @var Gebruiker
     * @ORM\ManyToOne(targetEntity="Gebruiker")
     * @ORM\JoinColumn(name="aanmaker_id", referencedColumnName="id", nullable=false)
     */
    private $aanmaker;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $aanmaakDatumTijd;

    /**
     * @var Gebruiker
     * @ORM\ManyToOne(targetEntity="Gebruiker")
     * @ORM\JoinColumn(name="aanmaker_id", referencedColumnName="id", nullable=false)
     */
    private $bewerker;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $bewerkDatumTijd;

    /*/*
     * @var SchuldItemHistorie
     * @ORM\OneToMany(targetEntity="SchuldItemHistorie", mappedBy="SchuldItem", cascade={"persist"})
     */
    //private $historie;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $verwijderd;

    /**
     * @var Schuldeiser
     * @ORM\ManyToOne(targetEntity="Schuldeiser", cascade={"persist"})
     * @ORM\JoinColumn(name="schuldeiser_id", referencedColumnName="id")
     * @Assert\NotBlank
     */
    private $schuldeiser;

    /**
     * @var Schuldeiser
     * @ORM\ManyToOne(targetEntity="Schuldeiser", cascade={"persist"})
     * @ORM\JoinColumn(name="schuldeiser_id", referencedColumnName="id")
     */
    private $incassant;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank
     * @Assert\Length(min=1, max=255)
     */
    private $referentie;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Length(max=10000)
     */
    private $opmerkingen;

    /**
     * @var string
     * @ORM\Column(type="string", length=15, nullable=false)
     * @Assert\NotBlank
     * @Assert\Choice(choices={"concurrent", "preferent"})
     */
    private $type;

    /**
     * @var float
     * @ORM\Column(type="decimal", precision=5, scale=2, nullable=true)
     * @Assert\NotBlank
     * @Assert\Type("numeric")
     */
    private $bedrag;

    /**
     * @var \DateTime
     * @ORM\Column(type="date", nullable=false)
     * @Assert\NotBlank
     */
    private $vaststelDatum;

    /**
     * @var DossierDocument[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="DossierDocument", mappedBy="schuldItem")
     * @ORM\OrderBy({"id"="ASC"})
     */
    private $dossierDocumenten;

    public function __construct()
    {
        $this->type = self::TYPE_CONCURRENT;
        $this->historie = new ArrayCollection();
        $this->dossierDocumenten = new ArrayCollection();
        $this->aanmaakDatumTijd = new \DateTime();
        $this->bewerkDatumTijd = new \DateTime();
        $this->vaststelDatum = new \DateTime();
        $this->verwijderd = false;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDossier()
    {
        return $this->dossier;
    }

    public function setDossier(Dossier $dossier)
    {
        if ($this->dossier !== $dossier) {
            $this->dossier = $dossier;
        }
        if ($dossier->hasSchuldItem($this) === false) {
            $dossier->addSchuldItem($this);
        }
    }

    public function getAanmaker()
    {
        return $this->aanmaker;
    }

    public function setAanmaker(Gebruiker $aanmaker)
    {
        $this->aanmaker = $aanmaker;
    }

    public function getAanmaakDatumTijd()
    {
        return $this->aanmaakDatumTijd;
    }

    public function setAanmaakDatumTijd(\DateTime $aanmaakDatumTijd)
    {
        $this->aanmaakDatumTijd = $aanmaakDatumTijd;
    }

    public function getBewerker()
    {
        return $this->bewerker;
    }

    public function setBewerker(Gebruiker $bewerker)
    {
        $this->bewerker = $bewerker;
    }

    public function getBewerkDatumTijd()
    {
        return $this->bewerkDatumTijd;
    }

    public function setBewerkDatumTijd(\DateTime $bewerkDatumTijd)
    {
        $this->bewerkDatumTijd = $bewerkDatumTijd;
    }

    public function getHistorie()
    {
        return $this->historie;
    }

    ///////

    public function isVerwijderd()
    {
        return $this->verwijderd;
    }

    public function setVerwijderd($verwijderd)
    {
        $this->verwijderd = $verwijderd;
    }

    public function getSchuldeiser()
    {
        return $this->schuldeiser;
    }

    public function setSchuldeiser(Schuldeiser $schuldeiser)
    {
        $this->schuldeiser = $schuldeiser;
    }

    public function getIncassant()
    {
        return $this->incassant;
    }

    public function setIncassant(Schuldeiser $incassant)
    {
        $this->incassant = $incassant;
    }

    public function getReferentie()
    {
        return $this->referentie;
    }

    public function setReferentie($referentie)
    {
        $this->referentie = $referentie;
    }

    public function getOpmerkingen()
    {
        return $this->opmerkingen;
    }

    public function setOpmerkingen($opmerkingen)
    {
        $this->opmerkingen = $opmerkingen;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getBedrag()
    {
        return $this->bedrag;
    }

    public function setBedrag($bedrag)
    {
        $this->bedrag = $bedrag;
    }

    public function getVaststelDatum()
    {
        return $this->vaststelDatum;
    }

    public function setVaststelDatum(\DateTime $vaststelDatum)
    {
        $this->vaststelDatum = $vaststelDatum;
    }

    public function getDossierDocumenten()
    {
        return  $this->dossierDocumenten;
    }

    public function hasDossierDocument(DossierDocument $dossierDocument)
    {
        return $this->dossierDocumenten->contains($dossierDocument);
    }

    public function addDossierDocumenten(DossierDocument $dossierDocument)
    {
        if ($this->hasDossierDocument($dossierDocument) === false) {
            $this->dossierDocumenten->add($dossierDocument);
        }
    }

    public function removeDossierDocument(DossierDocument $dossierDocument)
    {
        if ($this->hasDossierDocument($dossierDocument) === true) {
            $this->dossierDocumenten->removeElement($dossierDocument);
        }
    }

    public static function getTypes()
    {
        return [
            SchuldItem::TYPE_CONCURRENT => SchuldItem::TYPE_CONCURRENT,
            SchuldItem::TYPE_PREFERENT => SchuldItem::TYPE_PREFERENT,
        ];
    }
}