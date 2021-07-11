<?php

namespace App\Action\Home;

use App\Domain\Price\Service\PriceFinder;
use App\Responder\Responder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

/**
 * Action.
 */
final class HomeAction
{
    private $twig;
    private PriceFinder $priceFinder;
    private Responder $responder;


    /**
     * The constructor.
     *
     * @param PriceFinder $priceIndex The price index list viewer
     * @param Responder $responder The responder
     */
    public function __construct(Twig $twig, PriceFinder $priceIndex, Responder $responder)
    {
        $this->twig = $twig;

        $this->priceFinder = $priceIndex;
        $this->responder = $responder;
    }

    /**
     * Action.
     *
     * @param ServerRequestInterface $request The request
     * @param ResponseInterface $response The response
     *
     * @return ResponseInterface The response
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $prices = $this->priceFinder->findPrices();
        $viewData = [
            // 'prices' => "tes",
            'prices' => $prices,
            "router" => ''
        ];
        
        return $this->twig->render($response, 'home/index.twig', $viewData);
        // return $this->responder->withTemplate($response, 'home/index.twig', $viewData);
        // return $this->responder->withRedirectFor($response, 'docs');
    }
}
