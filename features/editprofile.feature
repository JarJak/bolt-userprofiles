Feature: User profiles

Scenario: Successfully edit user profile and check if it is saved
  # login
  Given I am on "management/login"
  And I fill in "Twoja nazwa użytkownika …" with "admin"
  And I fill in "Your password …" with "admin_najl_17"
  And I press "Zaloguj"
  Then I should see "Zostałeś zalogowany."

  Given I am on "management/extensions/user-profiles"
  And I select "1" from "user"
  Then the "user" field should contain "1"

  Given I am on "management/extensions/user-profiles/1"
  And I fill in "profile[avatar]" with "Test"
  And I fill in "description" with "Test"
  And I press "Zapisz użytkownika"

  Given I am on "management/extensions/user-profiles/1"
  Then the "profile[avatar]" field should contain "Test"
  Then the "description" field should contain "Test"
