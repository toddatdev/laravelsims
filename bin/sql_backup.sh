#!/bin/bash

export PATH=/bin:/usr/bin:/usr/local/bin
TODAY=`date +"%d%b%Y"`

## Script vars, Entering info inside the single quotes
DB_BACKUP_PATH=''
MYSQL_HOST=''
MYSQL_PORT=''
MYSQL_USER=''
MYSQL_PASSWORD=''
DATABASE_NAME=''
BACKUP_RETAIN_DAYS=7   ## Set the number of days to keep a copy,  script will delete old ones


## Create MySQL Backup ##
mkdir -p ${DB_BACKUP_PATH}/${TODAY}
echo "Backup started for database - ${DATABASE_NAME}"

# Script will throw warning about entering password this way is unsafe, but idk how else to add it ...
mysqldump -h ${MYSQL_HOST} \
   -P ${MYSQL_PORT} \
   -u ${MYSQL_USER} \
   -p${MYSQL_PASSWORD} \
   ${DATABASE_NAME} | gzip > ${DB_BACKUP_PATH}/${TODAY}/${DATABASE_NAME}-${TODAY}.sql.gz

if [ $? -eq 0 ]; then
  echo "${DATABASE_NAME} backup successfully completed"
else
  echo "Error found during backup"
  exit 1
fi


## Remove backups older than {BACKUP_RETAIN_DAYS} days  ##

DBDELDATE=`date +"%d%b%Y" --date="${BACKUP_RETAIN_DAYS} days ago"`

if [ ! -z ${DB_BACKUP_PATH} ]; then
      cd ${DB_BACKUP_PATH}
      if [ ! -z ${DBDELDATE} ] && [ -d ${DBDELDATE} ]; then
            rm -rf ${DBDELDATE}
      fi
fi

### EOF ###