<?php

namespace App\Controller\api;

use App\Entity\Brasserie;

use App\Form\BrasserieType;
use App\Repository\BrasserieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
  * @Route("/api/brasserie")
  */
class BrasserieController extends AbstractController
{
    private $serializer;
    private $repository;

    public function __construct(SerializerInterface $serializer, BrasserieRepository $brasserieRepository)
    {
        $this->serializer = $serializer;
        $this->repository = $brasserieRepository;
    }

    /**
     * @Route("/", name="api.brasserie.get_all", methods={"GET"})
     */
    public function read_all(): Response
    {
        $brasseries = $this->repository->findAll();
        $json = $this->serializer->serialize($brasseries, 'json');

        return new Response($json, 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * @Route("/{id}", name="api.brasserie.get_one", methods={"GET"})
     */
    public function read_one($id): Response
    {
        $brasserie = $this->repository->findById($id);
        if (count($brasserie) == 0) {
            return new Response("Not Found", 404, [
                'Content-Type' => 'application/json'
            ]);
        }
        $json = $this->serializer->serialize($brasserie[0], 'json');
        return new Response($json, 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * @Route("/", name="api.brasserie.new", methods="POST")
     */
    public function create(Request $request, EntityManagerInterface $em, ValidatorInterface $validator): Response
    {
        $json_data = $request->getContent();
        try {
            $post = $this->serializer->deserialize($json_data, Brasserie::class, 'json');
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
        $errors = $validator->validate($post);
        if (count($errors) == 0) {
            $em->persist($post);
            $em->flush();
            return new Response($json_data, 201, [
                'Content-Type' => 'application/json'
            ]);
        }
        return $this->json($errors, 400);
    }

    /**
     * @Route("/{id}", name="api.brasserie.update", methods={"PUT"})
     */
    public function edit(Request $request, $id, ValidatorInterface $validator, EntityManagerInterface $em): Response
    {
        $json_data = $request->getContent();
        try {
            $new_brasserie = $this->serializer->deserialize($json_data, Brasserie::class, 'json');
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
        $errors = $validator->validate($new_brasserie);
        if (count($errors) == 0) {
            $old_brasserie = $this->repository->findById($id);
            if (count($old_brasserie) == 0) {
                return $this->json("Not found", 404);
            }
            $old_brasserie = $old_brasserie[0];
            $old_brasserie->setName($new_brasserie->getName());
            $old_brasserie->setStreet($new_brasserie->getStreet());
            $old_brasserie->setCity($new_brasserie->getCity());
            $old_brasserie->setPostalCode($new_brasserie->getPostalCode());
            $old_brasserie->setCountry($new_brasserie->getCountry());
            $old_brasserie->setDateCreate($new_brasserie->getDateCreate());
            $old_brasserie->setDateUpdate($new_brasserie->getDateUpdate());
            $em->flush();
            return new Response($json_data, 200, [
                'Content-Type' => 'application/json'
            ]);
        }
        return $this->json($errors, 400);
    }

    /**
     * @Route("/{id}", name="brasserie_delete", methods={"DELETE"})
     */
    public function delete(Request $request, $id, EntityManagerInterface $em): Response
    {
        $brasserie = $this->repository->findById($id);
        if (count($brasserie) == 0) {
            return $this->json("Not found", 404);
        }
        $em->remove($brasserie[0]);
        $em->flush();
        return $this->json("Ok", 200);
    }

}
