# SERVIDORES-PARCIAL-JOSE_ANGEL

Servidor Nagios:
Iniciamos con la instalación de Nagios en Docker: 
    Primero, es necesario instalar Docker y Docker Compose ejecutando los comandos:

    + Inicia sesión como superusuario: sudo su
    + curl -fsSL https://get.docker.com -o get-docker.sh
    + sh get-docker.sh
    + docker –v
Instalamos Docker Compose:

    + sudo apt install docker-compose -y
    + docker-compose --version

Transferencia del archivo de Nagios Core: 
    Usamos WinSCP para transferir el archivo de Nagios Core al servidor.

Acceso a la carpeta del archivo docker-compose.yml: 
    Navegamos al directorio donde se encuentra el archivo docker-compose.yml.

Iniciar Nagios con Docker Compose: 

    + docker-compose up -d

Servidor Nginx:
    Instalamos de Docker y levantamiento del contenedor Nginx: 
    Después de instalar Docker y transferir la carpeta de Nginx con su respectivo Dockerfile, construye y ejecuta el contenedor con los siguientes comandos:

Construimos el contenedor personalizado de Nginx:

    + docker build -t nginx-custom .


Ejecutamos el contenedor en segundo plano:

    + docker run -d -p 80:80 --name nginx-container nginx-custom

Verificamos del funcionamiento de Nginx: 
    Abre un navegador y dirígete a la dirección IP del servidor, utilizando el puerto 80:

    + http://192.168.1.28:80



Servidor de Base de Datos:
Configuramos del contenedor para la base de datos: 
    Al igual que con el servidor Nginx, construimos y levanta el contenedor correspondiente para la base de datos:

    + docker-compose up --build

Acceder a la base de datos: 
    Para verificar que el contenedor esté funcionando correctamente, accedemos desde un navegador usando la dirección IP y el puerto 8080 del servidor:

    + http://192.168.1.29:8080
    + Introduce las credenciales para acceder a la base de datos.

Configuración en Nagios:
    Agregamos hosts y servicios para monitorear: 
    Para configurar los hosts y servicios en Nagios, primero debemos ingresar al directorio de configuración de Nagios en el contenedor y despues, crea un archivo hosts.cfg para definir los hosts y sus respectivos servicios:

Navega al directorio:

    + cd /var/lib/docker/volumes/nagios-docker_nagios_config/_data/conf.d

Creamos el archivo de configuración:

    + nano hosts.cfg

Configuración de los hosts y servicios: 
    Dentro de hosts.cfg, escribimos las siguientes configuraciones para los hosts dockerlinux y mysqlserver, incluyendo servicios como PING y HTTP:

    host dockerlinux:

    define host {
        use                     linux-server
        host_name               docker
        alias                   My Server
        address                 192.168.1.28
        max_check_attempts      5
        check_period            24x7
        notification_interval   30
        notification_period     24x7
    }

define service {
    use                     generic-service
    host_name               docker
    service_description     PING
    check_command           check_ping!100.0,20%!500.0,60%
}

define service {
    use                     generic-service
    host_name               docker
    service_description     HTTP
    check_command           check_http!192.168.1.28!80
}
Para el host mysqlserver:

bash
Copiar código
define host {
    use                     linux-server
    host_name               mysqlserver
    alias                   My Server mysql
    address                 192.168.1.29
    max_check_attempts      5
    check_period            24x7
    notification_interval   30
    notification_period     24x7
}

define service {
    use                     generic-service
    host_name               mysqlserver
    service_description     PING
    check_command           check_ping!100.0,20%!500.0,60%
}

define service {
    use                     generic-service
    host_name               mysqlserver
    service_description     HTTP
    check_command           check_http!192.168.1.29!8080
}

Verificacion de configuracion: 
    Para asegurarte de que no haya errores en la configuración, ejecutamos el siguiente comando para validar el archivo de configuración de Nagios:

    + docker exec -it 99517cfd2d80 nagios -v /opt/nagios/etc/nagios.cfg

Reinicimos de Nagios: 
    Si la validación fue exitosa, reiniciamos Nagios para aplicar los cambios:

    + docker exec -it 99517cfd2d80 pkill nagios