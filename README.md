# PASSWORD CHANGE FOR SAMBA

Administrador para reset de senha do samba.

## AUTOR

Tieferson Leandro Domingos <tiefersond@yahoo.com.br>

## INSTALAÇÃO

Após instalar o Apache com o PHP no servidor do Samba Acesse a pasta /var/www e clone o projeto.

git clone https://github.com/Tieferson/smbpasswdchange.git

E altere o arquivo /etc/apache2/sites-enabled/000-default.conf conforme abaixo


`sudo nano /etc/apache2/sites-enabled/000-default.conf`


DocumentRoot /var/www/smbpasswdchange

E reinicie o Apache

sudo service apache2 restart

Adicione as linhas seguintes para que o Apache consiga alterar a senha dos usuários.

sudo visudo

www-data ALL=(ALL) NOPASSWD: /usr/bin/smbpasswd
