<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface; #TODO to suppress when debugged

class JPMController extends AbstractController
{
    //This function exploits locala set By the LocaleSubscriber Service
	private function getCurrentLocaleFromLocaleSubscriberService(Request $request){
        $found_locale = $request->getLocale();
        if($found_locale == null){
            $found_locale = $this->container->getParameter('default_locale');
        }
        return $found_locale;
    }

    #[Route('/', name: 'app_j_p_m')]
    public function index(Request $request, TranslatorInterface $translator): Response
    {
        $my_locale = $this->getCurrentLocaleFromLocaleSubscriberService($request);
        //var_dump($request->getLocale());
        //$translated = $translator->trans('carousel.berlin_marathon.desc', [], 'messages', 'de');
        //var_dump( $translated );
        return $this->render('jpm/index.html.twig', [
            'nom' => "Jean-Pierre MENA",
			//'locale'=>'en' //for test only
			'locale'=>$my_locale
        ]);
    }

    #[Route("/setlocale/{language}", name:"setlocale")]
    public function setLocaleAction($language, Request $request): Response
    {
        if($language != null)
		{
            $request->getSession()->set('_locale', $language);
		}

		$url = $request->headers->get('referer');
		if(empty($url))
		{
			$url = $this->container->get('router')->generate('app_j_p_m');
		}
		return $this->redirect($url);
    }
}
