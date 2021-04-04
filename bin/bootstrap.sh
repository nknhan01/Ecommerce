#!/bin/bash

platform='unknown'

NORMAL="\\033[0;39m"
VERT="\\033[1;32m"
ROUGE="\\033[1;31m"
BLUE="\\033[1;34m"
ORANGE="\\033[1;33m"
echo -e "$ROUGE You are using $platform $NORMAL"
ESC_SEQ="\x1b["
COL_RESET=$ESC_SEQ"39;49;00m"
COL_RED=$ESC_SEQ"31;01m"
COL_GREEN=$ESC_SEQ"32;01m"
COL_YELLOW=$ESC_SEQ"33;01m"
COL_BLUE=$ESC_SEQ"34;01m"
COL_MAGENTA=$ESC_SEQ"35;01m"
COL_CYAN=$ESC_SEQ"36;01m"

## Linux bin paths, change this if it can not be autodetected via which command
BIN="/usr/bin"
CP="$($BIN/which cp)"
SSH="$($BIN/which ssh)"
CD="$($BIN/which cd)"
GIT="$($BIN/which git)"
ECHO="$($BIN/which echo)"
LN="$($BIN/which ln)"
MV="$($BIN/which mv)"
RM="$($BIN/which rm)"
NGINX="/etc/init.d/nginx"
MKDIR="$($BIN/which mkdir)"
MYSQL="$($BIN/which mysql)"
MYSQLDUMP="$($BIN/which mysqldump)"
CHOWN="$($BIN/which chown)"
CHMOD="$($BIN/which chmod)"
GZIP="$($BIN/which gzip)"
ZIP="$($BIN/which zip)"
FIND="$($BIN/which find)"
TOUCH="$($BIN/which touch)"
PHP="$($BIN/which php)"
PERL="$($BIN/which perl)"
CURL="$($BIN/which curl)"
HASCURL=1
DEVMODE="--no-dev"
PHPCOPTS=" -d memory_limit=-1 "

### directory and file modes for cron and mirror files
FDMODE=0777
CDMODE=0700
CFMODE=600
MDMODE=0755
MFMODE=644

os=${OSTYPE//[0-9.-]*/}
if [[ "$os" == 'darwin' ]]; then
  platform='macosx'
elif [[ "$os" == 'msys' ]]; then
  platform='window'
elif [[ "$os" == 'linux' ]]; then
  platform='linux'
fi
echo -e "$ROUGE You are using $platform $NORMAL"

###
## SOURCE="${BASH_SOURCE[0]}"
## while [ -h "$SOURCE" ]; do # resolve $SOURCE until the file is no longer a symlink
##   DIR="$( cd -P "$( dirname "$SOURCE" )" && pwd )"
##   SOURCE="$(readlink "$SOURCE")"
##   [[ $SOURCE != /* ]] && SOURCE="$DIR/$SOURCE" # if $SOURCE was a relative symlink, we need to resolve it relative to the path where the symlink file was located
## done
## DIR="$( cd -P "$( dirname "$SOURCE" )" && pwd )"
## cd $DIR
## SCRIPT_PATH=`pwd -P` # return wrong path if you are calling this script with wrong location
## SCRIPT_PATH=`pwd -P`
SCRIPT_PATH="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)" # return /path/bin
echo -e "$VERT--> Your path: $SCRIPT_PATH $NORMAL"

command -v php >/dev/null || {
  echo "php command not found."
  exit 1
}

## command -v curl >/dev/null || HASCURL=0
command -v curl >/dev/null || {
  echo "curl command not found."
  exit 1
}

# Usage info
show_help() {
  cat <<EOF
Usage: ${0##*/} [-hv] [-e APPLICATION_ENV] [development]...
    -h or --help         display this help and exit
    -e or --env APPLICATION_ENV
    -v or --verbose      verbose mode. Can be used multiple times for increased
                verbosity.
EOF
}
die() {
  printf '%s\n' "$1" >&2
  exit 1
}

# Initialize all the option variables.
# This ensures we are not contaminated by variables from the environment.
verbose=0
while :; do
  case $1 in
  -e | --env)
    if [ -z "$2" ]; then
      show_help
      die 'ERROR: please specify "--e" enviroment.'
    fi
    APPLICATION_ENV="$2"
    if [[ "$2" == 'd' ]]; then
      APPLICATION_ENV="development"
      DEVMODE="" # default is dev mode
    fi
    if [[ "$2" == 'p' ]]; then
      APPLICATION_ENV="production"
      DEVMODE="--no-dev"
    fi
    shift
    break
    ;;
  -h | -\? | --help)
    show_help # Display a usage synopsis.
    exit
    ;;
  -v | --verbose)
    verbose=$((verbose + 1)) # Each -v adds 1 to verbosity.
    ;;
  --) # End of all options.
    shift
    break
    ;;
  -?*)
    printf 'WARN: Unknown option (ignored): %s\n' "$1" >&2
    ;;
  *) # Default case: No more options, so break out of the loop.
    show_help # Display a usage synopsis.
    die 'ERROR: "--env" requires a non-empty option argument.'
    ;;
  esac
  shift
done

export APPLICATION_ENV="${APPLICATION_ENV}"
export APP_ENV="${APPLICATION_ENV}"
export NODE_ENV="${APPLICATION_ENV}"
export CI_ENV="${APPLICATION_ENV}"
export ENVIRONMENT="${APPLICATION_ENV}"

echo -e "$VERT--> You are uing APPLICATION_ENV: $APPLICATION_ENV $NORMAL"
echo "$(date) - Your composer devmod $DEVMODE is running"

nonce=$(md5sum <<< $(ip route get 8.8.8.8 | awk '{print $NF; exit}')$(hostname) | cut -c1-5 )
LOCKFILE=/tmp/zipping_$nonce
EMAILFILE=/tmp/zipping_$nonce.email
if [ -f "$LOCKFILE" ]; then
  # Remove lock file if script fails last time and did not run longer than 2 days due to lock file.
  find "$LOCKFILE" -mtime +2 -type f -delete
  echo "$(date) - Warning - process is running"
  exit 1
fi
## touch $LOCKFILE
## touch $EMAILFILE

################ FOR SYMFONY
if [ -f "$SCRIPT_PATH/../app/console" ]; then
  $RM -rf $SCRIPT_PATH/../app/cache/*
  $RM -rf $SCRIPT_PATH/../composer.lock

  [ ! -d "$SCRIPT_PATH/../app/cache/ip_data" ] && $MKDIR -m $FDMODE -p $SCRIPT_PATH/../app/cache/ip_data
  [ ! -f "$SCRIPT_PATH/../app/cache/ip_data/.gitignore" ] && touch $SCRIPT_PATH/../app/cache/ip_data/.gitignore && echo -e "*\n!.gitignore"$'\r' >$SCRIPT_PATH/../app/cache/ip_data/.gitignore

  [ ! -d "$SCRIPT_PATH/../app/cache/prod" ] && $MKDIR -m $FDMODE -p $SCRIPT_PATH/../app/cache/prod
  [ ! -f "$SCRIPT_PATH/../app/cache/prod/.gitignore" ] && touch $SCRIPT_PATH/../app/cache/prod/.gitignore && echo -e "*\n!.gitignore"$'\r' >$SCRIPT_PATH/../app/cache/prod/.gitignore

  [ ! -d "$SCRIPT_PATH/../app/cache/prod/annotations" ] && $MKDIR -m $FDMODE -p $SCRIPT_PATH/../app/cache/prod/annotations
  [ ! -f "$SCRIPT_PATH/../app/cache/prod/annotations/.gitignore" ] && touch $SCRIPT_PATH/../app/cache/prod/annotations/.gitignore && echo -e "*\n!.gitignore"$'\r' >$SCRIPT_PATH/../app/cache/prod/annotations/.gitignore

  [ ! -d "$SCRIPT_PATH/../app/cache/prod/data" ] && $MKDIR -m $FDMODE -p $SCRIPT_PATH/../app/cache/prod/data
  [ ! -f "$SCRIPT_PATH/../app/cache/prod/data/.gitignore" ] && touch $SCRIPT_PATH/../app/cache/prod/data/.gitignore && echo -e "*\n!.gitignore"$'\r' >$SCRIPT_PATH/../app/cache/prod/data/.gitignore

  [ ! -d "$SCRIPT_PATH/../app/cache/prod/doctrine" ] && $MKDIR -m $FDMODE -p $SCRIPT_PATH/../app/cache/prod/doctrine
  [ ! -f "$SCRIPT_PATH/../app/cache/prod/doctrine/.gitignore" ] && touch $SCRIPT_PATH/../app/cache/prod/doctrine/.gitignore && echo -e "*\n!.gitignore"$'\r' >$SCRIPT_PATH/../app/cache/prod/doctrine/.gitignore

  [ ! -d "$SCRIPT_PATH/../app/cache/prod/doctrine/cache" ] && $MKDIR -m $FDMODE -p $SCRIPT_PATH/../app/cache/prod/doctrine/cache
  [ ! -f "$SCRIPT_PATH/../app/cache/prod/doctrine/cache/.gitignore" ] && touch $SCRIPT_PATH/../app/cache/prod/doctrine/cache/.gitignore && echo -e "*\n!.gitignore"$'\r' >$SCRIPT_PATH/../app/cache/prod/doctrine/cache/.gitignore

  [ ! -d "$SCRIPT_PATH/../app/cache/prod/doctrine/cache/file_system" ] && $MKDIR -m $FDMODE -p $SCRIPT_PATH/../app/cache/prod/doctrine/cache/file_system
  [ ! -f "$SCRIPT_PATH/../app/cache/prod/doctrine/cache/file_system/.gitignore" ] && touch $SCRIPT_PATH/../app/cache/prod/doctrine/cache/file_system/.gitignore && echo -e "*\n!.gitignore"$'\r' >$SCRIPT_PATH/../app/cache/prod/doctrine/cache/file_system/.gitignore

  [ ! -d "$SCRIPT_PATH/../app/cache/prod/doctrine/orm" ] && $MKDIR -m $FDMODE -p $SCRIPT_PATH/../app/cache/prod/doctrine/orm
  [ ! -f "$SCRIPT_PATH/../app/cache/prod/doctrine/orm/.gitignore" ] && touch $SCRIPT_PATH/../app/cache/prod/doctrine/orm/.gitignore && echo -e "*\n!.gitignore"$'\r' >$SCRIPT_PATH/../app/cache/prod/doctrine/orm/.gitignore

  [ ! -d "$SCRIPT_PATH/../app/cache/prod/doctrine/orm/Proxies" ] && $MKDIR -m $FDMODE -p $SCRIPT_PATH/../app/cache/prod/doctrine/orm/Proxies
  [ ! -f "$SCRIPT_PATH/../app/cache/prod/doctrine/orm/Proxies/.gitignore" ] && touch $SCRIPT_PATH/../app/cache/prod/doctrine/orm/Proxies/.gitignore && echo -e "*\n!.gitignore"$'\r' >$SCRIPT_PATH/../app/cache/prod/doctrine/orm/Proxies/.gitignore

  [ ! -d "$SCRIPT_PATH/../app/cache/run" ] && $MKDIR -m $FDMODE -p $SCRIPT_PATH/../app/cache/run
  [ ! -f "$SCRIPT_PATH/../app/cache/run/.gitignore" ] && touch $SCRIPT_PATH/../app/cache/run/.gitignore && echo -e "*\n!.gitignore"$'\r' >$SCRIPT_PATH/../app/cache/run/.gitignore

  [ ! -d "$SCRIPT_PATH/../app/logs" ] && $MKDIR -m $FDMODE -p $SCRIPT_PATH/../app/logs
  [ ! -f "$SCRIPT_PATH/../app/logs/.gitignore" ] && touch $SCRIPT_PATH/../app/logs/.gitignore && echo -e "*\n!.gitignore"$'\r' >$SCRIPT_PATH/../app/logs/.gitignore

  [ ! -d "$SCRIPT_PATH/../translations" ] && $MKDIR -m $FDMODE -p $SCRIPT_PATH/../translations
  [ ! -f "$SCRIPT_PATH/../translations/.gitignore" ] && touch $SCRIPT_PATH/../translations/.gitignore && echo -e "*\n!.gitignore"$'\r' >$SCRIPT_PATH/../translations/.gitignore
fi

################ FOR LARAVEL
if [ -f "$SCRIPT_PATH/../artisan" ]; then
  $RM -rf $SCRIPT_PATH/../storage/framework/cache/data/*
  #  $RM -rf $SCRIPT_PATH/../storage/framework/sessions
  #  $RM -rf $SCRIPT_PATH/../storage/framework/testing
  $RM -rf $SCRIPT_PATH/../storage/framework/views/*.php
  $RM -rf $SCRIPT_PATH/../storage/logs/*.log
  $RM -rf $SCRIPT_PATH/../bootstrap/cache/*.php
  $RM -rf $SCRIPT_PATH/../composer.lock

  [ ! -d "$SCRIPT_PATH/../storage/framework/cache/data" ] && $MKDIR -m $FDMODE -p $SCRIPT_PATH/../storage/framework/cache/data || echo $SCRIPT_PATH/../storage/framework/cache/data
  #  [ -f "$SCRIPT_PATH/../storage/framework/cache/.gitignore" ] && $ECHO "Found: storage/framework/cache/.gitignore" || $TOUCH $SCRIPT_PATH/../storage/framework/cache/.gitignore && echo -e "*\n!.gitignore"$'\r' >$SCRIPT_PATH/../storage/framework/cache/.gitignore

  [ ! -d "$SCRIPT_PATH/../storage/framework/sessions" ] && $MKDIR -m $FDMODE -p $SCRIPT_PATH/../storage/framework/sessions
  #  [ -f "$SCRIPT_PATH/../storage/framework/sessions/.gitignore" ] && $ECHO "Found: storage/framework/sessions/.gitignore" || $TOUCH $SCRIPT_PATH/../storage/framework/sessions/.gitignore && echo -e "*\n!.gitignore"$'\r' >$SCRIPT_PATH/../storage/framework/sessions/.gitignore

  [ ! -d "$SCRIPT_PATH/../storage/framework/testing" ] && $MKDIR -m $FDMODE -p $SCRIPT_PATH/../storage/framework/testing
  #  [ -f "$SCRIPT_PATH/../storage/framework/testing/.gitignore" ] && $ECHO "Found: storage/framework/testing/.gitignore" || $TOUCH $SCRIPT_PATH/../storage/framework/testing/.gitignore && echo -e "*\n!.gitignore"$'\r' >$SCRIPT_PATH/../storage/framework/testing/.gitignore

  [ ! -d "$SCRIPT_PATH/../storage/framework/views" ] && $MKDIR -m $FDMODE -p $SCRIPT_PATH/../storage/framework/views
  [ -f "$SCRIPT_PATH/../storage/framework/views/.gitignore" ] && $ECHO "Found: storage/framework/views/.gitignore" || $TOUCH $SCRIPT_PATH/../storage/framework/views/.gitignore && echo -e "*\n!.gitignore"$'\r' >$SCRIPT_PATH/../storage/framework/views/.gitignore

  [ ! -d "$SCRIPT_PATH/../storage/logs" ] && $MKDIR -m $FDMODE -p $SCRIPT_PATH/../storage/logs
  [ -f "$SCRIPT_PATH/../storage/logs/.gitignore" ] && $ECHO "Found: storage/logs/.gitignore" || $TOUCH $SCRIPT_PATH/../storage/logs/.gitignore && echo -e "*\n!.gitignore"$'\r' >$SCRIPT_PATH/../storage/logs/.gitignore

  [ ! -d "$SCRIPT_PATH/../bootstrap/cache" ] && $MKDIR -m $FDMODE -p $SCRIPT_PATH/../bootstrap/cache
  [ -f "$SCRIPT_PATH/../bootstrap/cache/.gitignore" ] && $ECHO "Found: bootstrap/cache/.gitignore" || $TOUCH $SCRIPT_PATH/../bootstrap/cache/.gitignore && echo -e "*\n!.gitignore"$'\r' >$SCRIPT_PATH/../bootstrap/cache/.gitignore
fi

################ FOR Zend Framework & Doctrine
if [ -d "$SCRIPT_PATH/../data/DoctrineModule" ]; then
  ## [ ! -d "$SCRIPT_PATH/../public/themes/webapp/data/captcha"  ] && $MKDIR -m $FDMODE -p $SCRIPT_PATH/../public/themes/webapp/data/captcha && touch $SCRIPT_PATH/../public/themes/webapp/data/captcha/index.html
  ## [ ! -d "$SCRIPT_PATH/../public/themes/webapp/data/pdf"  ] && $MKDIR -m $FDMODE -p $SCRIPT_PATH/../public/themes/webapp/data/pdf && touch $SCRIPT_PATH/../public/themes/webapp/data/pdf/index.html

  $RM -rf $SCRIPT_PATH/../data/cache/*
  $RM -rf $SCRIPT_PATH/../data/Doctrine*
  $RM -rf $SCRIPT_PATH/../composer.lock

	[ ! -d "$SCRIPT_PATH/../data/cache"  ] && $MKDIR -m $FDMODE -p $SCRIPT_PATH/../data/cache && touch $SCRIPT_PATH/../data/cache/.gitignore && echo -e "*\n!.gitignore"$'\r' > $SCRIPT_PATH/../data/cache/.gitignore
	[ ! -d "$SCRIPT_PATH/../data/config"  ] && $MKDIR -m $FDMODE -p $SCRIPT_PATH/../data/config && touch $SCRIPT_PATH/../data/config/.gitignore && echo -e "*\n!.gitignore"$'\r' > $SCRIPT_PATH/../data/config/.gitignore
	[ ! -d "$SCRIPT_PATH/../data/tmp"  ] && $MKDIR -m $FDMODE -p $SCRIPT_PATH/../data/tmp && touch $SCRIPT_PATH/../data/tmp/.gitignore && echo -e "*\n!.gitignore"$'\r' > $SCRIPT_PATH/../data/tmp/.gitignore
	[ ! -d "$SCRIPT_PATH/../data/logs"  ] && $MKDIR -m $FDMODE -p $SCRIPT_PATH/../data/logs && touch $SCRIPT_PATH/../data/logs/.gitignore && echo -e "*\n!.gitignore"$'\r' > $SCRIPT_PATH/../data/logs/.gitignore

  [ ! -d "$SCRIPT_PATH/../data/DoctrineModule"  ] && $MKDIR -m $FDMODE -p $SCRIPT_PATH/../data/DoctrineModule && touch $SCRIPT_PATH/../data/DoctrineModule/.gitignore && echo -e "*\n!.gitignore"$'\r' > $SCRIPT_PATH/../data/DoctrineModule/.gitignore
  [ ! -d "$SCRIPT_PATH/../data/DoctrineORMModule"  ] && $MKDIR -m $FDMODE -p $SCRIPT_PATH/../data/DoctrineORMModule && touch $SCRIPT_PATH/../data/DoctrineORMModule/.gitignore && echo -e "*\n!.gitignore"$'\r' > $SCRIPT_PATH/../data/DoctrineORMModule/.gitignore
  [ ! -d "$SCRIPT_PATH/../data/DoctrineORMModule/Hydrator"  ] && $MKDIR -m $FDMODE -p $SCRIPT_PATH/../data/DoctrineORMModule/Hydrator && touch $SCRIPT_PATH/../data/DoctrineORMModule/Hydrator/.gitignore && echo -e "*\n!.gitignore"$'\r' > $SCRIPT_PATH/../data/DoctrineORMModule/Hydrator/.gitignore
  [ ! -d "$SCRIPT_PATH/../data/DoctrineORMModule/Proxy"  ] && $MKDIR -m $FDMODE -p $SCRIPT_PATH/../data/DoctrineORMModule/Proxy && touch $SCRIPT_PATH/../data/DoctrineORMModule/Proxy/.gitignore && echo -e "*\n!.gitignore"$'\r' > $SCRIPT_PATH/../data/DoctrineORMModule/Proxy/.gitignore
  [ ! -d "$SCRIPT_PATH/../data/DoctrineMongoODMModule"  ] && $MKDIR -m $FDMODE -p $SCRIPT_PATH/../data/DoctrineMongoODMModule && touch $SCRIPT_PATH/../data/DoctrineMongoODMModule/.gitignore && echo -e "*\n!.gitignore"$'\r' > $SCRIPT_PATH/../data/DoctrineMongoODMModule/.gitignore
  [ ! -d "$SCRIPT_PATH/../data/DoctrineMongoODMModule/Hydrator"  ] && $MKDIR -m $FDMODE -p $SCRIPT_PATH/../data/DoctrineMongoODMModule/Hydrator && touch $SCRIPT_PATH/../data/DoctrineMongoODMModule/Hydrator/.gitignore && echo -e "*\n!.gitignore"$'\r' > $SCRIPT_PATH/../data/DoctrineMongoODMModule/Hydrator/.gitignore
  [ ! -d "$SCRIPT_PATH/../data/DoctrineMongoODMModule/Proxy"  ] && $MKDIR -m $FDMODE -p $SCRIPT_PATH/../data/DoctrineMongoODMModule/Proxy && touch $SCRIPT_PATH/../data/DoctrineMongoODMModule/Proxy/.gitignore && echo -e "*\n!.gitignore"$'\r' > $SCRIPT_PATH/../data/DoctrineMongoODMModule/Proxy/.gitignore
fi

## ($CD $SCRIPT_PATH && $FIND $SCRIPT_PATH -type d -exec touch {}/index.html \;)

## get last composer
if [ -f composer.phar ]; then
  ## $PHP $PHPCOPTS composer.phar config --global discard-changes true
  $PHP $PHPCOPTS composer.phar self-update
else
  if [ $HASCURL == 1 ]; then
    curl -sS https://getcomposer.org/installer | $PHP
  else
    $PHP $PHPCOPTS -r "eval('?>'.file_get_contents('https://getcomposer.org/installer'));"
  fi
fi
## install or update with composer
if [ -f composer.lock ]; then
  ## $PHP $PHPCOPTS composer.phar config --global discard-changes true
  ## echo "$PHP $PHPCOPTS composer.phar config --global discard-changes true"
  $PHP $PHPCOPTS composer.phar $DEVMODE update -o -a
  echo "$PHP $PHPCOPTS composer.phar $DEVMODE update -o -a"
  ########### $PHP $PHPCOPTS composer.phar $DEVMODE update -o -a;
else
  ## $PHP $PHPCOPTS composer.phar config --global discard-changes true
  ## echo "$PHP $PHPCOPTS composer.phar config --global discard-changes true"
  $PHP $PHPCOPTS composer.phar $DEVMODE install -o -a
  echo "$PHP $PHPCOPTS composer.phar $DEVMODE install -o -a"
fi

################ FOR LARAVEL
if [ -f "$SCRIPT_PATH/../artisan" ]; then
  ## ($CD $SCRIPT_PATH/../ && $PHP $PHPCOPTS artisan vendor:publish --tag=public --force)
  ($CD $SCRIPT_PATH/../ && $PHP $PHPCOPTS artisan config:clear && $PHP $PHPCOPTS artisan cache:clear && $PHP $PHPCOPTS composer.phar dumpautoload)
fi
################ FOR SYMFONY
if [ -f "$SCRIPT_PATH/../app/console" ]; then
  ($CD $SCRIPT_PATH/../ && $PHP $PHPCOPTS app/console cache:clear && composer dumpautoload)
fi

# Ignore Symbolic links
# ($CD $SCRIPT_PATH && $FIND $SCRIPT_PATH/../ -type l | sed -e s'/^\.\///g' >> $SCRIPT_PATH/../.gitignore)

################ FOR LARAVEL
if [ -f "$SCRIPT_PATH/../artisan" ]; then
  ($CD $SCRIPT_PATH && $CHMOD -R 0777 $SCRIPT_PATH/../storage/ && $CHMOD 0777 $SCRIPT_PATH/../bootstrap/cache/)
  echo -e "$BLUE All paths created $NORMAL"
fi

################ FOR Zend Framework & Doctrine
if [ -d "$SCRIPT_PATH/../data/DoctrineModule" ]; then
  ($CD $SCRIPT_PATH && $CHMOD -R 0777 $SCRIPT_PATH/../data/ && $CHMOD 0777 $SCRIPT_PATH/../data/cache/)
  echo -e "$BLUE All paths created $NORMAL"
fi

################ FOR SYMFONY
if [ -f "$SCRIPT_PATH/../app/console" ]; then
  ($CD $SCRIPT_PATH && $CHMOD -R 0777 $SCRIPT_PATH/../app/cache/ && $CHMOD 0777 $SCRIPT_PATH/../app/logs/)
fi
