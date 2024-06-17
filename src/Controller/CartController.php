<?php

namespace App\Controller;

use App\Entity\Disque;
use App\Repository\DisqueRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/cart', name: 'cart_')]
class CartController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(SessionInterface $session, DisqueRepository $disqueRepository): Response
    {
        $panier = $session->get('panier', []);
        $data = [];
        $total = 0;

        foreach ($panier as $id => $quantity) {
            $product = $disqueRepository->find($id);
            $data[] = [
                'product' => $product,
                'quantity' => $quantity
            ];
            $total += $product->getPrix() * $quantity;
        }

        return $this->render('cart/index.html.twig', compact('data', 'total'));
    }
    #[Route('/add/{id}', name: 'add')]
    public function add(Disque $disque, SessionInterface $session, Request $request)
    {
        // on récupère l'ID produit
        $id = $disque->getId();
        // on récupère le panier existant
        $panier = $session->get('panier', []);
        // on ajoute le produit dans le pannier s'il n'y est pas, ou sinon, on incrémente sa quantité
        if (empty($panier[$id])) {
            // Vérifiez si la quantité demandée est disponible
            if ($disque->getQuantity() > 0) {
                $quantity = $request->request->getInt('quantity', 1);
                $panier[$id] = $quantity;
                
            } else {
                // Retournez un message d'erreur si la quantité demandée n'est pas disponible
                $this->addFlash('error', 'La qualtité demandée n\'est pas disponible');
                return $this->redirectToRoute('cart_index');
            }
        } else {
            // Si l'article est déjà dans le panier, incrémentez la quantité
            if ($disque->getQuantity() > $panier[$id]) {
                $panier[$id]++;
            } else {
                // Retournez un message d'erreur si la quantité demandée n'est pas disponible
                $this->addFlash('error', 'La qualtité demandée n\'est pas disponible');
                return $this->redirectToRoute('cart_index');
            }
        }
        $session->set('panier', $panier);
        // on redirige vers le panier
        return $this->redirectToRoute('cart_index');
    }
    #[Route('/remove/{id}', name: 'remove')]
    public function remove(Disque $disque, SessionInterface $session)
    {
        // on récupère l'ID produit
        $id = $disque->getId();
        // on récupère le panier existant
        $panier = $session->get('panier', []);
        // on retire le produit dans le pannier s'il n'y a qu'un exemplaire, sinon on décrémente la quantité
        if (!empty($panier[$id])) {
            if ($panier[$id] > 1) {
                $panier[$id]--;
            
        } else {
            unset($panier[$id]);
        }
    }
        $session->set('panier', $panier);
        // on redirige vers le panier
        return $this->redirectToRoute('cart_index');
    }
    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Disque $disque, SessionInterface $session)
    {
        // on récupère l'ID produit
        $id = $disque->getId();
        // on récupère le panier existant
        $panier = $session->get('panier', []);
        // on retire le produit dans le pannier
        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }
        $session->set('panier', $panier);
        // on redirige vers le panier
        return $this->redirectToRoute('cart_index');
    }
    #[Route('/empty', name: 'empty')]
    public function empty(SessionInterface $session)
    {
        $session->remove('panier');
        // on redirige vers le panier
        return $this->redirectToRoute('cart_index');
    }
}
