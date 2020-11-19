<?php

namespace App\Entity;

use App\Repository\ApprenantRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass=ApprenantRepository::class)
 * @ApiResource(
 *      collectionOperations={
 *          "get"={
 *              "access_control"="(is_granted('ROLE_Administrateur') or is_granted('ROLE_Formateur') or is_granted('ROLE_CM'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource"
 *          }, 
 *          "post"={
 *              "access_control"="(is_granted(is_granted('ROLE_Administrateur') or 'ROLE_Formateur'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource"
 *          }     
 *      },
 *     itemOperations={
 *          "get"={
 *              "access_control"="(is_granted('ROLE_Administrateur') or is_granted('ROLE_Formateur') or is_granted('ROLE_CM'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource"
 *          },
 *          "put"={
 *              "access_control"="(is_granted('ROLE_Administrateur') or is_granted('ROLE_Formateur'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource"
 *          }
 *     },
 * )
 */
class Apprenant extends User
{
}
