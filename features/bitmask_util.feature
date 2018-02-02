@util @base
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

    Scenario: parseBits
        Given I create BitMask with alias "test" and with mask "0"
        When I call util function "parseBits" on BitMask "test"

    Scenario: getMSB
        Given I create BitMask with alias "test" and with mask "0"
        When I call util function "getMSB" on BitMask "test"
        Then result for BitMask "test" should be "int" "0"
        When I set bit "b8" in BitMask "test"
        And I call util function "getMSB" on BitMask "test"
        Then result for BitMask "test" should be "int" "7"
        When I set bit "b3" in BitMask "test"
        And I call util function "getMSB" on BitMask "test"
        Then result for BitMask "test" should be "int" "7"
        When I unset bit "b8" in BitMask "test"
        And I call util function "getMSB" on BitMask "test"
        Then result for BitMask "test" should be "int" "2"

    Scenario: getBitCapacity
        Given I create BitMask with alias "test" and with mask "0"
        When I call util function "getBitCapacity" on BitMask "test"
        Then result for BitMask "test" should be "int" "0"
        When I set bit "b3" in BitMask "test"
        And I call util function "getBitCapacity" on BitMask "test"
        Then result for BitMask "test" should be "int" "3"
        When I set bit "b8" in BitMask "test"
        And I call util function "getBitCapacity" on BitMask "test"
        Then result for BitMask "test" should be "int" "8"

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

    Scenario: isSingleBit
        Given I create BitMask with alias "test" and with mask "0"
        When I call util function "isSingleBit" on BitMask "test"
        # mb true?
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
