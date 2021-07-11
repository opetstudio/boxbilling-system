<?php

namespace App\Action\Price;

use App\Domain\Price\Service\PriceFinder;
use App\Domain\Price\Service\PriceCreator;
use App\Domain\Price\Service\PriceReader;

use App\Responder\Responder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

/**
 * Action.
 */
final class PriceAction
{
    private $twig;
    private PriceReader $priceReader;
    private PriceCreator $priceCreator;
    private PriceFinder $priceFinder;
    private Responder $responder;

    /**
     * The constructor.
     *
     * @param PriceCreator $priceIndex The price index list viewer
     * @param PriceFinder $priceIndex The price index list viewer
     * @param Responder $responder The responder
     */
    public function __construct(Twig $twig, PriceReader $priceReader, PriceCreator $priceCreator, PriceFinder $priceIndex, Responder $responder)
    {
        $this->twig = $twig;
        $this->priceReader = $priceReader;
        $this->priceCreator = $priceCreator;
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
            'prices' => $prices,
        ];
        return $this->responder->withTemplate($response, 'home/index.php', $viewData);
        // return $this->responder->withRedirectFor($response, 'docs');
    }
    /**
     * Action.
     *
     * @param ServerRequestInterface $request The request
     * @param ResponseInterface $response The response
     *
     * @return ResponseInterface The response
     */
    public function priceNew(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $prices = $this->priceFinder->findPrices();
        $viewData = [
            'prices' => $prices,
        ];
        return $this->responder->withTemplate($response, 'price/priceNew.phtml', $viewData);
        // return $this->responder->withRedirectFor($response, 'docs');
    }
    public function priceNewSubmit(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        // Extract the form data from the request body
        $data = (array)$request->getParsedBody();
        // Invoke the Domain with inputs and retain the result
        $priceId = $this->priceCreator->createPrice($data);

        // $response = $response->withRedirect("/prices");
        // return $response;
    

        // $prices = $this->priceFinder->findPrices();
        // $viewData = [
        //     'prices' => $prices,
        // ];
        // return $this->responder->withTemplate($response, 'price/priceNew.phtml', $viewData);
        return $this->responder->withRedirectFor($response, 'priceListAll');
    }
    public function priceDetail(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        // Fetch parameters from the request
        $id = (int)$args['id'];
        // Invoke the domain (service class)
        $price = $this->priceReader->getPriceData($id);
        $viewData = [
            'id' => $id,
            'price' => $price,
        ];
        return $this->twig->render($response, 'price/priceDetail.twig', $viewData);
        // return $this->responder->withTemplate($response, 'price/priceDetail.phtml', $viewData);
    }
    public function priceListAll(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $prices = $this->priceFinder->findPrices();
        $viewData = [
            'prices' => $prices,
            "router" => ''
        ];
        
        return $this->twig->render($response, 'price/priceListAll.twig', $viewData);
    }
}
