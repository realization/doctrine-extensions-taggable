<?php

namespace Anh\Taggable\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="tagging_idx", columns={"tagId", "resourceType", "resourceId"})
 *      }
 * )
 * @ORM\Entity(repositoryClass="Anh\Taggable\Entity\TaggingRepository")
 */
class Tagging extends MappedSuperclass\AbstractTagging
{
    /**
     * @var $tag
     *
     * @ORM\ManyToOne(targetEntity="Tag", inversedBy="tagging", fetch="EAGER")
     * @ORM\JoinColumn(name="tagId", referencedColumnName="id")
     */
    protected $tag;
}
