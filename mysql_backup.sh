
# this script is somewhat missplaced, but if you've installed aws-cli you can upload local mysql data to s3 with this shell script.

/usr/local/mysql/bin/mysqldump -P 3306 -h 127.0.01 -u root -p morpheome | gzip -c > morpheome-tim-db-`date +%Y%m%d`.sql.gz
aws s3 cp morpheome-tim-db-`date +%Y%m%d`.sql.gz s3://timrpeterson-lab/morpheome/
rm morpheome-tim-db-`date +%Y%m%d`.sql.gz