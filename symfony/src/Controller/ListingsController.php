<?php

namespace App\Controller;

use App\Entity\Listings;
use App\Form\ListingType;
use App\Service\ListingsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class ListingsController extends AbstractController
{
    
    private ListingsService $listingsService;
    public function __construct( ListingsService $listingsService)
    {
        $this->listingsService=$listingsService;
    }

/*
    #[Route('/listings', name: 'app_listings')]
    public function index(): JsonResponse
    {
        dd($this->listingsService->getListingById(1));
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ListingsController.php',
        ]);
    }*/


    #[Route('/', name: 'home',methods:['GET'])]
    public function index(Request $request)
    {
        $page=$request->query->get('page')?intval($request->query->get('page')):1;
        $limit=$request->query->get('limit')?intval($request->query->get('limit')):6;
        $search=$request->query->get('search')?$request->query->get('search'):"";
        $data=null;
        if(!empty($search)){
            $data=$this->listingsService->getallListingbysearch_pagination($page,$limit,$search);
        }else if($request->query->get('tag')){
            $data=$this->listingsService->getallListingbyTags_pagination($page,$limit,$request->query->get('tag'));
        }else{
            $data=$this->listingsService->getallListing_pagination($page,$limit);
        }
        return $this->render('Listing/index.html.twig', [
            'data' => $data,
            'search'=>$search,
            'page'=>$page,
        ]);
    }
    #[Route('/Listing/{id<\d+>}', name: 'Listing.show', methods:['GET'])]
    public function show(int $id)
    {
        $data=$this->listingsService->getListingById($id);
        return $this->render('Listing/show.html.twig', [
            'data' => $data,
            "search"=>""
        ]);
    }

    #[Route('/Listing/add',name:"Listing.presave")]
    public function save(Request $request,Security $security)
    {
        if (!$security->getUser()) {
            return new RedirectResponse($this->generateUrl('app_login'));
        }
        $listing=new Listings();
        $form=$this->createForm(
            ListingType::class,$listing
        );
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()){
            $data=$form->getData();
            $imagePath =$form->get("logo")->getData();
            $path="";
            if($imagePath ){
                $newFileName = uniqid() . '.' . $imagePath->guessExtension();
                $imagePath->move(
                    $this->getParameter('kernel.project_dir') . '/public/uploads',
                    $newFileName
                );
                $path='/uploads/' . $newFileName;
            }
            $this->listingsService->saveListing($data,$path);
            return $this->redirectToRoute('home');
        }
        return $this->render('Listing/create.html.twig',[
            'form'=>$form->createView(),
        ]);
    }
}
