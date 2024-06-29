add-apt-repository ppa:ondrej/php -y;
curl https://packages.microsoft.com/keys/microsoft.asc | tee /etc/apt/trusted.gpg.d/microsoft.asc;
curl https://packages.microsoft.com/config/ubuntu/$(lsb_release -rs)/prod.list | tee /etc/apt/sources.list.d/mssql-release.list;
apt-get update;
ACCEPT_EULA=Y apt-get install -y msodbcsql18;
apt-get install -y unixodbc-dev;
apt-get install php8.2-dev  -y --allow-unauthenticated;
sudo pecl install sqlsrv;
sudo pecl install pdo_sqlsrv;
printf "; priority=20\nextension=sqlsrv.so\n" > /etc/php/8.2/mods-available/sqlsrv.ini;
printf "; priority=30\nextension=pdo_sqlsrv.so\n" > /etc/php/8.2/mods-available/pdo_sqlsrv.ini
phpenmod -v 8.2 sqlsrv pdo_sqlsrv;