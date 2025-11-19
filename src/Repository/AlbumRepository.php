<?php

namespace App\Repository;

use App\Entity\Album;
use App\Models\FiltreAlbum;
use Doctrine\ORM\Query;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Album>
 *
 * @method Album|null find($id, $lockMode = null, $lockVersion = null)
 * @method Album|null findOneBy(array $criteria, array $orderBy = null)
 * @method Album[]    findAll()
 * @method Album[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlbumRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Album::class);
    }

//    /**
//     * @return Query Returns an array of Album objects
     
    public function listeAlbumsComplete(): ?Query
   {
       return $this->createQueryBuilder('a')
            ->select('a','s','art','m')
            ->innerJoin('a.styles','s')
            ->innerJoin('a.artiste','art')
            ->innerJoin('a.morceaux','m')
            ->orderBy('a.nom', 'ASC')
            ->getQuery()

        ;
    }

//    /**
//     * @return Query Returns an array of Artiste objects
//     */
       public function listeAlbumsCompletePaginee(FiltreAlbum $filtre): Query
    {
        $rechercheNom = $filtre->getNom();
        $query = $this->createQueryBuilder('a')
            ->select('a','s','art','m')
            ->leftJoin('a.styles','s')
            ->leftJoin('a.artiste','art')
            ->leftJoin('a.morceaux','m')
            ->orderBy('a.nom', 'ASC');
            if(!empty($filtre->getNom())){
                $query->andWhere('a.nom LIKE :nom')
                      ->setParameter('nom', "%{$rechercheNom}%");
                    
                    }
             if(!empty($filtre->artiste)){
                $query->andWhere('art.id = :artiste')
                      ->setParameter('artiste', $filtre->artiste->getId());
                    }
            if(!empty($filtre->styles)){
                foreach($filtre->styles as $key => $style){
                    $key++;
                    $query->andWhere(":styleRecherche$key MEMBER OF a.styles")
                          ->setParameter("styleRecherche$key", $style);
                }
            }
            return $query->getQuery();
        ;
    }

//    public function findOneBySomeField($value): ?Album
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
