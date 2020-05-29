<?php

namespace App\Controller\api;

use App\Entity\User;
use App\Repository\UserRepository;

use App\Controller\api\apiController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
  * @Route("/api/user")
  */
class UserController extends apiController
{
    protected $serializer;
    protected $repository;
    protected $em;

    public function __construct(
        SerializerInterface $serializer,
        UserRepository $repository,
        EntityManagerInterface $em)
    {
        parent::__construct($serializer, $em);
        $this->repository = $repository;
    }

    /**
     * @Route("/", name="api.user.get_all", methods={"GET"})
     */
    public function read_all(): Response
    {
        return $this->api_read_all();
    }

    /**
     * @Route("/{id}", name="api.user.get_one", methods={"GET"})
     */
    public function read_one($id): Response
    {
        return $this->api_read_one($id);
    }

    /**
     * @Route("/", name="api.user.new", methods="POST")
     */
    public function create(Request $request, ValidatorInterface $validator): Response
    {
        return $this->api_create($request, $validator, User::class);
    }

    /**
     * @Route("/{id}", name="api.user.update", methods={"PUT"})
     */
    public function update(Request $request, $id, ValidatorInterface $validator): Response
    {
        return $this->api_update($request, $id, $validator, User::class);
    }

    /**
     * @Route("/{id}", name="api.user.delete", methods={"DELETE"})
     */
    public function delete($id): Response
    {
        return $this->api_delete($id);
    }

}
