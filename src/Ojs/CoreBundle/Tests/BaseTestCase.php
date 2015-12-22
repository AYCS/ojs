<?php

namespace Ojs\CoreBundle\Tests;

ini_set('session.save_handler', 'files');
ini_set('session.save_path', '/tmp');
ini_set('memory_limit', '-1');
session_start();

use Doctrine\ORM\EntityManager;
use Ojs\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class BaseTestCase
 * @package Ojs\CoreBundle\Tests
 * @return WebTestCase
 */
abstract class BaseTestCase extends WebTestCase
{
    /** @var Application */
    protected $app;

    /** @var  Client */
    protected $client;

    /** @var  EntityManager */
    protected $em;

    /** @var  Router */
    protected $router;

    /** @var  Crawler */
    protected $crawler;

    public function setUp()
    {
        $this->client = $this->createClient();

        $this->app = new Application($this->client->getKernel());
        $this->app->setAutoExit(false);

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->router = static::$kernel->getContainer()->get('router');
    }

    public function command($command, $opts = [])
    {
        $opts['-e'] = 'test';
        $opts['-q'] = null;
        $opts['command'] = $command;

        return $this->app->run(new ArrayInput($opts));
    }

    /**
     *
     * @param null $username
     * @param null $role
     * @throws \Exception
     */
    protected function logIn($username = null, $role = null)
    {
        $session = $this->client->getContainer()->get('session');
        $firewall = 'main';
        $user = $this->em->getRepository('OjsUserBundle:User')
            ->findOneByUsername($username ? $username : 'admin');
        if (!$user) {
            $_role = $this->em->getRepository('OjsUserBundle:Role')
                ->findOneByRole($role[0]);
            $user = $this->em->getRepository('OjsUserBundle:User')
                ->findOneByRole($_role);
        }
        if (!($user instanceof UserInterface)) {
            throw new \Exception("User not find. ".get_class($user));
        }

        $token = new UsernamePasswordToken($user, null, $firewall, $role ? $role : array('ROLE_SUPER_ADMIN'));
        $session->set('_security_'.$firewall, serialize($token));
        $session->save();
        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

    protected function apiRequest($url, $type = 'GET', $data = [])
    {
        $this->client->request(
            $type,
            $url.'?apikey=NTg3YjljYmYzZDA0MDZjMWY4MTlkOTYwMWQyZmNlMTYwMzU0NjY0Yw==',
            $data,
            [],
            ['HTTP_ACCEPT' => 'application/json']
        );
        /** @var Response $response */
        $response = $this->client->getResponse();

        return $response;
    }

    protected function getInputStream($input)
    {
        $stream = fopen('php://memory', 'r+', false);
        fputs($stream, $input);
        rewind($stream);

        return $stream;
    }

    protected function isAccessible($params, $data = [], $type = 'GET', $redirectOnSuccess = false)
    {
        $this->crawler = $this->client->request(
            $type,
            call_user_func_array([$this->router, 'generate'], $params),
            $data
        );
        /** @var Response $response */
        $response = $this->client->getResponse();
        if ($response->isServerError()) {
            echo $response->getContent();
        }
        if ($redirectOnSuccess) {
            return $response->isRedirection();
        }

        return $response->isSuccessful();
    }

    public function getRouteParams($parameters = array())
    {
        return array_merge($parameters, $this->getApiKeyParams());
    }

    /**
     * @return array
     */
    public function getApiKeyParams()
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('u')
            ->from('OjsUserBundle:User', 'u')
            ->where('u.roles LIKE :roles')
            ->setParameter('roles', '%ROLE_SUPER_ADMIN%')
            ->andWhere('u.apiKey IS NOT NULL')
            ->getQuery()
            ->getResult()
        ;
        $getAdminUsers = $qb->getQuery()->getResult();
        if(count($getAdminUsers) < 1){
            throw new NotFoundHttpException('Create an admin user with that apikey field not null');
        }
        /** @var User $getAdminUser */
        $getAdminUser = $getAdminUsers[0];
        return [
            'apikey' => $getAdminUser->getApiKey()
        ];
    }
}
