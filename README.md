#  𓊈𒆜 PAINEL SSH WEB 𒆜𓊉

⚠ <b>SISTEMA SUPORTADO:</b>
</br>

👉 <b>(UBUNTU 18)</b>

```
apt install wget -y; bash <(wget -qO- https://raw.githubusercontent.com/Mcassador/SSH-WEB/ubuinst.sh)
```
</br>

# 𓊈𒆜 CHAVE DE INSTALAÇÃO! 𒆜𓊉
```
*-*
```

# 𓊈𒆜 SINCRONIZAR NA VPS SSH! 𒆜𓊉
```
apt install wget -y; bash <(wget -qO- https://raw.githubusercontent.com/Mcassador/SSH-WEB/sincpainel.sh)
```

ALTERAR PORTA DO APACHE2 DE 80 PRA 81

sed -i 's,Listen 80,Listen 81,g' /etc/apache2/ports.conf

REINICIAR APACHE2

sudo service apache2 restart


# 𓊈𒆜 ATUALIZAÇÕES: 𒆜𓊉
```
1- Comando pweb
(Funciona via terminal SSH)
(Com bot telegram)

2- Painel Conecta4G 
(Usuário/Senha: admin/admin)

3- Loja de APPS 
(Link na tela de login/Revenda e Login/Admin)
(Troca de cor da Top-Bar e icones nos Textos)

4- Background Área Revenda e Área Admin
(Para alterar as imagens, bastar ir em persobalizar no menu lateral do admin e fazer o upload das imagens)

5- Texto Flutuante na Tela Login/Revenda
(📣 NOVIDADES AQUI !!!) Para editar, basta ir em /var/www/html/index.php (linha 86)

6- Página de Termos de Uso editada
(foi adicionado uma imagem no topo)
(cor do background trocada)

7- Todos os nomes GESTOR-SSH foi trocado por EMPRESA
(quando alterar o NOME DA LOGO no painel pweb, Todos os texto EMPRESA será trocado também.)
```