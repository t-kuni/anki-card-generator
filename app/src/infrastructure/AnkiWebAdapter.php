<?php

namespace TKuni\AnkiCardGenerator\Infrastructure;

use Nesk\Puphpeteer\Puppeteer;
use Nesk\Rialto\Data\JsFunction;
use TKuni\AnkiCardGenerator\Infrastructure\interfaces\IAnkiWebAdapter;

class AnkiWebAdapter implements IAnkiWebAdapter
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    public function __construct(\Psr\Log\LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function addCard($deck, $front, $back) {
        $puppeteer = new Puppeteer([
        ]);
        $browser   = $puppeteer->launch([
            'executablePath'  => getenv('CHROMIUM_PATH'),
            'defaultViewport' => [
                'width'            => 800,
                'height'           => 800,
                'logger'           => $this->logger,
                'log_node_console' => true,
                'debug'            => true,
            ],
            'args'            => [
                '--no-sandbox', '--disable-setuid-sandbox',
            ],
        ]);

        $this->logger->info('ログイン開始');
        $page = $browser->newPage();
        $page->goto('https://ankiweb.net/account/login');
        $page->type('#email', getenv('ANKI_WEB_ID'));
        $page->type('#password', getenv('ANKI_WEB_PW'));
        $page->querySelector('input[type="submit"]')->press('Enter');
        $page->waitForNavigation();
        //$page->screenshot(['path' => '/app/test.png']);
        $this->logger->info('ログイン終了');

        $this->logger->info('カード追加開始');
        $page->goto('https://ankiuser.net/edit/');
        $page->querySelectorEval('#deck', JsFunction::createWithParameters(['node'])
            ->body('node.value = ""'));
        $page->type('#deck', $deck);
        $page->type('#f0', $front);
        $page->type('#f1', $back);
        $page->querySelector('button.btn-primary')->press('Enter');
        //$page->screenshot(['path' => '/app/test.png']);
        $this->logger->info('カード追加完了');

        $browser->close();
    }
}