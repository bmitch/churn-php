# Changelog

## [Unreleased](https://github.com/bmitch/churn-php/tree/HEAD)

[Full Changelog](https://github.com/bmitch/churn-php/compare/1.4.0...HEAD)

## [1.3.0](https://github.com/bmitch/churn-php/tree/1.4.0) (2021-02-06)

[Full Changelog](https://github.com/bmitch/churn-php/compare/1.3.0...1.4.0)

**Implemented enhancements:**

- Add a --parallel option [\#293](https://github.com/bmitch/churn-php/issues/293)
- minScoreToShow should be nullable [\#290](https://github.com/bmitch/churn-php/issues/290)
- Support Fossil [\#276](https://github.com/bmitch/churn-php/issues/276)
- Support Mercurial [\#275](https://github.com/bmitch/churn-php/issues/275)
- Support SVN [\#274](https://github.com/bmitch/churn-php/issues/274)
- Incompatibility between dev dependencies [\#271](https://github.com/bmitch/churn-php/issues/271)
- Support mercurial/svn/etc. [\#95](https://github.com/bmitch/churn-php/issues/95)

**Closed issues:**

- Test\(s\) for ConsoleResultsRenderer [\#161](https://github.com/bmitch/churn-php/issues/161)
- Tests for ChurnCommand [\#158](https://github.com/bmitch/churn-php/issues/158)

**Merged pull requests:**

- Add support for subversion [\#299](https://github.com/bmitch/churn-php/pull/299) ([villfa](https://github.com/villfa))
- Give the ability to disable min score [\#298](https://github.com/bmitch/churn-php/pull/298) ([villfa](https://github.com/villfa))
- Add --parallel option [\#297](https://github.com/bmitch/churn-php/pull/297) ([villfa](https://github.com/villfa))
- Test the directoriesToScan configuration [\#296](https://github.com/bmitch/churn-php/pull/296) ([villfa](https://github.com/villfa))
- Add a configuration loader class [\#295](https://github.com/bmitch/churn-php/pull/295) ([villfa](https://github.com/villfa))
- Add a test for RunCommand [\#294](https://github.com/bmitch/churn-php/pull/294) ([villfa](https://github.com/villfa))
- Reduce boot time [\#289](https://github.com/bmitch/churn-php/pull/289) ([villfa](https://github.com/villfa))
- Move docker files in tests/ [\#288](https://github.com/bmitch/churn-php/pull/288) ([villfa](https://github.com/villfa))
- Improve how to handle invalid path in FileFinder [\#287](https://github.com/bmitch/churn-php/pull/287) ([villfa](https://github.com/villfa))
- \[ImgBot\] Optimize images [\#286](https://github.com/bmitch/churn-php/pull/286) ([villfa](https://github.com/villfa))
- Add support for fossil [\#285](https://github.com/bmitch/churn-php/pull/285) ([villfa](https://github.com/villfa))
- Add tests for mercurial [\#284](https://github.com/bmitch/churn-php/pull/284) ([villfa](https://github.com/villfa))
- Require CI tools only in dev mode [\#283](https://github.com/bmitch/churn-php/pull/283) ([villfa](https://github.com/villfa))
- Add support for Mercurial [\#282](https://github.com/bmitch/churn-php/pull/282) ([villfa](https://github.com/villfa))
- Throw error for invalid configuration path [\#281](https://github.com/bmitch/churn-php/pull/281) ([villfa](https://github.com/villfa))
- Check if running from PHAR only once [\#280](https://github.com/bmitch/churn-php/pull/280) ([villfa](https://github.com/villfa))
- Cache Composer artifacts [\#279](https://github.com/bmitch/churn-php/pull/279) ([villfa](https://github.com/villfa))
- Enable new PHPMD rule [\#278](https://github.com/bmitch/churn-php/pull/278) ([villfa](https://github.com/villfa))
- Update coding style [\#277](https://github.com/bmitch/churn-php/pull/277) ([villfa](https://github.com/villfa))
- Add tests for RunCommand [\#273](https://github.com/bmitch/churn-php/pull/273) ([villfa](https://github.com/villfa))
- Use composer-bin-plugin [\#272](https://github.com/bmitch/churn-php/pull/272) ([villfa](https://github.com/villfa))
- Remove Saythanks [\#270](https://github.com/bmitch/churn-php/pull/270) ([szepeviktor](https://github.com/szepeviktor))
- Release only releases [\#268](https://github.com/bmitch/churn-php/pull/268) ([szepeviktor](https://github.com/szepeviktor))

## [1.3.0](https://github.com/bmitch/churn-php/tree/1.3.0) (2020-11-26)

[Full Changelog](https://github.com/bmitch/churn-php/compare/1.2.0...1.3.0)

**Implemented enhancements:**

- Sign Phar with GPG Signature [\#253](https://github.com/bmitch/churn-php/issues/253)
- Support PHP-8 [\#229](https://github.com/bmitch/churn-php/issues/229)
- \[Improvement\] Work with GIT submodules [\#214](https://github.com/bmitch/churn-php/issues/214)
- Expected a value greater than 0. Got: 0 [\#179](https://github.com/bmitch/churn-php/issues/179)
- Check for ".git" file before running [\#173](https://github.com/bmitch/churn-php/issues/173)

**Fixed bugs:**

- Arbitrary amount of results shown [\#213](https://github.com/bmitch/churn-php/issues/213)

**Closed issues:**

- Publish churn.phar automatically at the release creation [\#252](https://github.com/bmitch/churn-php/issues/252)

**Merged pull requests:**

- Prepare changelog for 1.3.0 [\#269](https://github.com/bmitch/churn-php/pull/269) ([villfa](https://github.com/villfa))
- Automate release process [\#267](https://github.com/bmitch/churn-php/pull/267) ([villfa](https://github.com/villfa))
- Move from Travis to GH-Actions [\#266](https://github.com/bmitch/churn-php/pull/266) ([villfa](https://github.com/villfa))
- Fix some PHP docs [\#265](https://github.com/bmitch/churn-php/pull/265) ([villfa](https://github.com/villfa))
- Report all errors [\#264](https://github.com/bmitch/churn-php/pull/264) ([villfa](https://github.com/villfa))
- Improve GH actions [\#263](https://github.com/bmitch/churn-php/pull/263) ([villfa](https://github.com/villfa))
- Improve git configuration [\#262](https://github.com/bmitch/churn-php/pull/262) ([villfa](https://github.com/villfa))
- Ignore files with a score of zero [\#261](https://github.com/bmitch/churn-php/pull/261) ([villfa](https://github.com/villfa))
- Add support for non-versioned projects [\#260](https://github.com/bmitch/churn-php/pull/260) ([villfa](https://github.com/villfa))
- Use Box compactors [\#259](https://github.com/bmitch/churn-php/pull/259) ([villfa](https://github.com/villfa))
- Allow files as argument [\#258](https://github.com/bmitch/churn-php/pull/258) ([villfa](https://github.com/villfa))
- Improve the usability of Churn [\#257](https://github.com/bmitch/churn-php/pull/257) ([villfa](https://github.com/villfa))
- Add support for PHP 8 [\#256](https://github.com/bmitch/churn-php/pull/256) ([villfa](https://github.com/villfa))
- Use PHPUnit bridge [\#255](https://github.com/bmitch/churn-php/pull/255) ([villfa](https://github.com/villfa))
- Fix FQCN in Config [\#254](https://github.com/bmitch/churn-php/pull/254) ([szepeviktor](https://github.com/szepeviktor))

## [1.2.0](https://github.com/bmitch/churn-php/tree/1.2.0) (2020-10-23)

[Full Changelog](https://github.com/bmitch/churn-php/compare/1.1.0...1.2.0)

**Implemented enhancements:**

- Option to write the results in a file [\#235](https://github.com/bmitch/churn-php/issues/235)
- Option to disable parallelization [\#230](https://github.com/bmitch/churn-php/issues/230)
- More verbosity [\#199](https://github.com/bmitch/churn-php/issues/199)
- Build a "binary" for this project [\#126](https://github.com/bmitch/churn-php/issues/126)
- Doesn't work in windows. [\#71](https://github.com/bmitch/churn-php/issues/71)

**Fixed bugs:**

- Memory usage growing [\#237](https://github.com/bmitch/churn-php/issues/237)

**Closed issues:**

- Add composer-normalize to the CI pipeline [\#238](https://github.com/bmitch/churn-php/issues/238)
- Run the tests with GitHub actions [\#228](https://github.com/bmitch/churn-php/issues/228)

**Merged pull requests:**

- Prepare changelog for 1.2.0 [\#251](https://github.com/bmitch/churn-php/pull/251) ([villfa](https://github.com/villfa))
- Add a job to generate churn.phar [\#250](https://github.com/bmitch/churn-php/pull/250) ([villfa](https://github.com/villfa))
- Remove Composer v1 from GH actions [\#249](https://github.com/bmitch/churn-php/pull/249) ([villfa](https://github.com/villfa))
- Reduce the amount of data stored in memory [\#248](https://github.com/bmitch/churn-php/pull/248) ([villfa](https://github.com/villfa))
- Remove wrong file [\#247](https://github.com/bmitch/churn-php/pull/247) ([villfa](https://github.com/villfa))
- The LICENSE file must always be part of the code [\#246](https://github.com/bmitch/churn-php/pull/246) ([villfa](https://github.com/villfa))
- Add .github/ to the list [\#245](https://github.com/bmitch/churn-php/pull/245) ([villfa](https://github.com/villfa))
- Add an option to write in a file [\#244](https://github.com/bmitch/churn-php/pull/244) ([villfa](https://github.com/villfa))
- Fix execution on Windows [\#243](https://github.com/bmitch/churn-php/pull/243) ([villfa](https://github.com/villfa))
- Add composer-normalize to the CI pipeline [\#242](https://github.com/bmitch/churn-php/pull/242) ([villfa](https://github.com/villfa))
- Churn is now compatible with Windows [\#241](https://github.com/bmitch/churn-php/pull/241) ([villfa](https://github.com/villfa))
- Speed up CI builds [\#240](https://github.com/bmitch/churn-php/pull/240) ([villfa](https://github.com/villfa))
- Enable github actions [\#239](https://github.com/bmitch/churn-php/pull/239) ([villfa](https://github.com/villfa))
- Add a progress bar [\#236](https://github.com/bmitch/churn-php/pull/236) ([villfa](https://github.com/villfa))
- Enable more phpmd rules [\#234](https://github.com/bmitch/churn-php/pull/234) ([villfa](https://github.com/villfa))
- Add the ability to disable parallelization [\#233](https://github.com/bmitch/churn-php/pull/233) ([villfa](https://github.com/villfa))
- Update tools to fix PHP warnings [\#231](https://github.com/bmitch/churn-php/pull/231) ([villfa](https://github.com/villfa))

## [1.1.0](https://github.com/bmitch/churn-php/tree/1.1.0) (2020-10-13)

[Full Changelog](https://github.com/bmitch/churn-php/compare/1.0.3...1.1.0)

**Implemented enhancements:**

- Allow Symfony 5 [\#215](https://github.com/bmitch/churn-php/issues/215)

**Closed issues:**

- Looking for a new maintainer [\#222](https://github.com/bmitch/churn-php/issues/222)
- Cannot run the tool locally [\#219](https://github.com/bmitch/churn-php/issues/219)
- Churn `--version` flag does not work [\#211](https://github.com/bmitch/churn-php/issues/211)
- Tests for ProcessManager? [\#155](https://github.com/bmitch/churn-php/issues/155)

**Merged pull requests:**

- Prepare changelog for 1.1.0 [\#227](https://github.com/bmitch/churn-php/pull/227) ([villfa](https://github.com/villfa))
- Add compatibilty with Symfony 5 [\#226](https://github.com/bmitch/churn-php/pull/226) ([villfa](https://github.com/villfa))
- Add application version [\#225](https://github.com/bmitch/churn-php/pull/225) ([villfa](https://github.com/villfa))
- Update CHANGELOG.md [\#224](https://github.com/bmitch/churn-php/pull/224) ([villfa](https://github.com/villfa))
- Update README.md [\#223](https://github.com/bmitch/churn-php/pull/223) ([villfa](https://github.com/villfa))
- Write Tests for Process Manager [\#221](https://github.com/bmitch/churn-php/pull/221) ([Borumer](https://github.com/Borumer))
- Fix unit test [\#220](https://github.com/bmitch/churn-php/pull/220) ([villfa](https://github.com/villfa))
- Drop ChurnCommandOld [\#218](https://github.com/bmitch/churn-php/pull/218) ([simPod](https://github.com/simPod))
- Allow Symfony 5 [\#217](https://github.com/bmitch/churn-php/pull/217) ([simPod](https://github.com/simPod))
- Fix up Travis config [\#216](https://github.com/bmitch/churn-php/pull/216) ([szepeviktor](https://github.com/szepeviktor))
- Fix FileManager for Windows [\#201](https://github.com/bmitch/churn-php/pull/201) ([villfa](https://github.com/villfa))
- Update php-di/php-di dependency [\#200](https://github.com/bmitch/churn-php/pull/200) ([Tlapi](https://github.com/Tlapi))
- Enhancement: Update phpunit/phpunit [\#198](https://github.com/bmitch/churn-php/pull/198) ([localheinz](https://github.com/localheinz))
- Enhancement: Reference phpunit.xsd as installed with composer [\#197](https://github.com/bmitch/churn-php/pull/197) ([localheinz](https://github.com/localheinz))
- Fix: Install dependencies in install section [\#195](https://github.com/bmitch/churn-php/pull/195) ([localheinz](https://github.com/localheinz))
- Fix composer scripts for windows [\#194](https://github.com/bmitch/churn-php/pull/194) ([villfa](https://github.com/villfa))
- Tests on php 7.3 [\#193](https://github.com/bmitch/churn-php/pull/193) ([samnela](https://github.com/samnela))
- Fix: Remove unnecessary .gitkeep [\#192](https://github.com/bmitch/churn-php/pull/192) ([localheinz](https://github.com/localheinz))
- Enhancement: Cache dependencies installed with composer between builds [\#191](https://github.com/bmitch/churn-php/pull/191) ([localheinz](https://github.com/localheinz))
- Enhancement: Keep packages sorted in composer.json [\#190](https://github.com/bmitch/churn-php/pull/190) ([localheinz](https://github.com/localheinz))
- Enhancement: Normalize composer.json [\#189](https://github.com/bmitch/churn-php/pull/189) ([localheinz](https://github.com/localheinz))
- Added documentation for formats [\#188](https://github.com/bmitch/churn-php/pull/188) ([Nyholm](https://github.com/Nyholm))

## [1.0.3](https://github.com/bmitch/churn-php/tree/1.0.3) (2019-01-26)

[Full Changelog](https://github.com/bmitch/churn-php/compare/1.0.2...1.0.3)

**Merged pull requests:**

- Allow symfony 3.4 lts [\#186](https://github.com/bmitch/churn-php/pull/186) ([Nyholm](https://github.com/Nyholm))

## [1.0.2](https://github.com/bmitch/churn-php/tree/1.0.2) (2018-10-01)

[Full Changelog](https://github.com/bmitch/churn-php/compare/1.0.1...1.0.2)

**Closed issues:**

- PHP Fatal error:  Uncaught TypeError: Argument 1 passed to Symfony\Component\Yaml\Yaml::parse\(\) must be of the type string, boolean given, called in /app/vendor/bmitch/churn-php/src/Commands/ChurnCommand.php [\#183](https://github.com/bmitch/churn-php/issues/183)

**Merged pull requests:**

- update copyright year [\#185](https://github.com/bmitch/churn-php/pull/185) ([frazjp65](https://github.com/frazjp65))

## [1.0.1](https://github.com/bmitch/churn-php/tree/1.0.1) (2018-09-02)

[Full Changelog](https://github.com/bmitch/churn-php/compare/1.0.0...1.0.1)

**Merged pull requests:**

- Add cast to string to avoid YAML parse error [\#184](https://github.com/bmitch/churn-php/pull/184) ([ricardofiorani](https://github.com/ricardofiorani))

## [1.0.0](https://github.com/bmitch/churn-php/tree/1.0.0) (2018-07-04)

[Full Changelog](https://github.com/bmitch/churn-php/compare/0.5.0...1.0.0)

**Merged pull requests:**

- Docs: Update sample .yml with default min score [\#182](https://github.com/bmitch/churn-php/pull/182) ([GaryJones](https://github.com/GaryJones))
- update symfony components to v4 for compatibility with laravel 5.6 [\#180](https://github.com/bmitch/churn-php/pull/180) ([gahlawat](https://github.com/gahlawat))
- New Csv Result renderer added [\#178](https://github.com/bmitch/churn-php/pull/178) ([bartoszgolek](https://github.com/bartoszgolek))
- update changelog [\#177](https://github.com/bmitch/churn-php/pull/177) ([bmitch](https://github.com/bmitch))

## [0.5.0](https://github.com/bmitch/churn-php/tree/0.5.0) (2018-01-28)

[Full Changelog](https://github.com/bmitch/churn-php/compare/0.4.1...0.5.0)

**Closed issues:**

- Fix failing build [\#175](https://github.com/bmitch/churn-php/issues/175)
- No results - script hangs forever [\#172](https://github.com/bmitch/churn-php/issues/172)

**Merged pull requests:**

- Fixes \#175 - Fix failing build [\#176](https://github.com/bmitch/churn-php/pull/176) ([bmitch](https://github.com/bmitch))
- Escape shell arguments [\#174](https://github.com/bmitch/churn-php/pull/174) ([iquito](https://github.com/iquito))
- Add missing property declarations [\#171](https://github.com/bmitch/churn-php/pull/171) ([bmitch](https://github.com/bmitch))
- update changelog [\#170](https://github.com/bmitch/churn-php/pull/170) ([bmitch](https://github.com/bmitch))

## [0.4.1](https://github.com/bmitch/churn-php/tree/0.4.1) (2017-10-21)

[Full Changelog](https://github.com/bmitch/churn-php/compare/0.4.0...0.4.1)

**Implemented enhancements:**

- ChurnCommand is 243 lines - should be smaller. [\#133](https://github.com/bmitch/churn-php/issues/133)

**Closed issues:**

- ResultsFactory [\#166](https://github.com/bmitch/churn-php/issues/166)
- Add PHPCS forbidden functions sniff. [\#162](https://github.com/bmitch/churn-php/issues/162)
- Test\(s\) for JsonResultsRenderer [\#160](https://github.com/bmitch/churn-php/issues/160)
- Tests for ResultsLogic [\#157](https://github.com/bmitch/churn-php/issues/157)
- Tests for ResultsRendererFactory [\#156](https://github.com/bmitch/churn-php/issues/156)
- Tests for ResultCollection-\>whereScoreAbove\(\) method [\#154](https://github.com/bmitch/churn-php/issues/154)
- Create a custom PHPMD ruleset. [\#129](https://github.com/bmitch/churn-php/issues/129)

**Merged pull requests:**

- Fixes \#166 - Results Factory to just return the Renderer [\#169](https://github.com/bmitch/churn-php/pull/169) ([bmitch](https://github.com/bmitch))
- Fixes \#129 - Custom PHPMD ruleset [\#168](https://github.com/bmitch/churn-php/pull/168) ([bmitch](https://github.com/bmitch))
- Make green [\#167](https://github.com/bmitch/churn-php/pull/167) ([bmitch](https://github.com/bmitch))
- Tests for ResultsRendererFactory [\#165](https://github.com/bmitch/churn-php/pull/165) ([rmikalkenas](https://github.com/rmikalkenas))
- add unit tests for ResultsLogic class [\#164](https://github.com/bmitch/churn-php/pull/164) ([KNiepok](https://github.com/KNiepok))
- Add tests for JsonResultsRenderer [\#163](https://github.com/bmitch/churn-php/pull/163) ([marvin255](https://github.com/marvin255))
- Add test for whereScoreAbove. [\#159](https://github.com/bmitch/churn-php/pull/159) ([holic-cl](https://github.com/holic-cl))
- Changelog update [\#153](https://github.com/bmitch/churn-php/pull/153) ([bmitch](https://github.com/bmitch))

## [0.4.0](https://github.com/bmitch/churn-php/tree/0.4.0) (2017-10-19)

[Full Changelog](https://github.com/bmitch/churn-php/compare/0.3.1...0.4.0)

**Implemented enhancements:**

- \[Config\] Use regex in files to ignore [\#58](https://github.com/bmitch/churn-php/issues/58)

**Closed issues:**

- We get linear scores [\#149](https://github.com/bmitch/churn-php/issues/149)
- Possible problem in cyclomatic complexity calculation [\#148](https://github.com/bmitch/churn-php/issues/148)
- Unable to install for symfony 3.1.10 project [\#137](https://github.com/bmitch/churn-php/issues/137)
- New screenshot featuring new scoring method [\#136](https://github.com/bmitch/churn-php/issues/136)
- Fix broken build [\#130](https://github.com/bmitch/churn-php/issues/130)
- Enable PDS compliance [\#128](https://github.com/bmitch/churn-php/issues/128)
- Add a "downloads" badge with the other badges. [\#118](https://github.com/bmitch/churn-php/issues/118)
- Update readme to reflect new configuration location option. [\#117](https://github.com/bmitch/churn-php/issues/117)
- New screenshot [\#115](https://github.com/bmitch/churn-php/issues/115)
- Bug in FileManager - regex to ignore files? [\#113](https://github.com/bmitch/churn-php/issues/113)
- Update readme with new ability from \#110 [\#111](https://github.com/bmitch/churn-php/issues/111)
- Option to define where the config file `churn.yml` lives [\#108](https://github.com/bmitch/churn-php/issues/108)
- Enhance readme / documentation [\#104](https://github.com/bmitch/churn-php/issues/104)
- Suggestion to change the scoring [\#97](https://github.com/bmitch/churn-php/issues/97)
- Make multiple paths \(\#72\) something that be configured in churn.yml [\#76](https://github.com/bmitch/churn-php/issues/76)

**Merged pull requests:**

- Changelog update [\#152](https://github.com/bmitch/churn-php/pull/152) ([bmitch](https://github.com/bmitch))
- Big refactor [\#151](https://github.com/bmitch/churn-php/pull/151) ([bmitch](https://github.com/bmitch))
- Fixes \#148 - better method name [\#150](https://github.com/bmitch/churn-php/pull/150) ([bmitch](https://github.com/bmitch))
- Add Docker section to README.md [\#147](https://github.com/bmitch/churn-php/pull/147) ([tommy-muehle](https://github.com/tommy-muehle))
- Discovered compatability issue [\#145](https://github.com/bmitch/churn-php/pull/145) ([metamaker](https://github.com/metamaker))
- Added donate badge [\#144](https://github.com/bmitch/churn-php/pull/144) ([bmitch](https://github.com/bmitch))
- churn.yml file not needed [\#143](https://github.com/bmitch/churn-php/pull/143) ([bmitch](https://github.com/bmitch))
- Keep releases lean [\#142](https://github.com/bmitch/churn-php/pull/142) ([raphaelstolt](https://github.com/raphaelstolt))
- Update screenshot [\#141](https://github.com/bmitch/churn-php/pull/141) ([bmitch](https://github.com/bmitch))
- Utilise Composer scripts also for builds [\#140](https://github.com/bmitch/churn-php/pull/140) ([raphaelstolt](https://github.com/raphaelstolt))
- Enable PDS compliance [\#139](https://github.com/bmitch/churn-php/pull/139) ([raphaelstolt](https://github.com/raphaelstolt))
- Update the screenshot on the README.md to show new scores [\#138](https://github.com/bmitch/churn-php/pull/138) ([dhdutoit](https://github.com/dhdutoit))
- \[MRG\] New screenshot [\#135](https://github.com/bmitch/churn-php/pull/135) ([vrishank97](https://github.com/vrishank97))
- Get build back to green [\#134](https://github.com/bmitch/churn-php/pull/134) ([bmitch](https://github.com/bmitch))
- Fixes \#130 - Fix broken build [\#131](https://github.com/bmitch/churn-php/pull/131) ([bmitch](https://github.com/bmitch))
- Feature/configure paths in churn yml [\#125](https://github.com/bmitch/churn-php/pull/125) ([matthiasnoback](https://github.com/matthiasnoback))
- Add new configuration location option. [\#124](https://github.com/bmitch/churn-php/pull/124) ([tulikavijay](https://github.com/tulikavijay))
- Update README.md [\#123](https://github.com/bmitch/churn-php/pull/123) ([bmitch](https://github.com/bmitch))
- Improved scoring [\#122](https://github.com/bmitch/churn-php/pull/122) ([matthiasnoback](https://github.com/matthiasnoback))
- Feature/113 file manager regex [\#121](https://github.com/bmitch/churn-php/pull/121) ([bearzk](https://github.com/bearzk))
- Update README.md [\#119](https://github.com/bmitch/churn-php/pull/119) ([campionfellin](https://github.com/campionfellin))
- Update README.md [\#116](https://github.com/bmitch/churn-php/pull/116) ([bmitch](https://github.com/bmitch))
- Update README.md [\#114](https://github.com/bmitch/churn-php/pull/114) ([bmitch](https://github.com/bmitch))
- Fixes \#111 - update readme to show how ignore regex works [\#112](https://github.com/bmitch/churn-php/pull/112) ([bmitch](https://github.com/bmitch))
- Feature/58 regex ignore [\#110](https://github.com/bmitch/churn-php/pull/110) ([bearzk](https://github.com/bearzk))
- Introduce -c option custom churn.yml file [\#109](https://github.com/bmitch/churn-php/pull/109) ([Douglasdc3](https://github.com/Douglasdc3))

## [0.3.1](https://github.com/bmitch/churn-php/tree/0.3.1) (2017-09-29)

[Full Changelog](https://github.com/bmitch/churn-php/compare/0.3.0...0.3.1)

**Implemented enhancements:**

- Validate data being used to create config class [\#54](https://github.com/bmitch/churn-php/issues/54)

**Merged pull requests:**

- Added test section on readme [\#107](https://github.com/bmitch/churn-php/pull/107) ([bmitch](https://github.com/bmitch))
- Make it possible to run from global install [\#106](https://github.com/bmitch/churn-php/pull/106) ([olyckne](https://github.com/olyckne))
- Update README.md [\#105](https://github.com/bmitch/churn-php/pull/105) ([bmitch](https://github.com/bmitch))
- updated changelog after 0.3.0 release [\#103](https://github.com/bmitch/churn-php/pull/103) ([bmitch](https://github.com/bmitch))

## [0.3.0](https://github.com/bmitch/churn-php/tree/0.3.0) (2017-09-26)

[Full Changelog](https://github.com/bmitch/churn-php/compare/0.2.0...0.3.0)

**Closed issues:**

- Sniff to disallow set methods. [\#93](https://github.com/bmitch/churn-php/issues/93)

**Merged pull requests:**

- update changelog [\#102](https://github.com/bmitch/churn-php/pull/102) ([bmitch](https://github.com/bmitch))
- Inject dependencies into ChurnCommand [\#101](https://github.com/bmitch/churn-php/pull/101) ([bmitch](https://github.com/bmitch))
- Fixes config issue with non-date strings [\#100](https://github.com/bmitch/churn-php/pull/100) ([bmitch](https://github.com/bmitch))
- Validate data being used to create config class \#54 [\#99](https://github.com/bmitch/churn-php/pull/99) ([Lucky-Loek](https://github.com/Lucky-Loek))
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

[Full Changelog](https://github.com/bmitch/churn-php/compare/acf3696a02bc885b6d089f7a8af9023357f084c4...0.0.1)

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



\* *This Changelog was automatically generated by [github_changelog_generator](https://github.com/github-changelog-generator/github-changelog-generator)*
