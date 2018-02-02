<?php

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
class BitMaskUtilContext implements Context
{
    private $results;
    private $baseBitMaskContext;

    public function __construct()
    {
        $this->results = [];
    }

    /** @BeforeScenario */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $environment = $scope->getEnvironment();
        $this->baseBitMaskContext = $environment->getContext('BitMaskContext');
    }

    /**
     * @When I call util function :method on BitMask :object
     */
    public function iCallUtilFunctionOnBitmask($method, $object)
    {
        if (!method_exists(Bits::class, $method)) {
            throw new Exception('unknown method ' . $method);
        }
        $bm = $this->baseBitMaskContext->objects[$object] ?? null;
        $this->results[$object] = Bits::$method($bm->get());
    }

    /**
     * @Then result for BitMask :object should be :type :result
     */
    public function resultForBitmaskShouldBe($object, $type, $result)
    {
        $availableTypes = ['bool', 'int', 'array', 'string'];
        if (!in_array($type, $availableTypes)) {
            throw new \Exception(sprintf('Unsupported type %s. Available: %s', $type, implode('\', \'', $availableTypes)));
        }
        $storedResult = $this->results[$object];
        switch ($type) {
            case 'array':
            {
                $result = json_decode($result);
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

    /**
     * @Then result for BitMask :object should be :result
     */
    public function iShouldGetResult($result)
    {
        // dump($this->result);
    }
}
