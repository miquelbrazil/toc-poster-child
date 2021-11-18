<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use GuzzleHttp\Client;
use App\Model\Posts;

class PublishPostsCommand extends Command
{
    private $client;

    public function __construct()
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this-> setName('publish-posts')
             -> setDescription('Publish Posts with Poster Child.')
             -> setHelp('Publish Poster Child posts');
    }

    protected function initialize(InputInterface $input, OutputInterface $output) :void
    {
        $this->client = new Client([
            'base_uri' => getenv('SLACK_API_URL'),
            'timeout'  => 2.0,
        ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output) :int
    {
        $Posts = new Posts();
        
        $posts = $Posts->getUnpublished();
        
        if (!empty($posts)) {
            foreach($posts as $post) {
                $response = $this->publish($post);
                $info = json_decode($response->getBody()->getContents());

                if ($info->ok) { $Posts->setPublished($post); };
            }
        } else {
            // @todo: add 'No New Posts' message
        }

        return 0;
    }

    private function publish($post)
    {
        return $this->client->request('POST', 'chat.postMessage', [
            'headers' => [
                'Authorization' => 'Bearer ' . getenv('SLACK_OAUTH_TOKEN'),
                'Content-Type' => 'application/json; charset=utf-8'
            ],
            'json' => [
                'channel' => getenv('SLACK_CHANNEL_ID'),
                'blocks' => [
                    [
                        'type' => 'header',
                        'text' => [
                            'type' => 'plain_text',
                            'text' => $post['title']
                        ]
                    ],
                    [
                        'type' => 'section',
                        'text' => [
                            'type' => 'mrkdwn',
                            'text' => $post['excerpt']
                        ]
                    ],
                    [
                        'type' => 'actions',
                        'elements' => [
                            [
                                'type' => 'button',
                            'text' => [
                                'type' => 'plain_text',
                                'text' => 'Get the Whole Story',
                                'emoji' => true
                            ],
                            'value' => 'viewed',
                            'url' => $post['url'],
                            'action_id' => 'post_viewed'
                            ]
                        ]
                    ],
                    [
                        'type' => 'context',
                        'elements' => [
                            [
                                'type' => 'plain_text',
                                'text' => 'Daily ToC News',
                                'emoji' => true
                            ]
                        ]
                    ]
                ]
            ]
        ]);
    }
}