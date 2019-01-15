#!/bin/bash
domain=$1
if [ -z $domain ]
then
  domain="localhost"
fi
sudo docker exec -it mysql mysql -p -e "use prestashop; update ps_shop_url set domain='$domain', domain_ssl='$domain' where id_shop_url = 1; select * from ps_shop_url"
