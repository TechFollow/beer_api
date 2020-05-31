<?php

namespace App\Controller\api;

use Swagger\Annotations as Doc;

use App\Entity\Brasserie;

use App\Controller\api\ApiController;
use App\Repository\BrasserieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
  * @Route("/api/brasserie")
  */
class BrasserieController extends ApiController
{
    protected $serializer;
    protected $repository;
    protected $em;

    public function __construct(
        SerializerInterface $serializer,
        BrasserieRepository $brasserieRepository,
        EntityManagerInterface $em)
    {
        parent::__construct($serializer, $em);
        $this->repository = &$brasserieRepository;
    }

    /**
     * @Route("/", name="api.brasserie.get_all", methods={"GET"})
     * @Doc\Response(
     *      response=200,
     *      description="Get all brasseries"
     * )
     * @Doc\Tag(name="brasserie")
     */
    public function read_all(): Response
    {
        return $this->api_read_all();
    }

    /**
     * @Route("/{id}", name="api.brasserie.get_one", methods={"GET"})
     * @Doc\Response(
     *      response=200,
     *      description="Get one brasserie"
     * )
     * @Doc\Parameter(
     *      name="id",
     *      in="path",
     *      type="integer",
     *      description="ID of the brasserie"
     * )
     * @Doc\Tag(name="brasserie")
     */
    public function read_one($id): Response
    {
        return $this->api_read_one($id);
    }

    /**
     * @Route("/", name="api.brasserie.new", methods="POST")
     * @Doc\Response(
     *      response=201,
     *      description="Create a new brasserie"
     * )
     * @Doc\Tag(name="brasserie")
     */
    public function create(Request $request, ValidatorInterface $validator): Response
    {
        return $this->api_create($request, $validator, Brasserie::class);
    }

    /**
     * @Route("/{id}", name="api.brasserie.update", methods={"PUT"})
     * @Doc\Response(
     *      response=200,
     *      description="Update a brasserie"
     * )
     * @Doc\Parameter(
     *      name="id",
     *      in="path",
     *      type="integer",
     *      description="ID of the brasserie"
     * )
     * @Doc\Tag(name="brasserie")
     */
    public function update(Request $request, $id, ValidatorInterface $validator): Response
    {
        return $this->api_update($request, $id, $validator, Brasserie::class);
    }

    /**
     * @Route("/{id}", name="api.brasserie.delete", methods={"DELETE"})
     * @Doc\Response(
     *      response=200,
     *      description="Remove a brasserie"
     * )
     * @Doc\Parameter(
     *      name="id",
     *      in="path",
     *      type="integer",
     *      description="ID of the brasserie"
     * )
     * @Doc\Tag(name="brasserie")
     */
    public function delete($id): Response
    {
        return $this->api_delete($id);
    }

}
