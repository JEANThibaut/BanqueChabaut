<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Account;
use App\Entity\Operation;
use App\Entity\User;
use App\Repository\AccountRepository;
use App\Repository\OperationRepository;
use App\Form\RegistrationFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class ViewController extends AbstractController
{   
    #[Route('/', name: 'home', methods:["GET"])]
    #[Route('/index', name: 'index', methods:["GET"])]
    public function index(OperationRepository $operationRepository): Response
    {
        $accounts= $this->getUser()->getAccounts();
        // $operation = $operationRepository->getLastOperation($accounts[0]->getId(), $this->getUser());
        // dump($accounts[2]->getId());

        return $this->render('view/index.html.twig', [
            'accounts' => $accounts,
        ]);
    }


    #[Route('/user/account/{id}', methods:["GET", "POST"], name: 'single', requirements: ['id' => '\d+'])]
    public function single(int $id=1, AccountRepository $accountRepository): Response
    {
        $account = $accountRepository->find($id);
        $operations = $account->getOperations();

        if($account->getUser() == $this->getUser()){
            return $this->render('view/single.html.twig', [
                "account"=>$account, 
                "operations"=> $operations,
            ]);
        } 
        else{
            return $this->redirectToRoute('index');
        }
    }

    #[Route('/user/profil', methods:["GET", "POST"], name: 'userProfil')]
    public function userProfile(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(RegistrationFormType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $user= $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('index');
        }

        return $this->render('view/userProfil.html.twig', [
            "form" => $form->createView()
        ]);

        // commentaire 

        // commentaire commentaire commentaire commentaire 
        // commentaire 
        // commentaire 
        // commentaire 
        // commentaire 

    }

}