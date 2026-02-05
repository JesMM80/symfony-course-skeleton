<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderType;
use App\Entity\OrderItem;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/order')]
final class OrderController extends AbstractController
{
    #[Route('/index', name: 'app_order_index', methods: ['GET'])]
    public function index(OrderRepository $orderRepository): Response
    {
        $orders = $orderRepository->findByUsersWithItems($this->getUser());
        return $this->render('order/index.html.twig', [
            'orders' => $orders,
        ]);
    }

    #[Route('/create', name: 'app_order_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $order = new Order();
        $order->setUser($this->getUser());
        $order->addItem(new OrderItem());

        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $order->setStatus('pending');
            $order->calculateTotal();

            $entityManager->persist($order);
            $entityManager->flush();

            return $this->redirectToRoute('app_order_index');
        }


        return $this->render('order/new.html.twig', [
            'order' => $order,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_order_show', methods: ['GET'])]
    public function show(Order $order): Response
    {
        //Comprobamos que el usuario que ha creado la orden es el mismo que esta intentando verla, 
        //si no es asi lanzamos una excepcion de acceso denegado
        if ($order->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('order/show.html.twig', [
            'order' => $order,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_order_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Order $order, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_order_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('order/edit.html.twig', [
            'order' => $order,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_order_delete', methods: ['POST'])]
    public function delete(Request $request, Order $order, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$order->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($order);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_order_index', [], Response::HTTP_SEE_OTHER);
    }

    // #[Route('/{orderId}/item/{itemId}', name: 'app_order_item_delete', methods: ['POST'])]
    // public function deleteItem(Order $order, OrderItem $orderItem): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$order->getId(), $request->getPayload()->getString('_token'))) {
    //         $order->removeItem($order->getItemById($request->get('itemId')));
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('app_order_index', [], Response::HTTP_SEE_OTHER);
    // }
}
