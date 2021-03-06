<?php

namespace Ojs\ApiBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Ojs\JournalBundle\Form\Type\SectionType;
use Ojs\JournalBundle\Entity\Section;
use Ojs\JournalBundle\Service\JournalService;
use Symfony\Component\Form\FormFactoryInterface;
use Ojs\ApiBundle\Exception\InvalidFormException;
use Symfony\Component\Filesystem\Filesystem;

class JournalSectionHandler
{
    private $om;
    private $entityClass;
    private $repository;
    private $formFactory;
    private $journalService;

    public function __construct(ObjectManager $om, $entityClass, FormFactoryInterface $formFactory, JournalService $journalService)
    {
        $this->om = $om;
        $this->entityClass = $entityClass;
        $this->repository = $this->om->getRepository($this->entityClass);
        $this->formFactory = $formFactory;
        $this->journalService = $journalService;
    }

    /**
     * Get a Section.
     *
     * @param mixed $id
     *
     * @return Section
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Get a list of Sections.
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
     * Create a new Section.
     *
     * @param array $parameters
     *
     * @return Section
     */
    public function post(array $parameters)
    {
        $entity = $this->createSection();
        return $this->processForm($entity, $parameters, 'POST');
    }

    /**
     * Edit a Section.
     *
     * @param Section $entity
     * @param array         $parameters
     *
     * @return Section
     */
    public function put(Section $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PUT');
    }

    /**
     * Partially update a Section.
     *
     * @param Section $entity
     * @param array         $parameters
     *
     * @return Section
     */
    public function patch(Section $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PATCH');
    }

    /**
     * Delete a Section.
     *
     * @param Section $entity
     *
     * @return Section
     */
    public function delete(Section $entity)
    {
        $this->om->remove($entity);
        $this->om->flush();
        return $this;
    }

    /**
     * Processes the form.
     *
     * @param Section $entity
     * @param array         $parameters
     * @param String        $method
     *
     * @return Section
     *
     * @throws \Ojs\ApiBundle\Exception\InvalidFormException
     */
    private function processForm(Section $entity, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new SectionType(), $entity, array('method' => $method, 'csrf_protection' => false));
        $form->submit($parameters, 'PATCH' !== $method);
        $formData = $form->getData();

        if ($form->isValid()) {
            $entity->setCurrentLocale('en');
            $entity->setJournal($this->journalService->getSelectedJournal());
            $this->om->persist($entity);
            $this->om->flush();
            return $formData;
        }
        throw new InvalidFormException('Invalid submitted data', $form);
    }

    private function createSection()
    {
        return new $this->entityClass();
    }
}