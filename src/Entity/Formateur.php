<?php

namespace App\Entity;

use App\Repository\FormateurRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass=FormateurRepository::class)
 * @ApiResource(
 *      collectionOperations={
 *          "get"={
 *              "access_control"="(is_granted(is_granted('ROLE_Administrateur') or 'ROLE_CM'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource"
 *          }     
 *      },
 *     itemOperations={
 *          "get"={
 *              "access_control"="(is_granted('ROLE_Administrateur') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource"
 *          },
 *          "put"={
 *              "access_control"="(is_granted('ROLE_Administrateur') or is_granted('ROLE_FORMATEUR'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource"
 *          }
 *     },
 * )
 */
class Formateur extends User
{
}
