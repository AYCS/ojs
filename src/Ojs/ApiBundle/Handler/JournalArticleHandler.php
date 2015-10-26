<?php

namespace Ojs\ApiBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Ojs\JournalBundle\Form\Type\ArticleType;
use Ojs\JournalBundle\Entity\Article;
use Ojs\JournalBundle\Service\JournalService;
use Symfony\Component\Form\FormFactoryInterface;
use Ojs\ApiBundle\Exception\InvalidFormException;
use Symfony\Component\Filesystem\Filesystem;
use Ojs\CoreBundle\Service\ApiHandlerHelper;
use Symfony\Component\HttpKernel\KernelInterface;
use Ojs\CoreBundle\Helper\FileHelper;
use Doctrine\Common\Annotations\Reader;

class JournalArticleHandler
{
    private $om;
    private $entityClass;
    private $repository;
    private $formFactory;
    private $journalService;
    private $kernel;
    private $apiHelper;

    public function __construct(ObjectManager $om, $entityClass, FormFactoryInterface $formFactory, JournalService $journalService, KernelInterface $kernel, ApiHandlerHelper $apiHelper)
    {
        $this->om = $om;
        $this->entityClass = $entityClass;
        $this->repository = $this->om->getRepository($this->entityClass);
        $this->formFactory = $formFactory;
        $this->journalService = $journalService;
        $this->kernel = $kernel;
        $this->apiHelper = $apiHelper;
    }

    /**
     * Get a Article.
     *
     * @param mixed $id
     *
     * @return Article
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Get a list of Articles.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 5, $offset = 0)
    {
        return $this->repository->findBy(array(), null, $limit, $offset);
    }

    /**
     * Create a new Article.
     *
     * @param array $parameters
     *
     * @return Article
     */
    public function post(array $parameters)
    {
        $entity = $this->createArticle();
        return $this->processForm($entity, $parameters, 'POST');
    }

    /**
     * Edit a Article.
     *
     * @param Article $entity
     * @param array         $parameters
     *
     * @return Article
     */
    public function put(Article $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PUT');
    }

    /**
     * Partially update a Article.
     *
     * @param Article $entity
     * @param array         $parameters
     *
     * @return Article
     */
    public function patch(Article $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PATCH');
    }

    /**
     * Delete a Article.
     *
     * @param Article $entity
     *
     * @return Article
     */
    public function delete(Article $entity)
    {
        $this->om->remove($entity);
        $this->om->flush();
        return $this;
    }

    /**
     * Processes the form.
     *
     * @param Article $entity
     * @param array         $parameters
     * @param String        $method
     *
     * @return Article
     *
     * @throws \Ojs\ApiBundle\Exception\InvalidFormException
     */
    private function processForm(Article $entity, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new ArticleType(), $entity, array('method' => $method, 'csrf_protection' => false));
        $form->submit($parameters, 'PATCH' !== $method);
        $formData = $form->getData();

        $header = $formData->getHeader();
        if(isset($header)){
            $entity->setHeader($this->storeFile($header, true));
        }
        if ($form->isValid()) {
            $entity->setCurrentLocale('en');
            $entity->setJournal($this->journalService->getSelectedJournal());
            $this->om->persist($entity);
            $this->om->flush();
            return $formData;
        }
        throw new InvalidFormException('Invalid submitted data', $form);
    }

    private function storeFile($file, $isImage = false)
    {
        $rootDir = $this->kernel->getRootDir();
        $articleFileDir = $rootDir . '/../web/uploads/articlefiles/';
        $journalUploadDir = $rootDir . '/../web/uploads/journal/';
        if($isImage) {
            $fileHelper = new FileHelper();
            $generatePath = $fileHelper->generatePath($file['filename'], false);
            if(!is_dir($journalUploadDir.$generatePath) || !is_dir($journalUploadDir.'croped/'.$generatePath)){
                mkdir($journalUploadDir.$generatePath, 0775, true);
                mkdir($journalUploadDir.'croped/'.$generatePath, 0775, true);
            }
            $filePath = $generatePath . $file['filename'];
            file_put_contents($journalUploadDir.$filePath, base64_decode($file['encoded_content']));
            file_put_contents($journalUploadDir.'croped/'.$filePath, base64_decode($file['encoded_content']));
            return $filePath;
        }else{
            $fs = new Filesystem();
            $fs->mkdir($articleFileDir);
            $fs->dumpFile($articleFileDir . $file['filename'], base64_decode($file['encoded_content']));
            return $file['filename'];
        }
    }

    private function createArticle()
    {
        return new $this->entityClass();
    }
}