# Change Log

## [Unreleased](https://github.com/bmitch/churn-php/tree/HEAD)

[Full Changelog](https://github.com/bmitch/churn-php/compare/0.2.0...HEAD)

**Closed issues:**

- Sniff to disallow set methods. [\#93](https://github.com/bmitch/churn-php/issues/93)

**Merged pull requests:**

- Inject dependencies into ChurnCommand [\#101](https://github.com/bmitch/churn-php/pull/101) ([bmitch](https://github.com/bmitch))
- Fixes config issue with non-date strings [\#100](https://github.com/bmitch/churn-php/pull/100) ([bmitch](https://github.com/bmitch))
- Validate data being used to create config class \#54 [\#99](https://github.com/bmitch/churn-php/pull/99) ([loekiedepo](https://github.com/loekiedepo))
- Adds support for configurable file extensions [\#98](https://github.com/bmitch/churn-php/pull/98) ([nhoag](https://github.com/nhoag))
- Added another similar package [\#94](https://github.com/bmitch/churn-php/pull/94) ([bmitch](https://github.com/bmitch))
- changelog [\#92](https://github.com/bmitch/churn-php/pull/92) ([bmitch](https://github.com/bmitch))

## [0.2.0](https://github.com/bmitch/churn-php/tree/0.2.0) (2017-09-02)
[Full Changelog](https://github.com/bmitch/churn-php/compare/0.1.0...0.2.0)

**Implemented enhancements:**

- \[Config\] Threshold [\#56](https://github.com/bmitch/churn-php/issues/56)
- \[Config\] Score modifier? \(saving for possible future enhancement\) [\#55](https://github.com/bmitch/churn-php/issues/55)
- Set a max score a file can reach - if file is above it turns red in results? [\#53](https://github.com/bmitch/churn-php/issues/53)

**Closed issues:**

- Update sample output in Readme with a screen shot? [\#68](https://github.com/bmitch/churn-php/issues/68)

**Merged pull requests:**

- Changelog update [\#91](https://github.com/bmitch/churn-php/pull/91) ([bmitch](https://github.com/bmitch))
- Cleanup [\#90](https://github.com/bmitch/churn-php/pull/90) ([bmitch](https://github.com/bmitch))
- Make build green again [\#89](https://github.com/bmitch/churn-php/pull/89) ([bmitch](https://github.com/bmitch))
- Simplify Cyc Complex Assessor [\#88](https://github.com/bmitch/churn-php/pull/88) ([bmitch](https://github.com/bmitch))
- add min score threshold and some tests [\#87](https://github.com/bmitch/churn-php/pull/87) ([riverrun46](https://github.com/riverrun46))
- update readme [\#86](https://github.com/bmitch/churn-php/pull/86) ([bmitch](https://github.com/bmitch))
- Update changelog [\#85](https://github.com/bmitch/churn-php/pull/85) ([bmitch](https://github.com/bmitch))

## [0.1.0](https://github.com/bmitch/churn-php/tree/0.1.0) (2017-08-29)
[Full Changelog](https://github.com/bmitch/churn-php/compare/0.0.6...0.1.0)

**Implemented enhancements:**

- \[Config\] Paths of files [\#57](https://github.com/bmitch/churn-php/issues/57)

**Closed issues:**

- `churn` has `require`: should be `require\_once` [\#79](https://github.com/bmitch/churn-php/issues/79)
- Build failing - FileManager has too many indentation levels. [\#74](https://github.com/bmitch/churn-php/issues/74)
- Note in readme that this currently only works on unix command line [\#70](https://github.com/bmitch/churn-php/issues/70)
- Some missing declarations [\#64](https://github.com/bmitch/churn-php/issues/64)
- Test to make sure FileManager ignores files specified to ignore in config. [\#62](https://github.com/bmitch/churn-php/issues/62)
- Add sniff to check for use of: instanceof [\#61](https://github.com/bmitch/churn-php/issues/61)
- Ability to only display results where score \> X [\#26](https://github.com/bmitch/churn-php/issues/26)
- Ability to provide multiple paths. [\#24](https://github.com/bmitch/churn-php/issues/24)

**Merged pull requests:**

- Changelog update [\#82](https://github.com/bmitch/churn-php/pull/82) ([bmitch](https://github.com/bmitch))
- Fixes \#79 - require should be require\_once [\#81](https://github.com/bmitch/churn-php/pull/81) ([bmitch](https://github.com/bmitch))
- Tweaked composer file [\#80](https://github.com/bmitch/churn-php/pull/80) ([GrahamCampbell](https://github.com/GrahamCampbell))
- Enable global package installation [\#78](https://github.com/bmitch/churn-php/pull/78) ([jakzal](https://github.com/jakzal))
- Fix camelCase call [\#77](https://github.com/bmitch/churn-php/pull/77) ([EdouardTack](https://github.com/EdouardTack))
- Fixes \#74 - Reduce indentation levels in FileManager [\#75](https://github.com/bmitch/churn-php/pull/75) ([bmitch](https://github.com/bmitch))
- Fixes \#70 [\#73](https://github.com/bmitch/churn-php/pull/73) ([bmitch](https://github.com/bmitch))
- Multiple paths [\#72](https://github.com/bmitch/churn-php/pull/72) ([josephzidell](https://github.com/josephzidell))
- Fixed typo in name of churn.yml file [\#69](https://github.com/bmitch/churn-php/pull/69) ([sbkrogers](https://github.com/sbkrogers))
- Added a changelog [\#67](https://github.com/bmitch/churn-php/pull/67) ([bmitch](https://github.com/bmitch))
- Fix doc block [\#66](https://github.com/bmitch/churn-php/pull/66) ([bmitch](https://github.com/bmitch))
- Fixes \#64 - Added missing member declarations [\#65](https://github.com/bmitch/churn-php/pull/65) ([bmitch](https://github.com/bmitch))
- Fixes \#62 - Test for file manager to ignore files [\#63](https://github.com/bmitch/churn-php/pull/63) ([bmitch](https://github.com/bmitch))

## [0.0.6](https://github.com/bmitch/churn-php/tree/0.0.6) (2017-08-24)
[Full Changelog](https://github.com/bmitch/churn-php/compare/0.0.5...0.0.6)

**Closed issues:**

- Limit git history when counting changes. [\#51](https://github.com/bmitch/churn-php/issues/51)
- Config file \(yml\) [\#40](https://github.com/bmitch/churn-php/issues/40)
- Ability to only show top X results. [\#25](https://github.com/bmitch/churn-php/issues/25)
- Ability to ignore files [\#23](https://github.com/bmitch/churn-php/issues/23)

**Merged pull requests:**

- Downgrade to symfony/process 3.2 allowing this package to work with Laravel 5.4 installs. [\#60](https://github.com/bmitch/churn-php/pull/60) ([gms8994](https://github.com/gms8994))

## [0.0.5](https://github.com/bmitch/churn-php/tree/0.0.5) (2017-08-23)
[Full Changelog](https://github.com/bmitch/churn-php/compare/0.0.4...0.0.5)

**Closed issues:**

- Add footer with misc data under table? [\#42](https://github.com/bmitch/churn-php/issues/42)

**Merged pull requests:**

- Fixes \#42 [\#59](https://github.com/bmitch/churn-php/pull/59) ([bmitch](https://github.com/bmitch))
- Fixes \#42 [\#52](https://github.com/bmitch/churn-php/pull/52) ([bmitch](https://github.com/bmitch))

## [0.0.4](https://github.com/bmitch/churn-php/tree/0.0.4) (2017-08-19)
[Full Changelog](https://github.com/bmitch/churn-php/compare/0.0.3...0.0.4)

**Closed issues:**

- Changes for next version [\#39](https://github.com/bmitch/churn-php/issues/39)

**Merged pull requests:**

- Lower console version requirement [\#50](https://github.com/bmitch/churn-php/pull/50) ([bmitch](https://github.com/bmitch))

## [0.0.3](https://github.com/bmitch/churn-php/tree/0.0.3) (2017-08-19)
[Full Changelog](https://github.com/bmitch/churn-php/compare/0.0.2...0.0.3)

**Closed issues:**

- Add some sort of header above table [\#41](https://github.com/bmitch/churn-php/issues/41)
- Run in parallel [\#38](https://github.com/bmitch/churn-php/issues/38)
- Make FileManager return an object instead of an array? [\#33](https://github.com/bmitch/churn-php/issues/33)

**Merged pull requests:**

- New test [\#49](https://github.com/bmitch/churn-php/pull/49) ([bmitch](https://github.com/bmitch))
- Add PHP 7.2 to Travis-CI build [\#48](https://github.com/bmitch/churn-php/pull/48) ([bmitch](https://github.com/bmitch))
- Fixes \#33 - FileManager returns FileCollection [\#47](https://github.com/bmitch/churn-php/pull/47) ([bmitch](https://github.com/bmitch))
- Refactor [\#46](https://github.com/bmitch/churn-php/pull/46) ([bmitch](https://github.com/bmitch))
- Fixes \#38 - Run in parallel [\#44](https://github.com/bmitch/churn-php/pull/44) ([bmitch](https://github.com/bmitch))

## [0.0.2](https://github.com/bmitch/churn-php/tree/0.0.2) (2017-06-23)
[Full Changelog](https://github.com/bmitch/churn-php/compare/0.0.1...0.0.2)

**Closed issues:**

- ResutlsGenerator tests [\#35](https://github.com/bmitch/churn-php/issues/35)
- Tests for FileManager [\#31](https://github.com/bmitch/churn-php/issues/31)
- Tests for ResultCollection [\#29](https://github.com/bmitch/churn-php/issues/29)
- Tests for Result object [\#27](https://github.com/bmitch/churn-php/issues/27)
- Missing tests [\#20](https://github.com/bmitch/churn-php/issues/20)
- Once ready - update readme.md [\#10](https://github.com/bmitch/churn-php/issues/10)

**Merged pull requests:**

- Command test [\#37](https://github.com/bmitch/churn-php/pull/37) ([bmitch](https://github.com/bmitch))
- Fixes \#35 [\#36](https://github.com/bmitch/churn-php/pull/36) ([bmitch](https://github.com/bmitch))
- Add result test [\#34](https://github.com/bmitch/churn-php/pull/34) ([bmitch](https://github.com/bmitch))
- Fixes \#31 [\#32](https://github.com/bmitch/churn-php/pull/32) ([bmitch](https://github.com/bmitch))
- Fixes \#29 [\#30](https://github.com/bmitch/churn-php/pull/30) ([bmitch](https://github.com/bmitch))
- Fixes \#27 [\#28](https://github.com/bmitch/churn-php/pull/28) ([bmitch](https://github.com/bmitch))

## [0.0.1](https://github.com/bmitch/churn-php/tree/0.0.1) (2017-06-19)
**Closed issues:**

- Make results show files relative to provided path. [\#19](https://github.com/bmitch/churn-php/issues/19)
- Fix failing build [\#16](https://github.com/bmitch/churn-php/issues/16)
- Add the sniff for SlevomatCodingStandard [\#13](https://github.com/bmitch/churn-php/issues/13)
- How to calculate the scoring? [\#11](https://github.com/bmitch/churn-php/issues/11)
- Refactor churn command [\#9](https://github.com/bmitch/churn-php/issues/9)
- Make churn command live in bin folder for easy execution. [\#8](https://github.com/bmitch/churn-php/issues/8)
- Tighten up Cyc Complex assessor with some more tests. [\#7](https://github.com/bmitch/churn-php/issues/7)
- Command to display results. [\#5](https://github.com/bmitch/churn-php/issues/5)
- Number of git commits assessor. [\#3](https://github.com/bmitch/churn-php/issues/3)
- Cyclomatic Complexity Assessor [\#1](https://github.com/bmitch/churn-php/issues/1)

**Merged pull requests:**

- Fixes \#19 [\#22](https://github.com/bmitch/churn-php/pull/22) ([bmitch](https://github.com/bmitch))
- Fix type hints [\#21](https://github.com/bmitch/churn-php/pull/21) ([bmitch](https://github.com/bmitch))
- Fixes \#9 [\#18](https://github.com/bmitch/churn-php/pull/18) ([bmitch](https://github.com/bmitch))
- Fixes \#16 [\#17](https://github.com/bmitch/churn-php/pull/17) ([bmitch](https://github.com/bmitch))
- Fixes \#13 [\#15](https://github.com/bmitch/churn-php/pull/15) ([bmitch](https://github.com/bmitch))
- Fixes \#7 [\#14](https://github.com/bmitch/churn-php/pull/14) ([bmitch](https://github.com/bmitch))
- Fixes \#8 [\#12](https://github.com/bmitch/churn-php/pull/12) ([bmitch](https://github.com/bmitch))
- Fixes \#5 [\#6](https://github.com/bmitch/churn-php/pull/6) ([bmitch](https://github.com/bmitch))
- Fixes \#3 [\#4](https://github.com/bmitch/churn-php/pull/4) ([bmitch](https://github.com/bmitch))
- Fixes \#1 [\#2](https://github.com/bmitch/churn-php/pull/2) ([bmitch](https://github.com/bmitch))



\* *This Change Log was automatically generated by [github_changelog_generator](https://github.com/skywinder/Github-Changelog-Generator)*