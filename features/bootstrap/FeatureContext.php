<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Tester\Exception\PendingException;
use BitMask\AssociativeBitMask;
use PHPUnit\Framework\Assert;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    protected $files;

    public function __construct()
    {
        $this->files = [];
    }

    /**
     * @Given :file with :r:w:x :s
     */
    public function with($file, $r, $w, $x, $s)
    {
        $this->files[$file] = [
            'readable' => $r,
            'writable' => $w,
            'executable' => $x,
            'symbolic' => $s,
            'bitmask' => null
        ];
    }

    /**
     * @When create bitmask for :file
     */
    public function createBitmaskFor($file)
    {
        $fileData = $this->files[$file];
        $bm = new AssociativeBitMask(['readable', 'writable', 'executable']);
        $bm->readable = $fileData['readable'] === "1";
        $bm->writable = $fileData['writable'] === "1";
        $bm->executable = $fileData['executable'] === "1";
        $this->files[$file]['bitmask'] = $bm;
    }

    /**
     * @Then check properties for :file
     */
    public function checkPropertiesFor($file)
    {
        $fileData = $this->files[$file];
        Assert::assertSame($fileData['readable'], $fileData['bitmask']->readable ? '1' : '0');
        Assert::assertSame($fileData['writable'], $fileData['bitmask']->writable ? '1' : '0');
        Assert::assertSame($fileData['executable'], $fileData['bitmask']->executable ? '1' : '0');
        Assert::assertSame($fileData['symbolic'], $this->getSymbolic($fileData['bitmask']));
    }

    /**
     * @Given file without permissions
     */
    public function fileWithoutPermissions()
    {
        $this->files[] = [
            'readable' => "0",
            'writable' => "0",
            'executable' => "0",
            'symbolic' => "---",
            'bitmask' => new AssociativeBitMask(['readable', 'writable', 'executable'])
        ];
    }

    private function getSymbolic(AssociativeBitMask $bitmask)
    {
        return ($bitmask->readable ? 'r' : '-') . ($bitmask->writable ? 'w' : '-') . ($bitmask->executable ? 'x' : '-');
    }

    /**
     * @Then I should see symbolic :symbolic for :file
     */
    public function iShouldSeeSymbolic($symbolic, $file = 0)
    {
        $fileData = $this->files[$file];
        Assert::assertSame($symbolic, $this->getSymbolic($fileData['bitmask']));
    }

    /**
     * @When I set :key to :bool in :file
     */
    public function iSetTo($key, $bool, $file = 0)
    {
        $this->files[$file]['bitmask']->$key = $bool === 'true';
    }

    /**
     * @Then BitMask value for :file should be :value
     */
    public function bitmaskValueShouldBe($value, $file = 0)
    {
        $value = (int)$value;
        Assert::assertSame($value, $this->files[$file]['bitmask']->getMask());
    }

    /**
     * @When I set mask :mask to :file
     */
    public function iSetMaskTo($mask, $file = 0)
    {
        $this->files[$file]['bitmask']->setMask((int)$mask);
    }

    /**
     * @Then check rwx :r:w:x for :file
     */
    public function checkRwxFor($r, $w, $x, $file = 0)
    {
        $fileData = $this->files[$file];
        Assert::assertSame($r, $fileData['bitmask']->readable ? '1' : '0');
        Assert::assertSame($w, $fileData['bitmask']->writable ? '1' : '0');
        Assert::assertSame($x, $fileData['bitmask']->executable ? '1' : '0');
    }
}
