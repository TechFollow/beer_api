<?php

namespace App\Controller\api;

use App\Entity\Brasserie;
use Symfony\Component\HttpFoundation\Request;

use App\Exception\ResourceValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
  * @Route("/api/brasserie")
  */
class BrasserieController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(
     *      path = "/{id}",
     *      name = "brasserie.read",
     *      requirements = {"id"="\d+"}
     * )
     * @Rest\View(populateDefaultVars=false)
     */
    public function read_one(Brasserie $brasserie)
    {
        return $brasserie;
    }

    /**
     * @Rest\Get(
     *      path = "/",
     *      name = "brasserie.read.all",
     * )
     * @Rest\View(populateDefaultVars=false)
     */
    public function read_all()
    {
        $repository = $this->getDoctrine()->getRepository(Brasserie::class);

        return $repository->findAll();
    }

    /**
     * @Rest\Post("/")
     * @Rest\View(populateDefaultVars=false)
     * @ParamConverter("brasserie", converter="fos_rest.request_body")
     */
    public function create(Brasserie $brasserie, ValidatorInterface $validator)
    {
        $error = $validator->validate($brasserie);

        if (count($error) != 0) {
            throw new ResourceValidationException($error);
            return $this->view($error, Response::HTTP_BAD_REQUEST);
        }
        $manager = $this->getDoctrine()->getManager();

        $manager->persist($brasserie);
        $manager->flush();

        return $brasserie;
    }

     /**
     * @Rest\Put(
     *      path = "/{id}",
     *      name = "brasserie.update",
     *      requirements = {"id"="\d+"}
     * )
     * @ParamConverter("brasserie")
     * @ParamConverter("new_brasserie", converter="fos_rest.request_body")
     * @Rest\View(populateDefaultVars=false)
     */
    public function update(Brasserie $new_brasserie, Brasserie $brasserie, ConstraintViolationList $violations)
    {
        if (count($violations) > 0) {
            return $this->view($violations, Response::HTTP_BAD_REQUEST);
        }

        dump($brasserie, $new_brasserie);

        //$manager = $this->getDoctrine()->getManager();

        //$manager->persist($brasserie);
        //$manager->flush();

        return $brasserie;
    }
}
