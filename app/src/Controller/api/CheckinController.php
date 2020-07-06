<?php

namespace App\Controller\api;

use Swagger\Annotations as Doc;

use App\Entity\Checkin;
use App\Repository\CheckinRepository;

use App\Controller\api\ApiController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
  * @Route("/api/checkin")
  */
class CheckinController extends ApiController
{

    public function __construct(
        SerializerInterface $serializer,
        CheckinRepository $repository,
        EntityManagerInterface $em)
    {
        parent::__construct($serializer, $em, $repository);
    }

    /**
     * @Route("/", name="api.checkin.get_all", methods={"GET"})
     * @Doc\Response(
     *      response=200,
     *      description="Get all checkins"
     * )
     * @Doc\Tag(name="checkin")
     */
    public function readAll(): Response
    {
        return $this->apiReadAll();
    }

    /**
     * @Route("/{id}", name="api.checkin.get_one", methods={"GET"})
     * @Doc\Response(
     *      response=200,
     *      description="Get a checkin"
     * )
     * @Doc\Parameter(
     *      name="id",
     *      in="path",
     *      type="integer",
     *      description="ID of the checkin"
     * )
     * @Doc\Tag(name="checkin")
     */
    public function readOne($id): Response
    {
        return $this->apiReadOne($id);
    }

    /**
     * @Route("/", name="api.checkin.new", methods="POST")
     * @Doc\Response(
     *      response=201,
     *      description="Create a new checkin"
     * )
     * @Doc\Tag(name="checkin")
     */
    public function create(Request $request, ValidatorInterface $validator): Response
    {
        return $this->apiCreate($request, $validator, Checkin::class);
    }

    /**
     * @Route("/{id}", name="api.checkin.update", methods={"PUT"})
     * @Doc\Response(
     *      response=200,
     *      description="Update a checkin"
     * )
     * @Doc\Parameter(
     *      name="id",
     *      in="path",
     *      type="integer",
     *      description="ID of the checkin"
     * )
     * @Doc\Tag(name="checkin")
     */
    public function update(Request $request, $id, ValidatorInterface $validator): Response
    {
        return $this->apiUpdate($request, $id, $validator, Checkin::class);
    }

    /**
     * @Route("/{id}", name="api.checkin.delete", methods={"DELETE"})
     * @Doc\Response(
     *      response=200,
     *      description="Remove a checkin"
     * )
     * @Doc\Parameter(
     *      name="id",
     *      in="path",
     *      type="integer",
     *      description="ID of the checkin"
     * )
     * @Doc\Tag(name="checkin")
     */
    public function delete($id): Response
    {
        return $this->apiDelete($id);
    }

}
