<?php

namespace App\Controller\api;

use App\Entity\Beer;
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
    protected $serializer;
    protected $repository;
    protected $em;

    public function __construct(
        SerializerInterface $serializer,
        BeerRepository $repository,
        EntityManagerInterface $em)
    {
        parent::__construct($serializer, $em);
        $this->repository = $repository;
    }

    /**
     * @Route("/", name="api.beer.get_all", methods={"GET"})
     * )
     */
    public function read_all(): Response
    {
        return $this->api_read_all();
    }

    /**
     * @Route("/{id}", name="api.beer.get_one", methods={"GET"})
     */
    public function read_one($id): Response
    {
        return $this->api_read_one($id);
    }

    /**
     * @Route("/", name="api.beer.new", methods="POST")
     */
    public function create(Request $request, ValidatorInterface $validator): Response
    {
        return $this->api_create($request, $validator, Beer::class);
    }

    /**
     * @Route("/{id}", name="api.beer.update", methods={"PUT"})
     */
    public function update(Request $request, $id, ValidatorInterface $validator): Response
    {
        return $this->api_update($request, $id, $validator, Beer::class);
    }

    /**
     * @Route("/{id}", name="api.beer.delete", methods={"DELETE"})
     */
    public function delete($id): Response
    {
        return $this->api_delete($id);
    }

}
