<?php
namespace App\Service;

use Symfony\Component\HttpKernel\KernelInterface;
use App\Repository\MovieRepository;
use App\Repository\CategoryRepository;
use App\Exceptions\MovieNotFoundException;
use App\Exceptions\CategoryNotFoundException;

/**
 * Servicio de películas.
 * 
 * @author obarcia
 */
class MovieService 
{
    private $appKernel;
    private $movieRepository;
    private $categoryRepository;

    public function __construct(
            KernelInterface $appKernel,
            MovieRepository $movieRepository,
            CategoryRepository $categoryRepository) 
    {
        $this->appKernel            = $appKernel;
        $this->movieRepository      = $movieRepository;
        $this->categoryRepository   = $categoryRepository;
    }

    /**
     * Devuelve todas las películas.
     * @return Movie Listado.
     */
    public function findAll()
    {
        return $this->movieRepository->findAll();
    }
    
    /**
     * Devuelve una película por su identificador..
     * @param integer $id Identificador de la película.
     * @return Movie Instancia de la película.
     * @throws MovieNotFoundException
     */
    public function findOne($id)
    {
        $movie = $this->movieRepository->find($id);
        
        if (!empty($movie)) {
            return $movie;
        } else {
            throw new MovieNotFoundException();
        }
    }
    
    /**
     * Devuelve una categoría por su identificador.
     * @param integer $id Identificador de la categoría.
     * @return Category Instancia de la categoría.
     * @throws CategoryNotFoundException
     */
    public function findCategory($id)
    {
        $category = $this->categoryRepository->find($id);
        
        if (!empty($category)) {
            return $category;
        } else {
            throw new CategoryNotFoundException();
        }
    }
    
    /**
     * Devuelve el listado de pelóiculas por categoría.
     * @param integer $id Identificador de la categoría.
     * @return array Listado de películas.
     * @throws CategoryNotFoundException
     */
    public function findByCategory($id)
    {
        if (is_numeric($id)) {
            // Comprobar que la categoría existe
            $category = $this->findCategory($id);
            
            return $this->movieRepository->findByCategory($id, 12);
        } else {
            switch ($id) {
                case "recent": return $this->movieRepository->findRecent();
                case "most-viewed": return $this->movieRepository->findMostViewed();
                case "alerts": return $this->movieRepository->findAlerts();
            }
        }
        
        throw new CategoryNotFoundException();
    }
    
    /**
     * Devuelve el path a la imagen de la película.
     * @param integer $id Identificador de la película.
     * @return string Path a la imagen.
     */
    public function getImage($id)
    {
        $movie = $this->findOne($id);
        
        $path = $this->appKernel->getProjectDir();
        
        // TODO: Si no existe devolver una imagen genérica
        return $path."/data/images/".$movie->getImage();
    }
    
    /**
     * Devuelve el path al video de la película.
     * @param integer $id Identificador de la película.
     * @return string Path del video.
     */
    public function getVideo($id)
    {
        $movie = $this->findOne($id);
        
        $path = $this->appKernel->getProjectDir();
        
        // Se devuelve siempre el mismo video
        return $path."/data/movies/movie.mp4";
    }
}