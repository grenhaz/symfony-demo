<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\Service\MovieService;

/**
 * Controlador base de la web.
 * 
 * @author obarcia
 */
class WebController extends AbstractController
{
    /**
     * @Route("/", name="app_index", methods={"GET"})
     * 
     * Portada.
     */
    public function index(MovieService $movieService)
    {
        return $this->render('_index.html.twig', [
            'alertMovies'       => $movieService->findByCategory("alerts")
        ]);
    }
    /**
     * @Route("/movies", name="app_movies", methods={"GET"})
     * 
     * Listado completo de películas.
     */
    public function movies(MovieService $movieService)
    {
        return $this->render('movies/_movies.html.twig', [
            'movies'        => $movieService->findAll()
        ]);
    }
    
    /**
     * @Route("/movies/ajax/{category}", name="app_movies_ajax", methods={"GET"})
     * 
     * Listado completo de películas.
     */
    public function movies_ajax($category, MovieService $movieService)
    {
        return $this->render('movies/_movies_ajax.html.twig', [
            'movies' => $movieService->findByCategory($category)
        ]);
    }
    
    /**
     * @Route("/movies/{id}", name="app_movie", methods={"GET"})
     * 
     * Mostrar una película.
     */
    public function movie($id, MovieService $movieService)
    {
        $movie = $movieService->findOne($id);
        
        return $this->render('movies/_movie.html.twig', [
            'movie' => $movie
        ]);
    }
    
    /**
     * @Route("/categories/{id}", name="app_categories", methods={"GET"})
     * 
     * Mostrar una categoría.
     */
    public function categories($id, MovieService $movieService)
    {
        $category = $movieService->findCategory($id);
        
        return $this->render('movies/_category.html.twig', [
            'category'  => $category,
            'movies'    => $movieService->findByCategory($id)
        ]);
    }
    
    /**
     * @Route("/movies/{id}/image", name="app_movie_image", methods={"GET"})
     * 
     * Imagen de una película.
     */
    public function movieImage($id, MovieService $movieService)
    {
        $path = $movieService->getImage($id);
        
        $response = new BinaryFileResponse($path);
        
        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            $id.'.jpg'
        );

        $response->headers->set('Content-Disposition', $disposition);
        
        // cache for 3600 seconds
        $response->setSharedMaxAge(3600);

        // (optional) set a custom Cache-Control directive
        $response->headers->addCacheControlDirective('must-revalidate', true);
        
        $response->setCache(array(
            'etag'          => $id,
            'last_modified' => new \DateTime(date("Y-m-d H:i:s", filemtime($path))),
            'max_age'       => 600,
            's_maxage'      => 600,
            'private'       => false,
            'public'        => true
        ));
        
        return $response;
    }
    
    /**
     * @Route("/movies/{id}/video", name="app_movie_video", methods={"GET"})
     * 
     * Imagen de una película.
     */
    public function movieVideo($id, MovieService $movieService)
    {
        $path = $movieService->getVideo($id);
        
        $response = new BinaryFileResponse($path);
        
        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            $id.'.mp4'
        );

        $response->headers->set('Content-Disposition', $disposition);
        
        // cache for 3600 seconds
        $response->setSharedMaxAge(3600);

        // (optional) set a custom Cache-Control directive
        $response->headers->addCacheControlDirective('must-revalidate', true);
        
        $response->setCache(array(
            'etag'          => $id,
            'last_modified' => new \DateTime(date("Y-m-d H:i:s", filemtime($path))),
            'max_age'       => 600,
            's_maxage'      => 600,
            'private'       => false,
            'public'        => true
        ));
        
        return $response;
    }
}