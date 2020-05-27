<?php

namespace App\Controller\api;

use App\Entity\Brasserie;
use Symfony\Component\HttpFoundation\Request;

use App\Exception\ResourceValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
  * @Route("/api/brasserie")
  */
class BrasserieController extends AbstractController
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @Route("/", name="api.brasserie.get_all", methods={"GET"})
     */
    public function read_all()
    {
        $repository = $this->getDoctrine()->getRepository(Brasserie::class);
        $data = $repository->findAll();

        $json = $this->serializer->serialize($data, 'json');
        return new Response($json, 200, [
            'Content-Type' => 'application/json'
        ]);
    }

}
