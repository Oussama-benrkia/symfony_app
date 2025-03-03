<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PostController extends AbstractController
{
    #[Route('/posts', name: 'app_posts')]
    public function index(Request $request)
    {
        $search=$request->query->get('search')?$request->query->get('search'):"";
        $category=$request->query->get('category')?$request->query->get('category'):"";

        // Create an HTTP client
        $client = HttpClient::create();
        $string="";
        if(!empty($search)){
            $string="?search=" . urlencode(trim($search));
        }
        if(!empty($category)){
            $arr=explode("-", $category);
            $type="";
            if($arr[0]=="ctg"){
                $type="categories=".$arr[1];
            }elseif($arr[0]=="tgs") {
                $type="tags=".$arr[1];
            }elseif($arr[0]=="dfl") {
                $type="niveau_difficulte=".$arr[1];
            }
            if(!empty($type)){
                if(empty($string)){
                    $string="?";
                }else{
                    $string=$string."&";
                }
                $string=$string.$type;
            }
        }
        $localhost = $this->getParameter('WORDPRESS_API_URL');
        $baseUrl = "http://$localhost/wp-json/wp/v2/";
        
        // Construct the full URL with query parameters
        $requestUrl = $baseUrl . "posts" . $string;
        $response = $client->request('GET', $requestUrl);
        
        $response_catg = $client->request('GET', $baseUrl . "categories");
        $response_tag = $client->request('GET', $baseUrl . "tags");
        $response_difi = $client->request('GET', $baseUrl . "niveau_difficulte");
        
        // Convert JSON response to an array
        $posts = $response->toArray();
        $ctg = $response_catg->toArray();
        $tag = $response_tag->toArray();
        $dif = $response_difi->toArray();

        return $this->render('article/index.html.twig',[
            'data'=>$posts,
            'search'=>$search,
            'ctg'=>$ctg,
            'tag'=>$tag,
            'dif'=>$dif,
        ]); 
        }
}
