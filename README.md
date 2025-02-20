# Mysql Query Logger - Log all queries for MySQL/MariaDB 

The ultimate tool for: 

- [x] Debugging
- [x] Reverse engineering
- [x] Performance optimization.

Free, Easy to use and install!

![Overall View](/assets/docs/overall_view.png)

# How to launch with Docker?

`docker run -p 8181:8080 kirillzh87/beaver-mysql-logger`

Where `8181` is your local port. Then open [http://localhost:8181](http://localhost:8181)

MySQL connection could be specified via Web Interface:

![Setup Connection](/assets/docs/setup_connection.png)

## Supported Variables

`MYSQL_DSN` - A [PDO DSN](https://www.php.net/manual/en/ref.pdo-mysql.connection.php) for connection. Example: `mysql:host=host.docker.internal;port=32775;dbname=mysql`

`MYSQL_USERNAME` - Username 

`MYSQL_PASS` - Password

## How to launch with Docker Compose

```
services:
  mysql-logger:
    image: kirillzh87/beaver-mysql-logger
    environment:
      MYSQL_DSN: "mysql:host=host.docker.internal;port=32775;dbname=mysql"
      MYSQL_USERNAME: root
      MYSQL_PASS: root
    ports:
      - "9090:8080"
```