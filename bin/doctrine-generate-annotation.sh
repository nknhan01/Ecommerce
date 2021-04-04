#!/bin/bash
platform='unknown'
os=${OSTYPE//[0-9.-]*/}
if [[ "$os" == 'darwin' ]]; then
   platform='MAC OSX'
elif [[ "$os" == 'msys' ]]; then
   platform='window'
elif [[ "$os" == 'linux' ]]; then
   platform='linux'
fi
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

# Linux bin paths, change this if it can not be autodetected via which command
	
if [[ "$platform" != 'window' ]]; then
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
	FIND="$($BIN/which find)"
	TOUCH="$($BIN/which touch)"
else	
	CP="cp"
	SSH="ssh"
	CD="cd"
	GIT="git"
	ECHO="echo"
	LN="ln"
	MV="mv"
	RM="rm"
	NGINX="/etc/init.d/nginx"
	MKDIR="mkdir"
	MYSQL="mysql"
	MYSQLDUMP="mysqldump"
	#no support
	CHOWN="chown"
	CHMOD="chmod"
	GZIP="gzip"
	TOUCH="touch"
	#end no support
	FIND="find"	
fi

### directory and file modes for cron and mirror files
FDMODE=0777
CDMODE=0700
CFMODE=600
MDMODE=0755
MFMODE=644

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
SCRIPT_PATH="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )" # return /path/bin
echo -e "$VERT--> Booting now ... $NORMAL"
echo -e "$VERT--> Your path: $SCRIPT_PATH $NORMAL"

# Usage info
show_help() {
cat << EOF
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
        -e|--env)
            if [ -z "$2" ]
            then
				show_help
				die 'ERROR: please specify "--e" enviroment.'
            fi
            APPLICATION_ENV="$2"
			if [[ "$2" == 'd' ]]; then
				APPLICATION_ENV="development"		
			fi	
			if [[ "$2" == 'p' ]]; then
				APPLICATION_ENV="production"		
			fi	
            shift
            break
            ;;
        -h|-\?|--help)
            show_help    # Display a usage synopsis.
            exit
            ;;
        -v|--verbose)
            verbose=$((verbose + 1))  # Each -v adds 1 to verbosity.
            ;;
        --)              # End of all options.
            shift
            break
            ;;
        -?*)
            printf 'WARN: Unknown option (ignored): %s\n' "$1" >&2
            ;;
        *)               # Default case: No more options, so break out of the loop.
            show_help    # Display a usage synopsis.
            die 'ERROR: "--env" requires a non-empty option argument.'
    esac
    shift
done

export APPLICATION_ENV="${APPLICATION_ENV}";

echo -e "$VERT--> You are uing APPLICATION_ENV: $APPLICATION_ENV $NORMAL"

## Please execute this file from ROOT_PATH
## try if CMDS exist
command -v php > /dev/null || { echo "php command not found."; exit 1; }
HASCURL=1;
command -v curl > /dev/null || HASCURL=0;

### SET ENV
if [[ -z "${APPLICATION_ENV}" ]]; then
    if  [ "$HOSTNAME" = production-server ]; then 
        export APPLICATION_ENV="production";
        export ENVIRONMENT="production";
        export ENV="production";
    else
        export APPLICATION_ENV="development";
        export ENVIRONMENT="development";
        export ENV="development";
    fi
else
  export APPLICATION_ENV="${APPLICATION_ENV}";
  export ENVIRONMENT="${APPLICATION_ENV}";
  export ENV="${APPLICATION_ENV}";
fi

sh -ac  ". $SCRIPT_PATH/../.env;printenv"
printenv

# https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/reference/tools.html#tools
# https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/reference/working-with-associations.html#working-with-associations

## generating entities and annotations
# php artisan doctrine:clear:metadata:cache
# php artisan doctrine:clear:result:cache
# php artisan doctrine:clear:query:cache
# php artisan doctrine:convert:mapping --force --from-database --namespace "App\\Models\\JmsEntity\\" annotation library/
# php artisan doctrine:generate:entities library/ --no-backup --generate-annotations
# php artisan doctrine:generate:entities library/ --no-backup --generate-methods
# php artisan doctrine:schema:validate

# php vendor/doctrine/orm/bin/doctrine orm:clear-cache:metadata
# php vendor/doctrine/orm/bin/doctrine orm:clear-cache:metadata
# php vendor/doctrine/orm/bin/doctrine orm:clear-cache:query
# php vendor/doctrine/orm/bin/doctrine orm:clear-cache:region:collection
# php vendor/doctrine/orm/bin/doctrine orm:clear-cache:region:entity
# php vendor/doctrine/orm/bin/doctrine orm:clear-cache:region:query
# php vendor/doctrine/orm/bin/doctrine orm:clear-cache:result

#### Do not use if you do not know what is it. The CMD also have its priority/ordering 
## php vendor/doctrine/orm/bin/doctrine orm:convert-mapping --force --from-database --namespace "AppEntity\\" annotation library/
php vendor/doctrine/orm/bin/doctrine orm:generate-entities library/ --generate-annotations=true
## php vendor/doctrine/orm/bin/doctrine orm:generate-entities library/ --generate-methods=true
## php vendor/doctrine/orm/bin/doctrine orm:generate-entities library/ --regenerate-entities=true
## php vendor/doctrine/orm/bin/doctrine orm:generate-entities library/ --update-entities=true


# orm:clear-cache:metadata
# orm:clear-cache:query
# orm:clear-cache:region:collection
# orm:clear-cache:region:entity
# orm:clear-cache:region:query
# orm:clear-cache:result
# orm:convert-d1-schema
# orm:convert-mapping
# orm:convert:d1-schema
# orm:convert:mapping
# orm:ensure-production-settings
# orm:generate-entities
# orm:generate-proxies
# orm:generate-repositories
# orm:generate:entities
# orm:generate:proxies
# orm:generate:repositories
# orm:info