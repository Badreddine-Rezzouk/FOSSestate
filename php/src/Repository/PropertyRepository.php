<?php

namespace App\Repository;

use App\Entity\Property;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PropertyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Property::class);
    }

    public function findAllWithTenantCount(): array
    {
        return $this->getEntityManager()->getConnection()->fetchAllAssociative(
            'SELECT p.id, p.name, p.property_type AS propertyType, p.address, p.city,
                    p.postal_code AS postalCode, p.country,
                    COUNT(DISTINCT l.id) AS tenantCount
             FROM properties p
             LEFT JOIN rentals r ON r.property_id = p.id
             LEFT JOIN leases l ON l.rental_id = r.id AND l.status = "active"
             GROUP BY p.id, p.name, p.property_type, p.address, p.city, p.postal_code, p.country
             ORDER BY p.created_at DESC'
        );
    }
}
