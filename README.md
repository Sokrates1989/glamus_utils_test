# Glamus/Utils

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]


PHP Utilities for glamus employees to use in various projects to accumulate and share knowledge as well as to unify behaviour.


# Install

## Via Composer (recommended) ([click here for instructions how to install composer itsef](https://getcomposer.org/))

For new projects 
1. copy [install/composer_example.json](install/composer_example.json) into the new desired root directory of your project
2. rename that file to "composer.json"
3. edit the content to add information of your new project
   - Pay high attention to "require" key and edit the version number to glamus/:package according to [composer versions](https://getcomposer.org/doc/articles/versions.md) (recommendation: keep "^" at beginning to ensure backwards compatibility)
4. run the following command from bash or terminal 

``` bash
$ composer install
```

For existing projects
1. edit your existing composer.json as instructed in [install/additionsForExisting_composer.json](install/additionsForExisting_composer.json) into the your composer.json
2. Pay high attention to "require" key and edit the version number to glamus/:package according to [composer versions](https://getcomposer.org/doc/articles/versions.md) (recommendation: keep "^" at beginning to ensure backwards compatibility)

``` bash
$ composer require glamus/utils
```

## Via Git (not recommended) ([click here for instructions how to install git itsef](https://github.com/git-guides/install-git))

For new projects
1. create or go to the new root directory of your desired PHP project
2. run below command in the bash or terminal in that directory
3. take a look at updates to see how to get your desired version

``` bash
$ git clone https://github.com/Sokrates1989/glamus_utils_test.git
```

For existing projects
1. go to the root directory of your PHP project
2. run below command in the bash or terminal in that directory
3. take a look at updates to see how to get your desired version

``` bash
$ git clone https://github.com/Sokrates1989/glamus_utils_test.git
```

# Updates

## For Users / Updating via composer 

1. make a backup (at least copy the current project directory or the glamus directory of vendor)
2. if you made any custom adaptions to the source, make sure to save them
3. Take a look at your composer.json
    - Pay high attention to "require" key, especially the version number of glamus/:package according to [composer versions](https://getcomposer.org/doc/articles/versions.md) (recommendation: keep "^" at beginning to ensure backwards compatibility)
4. execute the following command in the terminal/ bash of the directory  where the composer.json resides

``` bash
$ composer update glamus/utils
```


## For Users / Updating via git

1. go to the directory of glamus utils 
2. open bash or terminal in that directory

``` bash
(COPY VERSION - use in terminal/bash, removed $ at beginning, so you can simply copy/ paste)

git fetch --all --tags
tag=$(git describe --tags `git rev-list --tags --max-count=1`)
echo $tag
git switch main
git branch -D latest
git checkout $tag -b latest
```

Going to specific version
``` bash
(see all available versions with their git commit message)
$ git log --oneline --graph
(choose version by tag name)
$ chosenversion=(your chosen version using tag name WITH starting v)
$ (e.g.) chosenversion=v1.0.0
$ echo $chosenversion

$ git switch main
$ git branch -D $chosenversion
$ git checkout $chosenversion -b $chosenversion
```


## For Developers / create updates

1. Test the code you want to update
2. create or update the phpunit test
3. push changes to main
4. add a version number according to [semantic versioning](https://semver.org/) by bash / terminal as follows

``` bash
(always use v before version, so vMAJOR.MINOR.PATCH, so composer understands versions)
$ newversion=vMAJOR.MINOR.PATCH
$ git tag $newversion
$ git push origin $newversion
```

## Usage

See [examples/simpleUsage.php](examples/simpleUsage.php)

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.


## Structure

If any of the following are applicable to your project, then the directory structure should follow industry best practices by being named the following.

```
bin/        
build/
docs/
config/
src/
tests/
vendor/
```

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email michiels@glamus.de instead of using the issue tracker.

## Credits

- [Patrick Michiels][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/glamus/utils.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/glamus/utils/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/glamus/utils.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/glamus/utils.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/glamus/utils.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/glamus/utils
[link-travis]: https://travis-ci.org/glamus/utils
[link-scrutinizer]: https://scrutinizer-ci.com/g/glamus/utils/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/glamus/utils
[link-downloads]: https://packagist.org/packages/glamus/utils
[link-author]: https://github.com/Sokrates1989
[link-contributors]: ../../contributors
