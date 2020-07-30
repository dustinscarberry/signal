#!/usr/bin/env bash

# add repos for php7.4
add-apt-repository ppa:ondrej/php
apt-get update

# install needed packages
apt-get install -y php7.4-fpm
apt-get install -y nginx php7.4 php7.4-cli php7.4-mysql php7.4-gd php7.4-gmp php7.4-bcmath php7.4-imagick php7.4-xml php7.4-curl php7.4-mbstring mariadb-server mariadb-client
apt-get upgrade -y

# write out nginx config files
>/etc/nginx/sites-enabled/default
cat >> /etc/nginx/sites-enabled/vagrant << 'EOF'
upstream php-fpm {
        server unix:/var/run/php/php7.4-fpm.sock;
}

server {
	server_name __;
	root /vagrant/public;
	listen 80;
	index index.php;

	location = /favicon.ico {
		log_not_found off;
		access_log off;
	}

	location = /robots.txt {
		allow all;
		log_not_found off;
		access_log off;
	}

    location / {
        try_files $uri /index.php$is_args$args;
    }

    location ~ ^/index\.php(/|$) {
        fastcgi_pass php-fpm;
        fastcgi_split_path_info ^(.+\.php)(/.*)$;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT $realpath_root;
        internal;
    }

    # return 404 for all other php files not matching the front controller
    location ~ \.php$ {
        return 404;
    }


	location ~ /\. {
		deny all;
	}

	# Feed
	location ~* \.(?:rss|atom)$ {
		expires 1h;
		add_header Cache-Control "public";
	}

	# Media: images, icons, video, audio, HTC
	location ~* \.(?:jpg|jpeg|gif|png|ico|cur|gz|svg|svgz|mp4|ogg|ogv|webm|htc)$ {
		expires 1M;
		access_log off;
		add_header Cache-Control "public";
	}

	# CSS and Javascript
	location ~* \.(?:css|js|woff)$ {
		expires 1y;
		access_log off;
		add_header Cache-Control "public";
	}
}
EOF

systemctl reload nginx

# install composer
cd /home/vagrant
curl -sS https://getcomposer.org/installer -o composer-setup.php
php composer-setup.php --install-dir=/usr/local/bin --filename=composer
rm -rf composer-setup.php

# setup mariadb user account
mysql -e "USE mysql;"
mysql -e "CREATE USER 'vagrant'@'localhost' IDENTIFIED BY 'vagrant';"
mysql -e "GRANT ALL PRIVILEGES ON *.* TO 'vagrant'@'localhost';"
mysql -e "FLUSH PRIVILEGES;"

# setup database
mysql -e "CREATE DATABASE demo;"
mysql -e "CREATE DATABASE demo_test;"

# change user accounts for web stack
sed -i 's/www-data/vagrant/g' /etc/nginx/nginx.conf
sed -i 's/www-data/vagrant/g' /etc/php/7.4/fpm/pool.d/www.conf

# restart services
systemctl restart php7.4-fpm
systemctl restart nginx
