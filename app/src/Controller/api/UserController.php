<?php

namespace App\Controller\api;

use Swagger\Annotations as Doc;

use App\Entity\User;
use App\Repository\UserRepository;

use App\Controller\api\ApiController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
  * @Route("/api/user")
  */
class UserController extends ApiController
{
    public function __construct(
        SerializerInterface $serializer,
        UserRepository $repository,
        EntityManagerInterface $em)
    {
        parent::__construct($serializer, $em, $repository);
    }

    /**
     * @Route("/", name="api.user.get_all", methods={"GET"})
     * @Doc\Response(
     *      response=200,
     *      description="Get all users"
     * )
     * @Doc\Tag(name="user")
     */
    public function readAll(): Response
    {
        return $this->apiReadAll();
    }

    /**
     * @Route("/{id}", name="api.user.get_one", methods={"GET"})
     * @Doc\Response(
     *      response=200,
     *      description="Get an user"
     * )
     * @Doc\Parameter(
     *      name="id",
     *      in="path",
     *      type="integer",
     *      description="ID of the user"
     * )
     * @Doc\Tag(name="user")
     */
    public function readOne($id): Response
    {
        return $this->apiReadOne($id);
    }

    /**
     * @Route("/", name="api.user.new", methods="POST")
     * @Doc\Response(
     *      response=201,
     *      description="Create a new user"
     * )
     * @Doc\Tag(name="user")
     */
    public function create(Request $request, ValidatorInterface $validator): Response
    {
        return $this->apiCreate($request, $validator, User::class);
    }

    /**
     * @Route("/{id}", name="api.user.update", methods={"PUT"})
     * @Doc\Response(
     *      response=200,
     *      description="Update an user"
     * )
     * @Doc\Parameter(
     *      name="id",
     *      in="path",
     *      type="integer",
     *      description="ID of the user"
     * )
     * @Doc\Tag(name="user")
     */
    public function update(Request $request, $id, ValidatorInterface $validator): Response
    {
        return $this->apiUpdate($request, $id, $validator, User::class);
    }

    /**
     * @Route("/{id}", name="api.user.delete", methods={"DELETE"})
     * @Doc\Response(
     *      response=200,
     *      description="Remove an user"
     * )
     * @Doc\Parameter(
     *      name="id",
     *      in="path",
     *      type="integer",
     *      description="ID of the user"
     * )
     * @Doc\Tag(name="user")
     */
    public function delete($id): Response
    {
        return $this->apiDelete($id);
    }

}
