<?php

namespace App\Service;

use App\Entity\Listings;
use App\Repository\ListingsRepository;
use Doctrine\ORM\EntityNotFoundException;
use Exception;
use Doctrine\ORM\EntityManagerInterface;

class ListingsService {
    private ListingsRepository $listingsRepository;
    private EntityManagerInterface $entityManager;
    public function __construct(ListingsRepository $listingsRepository,EntityManagerInterface $entityManager)
    {
        $this->listingsRepository=$listingsRepository;
        $this->entityManager=$entityManager;
    }

    public function getListingById(int $id){
        $listing=$this->listingsRepository->find($id);
        if(!$listing){
            throw new EntityNotFoundException("Post with $id not found");
        }
        return $listing;
    }

    public function getallListing(){
        return $this->listingsRepository->findAll();
    }
    public function getallListingbysearch(string $search){
        return $this->listingsRepository->findallbysearch_List($search);

    }
    public function getallListingbyTags(string $tags){
        return $this->listingsRepository->findallbyTags_List($tags);
    }

    /**Pagination */
    public function getallListing_pagination(int $page, int $limit){
        return $this->listingsRepository->getfindall_pagination($page, $limit);
    }
    
    public function getallListingbysearch_pagination(int $page, int $limit, string $search){
        return $this->listingsRepository->getListings_Search_pagination($page, $limit, $search);
    }
    
    public function getallListingbyTags_pagination(int $page, int $limit, string $tags){
        return $this->listingsRepository->getListings_tag_pagination($page, $limit, $tags);
    }
    
    public function saveListing(Listings $listing,string $path){
        $data=$this->listingsRepository->getListings_title_List($listing->getTitle());
        if($data){
            throw new Exception($listing->getTitle(),'all ready exist');
        }
        $listing->setImage($path);
        $tag=explode(',',$listing->getTags());
        $listing->setTags(json_encode($tag));
        $this->entityManager->persist($listing);
        $this->entityManager->flush();
    }
    public function updateListing(){

    }
    public function deleteListing(int $id){
        
    }
}