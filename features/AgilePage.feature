Feature: AMP Web Site Agile Page
  In order see what content the site has
  As a visitor to the site
  I need to have an Agile page

Scenario: Visit Agile Page
  Given I go to the AMP agile page
  Then I should be on "/agile"
  And there should be a link to "/" by clicking "AMP Logo"