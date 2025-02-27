<?php

namespace Okashi;


use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;
use Okashi\OkashiClient;


final class OkashiController {
    private readonly OkashiClient $client;
    private readonly Twig $view;

    public function __construct(ContainerInterface $container)
    {
        $this->client = $container->get(OkashiClient::class);
        $this->view = $container->get(Twig::class);
    }

    public function home(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        return $this->view->render($response, 'index.html.twig');
    }

    public function search(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $params = $request->getQueryParams();
        $keyword = $params['?keyword'] ?? '';
        $year = $params['year'] ?? '';
        $type = $params['type'] ?? '';
        $max = $params['max'] ?? 5;
        $offset = $params['offset'] ?? 0;
        if (empty($keyword) && empty($year) && empty($type)) {
            return $this->view->render(
                $response,
                'index.html.twig',
                ['message' => '検索条件を指定してください']
            );
        }
        $okashi = $this->client->getOkashi($keyword, $year, $type, $max, $offset);
        if ($okashi['status'] === 'OK') {
            return $this->view->render($response, 'index.html.twig', [
                'keyword' => $keyword,
                'year' => $year,
                'type' => $type,
                'max' => $max,
                'offset' => $offset,
                'items' => $okashi['item']
            ]);
        } else {
            return $this->view->render($response, 'index.html.twig', [
                'message' => 'お菓子情報が取得できませんでした'
            ]);
        }
    }
}