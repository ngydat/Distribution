<?php

/*
 * This file is part of the Claroline Connect package.
 *
 * (c) Claroline Consortium <consortium@claroline.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Claroline\CoreBundle\Entity\Facet;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="Claroline\CoreBundle\Repository\FieldFacetChoiceRepository")
 * @ORM\Table(name="claro_field_facet_choice")
 */
class FieldFacetChoice
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"api_facet_admin"})
     */
    private $id;

    /**
     * @ORM\Column
     * @Assert\NotBlank()
     * @Groups({"api_facet_admin"})
     */
    private $name;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Claroline\CoreBundle\Entity\Facet\FieldFacet",
     *     inversedBy="fieldFacetChoices"
     * )
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $fieldFacet;

    /**
     * @ORM\Column(type="integer", name="position")
     * @Groups({"api_facet_admin"})
     */
    protected $position;

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setFieldFacet(FieldFacet $ff)
    {
        $this->fieldFacet = $ff;
    }

    public function getFieldFacet()
    {
        return $this->fieldFacet;
    }
}
