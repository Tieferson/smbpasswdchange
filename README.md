# PASSWORD CHANGE FOR SAMBA

Administrador para reset de senhas do samba.

## AUTOR

Tieferson Leandro Domingos - <tiefersond@yahoo.com.br>

Caso encontrem falhas ou tenham sugestões ficarei feliz em saber.

## INSTALAÇÃO

Após instalar o Apache com o PHP no servidor do Samba, acesse a pasta /var/www e clone o projeto.

`git clone https://github.com/Tieferson/smbpasswdchange.git`

Altere o arquivo /etc/apache2/sites-enabled/000-default.conf conforme abaixo


`sudo nano /etc/apache2/sites-enabled/000-default.conf`


`DocumentRoot /var/www/smbpasswdchange`

Reinicie o Apache

`sudo service apache2 restart`

Adicione as linhas seguintes no sudoers para que o Apache consiga alterar a senha dos usuários.

`sudo visudo`

`www-data ALL=(ALL) NOPASSWD: /usr/bin/smbpasswd`
`www-data ALL=(ALL) NOPASSWD: /usr/bin/pdbedit`


Altere o arquivo .conf de acordo com suas necessidades