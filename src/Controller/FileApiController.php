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
use Evrinoma\CertBundle\Dto\FileApiDtoInterface;
use Evrinoma\CertBundle\Exception\File\FileCannotBeSavedException;
use Evrinoma\CertBundle\Exception\File\FileInvalidException;
use Evrinoma\CertBundle\Exception\File\FileNotFoundException;
use Evrinoma\CertBundle\Facade\File\FacadeInterface;
use Evrinoma\CertBundle\Serializer\GroupInterface;
use Evrinoma\DtoBundle\Factory\FactoryDtoInterface;
use Evrinoma\UtilsBundle\Controller\AbstractWrappedApiController;
use Evrinoma\UtilsBundle\Controller\ApiControllerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\Serializer\SerializerInterface;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class FileApiController extends AbstractWrappedApiController implements ApiControllerInterface
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
     * @Rest\Post("/api/cert/file/create", options={"expose": true}, name="api_cert_file_create")
     * @OA\Post(
     *     tags={"cert"},
     *     description="the method perform create cert type",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 allOf={
     *                     @OA\Schema(
     *                         type="object",
     *                         @OA\Property(property="class", type="string", default="Evrinoma\CertBundle\Dto\FileApiDto"),
     *                         @OA\Property(property="position", type="int"),
     *                         @OA\Property(property="brief", type="string"),
     *                         @OA\Property(property="image", type="string"),
     *                         @OA\Property(property="attachment", type="string"),
     *                         @OA\Property(property="cert", type="object",
     *                             @OA\Property(property="class", type="string", default="Evrinoma\CertBundle\Dto\CertApiDto"),
     *                             @OA\Property(property="id", type="string", default="1"),
     *                         ),
     *                         @OA\Property(property="Evrinoma\CertBundle\Dto\FileApiDto[image]", type="string",  format="binary"),
     *                         @OA\Property(property="Evrinoma\CertBundle\Dto\FileApiDto[attachment]", type="string",  format="binary")
     *                     )
     *                 }
     *             )
     *         )
     *     )
     * )
     * @OA\Response(response=200, description="Create cert file")
     *
     * @return JsonResponse
     */
    public function postAction(): JsonResponse
    {
        /** @var FileApiDtoInterface $fileApiDto */
        $fileApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);

        $this->setStatusCreated();

        $json = [];
        $error = [];
        $group = GroupInterface::API_POST_CERT_FILE;

        try {
            $this->facade->post($fileApiDto, $group, $json);
        } catch (\Exception $e) {
            $json = [];
            $error = $this->setRestStatus($e);
        }

        return $this->setSerializeGroup($group)->JsonResponse('Create cert file', $json, $error);
    }

    /**
     * @Rest\Post("/api/cert/file/save", options={"expose": true}, name="api_cert_file_save")
     * @OA\Post(
     *     tags={"cert"},
     *     description="the method perform save cert file for current entity",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 allOf={
     *                     @OA\Schema(
     *                         type="object",
     *                         @OA\Property(property="class", type="string", default="Evrinoma\CertBundle\Dto\FileApiDto"),
     *                         @OA\Property(property="position", type="int"),
     *                         @OA\Property(property="brief", type="string"),
     *                         @OA\Property(property="id", type="string"),
     *                         @OA\Property(property="active", type="string"),
     *                         @OA\Property(property="image", type="string"),
     *                         @OA\Property(property="attachment", type="string"),
     *                         @OA\Property(property="cert", type="object",
     *                             @OA\Property(property="class", type="string", default="Evrinoma\CertBundle\Dto\CertApiDto"),
     *                             @OA\Property(property="id", type="string", default="1"),
     *                         ),
     *                         @OA\Property(property="Evrinoma\CertBundle\Dto\FileApiDto[image]", type="string",  format="binary"),
     *                         @OA\Property(property="Evrinoma\CertBundle\Dto\FileApiDto[attachment]", type="string",  format="binary")
     *                     )
     *                 }
     *             )
     *         )
     *     )
     * )
     * @OA\Response(response=200, description="Save cert file")
     *
     * @return JsonResponse
     */
    public function putAction(): JsonResponse
    {
        /** @var FileApiDtoInterface $fileApiDto */
        $fileApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);

        $json = [];
        $error = [];
        $group = GroupInterface::API_PUT_CERT_FILE;

        try {
            $this->facade->put($fileApiDto, $group, $json);
        } catch (\Exception $e) {
            $json = [];
            $error = $this->setRestStatus($e);
        }

        return $this->setSerializeGroup($group)->JsonResponse('Save cert file', $json, $error);
    }

    /**
     * @Rest\Delete("/api/cert/file/delete", options={"expose": true}, name="api_cert_file_delete")
     * @OA\Delete(
     *     tags={"cert"},
     *     @OA\Parameter(
     *         description="class",
     *         in="query",
     *         name="class",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             default="Evrinoma\CertBundle\Dto\FileApiDto",
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
     * @OA\Response(response=200, description="Delete cert file")
     *
     * @return JsonResponse
     */
    public function deleteAction(): JsonResponse
    {
        /** @var FileApiDtoInterface $fileApiDto */
        $fileApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);

        $this->setStatusAccepted();

        $json = [];
        $error = [];

        try {
            $this->facade->delete($fileApiDto, '', $json);
        } catch (\Exception $e) {
            $json = [];
            $error = $this->setRestStatus($e);
        }

        return $this->JsonResponse('Delete cert file', $json, $error);
    }

    /**
     * @Rest\Get("/api/cert/file/criteria", options={"expose": true}, name="api_cert_file_criteria")
     * @OA\Get(
     *     tags={"cert"},
     *     @OA\Parameter(
     *         description="class",
     *         in="query",
     *         name="class",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             default="Evrinoma\CertBundle\Dto\FileApiDto",
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
     *         description="brief",
     *         in="query",
     *         name="brief",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     )
     * )
     * @OA\Response(response=200, description="Return cert file")
     *
     * @return JsonResponse
     */
    public function criteriaAction(): JsonResponse
    {
        /** @var FileApiDtoInterface $fileApiDto */
        $fileApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);

        $json = [];
        $error = [];
        $group = GroupInterface::API_CRITERIA_CERT_FILE;

        try {
            $this->facade->criteria($fileApiDto, $group, $json);
        } catch (\Exception $e) {
            $json = [];
            $error = $this->setRestStatus($e);
        }

        return $this->setSerializeGroup($group)->JsonResponse('Get cert file', $json, $error);
    }

    /**
     * @Rest\Get("/api/cert/file", options={"expose": true}, name="api_cert_file")
     * @OA\Get(
     *     tags={"cert"},
     *     @OA\Parameter(
     *         description="class",
     *         in="query",
     *         name="class",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             default="Evrinoma\CertBundle\Dto\FileApiDto",
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
     * @OA\Response(response=200, description="Return cert file")
     *
     * @return JsonResponse
     */
    public function getAction(): JsonResponse
    {
        /** @var FileApiDtoInterface $fileApiDto */
        $fileApiDto = $this->factoryDto->setRequest($this->request)->createDto($this->dtoClass);

        $json = [];
        $error = [];
        $group = GroupInterface::API_GET_CERT_FILE;

        try {
            $this->facade->get($fileApiDto, $group, $json);
        } catch (\Exception $e) {
            $json = [];
            $error = $this->setRestStatus($e);
        }

        return $this->setSerializeGroup($group)->JsonResponse('Get cert file', $json, $error);
    }

    /**
     * @param \Exception $e
     *
     * @return array
     */
    public function setRestStatus(\Exception $e): array
    {
        switch (true) {
            case $e instanceof FileCannotBeSavedException:
                $this->setStatusNotImplemented();
                break;
            case $e instanceof UniqueConstraintViolationException:
                $this->setStatusConflict();
                break;
            case $e instanceof FileNotFoundException:
                $this->setStatusNotFound();
                break;
            case $e instanceof FileInvalidException:
                $this->setStatusUnprocessableEntity();
                break;
            default:
                $this->setStatusBadRequest();
        }

        return [$e->getMessage()];
    }
}
