<?php

namespace Ojs\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation as JMS;
use Ojs\AnalyticsBundle\Entity\JournalStatistic;
use Ojs\CoreBundle\Entity\GenericEntityTrait;
use Ojs\LocationBundle\Entity\Country;
use Ojs\UserBundle\Entity\User;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Prezent\Doctrine\Translatable\Entity\AbstractTranslatable;

/**
 * Journal
 * @JMS\ExclusionPolicy("all")
 * @GRID\Source(columns="id,title,issn,eissn,country.name,publisher.name")
 */
class Journal extends AbstractTranslatable
{
    use GenericEntityTrait;

    /** @var  boolean */
    protected $setupFinished;

    /** @var  string */
    protected $footerText;

    /**
     * @var integer
     * @JMS\Expose
     * @JMS\Groups({"JournalDetail","IssueDetail"})
     */
    protected $id;
    /**
     * @Prezent\Translations(targetEntity="Ojs\JournalBundle\Entity\JournalTranslation")
     */
    protected $translations;
    /**
     * @var string
     * @JMS\Expose
     * @JMS\Groups({"JournalDetail","IssueDetail"})
     * @Grid\Column(title="Title")
     */
    private $title;
    /**
     * @var string
     * @JMS\Expose
     * @JMS\Groups({"JournalDetail"})
     */
    private $titleAbbr;
    /**
     * @var string
     * @JMS\Expose
     * @JMS\Groups({"JournalDetail"})
     */
    private $titleTransliterated;
    /**
     * @var string
     * @JMS\Expose
     * @JMS\Groups({"JournalDetail"})
     */
    private $subtitle;
    /**
     * @var string
     * @JMS\Expose
     * @JMS\Groups({"JournalDetail"})
     */
    private $path;
    /**
     * @var string
     * @JMS\Expose
     * @JMS\Groups({"JournalDetail"})
     */
    private $domain;
    /**
     * @var string
     * @JMS\Expose
     * @JMS\Groups({"JournalDetail","IssueDetail"})
     */
    private $issn;
    /**
     * @var string
     * @JMS\Expose
     * @JMS\Groups({"JournalDetail","IssueDetail"})
     */
    private $eissn;
    /**
     * @var \DateTime
     * @JMS\Expose
     * @JMS\Groups({"JournalDetail"})
     */
    private $founded;
    /**
     * @var string
     * @JMS\Expose
     * @JMS\Groups({"JournalDetail"})
     */
    private $url;
    /**
     * @var string
     * @JMS\Expose
     * @JMS\Groups({"JournalDetail"})
     */
    private $address;
    /**
     * @var string
     * @JMS\Expose
     * @JMS\Groups({"JournalDetail"})
     */
    private $phone;
    /**
     * @var string
     * @JMS\Expose
     * @JMS\Groups({"JournalDetail"})
     */
    private $email;
    /**
     * @var Country
     * @JMS\Expose
     * @JMS\Groups({"JournalDetail"})
     * @Grid\Column(field="country.name", title="country")
     */
    private $country;
    /**
     * @var boolean
     * @JMS\Expose
     */
    private $published;
    /**
     * @var integer
     * @JMS\Expose
     */
    private $status;
    /**
     * @var string
     * @JMS\Expose
     * @JMS\Groups({"JournalDetail"})
     */
    private $image;
    /**
     * @var string
     * @JMS\Expose
     * @JMS\Groups({"JournalDetail"})
     */
    private $header;
    /**
     * @var string
     * @JMS\Expose
     */
    private $googleAnalyticsId;
    /**
     * @var string
     * @JMS\Expose
     */
    private $slug;
    /**
     * @var JournalTheme
     * @JMS\Expose
     * @JMS\Groups({"JournalDetail"})
     */
    private $theme;
    /**
     * @var JournalDesign
     * @JMS\Expose
     * @JMS\Groups({"JournalDetail"})
     */
    private $design;
    /**
     * @var boolean
     * @JMS\Expose
     */
    private $configured;

    /**
     * @var ArrayCollection|Article[]
     * @JMS\Expose
     * @JMS\Groups({"IssueDetail"})
     */
    private $articles;
    /**
     * @var ArrayCollection|Issue[]
     * @JMS\Expose
     * @JMS\Groups({"JournalDetail"})
     */
    private $issues;
    /**
     * @var ArrayCollection|Board[]
     */
    private $boards;
    /**
     * @var ArrayCollection|Lang[]
     * @JMS\Expose
     * @JMS\Groups({"JournalDetail"})
     */
    private $languages;
    /**
     * @var ArrayCollection|Period[]
     * @JMS\Expose
     * @JMS\Groups({"JournalDetail"})
     */
    private $periods;
    /**
     * @var string
     */
    private $languageCodeSet;
    /**
     * @var Collection
     * @JMS\Expose
     * @JMS\Groups({"JournalDetail"})
     */
    private $subjects;
    /**
     * @var Collection
     * @JMS\Groups({"JournalDetail"})
     */
    private $sections;
    /**
     *
     * arbitrary settings
     * @var ArrayCollection|JournalSetting[]
     */
    private $settings;
    /**
     * @var Publisher
     * @JMS\Expose
     * @JMS\Groups({"JournalDetail"})
     * @Grid\Column(field="publisher.name", title="publisher")
     */
    private $publisher;
    /**
     * @var Collection
     * @JMS\Expose
     */
    private $journalThemes;
    /**
     * @var JournalDesign Collection
     * @JMS\Expose
     */
    private $journalDesigns;
    /**
     * @var Collection
     */
    private $bannedUsers;
    /**
     * @var string
     * @JMS\Expose
     * @JMS\Groups({"JournalDetail","IssueDetail"})
     */
    private $description;
    /**
     * @var string
     * @JMS\Expose
     * @JMS\Groups({"JournalDetail"})
     */
    private $logo;
    /**
     * @var ArrayCollection|JournalIndex[]
     * @JMS\Expose
     * @JMS\Groups({"JournalDetail"})
     */
    private $journalIndexs;
    /**
     * @var ArrayCollection|SubmissionChecklist[]
     * @JMS\Expose
     * @JMS\Groups({"JournalDetail"})
     */
    private $submissionChecklist;
    /**
     * @var ArrayCollection|JournalSubmissionFile[]
     * @JMS\Expose
     * @JMS\Groups({"JournalDetail"})
     */
    private $journalSubmissionFiles;
    /**
     * @var int
     * @JMS\Expose
     * @JMS\Groups({"JournalDetail","IssueDetail"})
     */
    private $viewCount;
    /**
     * @var int
     * @JMS\Expose
     * @JMS\Groups({"JournalDetail","IssueDetail"})
     */
    private $downloadCount;
    /**
     * @var boolean
     * @JMS\Expose
     * @JMS\Groups({"JournalDetail","IssueDetail"})
     */
    private $printed;
    /**
     * Object public URI
     * @var string
     */
    private $publicURI;
    /** @var Collection */
    private $journalUsers;

    /** @var ArrayCollection */
    private $journalContacts;

    /**
     * @var Lang
     */
    private $mandatoryLang;

    /**
     * @var ArrayCollection|JournalAnnouncement[]
     */
    private $announcements;

    /**
     * @var ArrayCollection|JournalStatistic[]
     */
    private $statistics;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->issues = new ArrayCollection();
        $this->boards = new ArrayCollection();
        $this->languages = new ArrayCollection();
        $this->sections = new ArrayCollection();
        $this->subjects = new ArrayCollection();
        $this->journalThemes = new ArrayCollection();
        $this->translations = new ArrayCollection();
        $this->journalDesigns = new ArrayCollection();
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
     * @param  string $settingName
     * @param  string $value
     * @return Journal
     */
    public function addSetting($settingName, $value)
    {
        $this->settings[$settingName] = new JournalSetting($settingName, $value, $this);

        return $this;
    }

    /**
     * @param  string $settingName
     * @return bool
     */
    public function hasSetting($settingName)
    {
        foreach ($this->settings as $setting) {
            if ($setting->getSetting() === $settingName) {
                return true;
            }
        }

        return false;
    }

    /**
     *
     * @param  string $settingName
     * @return JournalSetting|boolean
     */
    public function getAttribute($settingName)
    {
        return $this->getSetting($settingName);
    }

    /**
     *
     * @param  string $settingName
     * @return JournalSetting|boolean
     */
    public function getSetting($settingName)
    {
        return isset($this->settings[$settingName]) ? $this->settings[$settingName] : false;
    }

    /**
     * @param  Section $section
     * @return Journal
     */
    public function addSection(Section $section)
    {
        if (!$this->sections->contains($section)) {
            $this->sections->add($section);
            $section->setJournal($this);
        }

        return $this;
    }

    /**
     * @return ArrayCollection|Section[]
     */
    public function getSections()
    {
        return $this->sections;
    }

    /**
     * @param  JournalTheme $journalTheme
     * @return Journal
     */
    public function addJournalTheme(JournalTheme $journalTheme)
    {
        if (!$this->journalThemes->contains($journalTheme)) {
            $this->journalThemes->add($journalTheme);
            $journalTheme->setJournal($this);
        }

        return $this;
    }

    /**
     * @return ArrayCollection|JournalTheme[]
     */
    public function getJournalThemes()
    {
        return $this->journalThemes;
    }

    /**
     * @param  Lang $language
     * @return Journal
     */
    public function addLanguage(Lang $language)
    {
        if (!$this->languages->contains($language)) {
            $this->languages->add($language);
        }

        return $this;
    }

    /**
     * @return ArrayCollection|Lang[]
     */
    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * @param  Period $period
     * @return Journal
     */
    public function addPeriod(Period $period)
    {
        if ($this->periods !== null && !$this->periods->contains($period)) {
            $this->periods->add($period);
        }

        return $this;
    }

    /**
     * @param Period $period
     */
    public function removePeriod(Period $period)
    {
        if ($this->periods !== null && $this->periods->contains($period)) {
            $this->periods->removeElement($period);
        }

    }

    /**
     * @return ArrayCollection|Period[]
     */
    public function getPeriods()
    {
        return $this->periods;
    }

    /**
     * @return string
     */
    public function getLanguageCodeSet()
    {
        return $this->languageCodeSet;
    }

    /**
     * @param ArrayCollection|Lang[] $languages
     * @param $languages
     * @return $this
     */
    public function setLanguageCodeSet($languages)
    {
        $langIds = [];
        /** @var Lang $language */
        foreach ($languages as $language) {
            $langIds[] = $language->getCode();
        }
        $this->languageCodeSet = implode('-', $langIds);

        return $this;
    }

    /**
     * @param  Subject $subject
     * @return Journal
     */
    public function addSubject(Subject $subject)
    {
        if (!$this->subjects->contains($subject)) {
            $this->subjects->add($subject);
            $subject->addJournal($this);
        }

        return $this;
    }

    /**
     * @param Subject $subject
     */
    public function removeSubjects(Subject $subject)
    {
        if ($this->subjects->contains($subject)) {
            $this->subjects->removeElement($subject);
            $subject->removeJournal($this);
        }
    }

    /**
     * @return Collection
     */
    public function getSubjects()
    {
        return $this->subjects;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set path
     *
     * @param  string $path
     * @return Journal
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get domain
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Set domain
     * @param  string $domain
     * @return Journal
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Get titleAbbr
     *
     * @return string
     */
    public function getTitleAbbr()
    {
        return $this->translate()->getTitleAbbr();
    }

    /**
     * Set titleAbbr
     *
     * @param  string $titleAbbr
     * @return Journal
     */
    public function setTitleAbbr($titleAbbr)
    {
        $this->translate()->setTitleAbbr($titleAbbr);

        return $this;
    }

    /**
     * Translation helper method
     * @param null $locale
     * @return mixed|null|\Ojs\JournalBundle\Entity\JournalTranslation
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
            $translation = new JournalTranslation();
            if (!is_null($defaultTranslation)) {
                $translation->setTitle($defaultTranslation->getTitle());
                $translation->setSubtitle($defaultTranslation->getSubtitle());
                $translation->setDescription($defaultTranslation->getDescription());
                $translation->setTitleAbbr($defaultTranslation->getTitleAbbr());
            }
            $translation->setLocale($locale);
            $this->addTranslation($translation);
        }
        $this->currentTranslation = $translation;

        return $translation;
    }

    /**
     * Get titleTransliterated
     *
     * @return string
     */
    public function getTitleTransliterated()
    {
        return $this->titleTransliterated;
    }

    /**
     * Set titleTransliterated
     *
     * @param  string $titleTransliterated
     * @return Journal
     */
    public function setTitleTransliterated($titleTransliterated)
    {
        $this->titleTransliterated = $titleTransliterated;

        return $this;
    }

    /**
     * Get subtitle
     *
     * @return string
     */
    public function getSubtitle()
    {
        return $this->translate()->getSubtitle();
    }

    /**
     * Set subtitle
     *
     * @param  string $subtitle
     * @return Journal
     */
    public function setSubtitle($subtitle)
    {
        $this->translate()->setSubtitle($subtitle);

        return $this;
    }

    /**
     * Get issn
     *
     * @return string
     */
    public function getIssn()
    {
        return $this->issn;
    }

    /**
     * Set issn
     *
     * @param  string $issn
     * @return Journal
     */
    public function setIssn($issn)
    {
        $this->issn = $issn;

        return $this;
    }

    /**
     * Get eissn
     *
     * @return string
     */
    public function getEissn()
    {
        return $this->eissn;
    }

    /**
     * Set eissn
     *
     * @param  string $eissn
     * @return Journal
     */
    public function setEissn($eissn)
    {
        $this->eissn = $eissn;

        return $this;
    }

    /**
     * Get founded
     *
     * @return \DateTime
     */
    public function getFounded()
    {
        return $this->founded;
    }

    /**
     * Set founded
     *
     * @param  \DateTime $founded
     * @return Journal
     */
    public function setFounded($founded)
    {
        $this->founded = $founded;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set url
     *
     * @param  string $url
     * @return Journal
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return Publisher
     */
    public function getPublisher()
    {
        return $this->publisher;
    }

    /**
     * Set publisher
     * @param  Publisher $publisher
     * @return Journal
     */
    public function setPublisher($publisher)
    {
        $this->publisher = $publisher;

        return $this;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get country
     *
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set country
     * @param  Country $country
     * @return Journal
     */
    public function setCountry(Country $country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isPublished()
    {
        return $this->published;
    }

    /**
     * Set published
     *
     * @param  boolean $published
     * @return Journal
     */
    public function setPublished($published = false)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set status
     *
     * @param  integer $status
     * @return Journal
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set image
     *
     * @param  string $image
     * @return Journal
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set slug
     *
     * @param  string $slug
     * @return Journal
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get theme
     *
     * @return JournalTheme
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Set theme
     *
     * @param  JournalTheme $theme
     * @return Journal
     */
    public function setTheme(JournalTheme $theme = null)
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * Get googleAnalyticsId
     *
     * @return string
     */
    public function getGoogleAnalyticsId()
    {
        return $this->googleAnalyticsId;
    }

    /**
     * Set googleAnalyticsId
     *
     * @param  string $googleAnalyticsId
     * @return Journal
     */
    public function setGoogleAnalyticsId($googleAnalyticsId)
    {
        $this->googleAnalyticsId = $googleAnalyticsId;

        return $this;
    }

    /**
     * Get configured
     * @return boolean
     */
    public function isConfigured()
    {
        return $this->configured;
    }

    /**
     * Set configured
     *
     * @param  boolean $configured
     * @return Journal
     */
    public function setConfigured($configured)
    {
        $this->configured = $configured;

        return $this;
    }

    /**
     * Add articles
     *
     * @param  Article $article
     * @return Journal
     */
    public function addArticle(Article $article)
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
            $article->setJournal($this);
        }

        return $this;
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
     * Add board
     * @param  Board $board
     * @return $this
     */
    public function addBoard(Board $board)
    {
        if (!$this->boards->contains($board)) {
            $this->boards->add($board);
            $board->setJournal($this);
        }

        return $this;
    }

    /**
     * Get boards
     *
     * @return ArrayCollection|Board[]
     */
    public function getBoards()
    {
        return $this->boards;
    }

    /**
     * Add issue
     *
     * @param  Issue $issue
     * @return Journal
     */
    public function addIssue(Issue $issue)
    {
        if (!$this->issues->contains($issue)) {
            $this->issues->add($issue);
            $issue->setJournal($this);
        }

        return $this;
    }

    /**
     * Get issues
     *
     * @return ArrayCollection|Issue[]
     */
    public function getIssues()
    {
        return $this->issues;
    }

    /**
     * Get settings
     *
     * @return Collection
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * Remove subjects
     *
     * @param Subject $subject
     * @return Journal
     */
    public function removeSubject(Subject $subject)
    {
        if($this->subjects->contains($subject)){
            $this->subjects->removeElement($subject);
            $subject->removeJournal($this);
        }

        return $this;
    }

    /**
     * Add bannedUsers
     *
     * @param  User $bannedUser
     * @return Journal
     */
    public function addBannedUser(User $bannedUser)
    {
        $this->bannedUsers[] = $bannedUser;

        return $this;
    }

    /**
     * Remove bannedUsers
     *
     * @param User $bannedUsers
     */
    public function removeBannedUser(User $bannedUsers)
    {
        $this->bannedUsers->removeElement($bannedUsers);
    }

    /**
     * Get bannedUsers
     *
     * @return ArrayCollection|User[]
     */
    public function getBannedUsers()
    {
        return $this->bannedUsers;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        if (is_null($this->getTitle())) {
            return $this->translations->first()->getTitle();
        } else {
            return $this->getTitle();
        }
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->translate()->getTitle();
    }

    /**
     * Set title
     *
     * @param  string $title
     * @return Journal
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
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param  string $logo
     * @return $this
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Add journalIndexs
     *
     * @param  JournalIndex $journalIndex
     * @return Journal
     */
    public function addJournalIndex(JournalIndex $journalIndex)
    {
        if (!$this->journalIndexs->contains($journalIndex)) {
            $this->journalIndexs->add($journalIndex);
            $journalIndex->setJournal($this);
        }

        return $this;
    }

    /**
     * Get journalIndexs
     *
     * @return ArrayCollection|JournalIndex[]
     */
    public function getJournalIndexs()
    {
        return $this->journalIndexs;
    }

    /**
     * Add submission checklist item
     *
     * @param  SubmissionChecklist $submissionChecklist
     * @return Journal
     */
    public function addSubmissionChecklist(SubmissionChecklist $submissionChecklist)
    {
        if (!$this->submissionChecklist->contains($submissionChecklist)) {
            $this->submissionChecklist->add($submissionChecklist);
            $submissionChecklist->setJournal($this);
        }

        return $this;
    }

    /**
     * Get submission checklist
     *
     * @return ArrayCollection|SubmissionChecklist[]
     */
    public function getSubmissionChecklist()
    {
        return $this->submissionChecklist;
    }

    /**
     * Add submission file item
     *
     * @param  JournalSubmissionFile $journalSubmissionFile
     * @return Journal
     */
    public function addJournalSubmissionFile(JournalSubmissionFile $journalSubmissionFile)
    {
        if (!$this->journalSubmissionFiles->contains($journalSubmissionFile)) {
            $this->journalSubmissionFiles->add($journalSubmissionFile);
            $journalSubmissionFile->setJournal($this);
        }

        return $this;
    }

    /**
     * Get submission file
     *
     * @return ArrayCollection|JournalSubmissionFile[]
     */
    public function getJournalSubmissionFiles()
    {
        return $this->journalSubmissionFiles;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->translate()->getDescription();
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->translate()->setDescription($description);
    }

    /**
     * @return boolean
     */
    public function isSetupFinished()
    {
        return $this->setupFinished;
    }

    /**
     * @param boolean $setupFinished
     */
    public function setSetupFinished($setupFinished)
    {
        $this->setupFinished = $setupFinished;
    }

    /**
     * @return string
     */
    public function getFooterText()
    {
        return $this->footerText;
    }

    /**
     * @param string $footerText
     */
    public function setFooterText($footerText)
    {
        $this->footerText = $footerText;
    }

    /**
     * @return int
     */
    public function getDownloadCount()
    {
        return $this->downloadCount;
    }

    /**
     * @param  int $downloadCount
     * @return $this
     */
    public function setDownloadCount($downloadCount)
    {
        $this->downloadCount = $downloadCount;

        return $this;
    }

    /**
     * @return int
     */
    public function getViewCount()
    {
        return $this->viewCount;
    }

    /**
     * @param  int $viewCount
     * @return $this
     */
    public function setViewCount($viewCount)
    {
        $this->viewCount = $viewCount;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isPrinted()
    {
        return $this->printed;
    }

    /**
     * @param  boolean $printed
     * @return $this
     */
    public function setPrinted($printed)
    {
        $this->printed = $printed;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getJournalUsers()
    {
        return $this->journalUsers;
    }

    /**
     * @param mixed $journalUsers
     */
    public function setJournalUsers($journalUsers)
    {
        $this->journalUsers = $journalUsers;
    }

    /**
     * @return ArrayCollection
     */
    public function getJournalContacts()
    {
        return $this->journalContacts;
    }

    /**
     * @param ArrayCollection $journalContacts
     */
    public function setJournalContacts($journalContacts)
    {
        $this->journalContacts = $journalContacts;
    }

    /**
     * @return Lang
     */
    public function getMandatoryLang()
    {
        return $this->mandatoryLang;
    }

    /**
     * @param  Lang $mandatoryLang
     * @return $this
     */
    public function setMandatoryLang(Lang $mandatoryLang)
    {
        $this->mandatoryLang = $mandatoryLang;

        return $this;
    }

    /**
     * @return ArrayCollection|JournalAnnouncement[]
     */
    public function getAnnouncements()
    {
        return $this->announcements;
    }

    /**
     * @param ArrayCollection|JournalAnnouncement[] $announcements
     */
    public function setAnnouncements($announcements)
    {
        $this->announcements = $announcements;
    }

    /**
     * @return JournalDesign
     */
    public function getDesign()
    {
        return $this->design;
    }

    /**
     * @param JournalDesign $design
     */
    public function setDesign(JournalDesign $design)
    {
        $this->design = $design;
    }

    /**
     * @return JournalDesign|ArrayCollection
     */
    public function getJournalDesigns()
    {
        return $this->journalDesigns;
    }

    /**
     * @param JournalDesign|ArrayCollection $journalDesign
     */
    public function addJournalDesign($journalDesign)
    {
        if(!$this->journalDesigns->contains($journalDesign)){
            $this->journalDesigns->add($journalDesign);
            $journalDesign->setJournal($this);
        }
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Journal
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
     * @return Journal
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Add journalUser
     *
     * @param JournalUser $journalUser
     *
     * @return Journal
     */
    public function addJournalUser(JournalUser $journalUser)
    {
        if (!$this->journalUsers->contains($journalUser)) {
            $this->journalUsers->add($journalUser);
            $journalUser->setJournal($this);
        }

        return $this;
    }

    /**
     * Add journalContact
     *
     * @param JournalContact $journalContact
     *
     * @return Journal
     */
    public function addJournalContact(JournalContact $journalContact)
    {
        if (!$this->journalContacts->contains($journalContact)) {
            $this->journalContacts->add($journalContact);
            $journalContact->setJournal($this);
        }

        return $this;
    }

    /**
     * @return ArrayCollection|JournalStatistic[]
     */
    public function getStatistics()
    {
        return $this->statistics;
    }

    /**
     * @param ArrayCollection|JournalStatistic[] $statistics
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
