<?php

namespace App\DataProvider;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\PaginationExtension;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryResultCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGenerator;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\Exception\ResourceClassNotSupportedException;
use App\Entity\Apprenant;
use App\Entity\ApprenantLivrablePartiel;
use App\Entity\Brief;
use App\Entity\BriefApprenant;
use App\Entity\BriefMaPromo;
use App\Entity\CompetencesValides;
use App\Entity\Promo;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface, ItemDataProviderInterface
{
    private $context;
    private $managerRegistry;
    private $paginator;

    public function __construct(ManagerRegistry $managerRegistry, PaginationExtension $paginator)
    {
        $this->managerRegistry = $managerRegistry;
        $this->paginator = $paginator;
    }
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        $this->context = $context;
        return $resourceClass;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable
    {
        if ($operationName === "get_appAttente"){
            $App = $this->managerRegistry->getRepository(Apprenant::class)
                ->createQueryBuilder('a')
                ->andWhere('a.attente= :val')
                ->setParameter('val', true);
            $this->paginator->applyToCollection($App, new QueryNameGenerator(), $resourceClass, $operationName, $this->context);

            if ($this->paginator instanceof QueryResultCollectionExtensionInterface
                && $this->paginator->supportsResult($resourceClass, $operationName, $this->context)) {

                return $this->paginator->getResult($App, $resourceClass, $operationName, $this->context);
            }

            return $App->getQuery()->getResult();
        }
        if($operationName === "getOneApprenantAvecCpmt" || $operationName === "getOneAppAvecBrief"){
            $c = explode('/', $context["request_uri"]);
            $App = $this->managerRegistry->getRepository(Apprenant::class)
                            ->createQueryBuilder('a')
                            ->andWhere('a.id= :idA')
                            ->setParameter('idA', $c["3"])
                            ->innerJoin('a.promo', 'p')
                            ->andWhere('p.id= :idP')
                            ->setParameter('idP', $c["5"])
                            ->innerJoin('p.referentiel', 'r')
                            ->andWhere('r.id= :idR')
                            ->setParameter('idR', $c["7"]);
            return $App->getQuery()->getResult();
        }
        if ($operationName === "getCollection_apprenant"){
            $c = explode('/', $context["request_uri"]);
            //dd($c);
            $App = $this->managerRegistry->getRepository(Apprenant::class)
                ->createQueryBuilder('a')
                ->innerJoin('a.promo', 'p')
                ->andWhere('p.id= :idP')
                ->setParameter('idP', $c["4"])
                ->innerJoin('p.referentiel', 'r')
                ->andWhere('r.id= :idR')
                ->setParameter('idR', $c["6"]);
            $this->paginator->applyToCollection($App, new QueryNameGenerator(), $resourceClass, $operationName, $this->context);

            if ($this->paginator instanceof QueryResultCollectionExtensionInterface
                && $this->paginator->supportsResult($resourceClass, $operationName, $this->context)) {

                return $this->paginator->getResult($App, $resourceClass, $operationName, $this->context);
            }

            return $App->getQuery()->getResult();
        }
        if ($operationName === "get_brief_app"){
            $c = explode('/', $context["request_uri"]);
            //dd($c);
            $br = $this->managerRegistry->getRepository(BriefApprenant::class)
                ->createQueryBuilder('brA')
                ->innerJoin('brA.briefMaPromo', 'brmp')
                ->innerJoin('brmp.promo', 'p')
                ->andWhere('p.id= :val')
                ->setParameter('val', $c["4"])
                ;
            $this->paginator->applyToCollection($br, new QueryNameGenerator(), $resourceClass, $operationName, $this->context);

            if ($this->paginator instanceof QueryResultCollectionExtensionInterface
                && $this->paginator->supportsResult($resourceClass, $operationName, $this->context)) {

                return $this->paginator->getResult($br, $resourceClass, $operationName, $this->context);
            }

            return $br->getQuery()->getResult();
        }
        if ($operationName === "get_briefBrouillos_formateur"){
            $c = explode('/', $context["request_uri"]);
            //dd($c);
            $br = $this->managerRegistry->getRepository(Brief::class)
                ->createQueryBuilder('br')
                ->innerJoin('br.formateur', 'f')
                ->andWhere('f.id= :val')
                ->setParameter('val', $c["3"])
                ->andWhere('br.etatBrouillonsAssigneValide= :etat')
                ->setParameter('etat', "brouillon")
            ;
            $this->paginator->applyToCollection($br, new QueryNameGenerator(), $resourceClass, $operationName, $this->context);

            if ($this->paginator instanceof QueryResultCollectionExtensionInterface
                && $this->paginator->supportsResult($resourceClass, $operationName, $this->context)) {

                return $this->paginator->getResult($br, $resourceClass, $operationName, $this->context);
            }

            return $br->getQuery()->getResult();
        }
        if ($operationName === "get_briefvalide_formateur"){
            $c = explode('/', $context["request_uri"]);
            //dd($c);
            $br = $this->managerRegistry->getRepository(Brief::class)
                ->createQueryBuilder('br')
                ->innerJoin('br.formateur', 'f')
                ->andWhere('f.id= :val')
                ->setParameter('val', $c["3"])
                ->andWhere('br.etatBrouillonsAssigneValide= :etat')
                ->setParameter('etat', "valide")
            ;
            $this->paginator->applyToCollection($br, new QueryNameGenerator(), $resourceClass, $operationName, $this->context);

            if ($this->paginator instanceof QueryResultCollectionExtensionInterface
                && $this->paginator->supportsResult($resourceClass, $operationName, $this->context)) {

                return $this->paginator->getResult($br, $resourceClass, $operationName, $this->context);
            }

            return $br->getQuery()->getResult();
        }
        if ($operationName === "get_Onebrief_promo"){
            $c = explode('/', $context["request_uri"]);
            //dd($c);
            $br = $this->managerRegistry->getRepository(Brief::class)
                ->createQueryBuilder('br')
                ->innerJoin('br.briefMaPromos', 'brmp')
                ->innerJoin('brmp.promo', 'p')
                ->andWhere('p.id= :val')
                ->setParameter('val', $c["4"])
                ->andWhere('br.id= :idbr')
                ->setParameter('idbr', $c["6"])
            ;
            $this->paginator->applyToCollection($br, new QueryNameGenerator(), $resourceClass, $operationName, $this->context);

            if ($this->paginator instanceof QueryResultCollectionExtensionInterface
                && $this->paginator->supportsResult($resourceClass, $operationName, $this->context)) {

                return $this->paginator->getResult($br, $resourceClass, $operationName, $this->context);
            }

            return $br->getQuery()->getResult();
        }
        
        $repository = $this->managerRegistry->getRepository($resourceClass)
                                            ->createQueryBuilder('i')
                                            ->andWhere('i.archiver= :val')
                                            ->setParameter('val', false);
        $this->paginator->applyToCollection($repository, new QueryNameGenerator(), $resourceClass, $operationName, $this->context);

        if ($this->paginator instanceof QueryResultCollectionExtensionInterface
            && $this->paginator->supportsResult($resourceClass, $operationName, $this->context)) {

            return $this->paginator->getResult($repository, $resourceClass, $operationName, $this->context);
        }

        return $repository->getQuery()->getResult();

    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = [])
    {
        if ($operationName === "getOne_appAttente"){
            $c = explode('/', $context["request_uri"]);
            $App = $this->managerRegistry->getRepository(Apprenant::class)
                ->createQueryBuilder('a')
                ->andWhere('a.attente= :val')
                ->setParameter('val', true)
                ->andWhere('a.id= :value')
                ->setParameter('value', $c["4"]);
            return $App->getQuery()->getResult();
        }
        $repository = $this->managerRegistry->getRepository($resourceClass)
                                            ->createQueryBuilder('i')
                                            ->andWhere('i.id= :id')
                                            ->setParameter('id', $id)
                                            ->andWhere('i.archiver= :val')
                                            ->setParameter('val', false);
        return $repository->getQuery()->getResult();
    }
}