<?php

namespace App\Controller\api;

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

    private function get_json_response($data): Response
    {
        $json = $this->serializer->serialize($data, 'json', [
            'groups' => 'api.get'
        ]);
        return new Response($json, 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * Get a list of beer sorted by ABV
     *
     * @return Response
     * @Route("/beer_abv", methods={"GET"})
     */
    public function rank_beer_abv(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Beer::class);

        $data = $repository->getByABV(10);
        return $this->get_json_response($data);
    }

    /**
     * Get a list of beer sorted by IBU
     *
     * @return Response
     * @Route("/beer_ibu", methods={"GET"})
     */
    public function rank_beer_ibu(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Beer::class);

        $data = $repository->getByIBU(10);
        return $this->get_json_response($data);
    }

    /**
     * Get a list of country sorted by the number of brasserie
     *
     * @return Response
     * @Route("/country_brasserie", methods={"GET"})
     */
    public function rank_brasserie_country(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Brasserie::class);

        $data = $repository->getByCountry();

        $json = $this->serializer->serialize($data, 'json');
        return new Response($json, 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * Get a list of beer sorted by their mark :: ToImprove
     *
     * @return Response
     * @Route("/beer_mark", methods={"GET"})
     */
    public function rank_beer_mark(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Checkin::class);

        $data = $repository->getByMark();
        return $this->get_json_response($data);
    }
}
