<?php

namespace App\Repository;

use App\Entity\Enchere;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Enchere|null find($id, $lockMode = null, $lockVersion = null)
 * @method Enchere|null findOneBy(array $criteria, array $orderBy = null)
 * @method Enchere[]    findAll()
 * @method Enchere[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EnchereRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Enchere::class);
    }
	
	/**
	 * @return Enchere[] Retourne un tableau des enchères disponible
	 */
	public function findByEnchereAvailable()
	{
		date_default_timezone_set('Europe/Paris');
		$date_heure_actuelle = new \DateTime('now');
		return $this->createQueryBuilder('e')
			->andWhere(':date_heure_actuelle > e.date_debut AND :date_heure_actuelle < e.date_fin')
			->setParameter('date_heure_actuelle', $date_heure_actuelle)
            ->getQuery()
            ->getResult();
	}

    // /**
    //  * @return Enchere[] Returns an array of Enchere objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Enchere
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
