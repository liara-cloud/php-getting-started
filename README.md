# Connect To MSSQL in Liara PHP Apps
Just a simple php app which connects to MSSQL database on shared hosting by installing and enabling sqlsrv extension using hooks and returns a connections success or faliure message.

## Installation (Liara)
- create a [php app on liara](https://console.liara.ir/apps/create)
- set Needed DB_ENVs on Liara
```
git clone https://github.com/liara-cloud/php-getting-started.git
```
```
cd php-getting-started
```
```
git checkout mssql-connection
```
```
liara deploy
```
