<?php

namespace App\Controller\api;

use App\Entity\Beer;
use App\Entity\Checkin;
use App\Entity\Brasserie;

use ReflectionClass;
use ReflectionProperty;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMInvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

abstract class ApiController extends AbstractController
{
    protected SerializerInterface $serializer;
    protected $repository = NULL;
    protected EntityManagerInterface $em;

    protected const ERR_NOT_FOUND = "Not Found";
    protected const ERR_INTERN = "Internal error";
    protected const ERR_INVALID_ARG = "Invalid argument exception";
    protected const INFO_OK = "OK";

    protected function __construct(
        SerializerInterface $serializer,
        EntityManagerInterface $em
    )
    {
        $this->serializer = &$serializer;
        $this->repository = NULL;
        $this->em = &$em;
    }

    private function copyAttributes($obj_src, $obj_dest): void
    {
        $reflection = new ReflectionClass($obj_src);
        $vars = $reflection->getProperties(ReflectionProperty::IS_PRIVATE);

        foreach ($vars as $attr_name) {
            $method_setter = 'set'.ucfirst($attr_name->name);
            $method_getter = 'get'.ucfirst($attr_name->name);
            if (
                method_exists($obj_dest, $method_setter)
                && method_exists($obj_src, $method_getter)
            ) {
                $obj_dest->$method_setter($obj_src->$method_getter());
            }
        }
    }

    private function getJsonResponse($entity, int $status = 200): Response
    {
        $json = $this->serializer->serialize($entity, 'json', [
            'groups' => 'api.get'
        ]);
        return new Response($json, $status, [
            'Content-Type' => 'application/json'
        ]);
    }

    protected function apiReadAll(): Response
    {
        $entity = $this->repository->findAll();
        return $this->getJsonResponse($entity);
    }

    protected function apiReadOne(int $id): Response
    {
        $entities = $this->repository->findById($id);
        if (count($entities) == 0) {
            return new Response(self::ERR_NOT_FOUND, 404, [
                'Content-Type' => 'application/json'
            ]);
        }
        return $this->getJsonResponse($entities[0]);
    }

    private function getEntity(int $id, string $classType)
    {
        $repository = $this->getDoctrine()->getRepository($classType);
        $entity = $repository->findById($id);
        if (count($entity) == 0) {
            return NULL;
        } else {
            return $entity[0];
        }
    }

    private function insertSubEntity($entity, string $jsonData): void
    {
        $data = json_decode($jsonData);
        if ($entity instanceof Beer) {
            if (
                $entity->getBrasserie() == NULL
                && isset($data->{"brasserie_id"})
            ) {
                $subEntity = $this->getEntity($data->{"brasserie_id"}, Brasserie::class);
                if ($subEntity == NULL) {
                    throw new \Exception("Brasserie entity not found");
                }
                $entity->setBrasserie($subEntity);
            }
        } else if ($entity instanceof Checkin) {
            if ($entity->getUser() == NULL && isset($jsonData['user_id'])) {
                $subEntity = $this->getEntity($jsonData['user_id'], User::class);
                if ($subEntity == NULL) {
                    throw new \Exception("User entity not found");
                }
                $entity->setUser($subEntity);
            }
            if ($entity->getBeer() == NULL && isset($jsonData['beer_id'])) {
                $subEntity = $this->getEntity($jsonData['beer_id'], Beer::class);
                if ($subEntity == NULL) {
                    throw new \Exception("Beer entity not found");
                }
                $entity->setBeer($subEntity);
            }
        }
    }

    protected function apiCreate(
        Request $request,
        ValidatorInterface $validator,
        string $classType
    ): Response
    {
        $jsonData = $request->getContent();
        try {
            $entity = $this->serializer->deserialize($jsonData, $classType, 'json');
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
        try {
            $this->insertSubEntity($entity, $jsonData);
        } catch (\Exception $e) {
            return $this->json($e, 400);
        }
        $errors = $validator->validate($entity);
        if (count($errors) != 0) {
            return $this->json($errors, 400);
        }
        $this->em->persist($entity);
        try {
            $this->em->flush();
        } catch (ORMInvalidArgumentException $e) {
            return $this->json(self::ERR_INVALID_ARG, 400);
        }
        return $this->getJsonResponse($entity, 201);
    }

    protected function apiUpdate(
        Request $request,
        int $id,
        ValidatorInterface $validator,
        string $classType
    ): Response
    {
        $jsonData = $request->getContent();
        try {
            $newEntity = $this->serializer->deserialize($jsonData, $classType, 'json');
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
        try {
            $this->insertSubEntity($newEntity, $jsonData);
        } catch (\Exception $e) {
            return $this->json($e, 400);
        }
        $old_entity = $this->repository->findById($id);
        if (count($old_entity) == 0) {
            return $this->json(self::ERR_NOT_FOUND, 404);
        }
        $old_entity = $old_entity[0];
        $this->copyAttributes($newEntity, $old_entity);
        $errors = $validator->validate($old_entity);
        if (count($errors) != 0) {
            return $this->json($errors, 400);
        }
        try {
            $this->em->flush();
        } catch (ORMInvalidArgumentException $e) {
            return $this->json(self::ERR_INVALID_ARG, 400);
        }
        return $this->getJsonResponse($old_entity);
    }

    protected function apiDelete(int $id): Response
    {
        $entity = $this->repository->findById($id);
        if (count($entity) == 0) {
            return $this->json(self::ERR_NOT_FOUND, 404);
        }
        $this->em->remove($entity[0]);
        $this->em->flush();
        return $this->json(self::INFO_OK, 200);
    }
}
