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

abstract class ApiController extends AbstractController
{
    protected $serializer;
    protected $repository;
    protected $em;

    const ERR_NOT_FOUND = "Not Found";
    const INFO_OK = "OK";

    protected function __construct(SerializerInterface $serializer, EntityManagerInterface $em)
    {
        $this->serializer = &$serializer;
        $this->repository = NULL;
        $this->em = &$em;
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

    private function get_json_response($data, $status = 200)
    {
        $json = $this->serializer->serialize($data, 'json', [
            'groups' => 'api.get'
        ]);
        return new Response($json, $status, [
            'Content-Type' => 'application/json'
        ]);
    }

    protected function api_read_all(): Response
    {
        $entity = $this->repository->findAll();
        return $this->get_json_response($entity);
    }

    protected function api_read_one($id): Response
    {
        $entities = $this->repository->findById($id);
        if (count($entities) == 0) {
            return new Response(self::ERR_NOT_FOUND, 404, [
                'Content-Type' => 'application/json'
            ]);
        }
        return $this->get_json_response($entities[0]);
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
            return $this->get_json_response($entity, 201);
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
                return $this->json(self::ERR_NOT_FOUND, 404);
            }
            $old_entity = $old_entity[0];
            $this->copy_attributes($new_entity, $old_entity);
            $this->em->flush();
            return $this->get_json_response($old_entity);
        }
        return $this->json($errors, 400);
    }

    protected function api_delete($id): Response
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
