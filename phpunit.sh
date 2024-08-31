#!/usr/bin/env bash

# by default we execute the end-to-end encryption tests
if [[ -z "${END_TO_END_ENCRYPTION}" ]]
then
  END_TO_END_ENCRYPTION="1"
fi

# by default we execute the server-side encryption tests
if [[ -z "${SERVER_SIDE_ENCRYPTION}" ]]
then
  SERVER_SIDE_ENCRYPTION="1"
fi

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

if [[ "${END_TO_END_ENCRYPTION}" -gt "0" ]]
then
  # execute phpunit for the end-to-end encryption
  echo "===== END-TO-END ENCRYPTION ====="
  echo

  # check if the test data repository has been checked out
  echo "Preparing the test data for the end-to-end encryption, this could take a while..."
  if [[ -d ./tests/data/end-to-end-encryption ]]
  then
    git -C ./tests/data/end-to-end-encryption pull >/dev/null 2>&1
  else
    git clone https://github.com/nextcloud/end-to-end-encryption-testdata ./tests/data/end-to-end-encryption >/dev/null 2>&1
  fi
  if [[ "$?" -ne "0" ]]
  then
    echo "ERROR: Preparing the test data for the end-to-end encryption failed." >&2
    exit 5
  fi

  # separate output
  echo

  XDEBUG_MODE=coverage phpunit -c ./phpunit.end-to-end-encryption.xml --coverage-html ./tests/cache/end-to-end-encryption/ --coverage-text
  TEMP="$?"

  # only proceed if no errors occured
  if [[ "$TEMP" -ne "0" ]]
  then
    echo "ERROR: error during phpunit run for the end-to-end encryption" >&2
    exit "$TEMP"
  fi
fi

if [[ "${SERVER_SIDE_ENCRYPTION}" -gt "0" ]]
then
  # execute phpunit for the server-side encryption
  echo "===== SERVER-SIDE ENCRYPTION ====="
  echo

  # check if the test data repository has been checked out
  echo "Preparing the test data for the server-side encryption, this could take a while..."
  if [[ -d ./tests/data/server-side-encryption ]]
  then
    git -C ./tests/data/server-side-encryption pull >/dev/null 2>&1
  else
    git clone https://github.com/nextcloud/server-side-encryption-testdata ./tests/data/server-side-encryption >/dev/null 2>&1
  fi
  if [[ "$?" -ne "0" ]]
  then
    echo "ERROR: Preparing the test data for the server-side encryption failed." >&2
    exit 6
  fi

  # separate output
  echo

  XDEBUG_MODE=coverage phpunit -c ./phpunit.server-side-encryption.xml --coverage-html ./tests/cache/server-side-encryption/ --coverage-text
  TEMP="$?"

  # only proceed if no errors occured
  if [[ "$TEMP" -ne "0" ]]
  then
    echo "ERROR: error during phpunit run for the server-side encryption" >&2
    exit "$TEMP"
  fi
fi
