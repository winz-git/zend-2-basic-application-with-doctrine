<?php
/**
 * 
 * User: Winston
 * Date: 14/5/14
 * Time: 12:49 PM
 */

namespace Application\Model\Entity\Repository;


use Doctrine\ORM\EntityRepository;

class TrackInfo extends  EntityRepository {

    public function getAllData()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb->select('c')
            ->from('Application\Model\Entity\TrackInfo', 'c')
            ->where('1=1');



        return $query->getQuery()->getResult();
    }

}