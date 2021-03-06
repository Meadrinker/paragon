<?php

namespace App\Controller;

use App\Service\BillService;
use App\Validator\BillValidator;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

class BillController extends AbstractFOSRestController {

    /**
     * Invest in investment
     *
     * GET Route annotation.
     * @Rest\Get("/bills")
     */
    public function getBills(Request $request, BillService $billService) {
        $userId = $request->get('id');
        $bills = $billService->getBills($userId);
        $view = $this->view($bills, 200);
        return $this->handleView($view);
    }

    /**
     * Invest in investment
     *
     * GET Route annotation.
     * @Rest\Get("/bill/{id}")
     */
    public function getBill($id, BillService $billService) {
        $bill = $billService->getBill($id);
        $view = $this->view($bill, 200);
        return $this->handleView($view);
    }

    /**
     * Invest in investment
     *
     * POST Route annotation.
     * @Rest\Delete("/bill/{id}")
     */
    public function deleteBill($id, BillService $billService) {
        $delete = $billService->deleteBill($id);
        $view = $this->view($delete, 200);

        return $this->handleView($view);
    }

    /**
     * Invest in investment
     *
     * POST Route annotation.
     * @Rest\Post("/bill")
     */
    public function addBill(Request $request, BillService $billService) {
//        \Doctrine\Common\Util\Debug::dump($request);
//        die('lol');
        $json = json_decode($request->request->get('data'), true);
        $files = $request->files;
        $path = $this->getParameter('images_directory');
        $validator = new BillValidator();
        if (!$validator->valid($json, $files)) {
            $post['errors'] = $validator->getErrors();
            $view = $this->view($post, 400);
            return $this->handleView($view);
        }
        $billService->addBill($json, $files, $path);
        $view = $this->view(['message' => 'dodano nowy paragon'], 200);
        return $this->handleView($view);
    }

    /**
     * Invest in investment
     *
     * POST Route annotation.
     * @Rest\Post("/bill/{id}")
     */
    public function updateBill($id, Request $request, BillService $billService) {
//        \Doctrine\Common\Util\Debug::dump($array);
//        die('lol');
        $json = json_decode($request->request->get('data'), true);
        $path = $this->getParameter('images_directory');
        $files = $request->files;
        $validator = new BillValidator();
        if (!$validator->valid($json, $files)) {
            $post['errors'] = $validator->getErrors();
            $view = $this->view($post, 400);
            return $this->handleView($view);
        }
        $billService->updateBill($id, $json, $files, $path);
        $view = $this->view(['message' => 'zapisano zmiany w paragonie o id ' . $id], 200);
        return $this->handleView($view);
    }

}