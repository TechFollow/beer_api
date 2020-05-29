<?php

namespace App\Controller\api;

use ReflectionClass;
use ReflectionProperty;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

abstract class apiController extends AbstractController
{
    protected $serializer;
    protected $repository;
    protected $em;

    protected function __construct(SerializerInterface $serializer, EntityManagerInterface $em)
    {
        $this->serializer = $serializer;
        $this->repository = NULL;
        $this->em = $em;
    }

    private function copy_attributes($obj_src, $obj_dest)
    {
        $reflection = new ReflectionClass($obj_src);
        $vars = $reflection->getProperties(ReflectionProperty::IS_PRIVATE);

        foreach ($vars as $attr_name) {
            $method_setter = 'set'.ucfirst($attr_name->name);
            $method_getter = 'get'.ucfirst($attr_name->name);
            if (method_exists($obj_dest, $method_setter) && method_exists($obj_src, $method_getter)) {
                $obj_dest->$method_setter($obj_src->$method_getter());
            }
        }
    }

    protected function api_read_all(): Response
    {
        $entity = $this->repository->findAll();
        $json = $this->serializer->serialize($entity, 'json', [
            'groups' => 'api.get'
        ]);

        return new Response($json, 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    protected function api_read_one($id): Response
    {
        $entities = $this->repository->findById($id);
        if (count($entities) == 0) {
            return new Response("Not Found", 404, [
                'Content-Type' => 'application/json'
            ]);
        }
        $json = $this->serializer->serialize($entities[0], 'json', [
            'groups' => 'api.get'
        ]);
        return new Response($json, 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    protected function api_create(Request $request, ValidatorInterface $validator, $classType): Response
    {
        $json_data = $request->getContent();
        try {
            $entity = $this->serializer->deserialize($json_data, $classType, 'json');
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
        $errors = $validator->validate($entity);
        if (count($errors) == 0) {
            $this->em->persist($entity);
            $this->em->flush();
            $json_entity = $this->serializer->serialize($entity, 'json', [
                'groups' => 'api.get'
            ]);
            return new Response($json_entity, 201, [
                'Content-Type' => 'application/json'
            ]);
        }
        return $this->json($errors, 400);
    }

    protected function api_update(Request $request, $id, ValidatorInterface $validator, $classType): Response
    {
        $json_data = $request->getContent();
        try {
            $new_entity = $this->serializer->deserialize($json_data, $classType, 'json');
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
        $errors = $validator->validate($new_entity);
        if (count($errors) == 0) {
            $old_entity = $this->repository->findById($id);
            if (count($old_entity) == 0) {
                return $this->json("Not found", 404);
            }
            $old_entity = $old_entity[0];
            $this->copy_attributes($new_entity, $old_entity);
            $this->em->flush();
            $json_entity = $this->serializer->serialize($old_entity, 'json', [
                'groups' => 'api.get'
            ]);
            return new Response($json_entity, 200, [
                'Content-Type' => 'application/json'
            ]);
        }
        return $this->json($errors, 400);
    }

    protected function api_delete($id): Response
    {
        $entity = $this->repository->findById($id);
        if (count($entity) == 0) {
            return $this->json("Not found", 404);
        }
        $this->em->remove($entity[0]);
        $this->em->flush();
        return $this->json("Ok", 200);
    }

}
