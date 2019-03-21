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
        $json = $request->request;
        $validator = new BillValidator();
        if (!$validator->valid($json)) {
            $post['errors'] = $validator->getErrors();
            $view = $this->view($post, 400);
            return $this->handleView($view);
        }
        $billService->addBill($json);
        $view = $this->view(['message' => 'dodano nowy paragon'], 200);
        return $this->handleView($view);
    }

    /**
     * Invest in investment
     *
     * POST Route annotation.
     * @Rest\Put("/bill/{id}")
     */
    public function updateBill($id, Request $request, BillService $billService) {
        $json = $request->request;
        $validator = new BillValidator();
        if (!$validator->valid($json)) {
            $post['errors'] = $validator->getErrors();
            $view = $this->view($post, 400);
            return $this->handleView($view);
        }
        $billService->updateBill($id, $json);
        $view = $this->view(['message' => 'zapisano zmiany w paragonie o id ' . $id], 200);
        return $this->handleView($view);
    }

}