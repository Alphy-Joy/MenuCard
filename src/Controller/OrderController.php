<?php

namespace App\Controller;

use App\Entity\Dishes;
use App\Entity\Order;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/order', name: 'order')]
    public function index(OrderRepository $or)
    {
        $order = $or->findAll(
            ["tableName" => "Table1"]
        );
        return $this->render('order/index.html.twig', [
            'order' => $order,
        ]);
    }
    #[Route('/orders/{id}', name: 'orders')]
    public function orders(Dishes $dishes){
        $order = new Order();
        $order->setTableName("Table 1");
        $order->setOrderNumber($dishes->getId());
        $order->setDishName($dishes->getName());
        $order->setPrice($dishes->getPrice());
        $order->setStatus("open");

        $em = $this->getDoctrine()->getManager();
        $em->persist($order);
        $em->flush();

        $this->addFlash("order",$dishes->getName(). " ordered successfully");
        return $this->redirect($this->generateUrl('menu'));

    }
    #[Route('/status/{id},{status}', name: 'status')]
    public function status($id,$status){
        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository(Order::class)->find($id);
        $order->setStatus($status);
        $em->flush();

        return $this->redirect($this->generateUrl('order'));
    }
    #[Route('/delete/{id}', name: 'delete_order')]
    public function delete($id,OrderRepository $or){
        $em = $this->getDoctrine()->getManager();
        $order = $or->find($id);
        $em->remove($order);
        $em->flush();
        $this->addFlash('success','Order deleted successfully');
        return $this->redirect($this->generateUrl('order'));

    }

}
