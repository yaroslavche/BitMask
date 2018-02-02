@base
Feature: BitMask base class
    In order to test base class functionality
    As an developer
    I want create bitmasks, set/unset bits and return in different formats

    Scenario: create BitMask base object
        Given I create BitMask base object with alias "dynamic" and with mask "0"
        Then BitMask base object "dynamic" should be "0"
        Given I create BitMask base object with alias "dynamic_mask" and with mask "0b1010101"
        Then BitMask base object "dynamic_mask" should be "0b1010101"
        Given I create BitMask base object with alias "static" and with mask "0"
        Then BitMask base object "static" should be "0"
        Given I create BitMask base object with alias "static_mask" and with mask "0b1001001"
        Then BitMask base object "static_mask" should be "0b1001001"

    Scenario: set and unset bits, clear
        Given I create BitMask base object with alias "test" and with mask "0"
        And I define bit "0b1" as alias "readable"
        And I define bit "0b10" as alias "writable"
        And I define bit "0b100" as alias "executable"
        When I set bit "readable" in BitMask base object "test"
        Then BitMask base object "test" should be "0b001"
        When I set bit "writable" in BitMask base object "test"
        Then BitMask base object "test" should be "0b011"
        When I set bit "executable" in BitMask base object "test"
        Then BitMask base object "test" should be "0b111"
        When I unset bit "executable" in BitMask base object "test"
        And I unset bit "writable" in BitMask base object "test"
        And I unset bit "readable" in BitMask base object "test"
        Then BitMask base object "test" should be "0b0"

        When I clear BitMask base object "test"
        Then BitMask base object "test" should be "0b0"

        # When I set bit "1 << 2" in BitMask base object "test" # 1 << 2 => 0b100
        # Then BitMask base object "test" should be "" # empty => 0b0
