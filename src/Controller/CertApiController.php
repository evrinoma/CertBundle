<?php

declare(strict_types=1);

/*
 * This file is part of the package.
 *
 * (c) Nikolay Nikolaev <evrinoma@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Evrinoma\CertBundle\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Evrinoma\CertBundle\Dto\CertApiDtoInterface;
use Evrinoma\CertBundle\Exception\Cert\CertCannotBeSavedException;
use Evrinoma\CertBundle\Exception\Cert\CertInvalidException;
use Evrinoma\CertBundle\Exception\Cert\CertNotFoundException;
use Evrinoma\CertBundle\Facade\Cert\FacadeInterface;
use Evrinoma\CertBundle\Serializer\GroupInterface;
use Evrinoma\DtoBundle\Factory\FactoryDtoInterface;
use Evrinoma\UtilsBundle\Controller\AbstractWrappedApiController;
use Evrinoma\UtilsBundle\Controller\ApiControllerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class CertApiController extends AbstractWrappedApiController implements ApiControllerInterface
{
    private string $dtoClass;

    private ?Request $request;

    private FactoryDtoInterface $factoryDto;

    private FacadeInterface $facade;

    public function __construct(
        SerializerInterface $serializer,
        RequestStack $requestStack,
        FactoryDtoInterface $factoryDto,
        FacadeInterface $facade,
        string $dtoClass
    ) {
        parent::__construct($serializer);
        $this->request = $requestStack->getCurrentRequest();
        $this->factoryDto = $factoryDto;
        $this->dtoClass = $dtoClass;
        $this->facade = $facade;
    }

    /**
     * @Rest\Post("/api/cert/create", options={"expose": true}, name="api_cert_create")
     * @OA\Post(
     *     tags={"cert"},
     *     description="the method perform create cert",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 example={
     *                     "class": "Evrinoma\CertBundle\Dto\CertApiDto",
     *                     "position": "0",
     *                     "title": "bla bla",
     *                 },
     *                 type="object",
     *                 @OA\Property(property="class", type="string", default="Evrinoma\CertBundle\Dto\CertApiDto"),
     *                 @OA\Property(property="position", type="string"),
     *                 @OA\Property(property="title", type="string"),
     *             )
     *         )
     *     )
     * )
     * @OA\Response(response=200, description="Create cert")
     *
     * @return JsonResponse
     */
    public function postAction(): JsonResponse
    {
        /** @var CertApiDtoInterface $certApiDto */
        $certApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);

        $this->setStatusCreated();

        $json = [];
        $error = [];
        $group = GroupInterface::API_POST_CERT;

        try {
            $this->facade->post($certApiDto, $group, $json);
        } catch (\Exception $e) {
            $error = $this->setRestStatus($e);
        }

        return $this->setSerializeGroup($group)->JsonResponse('Create cert', $json, $error);
    }

    /**
     * @Rest\Put("/api/cert/save", options={"expose": true}, name="api_cert_save")
     * @OA\Put(
     *     tags={"cert"},
     *     description="the method perform save cert for current entity",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 example={
     *                     "class": "Evrinoma\CertBundle\Dto\CertApiDto",
     *                     "id": "1",
     *                     "active": "b",
     *                     "position": "0",
     *                     "title": "bla bla",
     *                 },
     *                 type="object",
     *                 @OA\Property(property="class", type="string", default="Evrinoma\CertBundle\Dto\CertApiDto"),
     *                 @OA\Property(property="id", type="string"),
     *                 @OA\Property(property="active", type="string"),
     *                 @OA\Property(property="position", type="string"),
     *                 @OA\Property(property="title", type="string"),
     *             )
     *         )
     *     )
     * )
     * @OA\Response(response=200, description="Save cert")
     *
     * @return JsonResponse
     */
    public function putAction(): JsonResponse
    {
        /** @var CertApiDtoInterface $certApiDto */
        $certApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);

        $json = [];
        $error = [];
        $group = GroupInterface::API_PUT_CERT;

        try {
            $this->facade->put($certApiDto, $group, $json);
        } catch (\Exception $e) {
            $error = $this->setRestStatus($e);
        }

        return $this->setSerializeGroup($group)->JsonResponse('Save cert', $json, $error);
    }

    /**
     * @Rest\Delete("/api/cert/delete", options={"expose": true}, name="api_cert_delete")
     * @OA\Delete(
     *     tags={"cert"},
     *     @OA\Parameter(
     *         description="class",
     *         in="query",
     *         name="class",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             default="Evrinoma\CertBundle\Dto\CertApiDto",
     *             readOnly=true
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="id Entity",
     *         in="query",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             default="3",
     *         )
     *     )
     * )
     * @OA\Response(response=200, description="Delete cert")
     *
     * @return JsonResponse
     */
    public function deleteAction(): JsonResponse
    {
        /** @var CertApiDtoInterface $certApiDto */
        $certApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);

        $this->setStatusAccepted();

        $json = [];
        $error = [];

        try {
            $this->facade->delete($certApiDto, '', $json);
        } catch (\Exception $e) {
            $error = $this->setRestStatus($e);
        }

        return $this->JsonResponse('Delete cert', $json, $error);
    }

    /**
     * @Rest\Get("/api/cert/criteria", options={"expose": true}, name="api_cert_criteria")
     * @OA\Get(
     *     tags={"cert"},
     *     @OA\Parameter(
     *         description="class",
     *         in="query",
     *         name="class",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             default="Evrinoma\CertBundle\Dto\CertApiDto",
     *             readOnly=true
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="id Entity",
     *         in="query",
     *         name="id",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="active",
     *         in="query",
     *         name="active",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="position",
     *         in="query",
     *         name="position",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="title",
     *         in="query",
     *         name="title",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="file[brief]",
     *         in="query",
     *         description="Type Cert",
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(
     *                 type="string",
     *                 ref=@Model(type=Evrinoma\CertBundle\Form\Rest\File\FileChoiceType::class, options={"data": "brief"})
     *             ),
     *         ),
     *         style="form"
     *     ),
     * )
     * @OA\Response(response=200, description="Return cert")
     *
     * @return JsonResponse
     */
    public function criteriaAction(): JsonResponse
    {
        /** @var CertApiDtoInterface $certApiDto */
        $certApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);

        $json = [];
        $error = [];
        $group = GroupInterface::API_CRITERIA_CERT;

        try {
            $this->facade->criteria($certApiDto, $group, $json);
        } catch (\Exception $e) {
            $error = $this->setRestStatus($e);
        }

        return $this->setSerializeGroup($group)->JsonResponse('Get cert', $json, $error);
    }

    /**
     * @Rest\Get("/api/cert", options={"expose": true}, name="api_cert")
     * @OA\Get(
     *     tags={"cert"},
     *     @OA\Parameter(
     *         description="class",
     *         in="query",
     *         name="class",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             default="Evrinoma\CertBundle\Dto\CertApiDto",
     *             readOnly=true
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="id Entity",
     *         in="query",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             default="3",
     *         )
     *     )
     * )
     * @OA\Response(response=200, description="Return cert")
     *
     * @return JsonResponse
     */
    public function getAction(): JsonResponse
    {
        /** @var CertApiDtoInterface $certApiDto */
        $certApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);

        $json = [];
        $error = [];
        $group = GroupInterface::API_GET_CERT;

        try {
            $this->facade->get($certApiDto, $group, $json);
        } catch (\Exception $e) {
            $error = $this->setRestStatus($e);
        }

        return $this->setSerializeGroup($group)->JsonResponse('Get cert', $json, $error);
    }

    /**
     * @param \Exception $e
     *
     * @return array
     */
    public function setRestStatus(\Exception $e): array
    {
        switch (true) {
            case $e instanceof CertCannotBeSavedException:
                $this->setStatusNotImplemented();
                break;
            case $e instanceof UniqueConstraintViolationException:
                $this->setStatusConflict();
                break;
            case $e instanceof CertNotFoundException:
                $this->setStatusNotFound();
                break;
            case $e instanceof CertInvalidException:
                $this->setStatusUnprocessableEntity();
                break;
            default:
                $this->setStatusBadRequest();
        }

        return [$e->getMessage()];
    }
}
