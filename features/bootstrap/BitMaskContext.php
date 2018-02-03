<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Tester\Exception\PendingException;
use BitMask\BitMask;
use PHPUnit\Framework\Assert;

/**
 * Defines application features from the specific context.
 */
class BitMaskContext implements Context
{
    private $objects;
    private $bitAliases;

    public function __construct()
    {
        $this->objects = [];
        $this->bitAliases = [];
    }

    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    /**
     * @todo !isSingleBit($bit) exception
     * @todo alias only [a-z]{1-20}[0-9]{3} else exception
     * @Given I define bit :bit as alias :alias
     */
    public function iDefineBitAsAlias($bit, $alias)
    {
        $this->bitAliases[$alias] = $this->parseInteger($bit);
    }

    /**
     * @todo if not integer (expected key) and not key - exception
     *
     * @param  string $integer
     * @return int
     */
    public function parseInteger(string $integer) : int
    {
        if (array_key_exists($integer, $this->bitAliases)) {
            return $this->bitAliases[$integer];
        }
        if (strpos($integer, '0b') === 0) {
            return (int)base_convert($integer, 2, 10);
        }
        return (int)$integer;
    }

    /**
     * @Given I create BitMask with alias :arg1 and with mask :mask
     */
    public function iCreateBitmaskWithAliasAndWithMask($alias, $mask)
    {
        if (isset($this->objects[$alias])) {
            throw new Exception(sprintf('BitMask object "%s" for this scenario already created', $alias));
        }
        $mask = $this->parseInteger($mask);
        $bm = strpos($alias, 'static') === 0 ? BitMask::init($mask) : new BitMask($mask);
        $this->objects[$alias] = $bm;
    }

    /**
     * @When I set bit :bit in BitMask :alias
     */
    public function iSetBitInBitmask($bit, $alias)
    {
        // dump($bit, $this->parseInteger($bit), $this->objects[$alias]->get());
        $this->objects[$alias]->setBit($this->parseInteger($bit));
    }

    /**
     * @When I unset bit :bit in BitMask :alias
     */
    public function iUnsetBitInBitmask($bit, $alias)
    {
        // dump($bit, $this->parseInteger($bit), $this->objects[$alias]->get());
        $this->objects[$alias]->unsetBit($this->parseInteger($bit));
    }

    /**
     * @Then BitMask :alias should be :mask
     */
    public function bitmaskShouldBe($alias, $mask)
    {
        Assert::assertSame($this->parseInteger($mask), $this->objects[$alias]->get());
    }

    /**
     * @When I clear BitMask :arg1
     */
    public function iClearBitmask($alias)
    {
        $this->objects[$alias]->unset();
    }
}
