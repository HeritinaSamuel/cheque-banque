<?php

namespace App\Controller;

use App\Entity\Banque;
use App\Entity\Cheque;
use App\Form\ChequeType;
use App\Repository\BanqueRepository;
use App\Repository\ChequeRepository;
use App\Service\StatsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class ChequeController extends AbstractController
{
    /**
     * Accueil de la site gestion de cheque bancaire
     * @Route("/", name="accueil")
     */
    public function home(): Response
    {
        return $this->render("home.html.twig");
    }

    /**
     * @Route("/cheqs/{page<\d+>?1}", name="cheque_index", methods={"GET"})
     */
    public function index(ChequeRepository $chequeRepo, $page): Response
    {
        $limit = 5;

        $start = $page * $limit - $limit;
        //$start = 1 * 5 -5 = 0
        //$start = 2 * 5 -5 = 5

        $total = count($chequeRepo->findAll());

        $pages = ceil($total / $limit);

        return $this->render('cheque/index.html.twig', [
            'cheques' => $chequeRepo->findBy([], [], $limit, $start),
            'pages' => $pages,
            'page' => $page
        ]);
    }

    /**
     * @Route("/new", name="cheque_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $cheque = new Cheque();
        $form = $this->createForm(ChequeType::class, $cheque);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($cheque);
            $entityManager->flush();

            return $this->redirectToRoute('cheque_index');
        }

        return $this->render('cheque/new.html.twig', [
            'cheque' => $cheque,
            'form' => $form->createView(),
        ]);
    }

    
    /**
     * @Route("/{id}/edit", name="cheque_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Cheque $cheque): Response
    {
        $form = $this->createForm(ChequeType::class, $cheque);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            
            return $this->redirectToRoute('cheque_index');
        }

        return $this->render('cheque/edit.html.twig', [
            'cheque' => $cheque,
            'form' => $form->createView(),
            ]);
        }
        
        /**
         * Permet de supprimer une cheque
         * 
         * @Route("/delete-{id}", name="cheque_delete")
         */
        public function delete(Request $request, Cheque $cheque): Response
        {
            $entityManager = $this->getDoctrine()->getManager();

            
            $entityManager->remove($cheque);
            $entityManager->flush();
            // if ($this->isCsrfTokenValid('delete'.$cheque->getId(), $request->request->get('_token'))) {
            // }
            
            return $this->redirectToRoute('cheque_index');
        }
        
        /**
         * Affichage de statistique
         * @Route("/statistic", name="statistic")
         * @param ChartBuilderInterface $chartBuilder
         * @return Response
         */
        public function statistic(ChartBuilderInterface $chartBuilder, StatsService $statsService, BanqueRepository $repo): Response
        {
            $labels = [];
            $datasets = [];

            $stats = $statsService->getStats();
            $bancStats = $statsService->getBancStats();

            $labels = [];
            $datasets = [];

            foreach($bancStats as $data){
                $labels[] = $data['banque'];
                $datasets[] = $data['cheque'];
            }

            $chart = $chartBuilder->createChart(Chart::TYPE_BAR);

            $chart->setData([
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'Statistique des cheques par banque' ,
                        'backgroundColor' => [
                            'rgb (220, 116, 232)',
                            'rgb (102, 204, 105)',
                            'rgb (245, 113, 135)',
                            'rgb (242, 231, 68)'
                        ] ,
                        'borderColor' => [
                            'rgb (247, 12, 36)',
                            'rgb (255, 99, 132)',
                            'rgb (255, 99, 132)',
                            'rgb (255, 99, 132)'
                        ] ,
                        'data' => $datasets,
                    ],
                ],
                ]);
    
            $chart->setOptions([
                'scales' => [
                    'yAxes' => [
                        ['ticks' => ['min' => 0, 'max' => 100]],
                    ],
                ],
            ]);
    
            return $this->render('cheque/statistic.html.twig',[
                'chart' => $chart,
                'stats' => $stats,
                'bancStats' => json_encode($bancStats),
                'cheqBancs' => $repo->findAllChequeBanque(),
            ]);
        }
    
        /**
         * @Route("/{id}", name="cheque_show", methods={"GET"})
         */
        public function show(Cheque $cheque): Response
        {
            return $this->render('cheque/show.html.twig', [
                'cheque' => $cheque,
            ]);
        }
        
        
    }
    