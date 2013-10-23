<?php

namespace Scribe\PheanstalkBundle\Tests\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

use Scribe\PheanstalkBundle\DependencyInjection\ScribePheanstalkExtension;
use Scribe\PheanstalkBundle\ScribePheanstalkBundle;

class ScribePheanstalkExtensionTest extends \PHPUnit_Framework_TestCase
{
    private $container;
    private $extension;

    public function setUp()
    {
        $this->container = new ContainerBuilder();
        $this->extension = new ScribePheanstalkExtension();

        $bundle = new ScribePheanstalkBundle();
        $bundle->build($this->container); // Attach all default factories
    }

    public function tearDown()
    {
        unset($this->container, $this->extension);
    }

    public function testInitConfiguration()
    {
        $config = array(
            "scribe_pheanstalk" => array (
                "enabled" => true,
                "pheanstalks" => array (
                    "primary" => array (
                        "server" => "beanstalkd.domain.tld",
                        "port" => 11300,
                        "timeout" => 60,
                        "default" => true
                    )
                )
            )
        );
        $this->extension->load($config, $this->container);
        $this->container->compile();

        $this->assertTrue($this->container->hasDefinition('scribe.pheanstalk.pheanstalk_locator'));
        $this->assertFalse($this->container->hasParameter('scribe.pheanstalk.pheanstalks'));
    }

    public function testDefaultPheanstalk()
    {
        $config = array(
            "scribe_pheanstalk" => array (
                "enabled" => true,
                "pheanstalks" => array (
                    "primary" => array (
                        "server" => "beanstalkd.domain.tld",
                        "port" => 11300,
                        "timeout" => 60,
                        "default" => true
                    )
                )
            )
        );
        $this->extension->load($config, $this->container);
        $this->container->compile();

        $this->assertTrue($this->container->hasDefinition('scribe.pheanstalk.primary'));
        $this->assertTrue($this->container->hasAlias('scribe.pheanstalk'));
    }

    public function testNoDefaultPheanstalk()
    {
        $config = array(
            "scribe_pheanstalk" => array (
                "enabled" => true,
                "pheanstalks" => array (
                    "primary" => array (
                        "server" => "beanstalkd.domain.tld",
                        "port" => 11300,
                        "timeout" => 60,
                    )
                )
            )
        );
        $this->extension->load($config, $this->container);
        $this->container->compile();

        $this->assertTrue($this->container->hasDefinition('scribe.pheanstalk.primary'));
        $this->assertFalse($this->container->hasAlias('scribe.pheanstalk'));
    }

    /**
     * @expectedException Scribe\PheanstalkBundle\Exceptions\PheanstalkException
     */
    public function testTwoDefaultPheanstalks()
    {
        $config = array(
            "scribe_pheanstalk" => array (
                "enabled" => true,
                "pheanstalks" => array (
                    "one" => array (
                        "server" => "beanstalkd.domain.tld",
                        "default" => true
                    ),
                    "two" => array (
                        "server" => "beanstalkd-2.domain.tld",
                        "default" => true
                    )
                )
            )
        );
        $this->extension->load($config, $this->container);
        $this->container->compile();
    }

    public function testMultiplePheanstalks()
    {
        $config = array(
            "scribe_pheanstalk" => array (
                "enabled" => true,
                "pheanstalks" => array (
                    "one" => array (
                        "server" => "beanstalkd.domain.tld",
                        "port" => 11300,
                        "timeout" => 60
                    ),
                    "two" => array (
                        "server" => "beanstalkd-2.domain.tld",
                    ),
                    "three" => array (
                        "server" => "beanstalkd-3.domain.tld",
                    )
                )
            )
        );
        $this->extension->load($config, $this->container);
        $this->container->compile();

        $this->assertTrue($this->container->hasDefinition('scribe.pheanstalk.one'));
        $this->assertTrue($this->container->hasDefinition('scribe.pheanstalk.two'));
        $this->assertTrue($this->container->hasDefinition('scribe.pheanstalk.three'));
    }

    public function testPheanstalkLocator()
    {
        $config = array(
            "scribe_pheanstalk" => array (
                "enabled" => true,
                "pheanstalks" => array (
                    "primary" => array (
                        "server" => "beanstalkd.domain.tld",
                        "port" => 11300,
                        "timeout" => 60,
                        "default" => true
                    )
                )
            )
        );
        $this->extension->load($config, $this->container);
        $this->container->compile();

        $this->assertTrue($this->container->hasDefinition('scribe.pheanstalk.pheanstalk_locator'));
    }

    /**
     * @expectedException \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     */
    public function testPheanstalkProxyCustomTypeNotDefined()
    {
        $config = array(
            "scribe_pheanstalk" => array (
                "enabled" => true,
                "pheanstalks" => array (
                    "primary" => array (
                        "server" => "beanstalkd.domain.tld",
                        "port" => 11300,
                        "timeout" => 60,
                        "proxy" => "acme.pheanstalk.pheanstalk_proxy"
                    )
                )
            )
        );
        $this->extension->load($config, $this->container);
        $this->container->compile();
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testPheanstalkReservedName()
    {
        $config = array(
            "scribe_pheanstalk" => array (
                "enabled" => true,
                "pheanstalks" => array (
                    "proxy" => array (
                        "server" => "beanstalkd.domain.tld",
                        "port" => 11300,
                        "timeout" => 60,
                        "proxy" => "acme.pheanstalk.pheanstalk_proxy"
                    )
                )
            )
        );
        $this->extension->load($config, $this->container);
        $this->container->compile();
    }

    public function testPheanstalkProxyCustomType()
    {
        $config = array(
            "scribe_pheanstalk" => array (
                "enabled" => true,
                "pheanstalks" => array (
                    "primary" => array (
                        "server" => "beanstalkd.domain.tld",
                        "port" => 11300,
                        "timeout" => 60,
                        "proxy" => "acme.pheanstalk.pheanstalk_proxy"
                    )
                )
            )
        );

        $this->container->setDefinition('acme.pheanstalk.pheanstalk_proxy', new Definition('Scribe\PheanstalkBundle\Proxy\PheanstalkProxyInterface'));

        $this->extension->load($config, $this->container);
        $this->container->compile();
    }
}
