@base @util
Feature: BitMask Util class
    In order to test util class functionality
    As an developer
    I want test all functions

    Background:
        Given I define bit "0b1" as alias "b1"
        And I define bit "0b10" as alias "b2"
        And I define bit "0b100" as alias "b3"
        And I define bit "0b1000" as alias "b4"
        And I define bit "0b10000" as alias "b5"
        And I define bit "0b100000" as alias "b6"
        And I define bit "0b1000000" as alias "b7"
        And I define bit "0b10000000" as alias "b8"

    @getMSB
    Scenario: getMSB
        Given I create BitMask with alias "test" and with mask "0"
        When I call util function "getMSB" on BitMask "test"
        Then result for BitMask "test" should be "int" "0"
        When I set bit "b1" in BitMask "test"
        And I call util function "getMSB" on BitMask "test"
        Then result for BitMask "test" should be "int" "1"
        When I set bit "b2" in BitMask "test"
        And I call util function "getMSB" on BitMask "test"
        Then result for BitMask "test" should be "int" "2"
        When I unset bit "b1" in BitMask "test"
        And I call util function "getMSB" on BitMask "test"
        Then result for BitMask "test" should be "int" "2"
        When I set bit "b3" in BitMask "test"
        And I call util function "getMSB" on BitMask "test"
        Then result for BitMask "test" should be "int" "3"
        When I unset bit "b2" in BitMask "test"
        And I call util function "getMSB" on BitMask "test"
        Then result for BitMask "test" should be "int" "3"
        When I set bit "b1" in BitMask "test"
        And I call util function "getMSB" on BitMask "test"
        Then result for BitMask "test" should be "int" "3"
        When I unset bit "b3" in BitMask "test"
        And I call util function "getMSB" on BitMask "test"
        Then result for BitMask "test" should be "int" "1"

    @getSetBits
    Scenario: getSetBits
        Given I create BitMask with alias "test" and with mask "0"
        When I call util function "getSetBits" on BitMask "test"
        Then result for BitMask "test" should be "array" "[]"
        When I set bit "b5" in BitMask "test"
        And I call util function "getSetBits" on BitMask "test"
        Then result for BitMask "test" should be "array" "[16]"
        When I set bit "b2" in BitMask "test"
        And I call util function "getSetBits" on BitMask "test"
        Then result for BitMask "test" should be "array" "[2, 16]"

    @isSingleBit
    Scenario: isSingleBit
        Given I create BitMask with alias "test" and with mask "0"
        When I call util function "isSingleBit" on BitMask "test"
        # mb true? because also it's not a non-single bits. Or maybe return null?
        Then result for BitMask "test" should be "bool" "false"
        When I set bit "b1" in BitMask "test"
        And I call util function "isSingleBit" on BitMask "test"
        Then result for BitMask "test" should be "bool" "true"
        When I set bit "b4" in BitMask "test"
        And I call util function "isSingleBit" on BitMask "test"
        Then result for BitMask "test" should be "bool" "false"
        When I unset bit "b1" in BitMask "test"
        And I call util function "isSingleBit" on BitMask "test"
        Then result for BitMask "test" should be "bool" "true"

    @toString
    Scenario: toString
        Given I create BitMask with alias "test" and with mask "0b10010"
        When I call util function "toString" on BitMask "test"
        Then result for BitMask "test" should be "string" "10010"
        When I unset bit "0b10" in BitMask "test"
        And I call util function "toString" on BitMask "test"
        Then result for BitMask "test" should be "string" "10000"

    @getSetBitsIndexes
    Scenario: getSetBitsIndexes
        Given I create BitMask with alias "test" and with mask "0b10010"
        When I call util function "getSetBitsIndexes" on BitMask "test"
        Then result for BitMask "test" should be "array" "[1, 4]"
        When I unset bit "0b10" in BitMask "test"
        And I call util function "getSetBitsIndexes" on BitMask "test"
        Then result for BitMask "test" should be "array" "[4]"

    @indexToBit
    # little bit tricky. BitMask in this case is index. And when call "indexToBit" on BitMask result would be bit.
    # indexToBit(3) =>  1 << 3 = 8
    # todo: When I call util function "indexToBit" with arguments "3"
    # todo: Then result should be "exception" "Must be single bit"
    Scenario: indexToBit
        Given I create BitMask with alias "test" and with mask "3"
        When I call util function "indexToBit" on BitMask "test"
        Then result for BitMask "test" should be "int" "8"

    @bitToIndexException
    Scenario: bitToIndexException
        Given I create BitMask with alias "test" and with mask "3"
        When I call util function "bitToIndex" on BitMask "test"
        Then result for BitMask "test" should be "exception" "Must be single bit"
