<?php

namespace Ojs\ApiBundle\Controller\Admin;

use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Ojs\AdminBundle\Form\Type\PublisherType;
use Ojs\JournalBundle\Entity\Publisher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\Form\FormTypeInterface;
use Ojs\ApiBundle\Exception\InvalidFormException;

class PublisherRestController extends FOSRestController
{
    /**
     * List all Publishers.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing Publishers.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many Publishers to return.")
     *
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getPublishersAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        if (!$this->isGranted('VIEW', new Publisher())) {
            throw new AccessDeniedHttpException;
        }
        $offset = $paramFetcher->get('offset');
        $offset = null === $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');
        return $this->container->get('ojs_api.publisher.handler')->all($limit, $offset);
    }

    /**
     * Get single Publisher.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a Publisher for a given id",
     *   output = "Ojs\PublisherBundle\Entity\Publisher",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the Publisher is not found"
     *   }
     * )
     *
     * @param int     $id      the Publisher id
     *
     * @return array
     *
     * @throws NotFoundHttpException when Publisher not exist
     */
    public function getPublisherAction($id)
    {
        $entity = $this->getOr404($id);
        if (!$this->isGranted('VIEW', $entity)) {
            throw new AccessDeniedHttpException;
        }
        return $entity;
    }

    /**
     * Presents the form to use to create a new Publisher.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @return FormTypeInterface
     */
    public function newPublisherAction()
    {
        if (!$this->isGranted('CREATE', new Publisher())) {
            throw new AccessDeniedHttpException;
        }
        return $this->createForm(new PublisherType(), null, ['csrf_protection' => false]);
    }

    /**
     * Create a Publisher from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new Publisher from the submitted data.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|View
     */
    public function postPublisherAction(Request $request)
    {
        if (!$this->isGranted('CREATE', new Publisher())) {
            throw new AccessDeniedHttpException;
        }
        try {
            $newEntity = $this->container->get('ojs_api.publisher.handler')->post(
                $request->request->all()
            );
            $routeOptions = array(
                'id' => $newEntity->getId(),
                '_format' => $request->get('_format')
            );
            return $this->routeRedirectView('api_1_get_publishers', $routeOptions, Codes::HTTP_CREATED);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Update existing Publisher from the submitted data or create a new Publisher at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     201 = "Returned when the Publisher is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the Publisher id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when Publisher not exist
     */
    public function putPublisherAction(Request $request, $id)
    {
        if (!$this->isGranted('CREATE', new Publisher())) {
            throw new AccessDeniedHttpException;
        }
        try {
            if (!($entity = $this->container->get('ojs_api.publisher.handler')->get($id))) {
                $statusCode = Codes::HTTP_CREATED;
                $entity = $this->container->get('ojs_api.publisher.handler')->post(
                    $request->request->all()
                );
            } else {
                $statusCode = Codes::HTTP_NO_CONTENT;
                $entity = $this->container->get('ojs_api.publisher.handler')->put(
                    $entity,
                    $request->request->all()
                );
            }
            $routeOptions = array(
                'id' => $entity->getId(),
                '_format' => $request->get('_format')
            );
            return $this->routeRedirectView('api_1_get_publisher', $routeOptions, $statusCode);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Update existing publisher from the submitted data or create a new publisher at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the publisher id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when publisher not exist
     */
    public function patchPublisherAction(Request $request, $id)
    {
        try {
            $entity = $this->container->get('ojs_api.publisher.handler')->patch(
                $this->getOr404($id),
                $request->request->all()
            );
            if (!$this->isGranted('EDIT', $entity)) {
                throw new AccessDeniedHttpException;
            }
            $routeOptions = array(
                'id' => $entity->getId(),
                '_format' => $request->get('_format')
            );
            return $this->routeRedirectView('api_1_get_publisher', $routeOptions, Codes::HTTP_NO_CONTENT);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * @param $id
     * @throws NotFoundHttpException
     * @return Response
     * @ApiDoc(
     *      resource = false,
     *      description = "Delete Publisher",
     *      requirements = {
     *          {
     *              "name" = "id",
     *              "dataType" = "integer",
     *              "requirement" = "Numeric",
     *              "description" = "Publisher ID"
     *          }
     *      },
     *      statusCodes = {
     *          "204" = "Deleted Successfully",
     *          "404" = "Object cannot found"
     *      }
     * )
     *
     */
    public function deletePublisherAction($id)
    {
        $entity = $this->getOr404($id);
        if (!$this->isGranted('DELETE', $entity)) {
            throw new AccessDeniedHttpException;
        }
        $this->container->get('ojs_api.publisher.handler')->delete($entity);
        return $this->view(null, Codes::HTTP_NO_CONTENT, []);
    }

    /**
     * Fetch a Publisher or throw an 404 Exception.
     *
     * @param mixed $id
     *
     * @return Publisher
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id)
    {
        if (!($entity = $this->container->get('ojs_api.publisher.handler')->get($id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
        }
        return $entity;
    }
}
