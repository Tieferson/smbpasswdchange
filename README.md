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

`www-data ALL=(ALL) NOPASSWD: /etc/init.d/smbd`

`www-data ALL=(ALL) NOPASSWD: /usr/sbin/reboot`

`www-data ALL=(ALL) NOPASSWD: /usr/sbin/halt`


Altere o arquivo .conf de acordo com suas necessidades

# UTILIZAÇÃO

Para um determinado usuário realizar a troca de senha basta acessar o servidor pelo navegador. Ex: http://servidordearquivos.local/

Será exibida e tela abaixo onde o usuário deve informar seu nome de usuário, senha atual e nova senha.

![Change pass screen](https://github.com/Tieferson/smbpasswdchange/blob/main/screenshots/user-pass-reset.png)

Caso o usuário não saiba sua senha atual a senha deve ser redefinda pelo administrador que deverá acessar o /admin no servidor de arquivos. Ex: http://servidordearquivos.local/admin

Lembre-se que o administrador deve fazer parte do grupo sudo e deverá utilizar sua senha do samba.

![Admin login](https://github.com/Tieferson/smbpasswdchange/blob/main/screenshots/admin-login.png)

Após efetuar o login basta selecionar o usuário que deseja alterar a senha e clicar no botão "Alterar senha". Uma senha aleatória será gerada.

![Admin Reset Passwd](https://github.com/Tieferson/smbpasswdchange/blob/main/screenshots/admin-user-reset-pass.png)

IMPORTANTE: Este processo altera apenas a senha do samba, não modificando a senha do Linux.