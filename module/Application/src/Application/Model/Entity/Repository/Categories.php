<?php
/**
 * 
 * User: Winston
 * Date: 15/5/14
 * Time: 10:23 AM
 */

namespace Application\Model\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class Categories extends  EntityRepository {

    public function getAllCategories()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb->select('c')
            ->from('Application\Model\Entity\Categories', 'c')
            ->where('1=1');



        return $query->getQuery()->getResult();

    }
} 