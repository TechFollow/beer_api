<?php

namespace App\Controller\api;

use App\Entity\Beer;

use Swagger\Annotations as Doc;
use App\Repository\BeerRepository;

use App\Controller\api\ApiController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
  * @Route("/api/beer")
  */
class BeerController extends ApiController
{

    public function __construct(
        SerializerInterface $serializer,
        BeerRepository $repository,
        EntityManagerInterface $em)
    {
        parent::__construct($serializer, $em, $repository);
    }

    /**
     * Get all beer
     * @Route("/", name="api.beer.get_all", methods={"GET"})
     * @Doc\Response(
     *      response=200,
     *      description="Get all beers"
     * )
     * @Doc\Tag(name="beer")
     */
    public function readAll(): Response
    {
        return $this->apiReadAll();
    }

    /**
     * Get one beer with a specific id
     * @Route("/{id}", name="api.beer.get_one", methods={"GET"})
     * @Doc\Response(
     *      response=200,
     *      description="Get a beer with a specific id"
     * )
     * @Doc\Parameter(
     *      name="id",
     *      in="path",
     *      type="integer",
     *      description="ID of the beer"
     * )
     * @Doc\Tag(name="beer")
     */
    public function readOne(int $id): Response
    {
        return $this->apiReadOne($id);
    }

    /**
     * Create a new beer
     * @Route("/", name="api.beer.new", methods="POST")
     * @Doc\Response(
     *      response=201,
     *      description="Create a new beer"
     * )
     * @Doc\Tag(name="beer")
     */
    public function create(Request $request, ValidatorInterface $validator): Response
    {
        return $this->apiCreate($request, $validator, Beer::class);
    }

    /**
     * Update a beer
     * @Route("/{id}", name="api.beer.update", methods={"PUT"})
     * @Doc\Response(
     *      response=200,
     *      description="Update a beer"
     * )
     * @Doc\Parameter(
     *      name="id",
     *      in="path",
     *      type="integer",
     *      description="ID of the beer"
     * )
     * @Doc\Tag(name="beer")
     */
    public function update(Request $request, int $id, ValidatorInterface $validator): Response
    {
        return $this->apiUpdate($request, $id, $validator, Beer::class);
    }

    /**
     * Remove a beer
     * @Route("/{id}", name="api.beer.delete", methods={"DELETE"})
     * @Doc\Response(
     *      response=200,
     *      description="Remove a beer"
     * )
     * @Doc\Parameter(
     *      name="id",
     *      in="path",
     *      type="integer",
     *      description="ID of the beer"
     * )
     * @Doc\Tag(name="beer")
     */
    public function delete(int $id): Response
    {
        return $this->apiDelete($id);
    }

}
