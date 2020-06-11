<?php

namespace App\Controller\api;

use Swagger\Annotations as Doc;

use App\Entity\Beer;

use App\Entity\Brasserie;
use App\Entity\Checkin;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
  * @Route("/api/rank")
  */
class RankingController extends AbstractController
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = &$serializer;
    }

    private function getJsonResponse($data): Response
    {
        $json = $this->serializer->serialize($data, 'json', [
            'groups' => 'api.get'
        ]);
        return new Response($json, 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * Get a list of beer ranked by ABV
     *
     * @return Response
     * @Route("/beer_abv", methods={"GET"})
     * @Doc\Response(
     *      response=200,
     *      description="Get a list of beer : ranked by ABV"
     * )
     * @Doc\Tag(name="ranking")
     */
    public function rankBeerByAbv(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Beer::class);

        $data = $repository->getByABV(10);
        return $this->getJsonResponse($data);
    }

    /**
     * Get a list of beer ranked by IBU
     *
     * @return Response
     * @Route("/beer_ibu", methods={"GET"})
     * @Doc\Response(
     *      response=200,
     *      description="Get a list of beer : ranked by IBU"
     * )
     * @Doc\Tag(name="ranking")
     */
    public function rankBeerByIbu(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Beer::class);

        $data = $repository->getByIBU(10);
        return $this->getJsonResponse($data);
    }

    /**
     * Get a list of country ranked by their number of brasserie
     *
     * @return Response
     * @Route("/country_brasserie", methods={"GET"})
     * @Doc\Response(
     *      response=200,
     *      description="Get a list of country : ranked by number of brasserie"
     * )
     * @Doc\Tag(name="ranking")
     */
    public function rankBrasserieByCountry(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Brasserie::class);

        $data = $repository->getByCountry();

        $json = $this->serializer->serialize($data, 'json');
        return new Response($json, 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * Get a list of beer ranked by their mark
     *
     * @return Response
     * @Route("/beer_mark", methods={"GET"})
     * @Doc\Response(
     *      response=200,
     *      description="Get a list of beer : ranked by mark"
     * )
     * @Doc\Tag(name="ranking")
     */
    public function rankBeerByMark(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Checkin::class);

        $data = $repository->getByMark();
        return $this->getJsonResponse($data);
    }
}
