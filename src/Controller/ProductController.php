<?php

namespace App\Controller;

use App\Dto\ProductDto;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Polyfill\Intl\Normalizer\Normalizer;

#[Route('/product', name: 'app_product')]
final class ProductController extends AbstractController
{
    #[Route('/list', name: 'app_product_list', methods: ['GET'])]
    public function index(
        ProductRepository $productRepo
    ): JsonResponse
    {
        $products = $productRepo->findBy([], limit: 10);

        return $this->json([
            'products' => $products
        ]);
    }

    #[Route('/show/{product}', name: 'app_product_show', methods: ['GET'])]
    public function show(?Product $product): JsonResponse
    {

        if ($product == null) {
            return $this->json(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }
        return $this->json($product);
    }

    #[Route('/update/{product}', name: 'app_product_update', methods: ['POST'])]
    public function update(
        ?Product $product, 
        #[MapRequestPayload()] ProductDto $productDto,
        EntityManagerInterface $em,
        LoggerInterface $logger
        ): JsonResponse
    {

        if ($product == null) {
            return $this->json(['message' => 'Product not found'], 404);
        }

        try {
            $product->setName($productDto->name);
            $em->persist($product);
            $em->flush();

            return $this->json(['message' => 'Product updated']);

        } catch (Exception $e) {
            $logger->error('Product update error', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'exception' => $e,
            ]);

            return $this->json(['message' => 'Product update failed'], Response::HTTP_BAD_REQUEST);
        }
    }

}
