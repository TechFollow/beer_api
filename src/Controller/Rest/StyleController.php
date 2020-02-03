<?php


namespace App\Controller\Rest;


use App\DTO\Assembler\StyleAssembler;
use App\DTO\StyleDTO;
use App\Entity\Style;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class StyleController extends AbstractRestController
{

    /**
     * @var StyleAssembler
     */
    private $styleAssembler;

    /**
     * StyleController constructor.
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @param StyleAssembler $styleAssembler
     */
    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator, StyleAssembler $styleAssembler)
    {
        parent::__construct($serializer, $validator);
        $this->styleAssembler = $styleAssembler;
    }

    /**
     * @Route("/styles", name="get_styles", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function getCollection(Request $request): Response
    {
        $results = $this->getCollectionEntity(Style::class, $request->query, ['style-collection']);

        if (empty($results['data'])) {
            return new Response(null, 204);
        }

        $response = new Response($results['data'], 200);
        $response->headers->set('totalHits', $results['totalHits']);
        $response->headers->set('totalPage', $results['totalPage']);
        $response->headers->set('nextPage', $results['nextPage']);

        return $response;
    }

    /**
     * @Route("/styles/{id}", name="get_style", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function getOne(Request $request) :Response
    {
        $style =  $this->getOneEntity(Style::class, $request->get('id'));
        $json = $this->serialize($style, ['style-collection']);

        return new Response($json, 200);
    }

    /**
     * @Route("/styles", name="post_styles", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function post(Request $request) :Response
    {
        $response = $this->postEntity($request->getContent(), StyleDTO::class, $this->styleAssembler);

        return $response;
    }

    /**
     * @Route("/breweries/{id}", name="patch_brewery", methods={"PATCH"})
     * @param Request $request
     * @return JsonResponse
     */
    public function patch(Request $request) :JsonResponse
    {

    }

    /**
     * @Route("/styles/{id}", name="delete_style", methods={"DELETE"}, requirements={"id"="\d+"})
     * @param Request $request
     * @return Response
     */
    public function deleteOne(Request $request):Response
    {
        $idRessource = $request->get('id') ? $request->get('id') : null;

        if (empty($idRessource)) {
            new Response(null, 404);
        }

        $resp = $this->delete(Style::class, $idRessource);

        return $resp;
    }
}