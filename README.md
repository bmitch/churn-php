# churn-php
Helps discover good candidates for refactoring.

[![Build Status](https://travis-ci.org/bmitch/churn-php.svg?branch=master)](https://travis-ci.org/bmitch/churn-php) [![codecov](https://codecov.io/gh/bmitch/churn-php/branch/master/graph/badge.svg)](https://codecov.io/gh/bmitch/churn-php) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/bmitch/churn-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/bmitch/churn-php/?branch=master) [![Code Climate](https://codeclimate.com/github/bmitch/churn-php/badges/gpa.svg)](https://codeclimate.com/github/bmitch/churn-php) [![Packagist](https://img.shields.io/packagist/v/bmitch/churn-php.svg)]() [![Packagist](https://img.shields.io/packagist/l/bmitch/churn-php.svg)]()
----------

* [What Is it?](#what-is-it)
* [Compatibility](#compatibility)
* [How to Install?](#how-to-install)
* [How to Use?](#how-to-use)
* [Similar Packages](#similar-packages)
* [Contributing](#contributing)
* [License](#license)

## What is it? ##
`churn-php` is a package that helps you identify files in your project that could be good candidates for refactoring. It examines each PHP file in the path it is provided and:
* Checks how many commits it has.
* Calculates the cyclomatic complexity.
* Creates a score based on these two values.

The results are displayed in a table:
```
+---------------------------------------------------------------------+---------------+------------+-------+
| File                                                                | Times Changed | Complexity | Score |
+---------------------------------------------------------------------+---------------+------------+-------+
| src/Assessors/CyclomaticComplexity/CyclomaticComplexityAssessor.php | 4             | 4          | 8     |
| src/Assessors/GitCommitCount/GitCommitCountAssessor.php             | 3             | 4          | 7     |
| src/Commands/ChurnCommand.php                                       | 3             | 2          | 5     |
| src/Managers/FileManager.php                                        | 2             | 3          | 5     |
| src/Results/ResultsGenerator.php                                    | 2             | 2          | 4     |
| src/Results/Result.php                                              | 2             | 1          | 3     |
| src/Services/CommandService.php                                     | 2             | 1          | 3     |
| src/Results/ResultCollection.php                                    | 1             | 1          | 2     |
+---------------------------------------------------------------------+---------------+------------+-------+
```


A file that changes a lot and has a high complexity might be a higher candidate for refactoring than a file that doesn't change a lot and has a low complexity.

`churn-php` only intends to assist the developer identifying files for refactoring. It's best to use the results in addition to your own judgement to decide which files you may want to refactor.

## Compatibility ##
* PHP 7+

## How to Install? ##
Install via Composer:
```
composer require bmitch/churn-php --dev
```

## How to Use? ##
```
vendor/bin/churn run <path to source code>
```

## Similar Packages
* https://github.com/danmayer/churn (Ruby)

## Contributing ##
Please see [CONTRIBUTING.md](CONTRIBUTING.md)

## License ##
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
