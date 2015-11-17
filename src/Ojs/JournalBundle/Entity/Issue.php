<?php

namespace Ojs\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as JMS;
use Ojs\AnalyticsBundle\Entity\IssueStatistic;
use Ojs\CoreBundle\Annotation\Display;
use Ojs\CoreBundle\Entity\AnalyticsTrait;
use Ojs\CoreBundle\Entity\GenericEntityTrait;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Prezent\Doctrine\Translatable\Entity\AbstractTranslatable;

/**
 * Issue
 * @GRID\Source(columns="id,journal.title,volume,number,title,year,datePublished")
 * @JMS\ExclusionPolicy("all")
 */
class Issue extends AbstractTranslatable
{
    use GenericEntityTrait;
    use AnalyticsTrait;

    /**
     * @var integer
     * @GRID\Column(title="id")
     * @JMS\Expose
     * @JMS\Groups({"JournalDetail","IssueDetail"})
     */
    protected $id;
    /**
     * @Prezent\Translations(targetEntity="Ojs\JournalBundle\Entity\IssueTranslation")
     */
    protected $translations;
    /**
     *
     * @var Journal
     * @JMS\Groups({"IssueDetail"})
     * @JMS\Expose
     * @JMS\Groups({"JournalDetail","IssueDetail"})
     */
    private $journal;
    /**
     * @var string
     * @GRID\Column(title="volume")
     * @JMS\Expose
     * @JMS\Groups({"JournalDetail","IssueDetail"})
     */
    private $volume;
    /**
     * @var string
     * @GRID\Column(title="number")
     * @JMS\Expose
     * @JMS\Groups({"JournalDetail","IssueDetail"})
     */
    private $number;
    /**
     * @var string
     * @GRID\Column(title="title")
     * @JMS\Groups({"JournalDetail","IssueDetail"})
     */
    private $title;
    /**
     * @var string
     *             cover image path
     * @JMS\Expose
     * @JMS\Groups({"IssueDetail"})
     * @Display\Image(filter="issue_cover")
     */
    private $cover;
    /**
     * @var boolean
     * @GRID\Column(title="special")
     * @JMS\Expose
     * @JMS\Groups({"IssueDetail"})
     */
    private $special = false;
    /**
     * @var string
     * @JMS\Expose
     * @JMS\Groups({"JournalDetail","IssueDetail"})
     */
    private $description;
    /**
     * @var string
     * @GRID\Column(title="year")
     * @JMS\Expose
     * @JMS\Groups({"JournalDetail","IssueDetail"})
     */
    private $year;
    /**
     * @var \DateTime
     * @GRID\Column(title="publishdate")
     * @JMS\Expose
     * @JMS\Groups({"IssueDetail"})
     */
    private $datePublished;
    /**
     * @var ArrayCollection|Article[]
     * @JMS\Expose
     * @JMS\Groups({"IssueDetail","JournalDetail"})
     */
    private $articles;
    /**
     * @var string
     * @JMS\Expose
     * @JMS\Groups({"IssueDetail"})
     * @Display\Image(filter="issue_header")
     */
    private $header;
    /**
     * @var ArrayCollection|Section[]
     * @JMS\Groups({"IssueDetail"})
     */
    private $sections;
    /**
     * @var ArrayCollection|IssueStatistic[]
     */
    private $statistics;

    /**
     * @var string
     */
    private $publicURI;
    /** @var  boolean */
    private $published = false;
    /** @var  boolean */
    private $public = false;
    /**
     * @var boolean
     * @JMS\Expose
     * @JMS\Groups({"IssueDetail"})
     */
    private $supplement = false;
    /**
     * @var string
     * @JMS\Expose
     * @JMS\Groups({"IssueDetail"})
     * @Display\File(path="issuefiles")
     */
    private $fullFile;
    /**
     * @var ArrayCollection|IssueFile[]
     * @JMS\Expose
     */
    private $issueFiles;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->sections = new ArrayCollection();
        $this->issueFiles = new ArrayCollection();
        $this->translations = new ArrayCollection();
        $this->statistics = new ArrayCollection();
    }

    /**
     * Get journal
     *
     * @return Journal
     */
    public function getJournal()
    {
        return $this->journal;
    }

    /**
     * Set journal
     * @param  Journal $journal
     * @return Issue
     */
    public function setJournal($journal)
    {
        $this->journal = $journal;

        return $this;
    }

    /**
     * Get volume
     *
     * @return string
     */
    public function getVolume()
    {
        return $this->volume;
    }

    /**
     * Set volume
     *
     * @param  string $volume
     * @return Issue
     */
    public function setVolume($volume)
    {
        $this->volume = $volume;

        return $this;
    }

    /**
     * Get number
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set number
     *
     * @param  string $number
     * @return Issue
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get cover image path
     *
     * @return string
     */
    public function getCover()
    {
        return $this->cover;
    }

    /**
     * Set cover image path
     *
     * @param  string $cover
     * @return Issue
     */
    public function setCover($cover)
    {
        $this->cover = $cover;

        return $this;
    }

    /**
     * is special
     *
     * @return boolean
     */
    public function getSpecial()
    {
        return $this->special;
    }

    public function isSpecial()
    {
        return (bool)$this->special;
    }

    /**
     * Set is special
     *
     * @param  boolean $special
     * @return Issue
     */
    public function setSpecial($special)
    {
        $this->special = $special;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->translate()->getDescription();
    }

    /**
     * Set description
     *
     * @param  string $description
     * @return Issue
     */
    public function setDescription($description)
    {
        $this->translate()->setDescription($description);

        return $this;
    }

    /**
     * Translation helper method
     * @param null $locale
     * @return mixed|null|\Ojs\JournalBundle\Entity\IssueTranslation
     */
    public function translate($locale = null)
    {
        if (null === $locale) {
            $locale = $this->currentLocale;
        }
        if (!$locale) {
            throw new \RuntimeException('No locale has been set and currentLocale is empty');
        }
        if ($this->currentTranslation && $this->currentTranslation->getLocale() === $locale) {
            return $this->currentTranslation;
        }
        $defaultTranslation = $this->translations->get($this->getDefaultLocale());
        if (!$translation = $this->translations->get($locale)) {
            $translation = new IssueTranslation();
            if (!is_null($defaultTranslation)) {
                $translation->setTitle($defaultTranslation->getTitle());
                $translation->setDescription($defaultTranslation->getDescription());
            }
            $translation->setLocale($locale);
            $this->addTranslation($translation);
        }
        $this->currentTranslation = $translation;

        return $translation;
    }

    /**
     * Get year
     *
     * @return string
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set year
     *
     * @param  string $year
     * @return Issue
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get datePublished
     *
     * @return \DateTime
     */
    public function getDatePublished()
    {
        return $this->datePublished;
    }

    /**
     * Set datePublished
     *
     * @param  \DateTime $datePublished
     * @return Issue
     */
    public function setDatePublished($datePublished)
    {
        $this->datePublished = $datePublished;

        return $this;
    }

    /**
     * Add article
     *
     * @param  Article $article
     * @return Issue
     */
    public function addArticle(Article $article)
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
            $article->setIssue($this);
        }

        return $this;
    }

    /**
     * Remove article
     *
     * @param Article $article
     * @return Issue
     */
    public function removeArticle(Article $article)
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            $article->setIssue(null);
        }
    }

    /**
     * Get articles
     *
     * @return ArrayCollection|Article[]
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * Add section to issue
     * @param  Section $section
     * @return $this
     */
    public function addSection(Section $section)
    {

        $this->sections[] = $section;

        return $this;
    }

    /**
     * Remove section from issue
     *
     * @param Section $section
     */
    public function removeSection(Section $section)
    {
        $this->articles->removeElement($section);
    }

    /**
     * Get sections
     *
     * @return ArrayCollection|Section[]
     */
    public function getSections()
    {
        return $this->sections;
    }

    /**
     * @return string
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @param  string $header
     * @return $this
     */
    public function setHeader($header)
    {
        $this->header = $header;

        return $this;
    }

    /**
     * Return formatted issue title and id eg. :  "Issue title [#id]"
     * @return string
     */
    public function __toString()
    {
        return $this->getTitle()."[#{$this->getId()}]";
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        if(!is_null($this->translate()->getTitle())){
            return $this->translate()->getTitle();
        }else{
            return $this->translations->first()->getTitle();
        }
    }

    /**
     * Set title
     *
     * @param  string $title
     * @return Issue
     */
    public function setTitle($title)
    {
        $this->translate()->setTitle($title);

        return $this;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return boolean
     */
    public function isPublished()
    {
        return $this->published;
    }

    /**
     * @param  boolean $published
     * @return $this
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isPublic()
    {
        return $this->public;
    }

    /**
     * @param  boolean $public
     * @return $this
     */
    public function setPublic($public)
    {
        $this->public = $public;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isSupplement()
    {
        return $this->supplement;
    }

    /**
     * Get supplement
     *
     * @return boolean
     */
    public function getSupplement()
    {
        return $this->supplement;
    }

    /**
     * @param  boolean $supplement
     * @return $this
     */
    public function setSupplement($supplement)
    {
        $this->supplement = $supplement;

        return $this;
    }

    /**
     * @return string
     */
    public function getFullFile()
    {
        return $this->fullFile;
    }

    /**
     * @param  string $fullFile
     * @return $this
     */
    public function setFullFile($fullFile)
    {
        $this->fullFile = $fullFile;

        return $this;
    }

    /**
     * @return ArrayCollection|IssueFile[]
     */
    public function getIssueFiles()
    {
        return $this->issueFiles;
    }

    /**
     * @param IssueFile $issueFile
     * @return $this
     */
    public function addIssueFile(IssueFile $issueFile)
    {
        if(!$this->issueFiles->contains($issueFile)){
            $this->issueFiles->add($issueFile);
            $issueFile->setIssue($this);
        }

        return $this;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Issue
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return Issue
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * @return ArrayCollection|IssueStatistic[]
     */
    public function getStatistics()
    {
        return $this->statistics;
    }

    /**
     * @param ArrayCollection|IssueStatistic[] $statistics
     */
    public function setStatistics($statistics)
    {
        $this->statistics = $statistics;
    }

    /**
     * @return string
     */
    public function getPublicURI()
    {
        return $this->publicURI;
    }

    /**
     * @param string $publicURI
     */
    public function setPublicURI($publicURI)
    {
        $this->publicURI = $publicURI;
    }
}
