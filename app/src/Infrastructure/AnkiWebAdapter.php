<?php

namespace TKuni\AnkiCardGenerator\Infrastructure;

use Nesk\Puphpeteer\Puppeteer;
use Nesk\Rialto\Data\JsFunction;
use Psr\Log\LoggerInterface;
use TKuni\AnkiCardGenerator\Domain\Models\Card;
use TKuni\AnkiCardGenerator\Infrastructure\interfaces\IAnkiWebAdapter;

class AnkiWebAdapter implements IAnkiWebAdapter
{
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var Puppeteer
     */
    private $puppeteer;

    private $browser;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;

        $this->puppeteer = new Puppeteer([
        ]);

        $this->browser   = $this->puppeteer->launch([
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
    }

    public function login($id, $pw) {
        # Don't logging $id and $pw because it be include sensitive info.
        $this->logger->info('Start to login to Anki Web');

        $page = $this->browser->newPage();
        $page->goto('https://ankiweb.net/account/login');
        $page->type('#email', $id);
        $page->type('#password', $pw);
        $page->querySelector('input[type="submit"]')->press('Enter');
        $page->waitForNavigation();
        $page->close();
        //$page->screenshot(['path' => '/app/test.png']);

        $this->logger->info('End to login to Anki Web');
    }

    public function saveCard($deck, Card $card) {
        $this->logger->info('Start to add card to Anki Web', func_get_args());

        $page = $this->browser->newPage();
        $page->goto('https://ankiuser.net/edit/');
        $page->querySelectorEval('#deck', JsFunction::createWithParameters(['node'])
            ->body('node.value = ""'));
        $page->type('#deck', $deck);
        $page->type('#f0', $card->front());
        $page->type('#f1', $card->back());
        $page->querySelector('button.btn-primary')->press('Enter');
        $page->close();
        //$page->screenshot(['path' => '/app/test.png']);

        $this->logger->info('End to add card to Anki Web');
    }

    function __destruct()
    {
        $this->browser->close();
    }
}