<?php

namespace App\Controller;

use App\Service\BillService;
use App\Service\ProductService;
use App\Validator\BillValidator;
use App\Validator\ProductValidator;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends AbstractFOSRestController {

    /**
     * Invest in investment
     *
     * GET Route annotation.
     * @Rest\Get("/products")
     */
    public function getProducts(Request $request, ProductService $productService) {
        $userId = $request->get('id');
        $products = $productService->getProducts($userId);
        $view = $this->view($products, 200);
        return $this->handleView($view);
    }

    /**
     * Invest in investment
     *
     * POST Route annotation.
     * @Rest\Delete("/product/{id}")
     */
    public function deleteProduct($id, ProductService $productService) {
        $delete = $productService->deleteProduct($id);
        $view = $this->view($delete, 200);

        return $this->handleView($view);
    }

    /**
     * Invest in investment
     *
     * POST Route annotation.
     * @Rest\Post("/bill/{billId}/product")
     */
    public function addProduct($billId, Request $request, ProductService $productService) {
        $json = json_decode($request->request->get('data'), true);
//        \Doctrine\Common\Util\Debug::dump($json['guarantee']);
//        die('lol');
        $files = $request->files;
        $path = $this->getParameter('images_directory');
        $validator = new ProductValidator();
        if (!$validator->valid($json, $files)) {
            $post['errors'] = $validator->getErrors();
            $view = $this->view($post, 400);
            return $this->handleView($view);
        }
        $productService->addProduct($billId, $json, $files, $path);
        $view = $this->view(['message' => 'dodano nowy product'], 200);
        return $this->handleView($view);
    }

    /**
     * Invest in investment
     *
     * POST Route annotation.
     * @Rest\Post("/bill/{billId}/product/{productId}")
     */
    public function updateProduct($id, Request $request, ProductService $productService) {
        $json = json_decode($request->request->get('data'), true);
        $validator = new ProductValidator();
        if (!$validator->validWithoutImage($json)) {
            $post['errors'] = $validator->getErrors();
            $view = $this->view($post, 400);
            return $this->handleView($view);
        }
        $productService->updateProduct($id, $json);
        $view = $this->view(['message' => 'zapisano zmiany w paragonie o id ' . $id], 200);
        return $this->handleView($view);
    }

}