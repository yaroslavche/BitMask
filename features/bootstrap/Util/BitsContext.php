<?php

namespace Util;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Tester\Exception\PendingException;
use BitMask\BitMask;
use BitMask\Util\Bits;
use PHPUnit\Framework\Assert;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;

/**
 * Defines application features from the specific context.
 */
class BitsContext implements Context
{
    private $results;
    private $contexts;

    public function __construct()
    {
        $this->results = [];
    }

    /** @BeforeScenario */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $environment = $scope->getEnvironment();
        $this->contexts['BitMaskContext'] = $environment->getContext('BitMaskContext');
    }

    public function __get($property)
    {
        if (array_key_exists($property, $this->contexts)) {
            return $this->contexts[$property];
        }
    }

    /**
     * @When I call util function :method on BitMask :object
     */
    public function iCallUtilFunctionOnBitmask($method, $object)
    {
        if (!method_exists(Bits::class, $method)) {
            throw new \Exception('unknown method ' . $method);
        }
        $bm = $this->BitMaskContext->objects[$object];
        try {
            $this->results[$object] = Bits::$method($bm->get());
        } catch (\Exception $exception) {
            $this->results[$object] = $exception->getMessage();
        }
    }

    /**
     * @Then result for BitMask :object should be :type :result
     */
    public function resultForBitmaskShouldBe($object, $type, $result)
    {
        $availableTypes = ['bool', 'int', 'array', 'string', 'exception'];
        if (!in_array($type, $availableTypes)) {
            throw new \Exception(sprintf('Unsupported type %s. Available: %s', $type, implode('\', \'', $availableTypes)));
        }
        $storedResult = $this->results[$object];
        switch ($type) {
            case 'exception':
            {
                break;
            }
            case 'array':
            {
                $result = json_decode($result);
                $storedResult = $storedResult;
                break;
            }
            case 'bool':
            {
                $result = $result != 'false' && $result != '0';
            }
            default:
            {
                settype($storedResult, $type);
                settype($result, $type);
            }
        }
        Assert::assertSame($storedResult, $result);
    }
}
