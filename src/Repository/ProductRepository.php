<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Supplier;
use App\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findLatest(int $page = 1): Paginator
    {
        $qb = $this->createQueryBuilder('p')->orderBy('p.createdOn', 'DESC');
        return (new Paginator($qb))->paginate($page);
    }

    public function findByFilters(?Supplier $supplier, ?Category $category, int $page = 1): Paginator
    {
        $qb = $this->createQueryBuilder('p');
        $qb->orderBy('p.createdOn', 'DESC');

        if ($supplier) {
            $qb->innerJoin('p.suppliers', 's')
               ->andWhere('s = :supplier')
               ->setParameter('supplier', $supplier);
        }
        if ($category) {
            $qb->innerJoin('p.category', 'c')
               ->andWhere('c = :category')
               ->setParameter('category', $category);
        }
        return (new Paginator($qb))->paginate($page);

    }

//    /**
//     * @return Product[] Returns an array of Product objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
