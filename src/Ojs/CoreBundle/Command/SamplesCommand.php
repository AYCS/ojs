<?php

namespace Ojs\CoreBundle\Command;

use Jb\Bundle\FileUploaderBundle\Entity\FileHistory;
use Ojs\AdminBundle\Entity\AdminAnnouncement;
use Ojs\AdminBundle\Entity\AdminPost;
use Ojs\CoreBundle\Params\ArticleStatuses;
use Ojs\CoreBundle\Params\JournalStatuses;
use Ojs\CoreBundle\Params\PublisherStatuses;
use Ojs\JournalBundle\Entity\Article;
use Ojs\JournalBundle\Entity\ArticleAuthor;
use Ojs\JournalBundle\Entity\ArticleFile;
use Ojs\JournalBundle\Entity\ArticleTypes;
use Ojs\JournalBundle\Entity\Author;
use Ojs\JournalBundle\Entity\Citation;
use Ojs\JournalBundle\Entity\ContactTypes;
use Ojs\JournalBundle\Entity\Issue;
use Ojs\JournalBundle\Entity\IssueFile;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Entity\Period;
use Ojs\JournalBundle\Entity\PublisherTypes;
use Ojs\JournalBundle\Entity\Section;
use Ojs\JournalBundle\Entity\Lang;
use Ojs\JournalBundle\Entity\Publisher;
use Ojs\JournalBundle\Entity\Subject;
use Ojs\JournalBundle\Entity\SubmissionChecklist;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class SamplesCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('ojs:install:samples')
            ->setDescription('Create some sample data');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Creating sample data...');
        $em = $this->getContainer()->get('doctrine')->getManager();

        $manipulator = $this->getContainer()->get('fos_user.util.user_manipulator');
        $manipulator->create('sample_author', 'author', 'author@example.com', false, false);

        $user = $em->getRepository('OjsUserBundle:User')->findOneBy(['username' => 'sample_author']);

        $announcement = new AdminAnnouncement();
        $announcement->setTitle('We are online!');
        $announcement->setContent('We are now online and accepting submissions!');

        $em->persist($announcement);
        $em->flush();

        $post = new AdminPost();
        $post->setCurrentLocale('en');
        $post->setTitle('Welcome to OJS!');
        $post->setSlug('Welcome to OJS!');
        $post->setContent(
            'Hello! We are now online and waiting for your submissions. ' .
            'Our readers will be able to follow you and read your work ' .
            'right after it gets published!'
        );

        $em->persist($post);
        $em->flush();

        $publisherTypes = [
            'University', 'Government', 'Association',
            'Foundation', 'Hospital', 'Chamber', 'Private'
        ];

        foreach ($publisherTypes as $typeName) {
            $publisherType = new PublisherTypes();
            $publisherType->setCurrentLocale('en');
            $publisherType->setName($typeName);
            $em->persist($publisherType);
        }

        $em->flush();

        $slug = $this->getContainer()->getParameter('defaultPublisherSlug');
        $publisherType = $em->getRepository('OjsJournalBundle:PublisherTypes')->find(1);

        $publisher = new Publisher();
        $publisher->setCurrentLocale('en');
        $publisher->setName('OJS');
        $publisher->setSlug($slug);
        $publisher->setEmail('publisher@example.com');
        $publisher->setAddress('First Avenue, Exampletown');
        $publisher->setPhone('+908501234567');
        $publisher->setVerified(1);
        $publisher->setStatus(PublisherStatuses::STATUS_COMPLETE);
        $publisher->setPublisherType($publisherType);

        $em->persist($publisher);
        $em->flush();

        $subject1 = new Subject();
        $subject1->setCurrentLocale('en');
        $subject1->setSubject('Computer Science');
        $subject1->setTags('computer, science');

        $subject2 = new Subject();
        $subject2->setCurrentLocale('en');
        $subject2->setSubject('Journalism');
        $subject2->setTags('journalism');

        $em->persist($subject1);
        $em->persist($subject2);
        $em->flush();

        $language1 = new Lang();
        $language1->setCurrentLocale('en');
        $language1->setName('English');
        $language1->setCode('en');
        $language1->setRtl(false);

        $language2 = new Lang();
        $language2->setCurrentLocale('tr');
        $language2->setName('Türkçe');
        $language2->setCode('tr');
        $language2->setRtl(false);

        $em->persist($language1);
        $em->persist($language2);
        $em->flush();

        $articleTypes = [
            #[ en, tr ]
            ['Case Report',             'Olgu Sunumu'           ],
            ['Research papers',         'Araştırma Makalesi'    ],
            ['Translation',             'Çeviri'                ],
            ['Note',                    'Not'                   ],
            ['Letter',                  'Editöre Mektup'        ],
            ['Review Articles',         'Derleme'               ],
            ['Book review',             'Kitap İncelemesi'      ],
            ['Correction',              'Düzeltme'              ],
            ['Editorial',               'Editoryal'             ],
            ['Short Communication',     'Kısa Bildiri'          ],
            ['Meeting abstract',        'Toplantı Özetleri'     ],
            ['Conference Paper',        'Konferans Bildirisi'   ],
            ['Biography',               'Biyografi'             ],
            ['Bibliography',            'Bibliyografi'          ],
            ['News',                    'Haber'                 ],
            ['Report',                  'Rapor'                 ],
            ['Legislation Review',      'Yasa İncelemesi'       ],
            ['Decision Review',         'Karar İncelemesi'      ],
            ['Art and Literature',      'Sanat ve Edebiyat'     ],
            ['Other',                   'Diğer'                 ],
        ];

        foreach ($articleTypes as $typeNames) {
            $type = new ArticleTypes();
            $type->setCurrentLocale('en');
            $type->setName($typeNames[0]);
            $type->setCurrentLocale('tr');
            $type->setName($typeNames[1]);
            $em->persist($type);
        }

        $em->flush();

        $contactTypes = [
            'Journal Contact', 'Primary Contact',
            'Technical Contact', 'Author Support',
            'Subscription Support', 'Publisher Support',
            'Submission Support', 'Advertising', 'Media',
            'Editor', 'Co-Editor',
        ];

        foreach ($contactTypes as $typeName) {
            $type = new ContactTypes();
            $type->setCurrentLocale('en');
            $type->setName($typeName);
            $em->persist($type);
        }

        $em->flush();

        $journal = new Journal();
        $journal->setCurrentLocale('en');
        $journal->setPublisher($publisher);
        $journal->setTitle('Introduction to OJS');
        $journal->setSubtitle('How to use OJS');
        $journal->setDescription('A journal about OJS');
        $journal->setTitleAbbr('INTROJS');
        $journal->setUrl('http://ojs.io');
        $journal->setSlug('intro');
        $journal->addSubject($subject1);
        $journal->addSubject($subject2);
        $journal->addLanguage($language1);
        $journal->addLanguage($language2);
        $journal->setMandatoryLang($language2);
        $journal->setFounded(new \DateTime('now'));
        $journal->setIssn('1234-5679');
        $journal->setEissn('1234-5679');
        $journal->setStatus(JournalStatuses::STATUS_PUBLISHED);
        $journal->setPublished(1);

        $em->persist($journal);
        $em->flush();

        $this->createDemoFiles();

        $issueFile = new IssueFile();
        $issueFile->setCurrentLocale('en');
        $issueFile->setTitle('Demo File');
        $issueFile->setDescription('A file');
        $issueFile->setFile('issue.txt');
        $issueFile->setLangCode('en');
        $issueFile->setType(0);
        $issueFile->setVersion(0);
        $issueFile->setUpdatedBy($user->getUsername());

        $issueFileHistory = new FileHistory();
        $issueFileHistory->setFileName('issue.txt');
        $issueFileHistory->setOriginalName('issue.txt');
        $issueFileHistory->setType('issuefiles');

        $em->persist($issueFile);
        $em->persist($issueFileHistory);
        $em->flush();

        $issue = new Issue();
        $issue->setCurrentLocale('en');
        $issue->setJournal($journal);
        $issue->setTitle('First Issue: Hello OJS!');
        $issue->setDescription('First issue of the journal');
        $issue->setNumber(1);
        $issue->setVolume(1);
        $issue->setYear(2015);
        $issue->setSpecial(1);
        $issue->setDatePublished(new \DateTime('now'));
        $issue->setTags('first, guide, tutorial');
        $issue->setDatePublished(new \DateTime('now'));
        $issue->addIssueFile($issueFile);

        $em->persist($issue);
        $em->flush();

        $section = new Section();
        $section->setCurrentLocale('en');
        $section->setJournal($journal);
        $section->setTitle('Tutorials');
        $section->setHideTitle(0);
        $section->setAllowIndex(1);

        $em->persist($section);
        $em->flush();

        $citation1 = new Citation();
        $citation1->setCurrentLocale('en');
        $citation1->setRaw('The Matrix [Motion picture]. (2001). Warner Bros. Pictures.');
        $citation1->setOrderNum(0);

        $em->persist($citation1);
        $em->flush();

        $articleFile = new ArticleFile();
        $articleFile->setCurrentLocale('en');
        $articleFile->setTitle('Demo File');
        $articleFile->setDescription('A file');
        $articleFile->setFile('article.txt');
        $articleFile->setLangCode('en');
        $articleFile->setType(0);
        $articleFile->setVersion(0);
        $articleFile->setUpdatedBy($user->getUsername());

        $articleFileHistory = new FileHistory();
        $articleFileHistory->setFileName('article.txt');
        $articleFileHistory->setOriginalName('article.txt');
        $articleFileHistory->setType('articlefiles');

        $em->persist($articleFile);
        $em->persist($articleFileHistory);
        $em->flush();

        $author = new Author();
        $author->setCurrentLocale('en');
        $author->setTitle('Dr.');
        $author->setFirstName('John');
        $author->setLastName('Doe');
        $author->setEmail('doe@example.com');

        $em->persist($author);
        $em->flush();

        $article1 = new Article();
        $article1->setCurrentLocale('en');
        $article1->setJournal($journal);
        $article1->setSection($section);
        $article1->setIssue($issue);
        $article1->setTitle('Getting Started with OJS');
        $article1->setAbstract('A tutorial about using OJS');
        $article1->setSubjects('OJS');
        $article1->setKeywords('ojs, intro, starting');
        $article1->setDoi('10.5281/zenodo.14791');
        $article1->setSubmissionDate(new \DateTime('now'));
        $article1->setPubdate(new \DateTime('now'));
        $article1->setAnonymous(0);
        $article1->setFirstPage(1);
        $article1->setLastPage(5);
        $article1->setStatus(ArticleStatuses::STATUS_PUBLISHED);
        $article1->addCitation($citation1);
        $article1->addArticleFile($articleFile);

        $em->persist($article1);
        $em->flush();

        $articleAuthor = new ArticleAuthor();
        $articleAuthor->setAuthor($author);
        $articleAuthor->setArticle($article1);
        $articleAuthor->setAuthorOrder(0);

        $em->persist($articleAuthor);
        $em->flush();

        $checklistItems = [
            [
                'The title page should include necessary information.',
                "<ul>
                    <li>The name(s) of the author(s)</li>
                     <li>A concise and informative title</li>
                     <li>The affiliation(s) of the author(s)</li>
                     <li>The e-mail address, telephone number of the corresponding author </li>
                 </ul>"
            ],
            [
                'Manuscript must be approved.',
                'All authors must have read and approved the most recent version of the manuscript.'
            ],
            [
                'Manuscript must be <i>spell checked</i>.',
                'The most recent version of the manuscript must be spell checked.'
            ],
        ];

        foreach ($checklistItems as $checklistItem) {
            $label = $checklistItem[0];
            $detail = $checklistItem[1];

            $item = new SubmissionChecklist();
            $item->setLabel($label);
            $item->setDetail($detail);
            $item->setLocale('en');
            $item->setJournal($journal);
            $em->persist($item);
        }

        $em->flush();

        $periods = [
            'Monthly', 'Bimonthly', 'Quarterly', 'Triquarterly', 'Biannually',
            'Annually', 'Spring', 'Summer', 'Fall', 'Winter'
        ];

        foreach ($periods as $period) {
            $journalPeriod = new Period();
            $journalPeriod->setCurrentLocale('en');
            $journalPeriod->setPeriod($period);
            $em->persist($journalPeriod);
        }

        $em->flush();
    }

    private function createDemoFiles()
    {
        $rootDir = $this->getContainer()->get('kernel')->getRootDir();
        $articleFileDir = $rootDir . '/../web/uploads/articlefiles';
        $issueFileDir = $rootDir . '/../web/uploads/issuefiles';

        $fs = new Filesystem();
        $fs->mkdir($articleFileDir);
        $fs->mkdir($issueFileDir);
        $fs->dumpFile($articleFileDir . '/article.txt', 'Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit...');
        $fs->dumpFile($issueFileDir . '/issue.txt', 'Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit...');
    }
}
