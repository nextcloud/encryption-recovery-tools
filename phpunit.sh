#!/usr/bin/env bash

# check if git is installed
if [[ ! -x "$(command -v git)" ]]
then
  echo "ERROR: git is not installed." >&2
  echo "ERROR: Try installing it with: brew install git" >&2
  exit 1
fi

# check if php is installed
if [[ ! -x "$(command -v php)" ]]
then
  echo "ERROR: php is not installed." >&2
  echo "ERROR: Try installing it with: brew install php" >&2
  exit 2
fi

# check if phpunit is installed
if [[ ! -x "$(command -v phpunit)" ]]
then
  echo "ERROR: phpunit is not installed." >&2
  echo "ERROR: Try installing it with: brew install phpunit" >&2
  exit 3
fi

# check if xdebug is installed
XDEBUG_OUTPUT=$(php -m -c 2>/dev/null | grep --quiet Xdebug)
if [[ "$?" -ne "0" ]]
then
  echo "ERROR: xdebug is not installed." >&2
  echo "ERROR: Try installing it with: pecl install xdebug" >&2
  exit 4
fi

# check if the test data repository has been checked out
echo "Preparing the test data, this could take a while..."
if [[ -d ./tests/data/server-side-encryption ]]
then
  git -C ./tests/data/server-side-encryption pull >/dev/null 2>&1
else
  git clone https://github.com/nextcloud/server-side-encryption-testdata ./tests/data/server-side-encryption >/dev/null 2>&1
fi
if [[ "$?" -ne "0" ]]
then
  echo "ERROR: Preparing the test data failed." >&2
  exit 5
fi

# print an empty line to separate the phpunit output optically
echo

# execute phpunit
XDEBUG_MODE=coverage phpunit -c ./phpunit.xml --coverage-text

