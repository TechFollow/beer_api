<?php

namespace App\Controller\api;

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
     */
    public function read_all(): Response
    {
        return $this->api_read_all();
    }

    /**
     * @Route("/{id}", name="api.brasserie.get_one", methods={"GET"})
     */
    public function read_one($id): Response
    {
        return $this->api_read_one($id);
    }

    /**
     * @Route("/", name="api.brasserie.new", methods="POST")
     */
    public function create(Request $request, ValidatorInterface $validator): Response
    {
        return $this->api_create($request, $validator, Brasserie::class);
    }

    /**
     * @Route("/{id}", name="api.brasserie.update", methods={"PUT"})
     */
    public function update(Request $request, $id, ValidatorInterface $validator): Response
    {
        return $this->api_update($request, $id, $validator, Brasserie::class);
    }

    /**
     * @Route("/{id}", name="api.brasserie.delete", methods={"DELETE"})
     */
    public function delete($id): Response
    {
        return $this->api_delete($id);
    }

}
