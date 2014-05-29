<?php
/**
 * 
 * User: Winston
 * Date: 15/5/14
 * Time: 10:21 AM
 */

namespace Application\Model\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityRepository;


/**
 * Class Categories
 * @package Application\Model\Entity
 *
 * @ORM\Table(name="categories")
 * @ORM\Entity(repositoryClass="Application\Model\Entity\Repository\Categories")
 */
class Categories {

    /**
     * @var
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $id;

    /**
     * @var
     *
     * @ORM\Column(type="string", nullable=false)
     */
    protected $name;

    /**
     * @var
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $created_date;

    /**
     * @var
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updated_date;


    /******************************************
     *
     * setter
     *
     ******************************************/

    public function getId()
    {
        return $this->id;
    }


    public function getName()
    {
        return $this->name;
    }


    public function getCreatedDate()
    {
        return $this->created_date;
    }

    public function getUpdatedDate()
    {
        return $this->updated_date;
    }


    /******************************************
     *
     * setter
     *
     ******************************************/
    public function setName( $name )
    {
        $this->name = $name;

        return $this;
    }


    public function setCreatedDate( $created_date )
    {
        $this->created_date = $created_date;

        return $this;
    }


    public function setUpdatedDate( $update_date )
    {
        $this->updated_date = $update_date;

        return $this;
    }

} 