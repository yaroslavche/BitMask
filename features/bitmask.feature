Feature: BitMask file
    In order to
    As a
    I want to

    Scenario Outline: check file permissions from bitmask
        Given "<file>" with "<r>""<w>""<x>" "<symbolic>"
        When create bitmask for "<file>"
        Then I should see symbolic "<symbolic>" for "<file>"
        And BitMask value for "<file>" should be "<mask>"
        And check properties for "<file>"

        Examples:
            | file | r  | w  | x  | symbolic | mask |
            |  f1  |  0 |  0 |  0 |  ---     |  0   |
            |  f2  |  1 |  0 |  0 |  r--     |  1   |
            |  f3  |  0 |  1 |  0 |  -w-     |  2   |
            |  f4  |  1 |  1 |  0 |  rw-     |  3   |
            |  f5  |  0 |  0 |  1 |  --x     |  4   |
            |  f6  |  1 |  0 |  1 |  r-x     |  5   |
            |  f7  |  0 |  1 |  1 |  -wx     |  6   |
            |  f8  |  1 |  1 |  1 |  rwx     |  7   |

    Scenario: change file permissions
        Given file without permissions
        Then I should see symbolic "---" for "0"
        And BitMask value for "0" should be "0"
        When I set "readable" to "true" in "0"
        Then I should see symbolic "r--" for "0"
        And BitMask value for "0" should be "1"
        When I set "writable" to "true" in "0"
        Then I should see symbolic "rw-" for "0"
        And BitMask value for "0" should be "3"
        When I set "executable" to "true" in "0"
        Then I should see symbolic "rwx" for "0"
        And BitMask value for "0" should be "7"
        When I set "readable" to "false" in "0"
        Then I should see symbolic "-wx" for "0"
        And BitMask value for "0" should be "6"
        When I set "writable" to "false" in "0"
        Then I should see symbolic "--x" for "0"
        And BitMask value for "0" should be "4"
        When I set "executable" to "false" in "0"
        Then I should see symbolic "---" for "0"
        And BitMask value for "0" should be "0"
        When I set "readable" to "true" in "0"
        And I set "executable" to "true" in "0"
        Then I should see symbolic "r-x" for "0"
        And BitMask value for "0" should be "5"
        When I set "readable" to "false" in "0"
        And I set "executable" to "false" in "0"
        And I set "writable" to "true" in "0"
        Then I should see symbolic "-w-" for "0"
        And BitMask value for "0" should be "2"

    Scenario Outline:
        Given file without permissions
        When I set mask "<mask>" to "0"
        Then check rwx "<r>""<w>""<x>" for "0"

        Examples:
            | mask | r  | w  | x  |
            |  0   |  0 |  0 |  0 |
            |  1   |  1 |  0 |  0 |
            |  2   |  0 |  1 |  0 |
            |  3   |  1 |  1 |  0 |
            |  4   |  0 |  0 |  1 |
            |  5   |  1 |  0 |  1 |
            |  6   |  0 |  1 |  1 |
            |  7   |  1 |  1 |  1 |
            |  8   |  0 |  0 |  0 |
