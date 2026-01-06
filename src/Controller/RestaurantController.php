<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Repository\RestaurantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/restaurants')]
class RestaurantController extends AbstractController
{
    #[Route('/', name: 'app_restaurants')]
    public function index(RestaurantRepository $restaurantRepository, Request $request): Response
    {
        $searchTerm = $request->query->get('q');

        $sortType = $request->query->get('sort');

        $restaurants = $restaurantRepository->findByComplexSearch($searchTerm, $sortType);

        return $this->render('restaurant/index.html.twig', [
            'restaurants' => $restaurants,
            'searchTerm' => $searchTerm,
            'currentSort' => $sortType,
        ]);
    }

    #[Route('/{id}', name: 'app_restaurant_show')]
    public function show(Restaurant $restaurant): Response
    {
        $plats = $restaurant->getPlats();
        $horaires = $restaurant->getHoraires();

        return $this->render('restaurant/show.html.twig', [
            'restaurant' => $restaurant,
            'plats'      => $plats,
            'horaires'   => $horaires,
        ]);
    }
}
