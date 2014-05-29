<?php
/**
 * 
 * User: Winston
 * Date: 14/5/14
 * Time: 12:47 PM
 */

namespace Application\Model\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityRepository;


/**
 * Class TrackInfo
 * @package Application\Model\Entity
 *
 * @ORM\Table(name="trackinfo")
 * @ORM\Entity(repositoryClass="Application\Model\Entity\Repository\TrackInfo")
 */
class TrackInfo {

    /**
     * @var
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $id;

    /**
     * @var
     * @ORM\Column(type="string", nullable=true)
     */
    protected $title;

    /**
     * @var
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $description;

    /**
     * @var
     *
     * @ORM\Column(type="string")
     */
    protected $year;

    /**
     * @var
     *
     * @ORM\Column(type="integer")
     */
    protected $category_id;

    /**
     * @var
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $created_date;

    /**
     * @var
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $updated_date;



    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getYear()
    {
        return $this->year;
    }

    public function getCategoryId()
    {
        return $this->category_id;
    }

    public function getCreatedDate()
    {
        return $this->created_date;
    }

    public function getUpdatedDate()
    {
        return $this->updated_date;
    }


    public function setTitle( $title )
    {
        $this->title = $title;
        return $this;
    }

    public function setDescription( $description )
    {
        $this->description = $description;
        return $this;
    }

    public function setYear( $year )
    {
        $this->year = $year;
        return $this;
    }

    public function setCategoryId( $category_id )
    {
        $this->category_id = $category_id;
        return $this;
    }

    public function setCreatedDate( $created_date )
    {
        $this->created_date = $created_date;
        return $this;
    }

    public function setUpdatedDate( $updated_date )
    {
        $this->updated_date = $updated_date;
        return $this;
    }

} 